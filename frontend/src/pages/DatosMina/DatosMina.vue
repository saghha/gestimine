<template>
  <div class="col-lg-12 mt-2">
    <div class="card">
      <div class="card-body">
        <b-tabs>
          <b-tab title="Datos Generales">
            <div class="col-12">
              <b-table :items="datos" :fields="fields" responsive small hover no-border-collapse striped>
                <template #cell(actions)="row">
                  <b-button-group>
                    <b-button variant="primary" class="btn-sm">Editar</b-button>
                    <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                  </b-button-group>
                </template>
              </b-table>
            </div>
          </b-tab>
          <b-tab title="Mineral Recuperado">
            <div class="col-12">
              <b-table :items="datos_recuperados" :fields="fields" responsive small hover no-border-collapse striped>
                <template #cell(actions)="row">
                  <b-button-group>
                    <b-button variant="primary" class="btn-sm">Editar</b-button>
                    <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                  </b-button-group>
                </template>
              </b-table>
            </div>
          </b-tab>
          <b-tab title="Perforaciones">
            <b-table :items="datos_perforacion" :fields="fields" responsive small hover no-border-collapse striped>
              <template #cell(actions)="row">
                <b-button-group>
                  <b-button variant="primary" class="btn-sm">Editar</b-button>
                  <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                </b-button-group>
              </template>
            </b-table>
          </b-tab>
          <b-tab title="Tronadura">
            <b-table :items="datos_tronadura" :fields="fields" responsive small hover no-border-collapse striped>
              <template #cell(actions)="row">
                <b-button-group>
                  <b-button variant="primary" class="btn-sm">Editar</b-button>
                  <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                </b-button-group>
              </template>
            </b-table>
          </b-tab>
          <b-tab title="Operativos">
            <b-table :items="datos_operativos" :fields="fields" responsive small hover no-border-collapse striped>
              <template #cell(actions)="row">
                <b-button-group>
                  <b-button variant="primary" class="btn-sm">Editar</b-button>
                  <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                </b-button-group>
              </template>
            </b-table>
          </b-tab>
          <b-tab title="Tronadura de Producción">
            <b-table :items="datos_tronadura_produccion" :fields="fields" responsive small hover no-border-collapse striped>
              <template #cell(actions)="row">
                <b-button-group>
                  <b-button variant="primary" class="btn-sm">Editar</b-button>
                  <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                </b-button-group>
              </template>
            </b-table>
          </b-tab>
        </b-tabs>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'DatosMina',
  components: {

  },
  data () {
    return {
      fields: [
        {key: 'tipo', label: "Tipo de indicador"},
        {key: 'cantidad', label: "Cantidad"},
        {key: 'unidad_medida', label: "Unidad Medida"},
        {key: 'actions', label: 'Acciones'}
      ],
      datos: [
        {tipo: 'Densidad esteril', cantidad: 12.3, unidad_medida: 'MT'}
      ],
      datos_recuperados: [],
      datos_perforacion: [],
      datos_tronadura: [],
      datos_operativos: [],
      datos_tronadura_produccion: []
    }
  },
  created () {

  },
  methods: {

  }
}
</script>