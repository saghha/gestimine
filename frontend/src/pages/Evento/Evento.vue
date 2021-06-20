<template>
  <div class="col-lg-12">
    <div class="card mt-2">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <b-table striped hover responsive small :items="eventos" :fields="fields">
              <template #cell(fecha)="row">
                {{formatDate(row.item.fecha)}}
              </template>
            </b-table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import helpers from '../../components/Helper'
export default {
  name: 'ListEvents',
  data () {
    return {
      eventos: [],
      fields: [
        {key: 'evento', label: 'Evento'},
        {key: 'tipo_evento', label: 'Tipo Evento'},
        {key: 'resultado', label: 'Resultado'},
        {key: 'mensaje', label: 'Mensaje'},
        {key: 'fecha', label: 'Fecha Evento'},
        {key: 'emisor', label: 'Emisor'},
      ]
    }
  },
  created () {
    this.$nextTick(function () {
      this.getEventos()
    })
  },
  methods: {
    ...helpers,
    getEventos: function () {
      this.$store.commit('setLoading', true)
      axios.get('registro-datos/evento').then((response) => {
        this.eventos = response.data
      }).catch((err) => {
        this.showToast({icon: 'error', title: err.response.data.message})
      }).finally(() => {
        this.$store.commit('setLoading', false)
      })
    }
  }
}
</script>