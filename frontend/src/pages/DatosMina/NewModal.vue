<template>
  <b-modal v-model="showModal" size="lg" v-if="showModal" hide-footer @hide="closeModal()">
    <template v-slot:modal-title>
      <strong>Crear Datos Mina</strong>
    </template>
    <ValidationObserver v-slot="{invalid}">
      <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="tipo_dato" :class="{'text-danger': v.failedRules.required}">Tipo de Dato Minero</label>
              <v-select :options="tipos_datos" v-model="datos.tipo_dato" :reduce="item => item.value" label="text"/>
              <span class="text-danger" v-if="v.failedRules.required">El tipo de dato es requerido</span>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="tipo" :class="{'text-danger': v.failedRules.required}">Nombre Indicador</label>
              <b-form-input v-model="datos.tipo" id="tipo"/>
              <span class="text-danger" v-if="v.failedRules.required">El nombre del indicador es requerido</span>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="cantidad" :class="{'text-danger': v.failedRules.required}">Cantidad</label>
              <b-form-input type="number" v-model="datos.cantidad" id="cantidad"/>
              <span class="text-danger" v-if="v.failedRules.required">La cantidad es requerida</span>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-6">
          <ValidationProvider rules="required" v-slot="v">
            <div class="form-group">
              <label for="unidad_medida" :class="{'text-danger': v.failedRules.required}">Unidad de Medida</label>
              <b-form-input v-model="datos.unidad_medida" id="unidad_medida"/>
              <span class="text-danger" v-if="v.failedRules.required">La unidad de medida es requerida</span>
            </div>
          </ValidationProvider>
        </div>
        <div class="col-12 text-right">
          <b-button variant="success">Agregar Dato de Mina</b-button>
        </div>
      </div>
    </ValidationObserver>
  </b-modal>
</template>
<script>
export default {
  name: 'EditDatosMina',
  props: {
    showModal: {
      type: Boolean,
      default: false
    },
  },
  data () {
    return {
      datos : {},
      tipos_datos: [
        {value: 'datos_generales', text: 'Datos Generales'},
        {value: 'mineral_recuperado', text: 'Mineral Recuperado'},
        {value: 'perforaciones', text: 'Perforaciones'},
        {value: 'tronadura', text: 'Tronadura'},
        {value: 'operativos', text: 'Operativos'},
        {value: 'tronadura_produccion', text: 'Tronadura de Producción'},
      ]
    }
  },
  created () {
  },
  methods: {
    closeModal: function () {
      this.$emit('close')
    },
    createInfo: function () {
      //aca se hace la consulta axios
    }
  }
}
</script>
<style>
.modal-header {
  padding: 1rem 1rem !important;
  border-bottom: 1px solid #dee2e6 !important;
}
.h5 {
  font-size: 1.25rem !important;
}
</style>