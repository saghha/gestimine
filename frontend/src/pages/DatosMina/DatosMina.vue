<template>
  <div class="col-lg-12 mt-2">
    <div class="mt-2 mb-2">
      <b-button variant="primary" class="" @click="handleNewModal(true)">Agregar Dato de Mina</b-button>
    </div>
    <div class="card">
      <div class="card-body">
        <b-tabs>
          <b-tab title="Datos Generales">
            <div class="col-12">
              <b-table :items="datos" :fields="fields" responsive small hover no-border-collapse striped>
                <template #cell(actions)="row">
                  <b-button-group>
                    <b-button variant="primary" class="btn-sm" @click="handleEditModal(row.item, 'datos_generales', true)">Editar</b-button>
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
                    <b-button variant="primary" class="btn-sm" @click="handleEditModal(row.item, 'mineral_recuperado', true)">Editar</b-button>
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
                  <b-button variant="primary" class="btn-sm" @click="handleEditModal(row.item, 'perforaciones', true)">Editar</b-button>
                  <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                </b-button-group>
              </template>
            </b-table>
          </b-tab>
          <b-tab title="Tronadura">
            <b-table :items="datos_tronadura" :fields="fields" responsive small hover no-border-collapse striped>
              <template #cell(actions)="row">
                <b-button-group>
                  <b-button variant="primary" class="btn-sm" @click="handleEditModal(row.item, 'tronadura', true)">Editar</b-button>
                  <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                </b-button-group>
              </template>
            </b-table>
          </b-tab>
          <b-tab title="Operativos">
            <b-table :items="datos_operativos" :fields="fields" responsive small hover no-border-collapse striped>
              <template #cell(actions)="row">
                <b-button-group>
                  <b-button variant="primary" class="btn-sm" @click="handleEditModal(row.item, 'operativos', true)">Editar</b-button>
                  <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                </b-button-group>
              </template>
            </b-table>
          </b-tab>
          <b-tab title="Tronadura de Producción">
            <b-table :items="datos_tronadura_produccion" :fields="fields" responsive small hover no-border-collapse striped>
              <template #cell(actions)="row">
                <b-button-group>
                  <b-button variant="primary" class="btn-sm" @click="handleEditModal(row.item, 'tronadura_produccion', true)">Editar</b-button>
                  <b-button variant="danger" class="btn-sm">Eliminar</b-button>
                </b-button-group>
              </template>
            </b-table>
          </b-tab>
        </b-tabs>
      </div>
    </div>
    <edit-modal :showModal="showEditModal" v-if="showEditModal" :info="itemSelected" @close="handleEditModal(null, false)"/>
    <new-modal :showModal="showNewModal" v-if="showNewModal" @close="handleNewModal(false)"/>
  </div>
</template>
<script>
import EditModal from './EditModal.vue'
import NewModal from './NewModal.vue'
export default {
  name: 'DatosMina',
  components: {
    EditModal,
    NewModal
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
      itemSelected: null,
      datos_recuperados: [],
      datos_perforacion: [],
      datos_tronadura: [],
      datos_operativos: [],
      datos_tronadura_produccion: [],
      showEditModal: false,
      showNewModal: false,
    }
  },
  created () {

  },
  methods: {
    handleEditModal: function (item ,tipo, cond) {
      if(cond) {
        this.itemSelected = JSON.parse(JSON.stringify(item))
        this.itemSelected.tipo_dato = tipo
      } else {
        this.itemSelected = null
      }
      this.showEditModal = cond
    },
    handleNewModal: function (cond) {
      this.showNewModal = cond
    },
    deleteDato: function (item) {
      //axios para eliminar
    }
  }
}
</script>