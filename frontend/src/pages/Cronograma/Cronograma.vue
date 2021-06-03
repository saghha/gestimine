<template>
  <div class="col-lg-12">
    <div class="mt-2">
      <b-button variant="primary" @click="handleNewModal(true)">Agregar Item</b-button>
    </div>
    <div class="card mt-2">
      <div class="card-body">
        <h4 class="card-title">Cronograma</h4>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <b-table :items="datos" :fields="fields" responsive small hover no-border-collapse striped>
              <template #cell(options)="row">
                <b-button-group>
                  <b-button variant="warning" class="btn-sm">Editar</b-button>
                  <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                </b-button-group>
              </template>
            </b-table>
          </div>
        </div>
      </div>
    </div>
    <NewItemCronograma v-if="showNewItemModal" :showModal="showNewItemModal" @close="handleNewModal(false)"/>
  </div>
</template>
<script>
import NewItemCronograma from './New'
import helpers from '../../components/Helper'
export default {
  name: 'Cronograma',
  components: {
    NewItemCronograma
  },
  data () {
    return {
      datos: [
        {nombre_estructura: 'nombre', area: 'area', ley: "0.5%", densidad: 132, metros_totales:200, ano1: 220202, ano2: 220202, ano3: 220202, ano4: 220202, ano5: 220202, ano6: 220202, total: 2330003}
      ],
      showNewItemModal: false,
      datos_final: [
        {total: 2330003}
      ],
      headers: '',
      cronograma: [],
      fields: [
        {key: 'nombre_estructura', label: 'Nombre Estructura'},
        {key: 'area', label: 'Area'},
        {key: 'ley', label: '% Ley'},
        {key: 'densidad', label: 'Densidad'},
        {key: 'metros_totales', label: 'Metros Totales'},
        {key: 'ano1', label: 'Año 1'},
        {key: 'total', label: 'Total'},
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
      axios.get('cronograma/infraestructura/buscar-cronograma', {
        params: {
          datos_mina: this.$store.getters.slugDatosMina
        }
      }).then((response) => {
        this.cronograma = response.data
        var anios_infra = [
          {key: 'ano1', label: 'Año 1'},
          {key: 'ano2', label: 'Año 2'},
          {key: 'ano3', label: 'Año 3'},
          {key: 'ano4', label: 'Año 4'},
        ]
        this.fields = [
          {key: 'nombre_estructura', label: 'Nombre Estructura'},
          {key: 'area', label: 'Area'},
          {key: 'ley', label: '% Ley'},
          {key: 'densidad', label: 'Densidad'},
          {key: 'metros_totales', label: 'Metros Totales'},
        ]
        //this.fields.push(anios_infra)
        this.fields = _.concat(this.fields, anios_infra)
        this.fields = _.concat(this.fields, [{key: 'total', label: 'Total'},
        {key: 'options', label: 'Opciones'}])
      }).catch((err) => {
        this.showToast({icon: 'error', title: 'err.response.data.message'})
      }).finally(() => {
        this.$store.commit('setLoading', false)
      })
    },
    handleNewModal: function (cond) {
      this.showNewItemModal = cond
    }
  }
}
</script>