<template>
  <div class="col-lg-12">
    <div class="mt-2">
      <b-button variant="primary" @click="handleNewModal(true, 'infraestructura')" v-if="tab_selected == 'infraestructura'">Agregar Item Infraestructura</b-button>
      <b-button variant="primary" @click="handleNewModal(true, 'preparación')" v-if="tab_selected == 'preparación'">Agregar Item Preparación</b-button>
      <b-button variant="primary" @click="handleNewModal(true, 'producción')" v-if="tab_selected == 'producción'">Agregar Item Producción</b-button>
    </div>
    <div class="card mt-2">
      <div class="card-body">
        <b-tabs>
          <b-tab title="Infraestructuras" @click="selectTab('infraestructura')">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <b-table-simple hover small caption-top responsive>
                  <b-thead>
                    <b-tr>
                      <b-th v-for="(field, index_field) in fields_infra" :key="index_field">{{field.label}}</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr v-for="(item, index_item) in infraestructuras" :key="index_item">
                      <b-td>{{item.nombre}}</b-td>
                      <b-td>{{item.area}}</b-td>
                      <b-td>{{item.seccion}}</b-td>
                      <b-td>{{item.nro_tiros}}</b-td>
                      <b-td>{{item.longitud}}</b-td>
                      <b-td class="text-center" v-for="(valor, index_valor) in item.valores" :key="index_valor">{{formatAllMoney(valor.valor_desgloce_anual)}}</b-td>
                      <b-td>{{formatAllMoney(item.total_desgloce_total)}}</b-td>
                      <b-td>
                        <b-button-group>
                          <b-button variant="warning" class="btn-sm" @click="selectItem(item)">Editar</b-button>
                          <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                        </b-button-group>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </div>
            </div>
          </b-tab>
          <b-tab title="Preparación" @click="selectTab('preparación')">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <b-table :items="preparaciones" :fields="fields_prepa" responsive small hover no-border-collapse striped>
                  <template #cell(options)="row">
                    <b-button-group>
                      <b-button variant="warning" class="btn-sm">Editar</b-button>
                      <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                    </b-button-group>
                  </template>
                </b-table>
              </div>
            </div>
          </b-tab>
          <b-tab title="Producción" @click="selectTab('producción')">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <b-table :items="produccion" :fields="fields_prod" responsive small hover no-border-collapse striped>
                  <template #cell(options)="row">
                    <b-button-group>
                      <b-button variant="warning" class="btn-sm">Editar</b-button>
                      <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                    </b-button-group>
                  </template>
                </b-table>
              </div>
            </div>
          </b-tab>
        </b-tabs>
      </div>
    </div>
    <NewItemCronograma v-if="showNewItemModal" :showModal="showNewItemModal" :id_datos_mina="this.$store.getters.slugDatosMina" @close="handleNewModal(false)" @add="getCronograma()"/>
    <EditItemCronograma
      v-if="editInfraItemModal"
      :showModal="editInfraItemModal"
      :id_dato="selectedEdit.slug"
      :id_datos_mina="this.$store.getters.slugDatosMina"
      @close="handleEditModal(false)"
      @add="getCronograma()"/>
  </div>
