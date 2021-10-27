<template>
  <div class="content" style='background-image: linear-gradient(black, gray);'>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12" style="color:white" align="center">
            <div><h4>Trabajo a realizar operador.</h4></div>
            <div>Fecha Actual: 02/10/2012</div>
            <div class="col-12">
                <card class="strpied-tabled-with-hover" body-classes="table-full-width table-responsive" style='background-color: gray;'>
                    <b-table-simple hover small caption-top responsive style='background-color: gray;'>
                        <b-thead>
                            <b-tr>
                                <b-th sticky-column style='background-color: gray;color:white'>Estructura</b-th>
                                <b-th sticky-column style='background-color: gray;color:white'>Tareas Intermedias</b-th>
                                <b-th sticky-column style='background-color: gray;color:white'>Tareas Contables</b-th>
                            </b-tr>
                        </b-thead>
                        <b-tbody>
                            <b-tr v-for="(item, index_item) in data" :key="index_item">
                            <b-td style='color:white'>{{item.estructura}}</b-td>
                            <b-td style='color:white'>{{item.tarea_intermedia}}: {{item.valor_tarea_intermedia}}%</b-td>
                            <b-td style='color:white'>{{item.tarea_contable}}: {{item.valor_tarea_contable}} {{item.unidad_tarea_contable}}</b-td>
                            </b-tr>
                        </b-tbody>
                    </b-table-simple>
                </card>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import LTable from 'src/components/Table.vue'
  import Card from 'src/components/Cards/Card.vue'
  export default {
    components: {
      LTable,
      Card
    },
    data () {
      return {
        data: [
          //ESTA INFORMACION DEBE CONSTRUIRSE LLAMANDO A LAS 4 TABLAS DE TAREAS, LA INFORMACION DE TAREAS CONTABLES ES CALCULADA POR TURNO CON EL DATOS MINA
          {
              estructura: "estructura A",
              tarea_intermedia: "Aislamiento de Sector",
              valor_tarea_intermedia: 0,
              tarea_contable: "Perforacion",
              valor_tarea_contable: 240,
              unidad_tarea_contable: "m"
          },
          {
              estructura: "estructura B",
              tarea_intermedia: "Revision de seguridad",
              valor_tarea_intermedia: 0,
              tarea_contable: "Tronadura",
              valor_tarea_contable: 1,
              unidad_tarea_contable: "Un."
          },
        ]
      }
    }
  }
</script>
<style>
</style>
