<template>
  <div class="col-lg-12 mt-2">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <h4 class="card-title">Datos Mina</h4>
          </div>
          <div class="col-6 text-right">
            <b-button variant="info" class="" v-if="!editMode" @click="handleEdit(true)">Habilitar Edición</b-button>
            <b-button variant="warning" class="" v-else @click="handleEdit(false)">Cancelar Edición</b-button>
          </div>
        </div>
        <div class="mb-2"></div>
        <ValidationObserver v-slot="{invalid}">
          <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Periodos por año</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.periodo_por_ano" locale="es" :precision="0" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Meses por periodo</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.meses_por_periodo" locale="es" :precision="0" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Días por mes</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.dias_por_mes" locale="es" :precision="0" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Turnos por día</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.turnos_por_dia" locale="es" :precision="0" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Fecha de Inicio</label>
                  <VueDatePicker v-model="fecha_inicio" class="form-control" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Avance Tronadura</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.avance_tronadura" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Toneladas incorporadas en Tronadura</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.toneladas_incorporadas_tronadura" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Ritmo Extracción</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.ritmo_extraccion" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Mineral Recuperado Módulo</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.mineral_recuperado_modulo" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Mineral Recuperado Pilares</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.mineral_recuperado_pilares" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Densidad Estéril</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.densidad_esteril" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Densidad Mineral</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.densidad_mineral" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Densidad Dilusión</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.densidad_dilusion" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Ley Estéril</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.ley_esteril" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Ley Mineral</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.ley_mineral" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Ley Dilusión</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.ley_diluida" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Tiros por Metro Cuadrado</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.tiros_por_m2" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
              <ValidationProvider rules="required" v-slot="v">
                <div class="form-group">
                  <label :class="{'text-danger': v.failedRules.required}">Profundidad de Tiro</label>
                  <currency-input class="form-control" :currency="null" v-model="datos_mina.profundidad_tiro" locale="es" :precision="2" :disabled="!editMode"/>
                </div>
              </ValidationProvider>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right pt-4">
              <b-button variant="success" v-if="editMode" class="" @click="validateEdit()" :disabled="invalid">Subir Cambios</b-button>
            </div>
          </div>
        </ValidationObserver>
      </div>
    </div>
  </div>
</template>
<script>
import helpers from '../../components/Helper'
import moment from 'moment'
export default {
  name: 'DatosMina',
  components: {
  },
  data () {
    return {
      datos_mina: {
        'periodo_por_ano': 6,
        'meses_por_periodo': 2,
        'dias_por_mes': 30,
        'turnos_por_dia': 2,
        'fecha_inicio': '2020-01-01',
        'avance_tronadura': 1.1,
        'toneladas_incorporadas_tronadura': 1.2,
        'ritmo_extraccion': 1.3,
        'mineral_recuperado_modulo': 1.4,
        'mineral_recuperado_pilares': 1.5,
        'densidad_esteril': 1.2,
        'densidad_mineral': 0.1,
        'densidad_dilusion': 0.3,
        'ley_esteril': 2,
        'ley_mineral': 4,
        'ley_diluida': 7,
        'tiros_por_m2': 5,
        'profundidad_tiro': 5
      },
      fecha_inicio: null,
      editMode: false,
      respaldo_datos_mina: null
    }
  },
  created () {
    this.$nextTick(function () {
      this.$store.commit('setLoading', true)
      var promises = []
      promises.push(this.getDatosMina())

      Promise.all(promises).then(() => {
        this.$store.commit('setLoading', false)
      }).catch(() => {
        this.$store.commit('setLoading', false)
      })
    })
  },
  methods: {
    ...helpers,
    getDatosMina: function () {
      return axios.get('datos-mina/ultimo').then((response) => {
        this.datos_mina = response.data
        this.fecha_inicio = response.data.fecha_inicio
      }).catch((err) => {
        this.showToast({icon: 'error', title: err.response.data.message})
      })
    },
    handleEdit: function (cond) {
      if(!cond) {
        this.datos_mina = JSON.parse(JSON.stringify(this.respaldo_datos_mina))
      } else {      
        this.respaldo_datos_mina = JSON.parse(JSON.stringify(this.datos_mina))
      }
      this.editMode = cond
    },
    validateEdit: function () {
      this.$swal({
        title: '¿Seguro?',
        icon: 'question',
        text: '¿Estás seguro de realizar los cambios?',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Subir Cambio',
        confirmButtonColor: '#ffc107'
      }).then((result) => {
        console.log(result)
        if(result.value) {
          console.log("confirma")
          //this.submitEdit()
        } else {
          console.log('rechaza')
        }
      })
    },
    submitEdit: function () {
      this.$store.commit('setLoading', true)
      this.datos_mina.fecha_inicio = moment(this.fecha_inicio).format('DD/MM/YYYY').toString()
      axios.put('datos-mina/informacion/'+this.datos_mina.slug, this.datos_mina).then((response) => {
        this.showToast({icon: 'success', title: response.data.message})
        this.respaldo_datos_mina = JSON.parse(JSON.stringify(this.datos_mina))
      }).catch((err) => {
        this.showToast({icon: 'error', title: err.response.data.message})
      }).finally(() => {
        this.$store.commit('setLoading', false)
      })
    }
  }
}
</script>