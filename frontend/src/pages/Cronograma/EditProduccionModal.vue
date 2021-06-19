<template>
  <b-modal v-model="showModal" v-if="showModal" size="xl" @hide="closeModal()" hide-footer>
    <template v-slot:modal-title>
      <strong>Editar Item Producción</strong>
    </template>
    <ValidationObserver v-slot="{invalid}">
      <div class="row">
        <div class="col-12 text-right">
          <b-button variant="warning" :disabled="invalid || info.valores.length == 0" @click="addItem()">Editar item</b-button>
        </div>
        <div class="col-ld-4 col-md-4 col-sm-12">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label :class="{'text-danger': v.failedRules.required}">Nombre Producción</label>
              <b-form-input v-model="info.nombre_produccion"/>
            </div>
          </ValidationProvider>
        </div>
      </div>
    </ValidationObserver>
    <div class="col-12">
      <hr>
    </div>
    <ValidationObserver v-slot="{invalid}">
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="ano" :class="{'text-danger': v.failedRules.required}">Año</label>
              <b-form-input v-model="info_periodo.ano" type="number"/>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="ano" :class="{'text-danger': v.failedRules.required}">Periodo</label>
              <b-form-input v-model="info_periodo.periodo" type="number"/>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="total" :class="{'text-danger': v.failedRules.required}">Total Perforación</label>
              <b-form-input type="number" v-model="info_periodo.valor_desgloce_perforacion"/>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="total" :class="{'text-danger': v.failedRules.required}">Total Carguío</label>
              <b-form-input type="number" v-model="info_periodo.valor_desgloce_carguio"/>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="total" :class="{'text-danger': v.failedRules.required}">Total Tronadura</label>
              <b-form-input type="number" v-model="info_periodo.valor_desgloce_tronadura"/>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="total" :class="{'text-danger': v.failedRules.required}">Total Transporte</label>
              <b-form-input type="number" v-model="info_periodo.valor_desgloce_transporte"/>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="total" :class="{'text-danger': v.failedRules.required}">Total Periodo</label>
              <b-form-input type="number" v-model="info_periodo.valor_desgloce"/>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 text-right">
          <b-button-group>
            <b-button variant="info" style="margin-top: 4.5vh;" v-if="!editMode" :disabled="invalid" @click="agregarPeriodo()">Agregar</b-button>
            <b-button variant="warning" style="margin-top: 4.5vh;" v-if="editMode" @click="changePeriodo()">Editar</b-button>
            <b-button variant="danger" style="margin-top: 4.5vh;" v-if="editMode" @click="deletePeriodo()">Eliminar</b-button>
          </b-button-group>
        </div>
        <div class="col-12">
          <b-table :items="info.valores" :fields="fields" responsive hover small>
            <template #cell(options)="row">
              <b-button-group>
                <b-button variant="warning" v-if="row.index != indexEdit" class="btn-sm" @click="setEditMode(row.index, row.item)">Editar</b-button>
                <b-button variant="info" v-else class="btn-sm" @click="cancelEdit()">Cancelar Edición</b-button>
                <b-button variant="danger" class="btn-sm" @click="deletePeriodo()">Eliminar</b-button>
              </b-button-group>
            </template>
          </b-table>
        </div>
      </div>
    </ValidationObserver>
  </b-modal>
</template>
<script>
import VueCurrencyInput from 'vue-currency-input'
import helpers from '../../components/Helper'
export default {
  name: 'EditProduccionCronograma',
  components: {
    VueCurrencyInput
  },
  props: {
    showModal: {
      type: Boolean,
      default: false
    },
    id_dato: {
      type: String,
      default: null
    },
    id_datos_mina: {
      type: String,
      defualt: null
    }
  },
  data () {
    return {
      info: {
        valores: []
      },
      editMode: false,
      indexEdit: null,
      info_periodo: {},
      fields: [
        {key: 'ano', label: 'Año'},
        {key: 'periodo', label: 'Periodo'},
        {key: 'valor_desgloce_perforacion', label: 'Total Perforación'},
        {key: 'valor_desgloce_carguio', label: 'Total Carguío'},
        {key: 'valor_desgloce_tronadura', label: 'Total Tronadura'},
        {key: 'valor_desgloce_transporte', label: 'Total Transporte'},
        {key: 'valor_desgloce', label: 'Valor'},
        {key: 'options', label: 'Acciones'}
      ]
    }
  },
  created () {
    this.$nextTick(function () {
      this.getDato()
    })
  },
  methods: {
    ...helpers,
    getDato: function () {
      this.$store.commit('setLoading', true)
      axios.get('cronograma/produccion/'+this.id_dato).then((response) => {
        this.info = response.data
      }).catch((err) => {
        this.showToast({icon: 'error', title: err.response.data.message})
      }).finally(() => {
        this.$store.commit('setLoading', false)
      })
    },
    closeModal: function() {
      this.$emit('close')
    },
    clearInfoPeriodo: function () {
      this.info_periodo = {}
    },
    setEditMode: function (index, item) {
      this.editMode = true
      this.indexEdit = index
      console.log(item)
      this.info_periodo = JSON.parse(JSON.stringify(item))
    },
    cancelEdit: function () {
      this.editMode = false
      this.indexEdit = null
      this.clearInfoPeriodo()
    },
    agregarPeriodo: function () {
      this.info.valores.push(this.info_periodo)
      this.clearInfoPeriodo()
    },
    deletePeriodo: function (index) {
      this.info.valores.splice(index, 1)
      if(this.editMode) {
        this.cancelEdit()
      }
    },
    changePeriodo: function () {
      this.info.valores.splice(this.indexEdit, 1, this.info_periodo)
      this.cancelEdit()
    },
    addItem: function () {
      this.$store.commit('setLoading', true)
      this.info.id_datos_mina = this.id_datos_mina
      axios.put('cronograma/produccion/'+this.id_dato, this.info).then((response) => {
        this.closeModal()
        this.showToast({icon: 'success', title: 'Item editado correctamente'})
        this.$emit('edit');
      }).catch((err) => {
        this.showToast({icon:'error', title: err.response.data.message})
        this.$store.commit('setLoading', false)
      })
    }
  }
}
</script>