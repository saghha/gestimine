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
                      <b-th sticky-column>Nombre de estructura</b-th>
                      <b-th sticky-column>Área</b-th>
                      <b-th sticky-column>Sección</b-th>
                      <b-th sticky-column>N° Tiros</b-th>
                      <b-th sticky-column>Metros Totales</b-th>
                      <b-th v-for="(anio, index_field) in anos_infraestructura" :key="index_field">
                        <div class="pointer" @click="selectValue(anio.key)">
                          {{anio.label}}
                        </div>
                        </b-th>
                      <b-th>Total</b-th>
                      <b-th>Opciones</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr v-for="(item, index_item) in infraestructuras" :key="index_item">
                      <b-td>{{item.nombre}}</b-td>
                      <b-td>{{item.area}}</b-td>
                      <b-td>{{item.seccion}}</b-td>
                      <b-td>{{item.nro_tiros}}</b-td>
                      <b-td>{{item.longitud}}</b-td>
                      <b-td class="text-center" v-for="(value_ano, index_ano) in anos_infraestructura" :key="index_ano">
                        {{mostrarValor(value_ano, item)}}
                      </b-td>
                      <b-td>{{formatAllMoney(item.total_desgloce_total)}}</b-td>
                      <b-td>
                        <b-button-group>
                          <b-button variant="warning" class="btn-sm" @click="selectItem(item)">Editar</b-button>
                          <b-button variant="danger" class="btn-sm" @click="confirmDelete(item.slug)">Eliminar</b-button>
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
                <b-table-simple hover small caption-top responsive>
                  <b-thead>
                    <b-tr>
                      <b-th sticky-column>Nombre Preparación</b-th>
                      <b-th sticky-column>Área</b-th>
                      <b-th sticky-column>Sección</b-th>
                      <b-th sticky-column>N° Tiros</b-th>
                      <b-th sticky-column>Metros Totales</b-th>
                      <b-th v-for="(anio, index_field) in anos_preparaciones" :key="index_field">
                        <div class="pointer" @click="selectValue(anio.key)">
                          {{anio.label}}
                        </div>
                        </b-th>
                      <b-th>Total</b-th>
                      <b-th>Opciones</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr v-for="(item, index_item) in preparaciones" :key="index_item">
                      <b-td>{{item.nombre}}</b-td>
                      <b-td>{{item.area}}</b-td>
                      <b-td>{{item.seccion}}</b-td>
                      <b-td>{{item.nro_tiros}}</b-td>
                      <b-td>{{item.longitud}}</b-td>
                      <b-td class="text-center" v-for="(value_ano, index_ano) in anos_preparaciones" :key="index_ano">
                        {{mostrarValor(value_ano, item)}}
                      </b-td>
                      <b-td>{{formatAllMoney(item.total_desgloce_total)}}</b-td>
                      <b-td>
                        <b-button-group>
                          <b-button variant="warning" class="btn-sm" @click="selectItem(item)">Editar</b-button>
                          <b-button variant="danger" class="btn-sm" @click="confirmDelete(item.slug)">Eliminar</b-button>
                        </b-button-group>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </div>
            </div>
          </b-tab>
          <b-tab title="Producción" @click="selectTab('producción')">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <b-table-simple hover small caption-top responsive>
                  <b-thead>
                    <b-tr>
                      <b-th sticky-column>Nombre Producción</b-th>
                      <b-th v-for="(anio, index_field) in anos_produccion" :key="index_field">
                        <div class="pointer" @click="selectValue(anio.key)">
                          {{anio.label}}
                        </div>
                        </b-th>
                      <b-th>Total</b-th>
                      <b-th>Opciones</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr v-for="(item, index_item) in produccion" :key="index_item">
                      <b-td>{{item.nombre}}</b-td>
                      <b-td class="text-center" v-for="(value_ano, index_ano) in anos_produccion" :key="index_ano">
                        {{mostrarValor(value_ano, item)}}
                      </b-td>
                      <b-td>{{formatAllMoney(item.total_desgloce_total)}}</b-td>
                      <b-td>
                        <b-button-group>
                          <b-button variant="warning" class="btn-sm" @click="selectItem(item)">Editar</b-button>
                          <b-button variant="danger" class="btn-sm" @click="confirmDelete(item.slug)">Eliminar</b-button>
                        </b-button-group>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </div>
            </div>
          </b-tab>
        </b-tabs>
      </div>
    </div>
    <NewItemCronograma v-if="showNewItemModal" :showModal="showNewItemModal" :id_datos_mina="this.$store.getters.slugDatosMina" @close="handleNewModal(false)" @add="getCronograma()"/>
    <NewPreparacionCronograma v-if="showNewPrepaModal" :showModal="showNewPrepaModal" :id_datos_mina="this.$store.getters.slugDatosMina" @close="handleNewModal(false)" @add="getCronograma()"/>
    <NewProduccionCronograma v-if="showNewProdModal" :showModal="showNewProdModal" :id_datos_mina="this.$store.getters.slugDatosMina" @close="handleNewModal(false)" @add="getCronograma()"/>
    <EditItemCronograma
      v-if="editInfraItemModal"
      :showModal="editInfraItemModal"
      :id_dato="selectedEdit.slug"
      :id_datos_mina="this.$store.getters.slugDatosMina"
      @close="handleEditModal(false)"
      @edit="getCronograma()"/>
    <EditPreparacionCronograma
      v-if="editPrepaItemModal"
      :showModal="editPrepaItemModal"
      :id_dato="selectedEdit.slug"
      :id_datos_mina="this.$store.getters.slugDatosMina"
      @close="handleEditModal(false)"
      @edit="getCronograma()"/>
    <EditProduccionCronograma
      v-if="editProdItemModal"
      :showModal="editProdItemModal"
      :id_dato="selectedEdit.slug"
      :id_datos_mina="this.$store.getters.slugDatosMina"
      @close="handleEditModal(false)"
      @edit="getCronograma()"/>
    <CronogramaPeriodo
      v-if="showPeriodoModal"
      :showModal="showPeriodoModal"
      :data="info_periodos"
      :ano="ano_consultado"
      @close="handlePeriodoModal(false)"/>
  </div>
