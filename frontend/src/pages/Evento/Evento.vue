<template>
  <div class="col-lg-12">
    <div class="card mt-2">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <b-table striped hover responsive small :items="eventos" :fields="fields">
              <template #cell(evento)="row">
                <b-badge variant="warning" v-if="row.item.evento == 'INCIDENTE'">{{row.item.evento}}</b-badge>
                <b-badge variant="danger" v-else>{{row.item.evento}}</b-badge>
              </template>
              <template #cell(fecha)="row">
                {{formatDate(row.item.fecha)}}
              </template>
              <template #cell(emisor)="row">
                {{row.item.user.name}}
              </template>
            </b-table>
            <div class="overflow-auto">
              <b-pagination
                v-model="data.current_page"
                :total-rows="data.total"
                :per-page="data.per_page"
                @change="getEventos"
              ></b-pagination>
            </div>
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
      data: {},
      eventos: [],
      fields: [
        {key: 'evento', label: 'Evento'},
        {key: 'tipo', label: 'Tipo Evento'},
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
    getEventos: function (page = 1) {
      this.$store.commit('setLoading', true)
      axios.get('registro-datos/evento', {
        params: {
          page: page
        }
      }).then((response) => {
        this.data = response.data
        this.eventos = response.data.data
      }).catch((err) => {
        this.showToast({icon: 'error', title: err.response.data.message})
      }).finally(() => {
        this.$store.commit('setLoading', false)
      })
    }
  }
}
</script>