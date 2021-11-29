<template>
  <div class="col-lg-12">
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
                      <b-th sticky-column>Área [mt2]</b-th>
                      <b-th sticky-column>Sección</b-th>
                      <b-th sticky-column>N° Tiros</b-th>
                      <b-th sticky-column>Toneladas</b-th>
                      <b-th v-for="(anio, index_field) in anos_infraestructura" :key="index_field">
                        <div class="pointer" @click="selectValue(anio.key)">
                          {{anio.label}}
                        </div>
                        </b-th>
                      <b-th>Total</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr v-for="(item, index_item) in infraestructuras" :key="index_item">
                      <b-td>{{item.nombre}}</b-td>
                      <b-td>{{item.area}}</b-td>
                      <b-td>{{item.seccion}}</b-td>
                      <b-td>{{item.nro_tiros}}</b-td>
                      <b-td>{{item.toneladas}}</b-td>
                      <b-td class="text-center" v-for="(value_ano, index_ano) in anos_infraestructura" :key="index_ano">
                        {{mostrarValor(value_ano, item)}}
                      </b-td>
                      <b-td>{{formatAllMoney(item.total_desgloce_anual)}}</b-td>
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
                      <b-th sticky-column>Toneladas</b-th>
                      <b-th v-for="(anio, index_field) in anos_preparaciones" :key="index_field">
                        <div class="pointer" @click="selectValue(anio.key)">
                          {{anio.label}}
                        </div>
                        </b-th>
                      <b-th>Total</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr v-for="(item, index_item) in preparaciones" :key="index_item">
                      <b-td>{{item.nombre}}</b-td>
                      <b-td>{{item.area}}</b-td>
                      <b-td>{{item.seccion}}</b-td>
                      <b-td>{{item.nro_tiros}}</b-td>
                      <b-td>{{item.toneladas}}</b-td>
                      <b-td class="text-center" v-for="(value_ano, index_ano) in anos_preparaciones" :key="index_ano">
                        {{mostrarValor(value_ano, item)}}
                      </b-td>
                      <b-td>{{formatAllMoney(item.total_desgloce_anual)}}</b-td>
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
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr v-for="(item, index_item) in produccion" :key="index_item">
                      <b-td>{{item.nombre}}</b-td>
                      <b-td class="text-center" v-for="(value_ano, index_ano) in anos_produccion" :key="index_ano">
                        {{mostrarValor(value_ano, item)}}
                      </b-td>
                      <b-td>{{formatAllMoney(item.total_desgloce_anual)}}</b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </div>
            </div>
          </b-tab>
        </b-tabs>
      </div>
    </div>
    <CronogramaPeriodo
      v-if="showPeriodoModal"
      :showModal="showPeriodoModal"
      :data="info_periodos"
      :ano="ano_consultado"
      @close="handlePeriodoModal(false)"/>
  </div>
</template>
<script>
import CronogramaPeriodo from './CronogramaPeriodo.vue'
import helpers from '../../components/Helper'
export default {
  name: 'PlanMinero',
  components: {
    CronogramaPeriodo,
  },
  data () {
    return {
      datos: [],
      info_periodos: [],
      infraestructuras: [],
      preparaciones: [],
      produccion: [],
      selectedEdit: {},
      ano_consultado: null,
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
      axios.get('cronograma/infraestructura/mostrar-plan-mina-anual', {
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
    selectValue: function (anio) {
      this.$store.commit('setLoading', true)
      axios.get('cronograma/infraestructura/mostrar-plan-mina-periodo', {
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
    }
  }
}
</script>
<style>
.pointer {
  cursor: pointer;
}
</style>