</template>
<script>
import NewItemCronograma from './New'
import EditItemCronograma from './EditInfraestructura'
import EditPreparacionCronograma from './EditPreparacionModal.vue'
import EditProduccionCronograma from './EditProduccionModal.vue'
import NewPreparacionCronograma from './NewPreparacion.vue'
import NewProduccionCronograma from './NewProduccionModal.vue'
import helpers from '../../components/Helper'
import CronogramaPeriodo from './CronogramaPeriodo'
export default {
  name: 'Cronograma',
  components: {
    NewItemCronograma,
    EditItemCronograma,
    CronogramaPeriodo,
    NewPreparacionCronograma,
    NewProduccionCronograma,
    EditPreparacionCronograma,
    EditProduccionCronograma
  },
  data () {
    return {
      datos: [],
      showNewItemModal: false,
      showNewPrepaModal: false,
      showNewProdModal: false,
      editInfraItemModal: false,
      editPrepaItemModal: false,
      editProdItemModal: false,
      datos_final: [
        {total: 2330003}
      ],
      headers: '',
      ano_consultado: null,
      info_periodos: [],
      infraestructuras: [],
      preparaciones: [],
      produccion: [],
      selectedEdit: {},
      anos_infraestructura: [],
      anos_preparaciones: [],
      anos_produccion: [],
      showPeriodoModal: false,
      tab_selected: 'infraestructura',
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

        var anios_infa = response.data.anos_infraestructura
        var anios_prepa = response.data.anos_preparaciones
        var anios_prod = response.data.anos_produccion

        this.anos_infraestructura = _.sortBy(anios_infa, (value, index) => {return value.key});
        this.anos_preparaciones = _.sortBy(anios_prepa, (value, index) => {return value.key});
        this.anos_produccion = _.sortBy(anios_prod, (value, index) => {return value.key});

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
        this.editPrepaItemModal = cond
        this.editProdItemModal = cond
      }
      if(tab == 'infraestructura') {
        this.editInfraItemModal = cond
      } else if(tab == 'preparación') {
        this.editPrepaItemModal = cond
      } else  if(tab == 'producción') {
        this.editProdItemModal = cond
      }
    },
    selectValue: function (anio) {
      this.$store.commit('setLoading', true)
      axios.get('cronograma/infraestructura/mostrar-cronograma-periodo', {
        params: {
          ano: anio,
          datos_mina: this.$store.getters.slugDatosMina
        }
      }).then((response) => {
        this.ano_consultado = anio
        this.info_periodos = response.data
        this.handlePeriodoModal(true)
      }).catch((err) => {
        this.showToast({icon: 'success', title: err.response.data.message})
      }).finally(() => {
        this.$store.commit('setLoading', false)
      })
    },
    mostrarValor: function (value_ano, item) {
      var data = _.find(item.valores, (value, index) => {return value_ano.key == index})
      if(!!data) {
        return this.formatAllMoney(data.valor_desgloce_anual)
      } else {
        return 0
      }
    },
    handlePeriodoModal: function (cond) {
      this.showPeriodoModal = cond
    },
    selectItem: function (item) {
      console.log("edita")
      this.selectedEdit = JSON.parse(JSON.stringify(item))
      this.handleEditModal(true, this.tab_selected)
    },
    deleteItem: function (tab, slug) {
      var url = 'cronograma/'
      if(tab == 'infraestructura') {
        url = url + 'infraestructura/'+slug
      } else if (tab == 'preparación') {
        url = url + 'preparacion/'+slug
      } else if (tab == 'producción') {
        url = url + 'produccion/'+slug
      }
      this.$store.commit('setLoading', true)
      axios.delete(url).then((response) => {
        this.showToast({icon: 'success', title: 'Item eliminado correctamente'})
        this.getCronograma()
      }).catch((err) => {
        this.showToast({icon: 'error', title: err.response.data.message})
        this.$store.commit('setLoading', false)
      })
    },
    confirmDelete: function (slug) {
      this.$swal({
        title: '¿Seguro?',
        icon: 'question',
        text: '¿Estás seguro de eliminar el item?',
        confirmButtonText: 'Eliminar',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if(result.value) {
          this.deleteItem(this.tab_selected, slug)
        }
      })
    },
    handleNewModal: function (cond, tab) {
      if(!cond) {
        this.showNewItemModal = cond
        this.showNewPrepaModal = cond
        this.showNewProdModal = cond
      }
      if(tab == 'infraestructura') {
        this.showNewItemModal = cond
      } else if (tab == 'preparación') {
        this.showNewPrepaModal = cond
      } else if (tab == 'producción') {
        this.showNewProdModal = cond
      }
    }
  }
}
</script>
<style>
.pointer {
  cursor: pointer;
}
</style>