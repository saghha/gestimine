<template>
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-header">
        <h3>Generar Alerta!</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
              <label>Sección Productiva</label>
              <v-select label="text" v-model="seccion_selected" :options="secciones" @input="SelectSeccion"></v-select>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
              <label>Item</label>
              <v-select label="nombre" v-model="info.item" :options="items" :reduce="item => item.nombre"></v-select>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6">
            <b-button :variant="styleButtonIncidente" size="lg" class="btn-block" @click="selectButton('incidente')">Incidente</b-button>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6">
            <b-button :variant="styleButtonAccidente" size="lg" class="btn-block" @click="selectButton('accidente')">Accidente</b-button>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label>Evento</label>
              <v-select label="text" v-model="evento_selected" :options="eventos" @input="selectEvento"></v-select>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label>Resultados</label>
              <v-select v-model="info.resultado" :options="resultados"></v-select>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label>Observaciones</label>
              <b-form-textarea
                id="textarea"
                v-model="info.observaciones"
                rows="3"
                max-rows="6"
              ></b-form-textarea>
            </div>
          </div>
          <div class="col-12">
            <b-button variant="info" block @click="sendEmail()">Enviar</b-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import helpers from '../../components/Helper'
import moment from 'moment'
export default {
  name: 'Alertas',
  data () {
    return {
      secciones: [
        {text: 'Infraestructuras', value: 'infraestructura'},
        {text: 'Preparación', value: 'preparacion'},
        {text: 'Producción', value: 'produccion'},
      ],
      items: [],
      data: null,
      evento_selected: null,
      seccion_selected: null,
      btn_selected: null,
      eventos: [],
      resultados: [],
      eventos_incidente: [
        {text: 'Estructural', value: 'estructural', resultados: ['Grieta', 'Sostenimiento', 'Ventilación', 'Líneas de Suministro']},
        {text: 'Mecánico', value: 'mecánico', resultados: ['Fallas', 'Vibraciones', 'Desgaste']},
        {text: 'Ambiental', value: 'ambiental', resultados: ['Equipos de Contención', 'Medidas de Contención']},
        {text: 'Seguridad', value: 'seguridad', resultados: ['Medidas de Seguridad', 'Estructuras de Seguridad']},
      ],
      eventos_accidente: [
        {text: 'Estructural', value: 'estructural', resultados: ['Incendio', 'Derrumbe', 'Ventilación']},
        {text: 'Maquinaria', value: 'maquinaria', resultados: ['Choque', 'Atropello', 'Caída']},
        {text: 'Personal', value: 'personal', resultados: ['Sin Afectados', 'Heridos', 'Muertes']},
        {text: 'Ambiental', value: 'ambiental', resultados: ['Derrame Ácido', 'Derrame de combustible', 'Polución', 'Gases Tóxicos']},
      ],
      info: {
        resultado: null,
        item: null,
        observaciones: null
      }
    }
  },
  computed: {
    styleButtonIncidente () {
      if(this.btn_selected == 'incidente') {
        return 'warning'
      } else {
        return 'outline-warning'
      }
    },
    styleButtonAccidente () {
      if(this.btn_selected == 'accidente') {
        return 'danger'
      } else {
        return 'outline-danger'
      }
    }
  },
  created () {
    // Acá obtiene la info para mostrar en los desplegables
    this.$nextTick(function () {
      this.getItems()
    })
  },
  methods: {
    ...helpers,
    getItems: function () {
      this.$store.commit('setLoading', true)
      axios.get('registro-datos/evento/items').then((response) => {
        this.data = response.data
      }).catch((err) => {
        this.showToast({icon: 'error', title: err.response.data.message})
      }).finally(() => {
        this.$store.commit('setLoading', false)
      })
    },
    SelectSeccion: function (payload) {
      this.info.item = null
      if(payload) {
        this.items = this.data[payload.value]
        this.info.seccion = payload.text
      } else {
        this.info.seccion = null
        this.items = []
      }
    },
    selectButton: function (type) {
      this.info.evento = null
      this.info.resultado = null
      this.resultados = []
      this.evento_selected = null
      if(this.btn_selected == type) {
        this.btn_selected = null
        this.eventos = []
      } else {
        if(type == 'accidente') {
          this.eventos = JSON.parse(JSON.stringify(this.eventos_accidente))
        } else {
          this.eventos = JSON.parse(JSON.stringify(this.eventos_incidente))
        }
        this.btn_selected = type
      }
    },
    selectEvento: function (payload) {
      this.info.resultado = null
      if(payload) {
        this.resultados = payload.resultados
        this.info.evento = payload.value
      } else {
        this.info.evento = null
        this.resultados = []
      }
    },
    sendEmail: function () {
      this.$store.commit('setLoading', true)
      var data = {
        "operacion_infraestructura": "OPERACION 1",
        "periodo": this.data.periodo,
        "ano": moment().format('YYYY').toString(),
        "fecha": moment().format('DD/MM/YYYY').toString(),
        "evento": this.btn_selected,
        "tipo": this.info.seccion,
        "resultado": this.info.resultado,
        "mensaje": this.info.observaciones
      }
      axios.post('registro-datos/evento', data).then((response) => {
        this.showToast({icon: 'success', title: 'Correo de alerta enviado'})
      }).catch((err) => {
        this.showToast({icon: 'error', title: err.response.data.message})
      }).finally(() => {
        this.$store.commit('setLoading', false)
      })
    }
  }
}
</script>