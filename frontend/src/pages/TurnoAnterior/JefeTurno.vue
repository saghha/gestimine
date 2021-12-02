<template>
  <div class="content" style='background-image: linear-gradient(black, gray);'>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12" style="color:white" align="center">
            <div><h4>Avance Turno Anterior Jefe De Turno: Mauricio Aros Morales</h4></div>
            <h3>Registro De Periodo Anterior [22] (DEMO)</h3>
            <div>Fecha Actual: 02/10/2012</div>
            <div class="col-12 center">
              <div class="mt-2">
                <b-button variant="primary" @click="handleNewModal(true, 'infraestructura')" v-if="tab_selected == 'infraestructura'">Agregar Item Infraestructura</b-button>
                <b-button variant="primary" @click="handleNewModal(true, 'preparación')" v-if="tab_selected == 'preparación'">Agregar Item Preparación</b-button>
                <b-button variant="primary" @click="handleNewModal(true, 'producción')" v-if="tab_selected == 'producción'">Agregar Item Producción</b-button>
              </div>
              <div class="card mt-2">
                <div class="card-body">
                  <b-tabs>
                    <b-tab title="Infraestructuras" @click="selectTab('infraestructura')">
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
                    </b-tab>
                    <b-tab title="Preparación" @click="selectTab('preparacion')">
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
                                  <b-tr v-for="(item, index_item) in data_2" :key="index_item">
                                  <b-td style='color:white'>{{item.estructura}}</b-td>
                                  <b-td style='color:white'>{{item.tarea_intermedia}}: {{item.valor_tarea_intermedia}}%</b-td>
                                  <b-td style='color:white'>{{item.tarea_contable}}: {{item.valor_tarea_contable}} {{item.unidad_tarea_contable}}</b-td>
                                  </b-tr>
                              </b-tbody>
                          </b-table-simple>
                      </card>
                    </b-tab>
                    <b-tab title="Producción" @click="selectTab('produccion')">
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
                                  <div v-if="data_3[1]">
                                    <b-tr v-for="(item, index_item) in data_3" :key="index_item">
                                    <b-td style='color:white'>{{item.estructura}}</b-td>
                                    <b-td style='color:white'>{{item.tarea_intermedia}}: {{item.valor_tarea_intermedia}}%</b-td>
                                    <b-td style='color:white'>{{item.tarea_contable}}: {{item.valor_tarea_contable}} {{item.unidad_tarea_contable}}</b-td>
                                    </b-tr>
                                  </div>
                                  <div v-else>
                                    <b-td><span style='color:white'>No existen tareas asignadas a este item.</span></b-td>
                                  </div>
                              </b-tbody>
                          </b-table-simple>
                      </card>
                    </b-tab>
                  </b-tabs>
                </div>
              </div>
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
          //COLOCAR TABS DE PERFORACION, TRONADURA Y CARGUIO_TRANSPORTE
          //ESTA INFORMACION DEBE CONSTRUIRSE LLAMANDO A LAS 4 TABLAS DE TAREAS E IR AGREGANDO MANUALMENTE LA UNIDAD. SE ORDENAN POR ESTRUCTURA ALFABETICAMENTE
          {
              estructura: "Estructura Rampa 1-A",
              tarea_intermedia: "Aislamiento de Sector",
              valor_tarea_intermedia: 20,
              tarea_contable: "Perforacion",
              valor_tarea_contable: 240,
              unidad_tarea_contable: "m"
          },
          {
              estructura: "Estructura Rampa 1-B",
              tarea_intermedia: "Revision de seguridad",
              valor_tarea_intermedia: 47,
              tarea_contable: "Tronadura",
              valor_tarea_contable: 1,
              unidad_tarea_contable: "Un."
          },
        ],
        data_2: [
          //ESTA INFORMACION DEBE CONSTRUIRSE LLAMANDO A LAS 4 TABLAS DE TAREAS, LA INFORMACION DE TAREAS CONTABLES ES CALCULADA POR TURNO CON EL DATOS MINA
          {
              estructura: "Preparacion Caseron",
              tarea_intermedia: "Posicionamiento de equipo",
              valor_tarea_intermedia: 38,
              tarea_contable: "Perforación",
              valor_tarea_contable: 20,
              unidad_tarea_contable: "m"
          }
        ],
        data_3: [
          //ESTA INFORMACION DEBE CONSTRUIRSE LLAMANDO A LAS 4 TABLAS DE TAREAS, LA INFORMACION DE TAREAS CONTABLES ES CALCULADA POR TURNO CON EL DATOS MINA
          
        ]
      }
    },
    methods: {
        
    }
  }
</script>
<style>
</style>
