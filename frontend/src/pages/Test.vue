<template>
  <div class="col-lg-12">
    <b-table :items="items" :fields="fields" :sticky-header="true" no-border-collapse responsive small>
    </b-table>
  </div>
</template>
<script>
export default {
  name: "Test",
  components: {

  },
  data () {
    return {
      items: [
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
        {numero: '0', nombre: 'nombre', apellido: 'apellido', periodo1: 'periodo1', periodo2: 'periodo2', periodo3: 'periodo3', periodo4: 'periodo4', periodo5: 'periodo5',periodo6: 'periodo6', periodo7: 'periodo7', periodo8: 'periodo8',periodo9: 'periodo9', total: 'total', accion: 'acciones'},
      ],
      fields: [
        {key: 'numero', label: 'Numero', stickyColumn: true, isRowHeader: true},
        {key: 'nombre', label: 'nombre', stickyColumn: true, isRowHeader: true},
        {key: 'apellido', label: 'apellido'},
        {key: 'periodo1', label: 'periodo1'},
        {key: 'periodo2', label: 'periodo2'},
        {key: 'periodo3', label: 'periodo3'},
        {key: 'periodo4', label: 'periodo4'},
        {key: 'periodo5', label: 'periodo5'},
        {key: 'periodo6', label: 'periodo6'},
        {key: 'periodo7', label: 'periodo7'},
        {key: 'periodo8', label: 'periodo8'},
        {key: 'periodo9', label: 'periodo9'},
        {key: 'periodo10', label: 'periodo10'},
        {key: 'periodo11', label: 'periodo11'},
        {key: 'periodo12', label: 'periodo12'},
        {key: 'periodo13', label: 'periodo13'},
        {key: 'total', label: 'total', stickyColumn: true},
        {key: 'accion', label: 'accion', stickyColumn: true},
      ]
    }
  },
  created () {

  },
  methods: {

  }
}
</script>