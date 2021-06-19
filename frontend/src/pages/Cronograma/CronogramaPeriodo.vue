<template>
  <b-modal v-model="showModal" v-if="showModal" size="xl" @hide="closeModal()" hide-footer>
    <b-tabs>
      <b-tab title="Infraestructuras">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <b-table-simple hover small caption-top responsive>
              <b-thead>
                <b-tr>
                  <b-th sticky-column>Nombre Infraestructura</b-th>
                  <b-th sticky-column>Área</b-th>
                  <b-th sticky-column>Sección</b-th>
                  <b-th sticky-column>N° Tiros</b-th>
                  <b-th sticky-column>Metros Totales</b-th>
                  <b-th v-for="(periodo, index_field) in data.periodo_infraestructura" :key="index_field">
                    {{periodo.label}}
                  </b-th>
                  <b-th>Total</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <b-tr v-for="(item, index_item) in data.infraestructura" :key="index_item">
                  <b-td>{{item.nombre}}</b-td >
                  <b-td>{{item.area}}</b-td>
                  <b-td>{{item.seccion}}</b-td>
                  <b-td>{{item.nro_tiros}}</b-td>
                  <b-td>{{item.longitud}}</b-td>
                  <b-td class="text-center" v-for="(value_ano, index_ano) in data.periodo_infraestructura" :key="index_ano">
                    {{mostrarValor(value_ano, item)}}
                  </b-td>
                  <b-td>{{formatAllMoney(item.total_desgloce_total)}}</b-td>
                </b-tr>
              </b-tbody>
            </b-table-simple>
          </div>
        </div>
      </b-tab>
      <b-tab title="Preparación">
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
                  <b-th v-for="(periodo, index_field) in data.periodo_preparacion" :key="index_field">
                    {{periodo.label}}
                  </b-th>
                  <b-th>Total</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <b-tr v-for="(item, index_item) in data.preparacion" :key="index_item">
                  <b-td>{{item.nombre}}</b-td >
                  <b-td>{{item.area}}</b-td>
                  <b-td>{{item.seccion}}</b-td>
                  <b-td>{{item.nro_tiros}}</b-td>
                  <b-td>{{item.longitud}}</b-td>
                  <b-td class="text-center" v-for="(value_ano, index_ano) in data.periodo_preparacion" :key="index_ano">
                    {{mostrarValor(value_ano, item)}}
                  </b-td>
                  <b-td>{{formatAllMoney(item.total_desgloce_total)}}</b-td>
                </b-tr>
              </b-tbody>
            </b-table-simple>
          </div>
        </div>
      </b-tab>
      <b-tab title="Producción">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <b-table-simple hover small caption-top responsive>
              <b-thead>
                <b-tr>
                  <b-th sticky-column>Nombre Producción</b-th>
                  <b-th v-for="(periodo, index_field) in data.periodo_produccion" :key="index_field">
                    {{periodo.label}}
                  </b-th>
                  <b-th>Total</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <b-tr v-for="(item, index_item) in data.produccion" :key="index_item">
                  <b-td>{{item.nombre}}</b-td >
                  <b-td class="text-left" v-for="(value_ano, index_ano) in data.periodo_produccion" :key="index_ano">
                    {{mostrarValor(value_ano, item)}}
                  </b-td>
                  <b-td>{{formatAllMoney(item.total_desgloce_total)}}</b-td>
                </b-tr>
              </b-tbody>
            </b-table-simple>
          </div>
        </div>
      </b-tab>
    </b-tabs>
  </b-modal>
</template>
<script>
import helpers from '../../components/Helper'
export default {
  name: 'CronogramaPeriodo',
  props: {
    data: {
      type: Array,
      default: []
    },
    showModal: {
      type: Boolean,
      default: false
    },
  },
  data () {
    return {
    }
  },
  created () {

  },
  methods: {
    ...helpers,
    closeModal: function() {
      this.$emit('close')
    },
    mostrarValor: function (value_ano, item) {
      console.log(value_ano, item)
      var data = _.find(item.valores, (value, index) => {return value_ano.key == index})
      console.log(data)
      if(!!data) {
        return this.formatAllMoney(data.valor_desgloce_periodo)
      } else {
        return 0
      }
    },
  }
}
</script>