</template>
<script>
import NewItemCronograma from './New'
import EditItemCronograma from './EditInfraestructura'
import helpers from '../../components/Helper'
export default {
  name: 'Cronograma',
  components: {
    NewItemCronograma,
    EditItemCronograma
  },
  data () {
    return {
      datos: [
        {"nombre":"OPERACIONEES PRUEBA","seccion":"6X6","area":"36.00","longitud":"200.00","nro_tiros":40,"total_desgloce_periodo":100,"valores":{"2021":{"ano":2021,"valor_desgloce_anual":100}}}
      ],
      showNewItemModal: false,
      editInfraItemModal: false,
      datos_final: [
        {total: 2330003}
      ],
      headers: '',
      infraestructuras: [],
      preparaciones: [],
      produccion: [],
      fields_prepa: [
        {key: 'nombre', label: 'Nombre Estructura'},
        {key: 'area', label: 'Area'},
        {key: 'seccion', label: 'Sección'},
        {key: 'nro_tiros', label: 'N° Tiros'},
        {key: 'longitud', label: 'Metros Totales'},
        {key: '2021', label: 'Año 1'},
        {key: 'total_desgloce', label: 'Total'},
        {key: 'options', label: 'Opciones'},
      ],
      selectedEdit: {},
      anos_infraestructura: [],
      tab_selected: 'infraestructura',
      fields_prod: [
        {key: 'nombre_estructura', label: 'Nombre Estructura'},
        {key: 'area', label: 'Area'},
        {key: 'ley', label: '% Ley'},
        {key: 'densidad', label: 'Densidad'},
        {key: 'metros_totales', label: 'Metros Totales'},
        {key: 'ano1', label: 'Año 1'},
        {key: 'total', label: 'Total'},
        {key: 'options', label: 'Opciones'},
      ],
      fields_infra: [
        {key: 'nombre', label: 'Nombre Estructura'},
        {key: 'area', label: 'Area'},
        {key: 'seccion', label: 'Sección'},
        {key: 'nro_tiros', label: 'N° Tiros'},
        {key: 'longitud', label: 'Metros Totales'},
        {key: 'valores.2021.valor_desgloce_anual', label: 'Año 1'},
        {key: 'total_desgloce', label: 'Total'},
        {key: 'options', label: 'Opciones'},
      ]
    }
  },
  created () {
    this.$nextTick(function () {
      this.getCronograma()
    })
  },
  methods: {
    ...helpers,
    getCronograma: function () {
      this.$store.commit('setLoading', true)
      axios.get('cronograma/infraestructura/mostrar-cronograma-anual', {
        params: {
          datos_mina: this.$store.getters.slugDatosMina
        }
      }).then((response) => {
        this.infraestructuras = response.data.infraestructura
        this.preparaciones = response.data.preparacion
        this.produccion = response.data.produccion
        this.fields_infra = [
          {key: 'nombre', label: 'Nombre Estructura'},
          {key: 'area', label: 'Area'},
          {key: 'seccion', label: 'Sección'},
          {key: 'nro_tiros', label: 'N° Tiros'},
          {key: 'longitud', label: 'Metros Totales'},
        ]
        this.fields_prepa = [
          {key: 'nombre_estructura', label: 'Nombre Estructura'},
          {key: 'area', label: 'Area'},
          {key: 'ley', label: '% Ley'},
          {key: 'densidad', label: 'Densidad'},
          {key: 'metros_totales', label: 'Metros Totales'},
        ]
        this.fields_prod = [
          {key: 'nombre_estructura', label: 'Nombre Estructura'},
          {key: 'area', label: 'Area'},
          {key: 'ley', label: '% Ley'},
          {key: 'densidad', label: 'Densidad'},
          {key: 'metros_totales', label: 'Metros Totales'},
        ]
        //this.fields.push(anios_infra)
        this.fields_infra = _.concat(this.fields_infra, response.data.anos_infraestructura)
        this.fields_infra = _.concat(this.fields_infra, [{key: 'total_desgloce_periodo', label: 'Total'},
        {key: 'options', label: 'Opciones'}])
        this.anos_infraestructura = response.data.anos_infraestructura
        this.fields_prepa = _.concat(this.fields_prepa, response.data.anos_preparaciones)
        this.fields_prepa = _.concat(this.fields_prepa, [{key: 'total', label: 'Total'},
        {key: 'options', label: 'Opciones'}])
        this.fields_prod = _.concat(this.fields_prod, response.data.anos_produccion)
        this.fields_prod = _.concat(this.fields_prod, [{key: 'total', label: 'Total'},
        {key: 'options', label: 'Opciones'}])
      }).catch((err) => {
        this.showToast({icon: 'error', title: err.response.data.message})
      }).finally(() => {
        this.$store.commit('setLoading', false)
      })
    },
    selectTab: function (tab_name) {
      this.tab_selected = tab_name
    },
    handleEditModal: function (cond, tab) {
      if(!cond) {
        this.editInfraItemModal = cond
      }
      if(tab == 'infraestructura') {
        this.editInfraItemModal = cond
      }
    },
    selectItem: function (item) {
      console.log("edita")
      this.selectedEdit = JSON.parse(JSON.stringify(item))
      this.handleEditModal(true, this.tab_selected)
    },
    handleNewModal: function (cond, tab) {
      if(!cond) {
        this.showNewItemModal = cond
      }
      if(tab == 'infraestructura') {
        this.showNewItemModal = cond
      }
    }
  }
}
</script>