<template>
  <div class="content" style='background-image: linear-gradient(black, gray);'>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12" style="color:white" align="center">
            <div><h4>Registrar Avance Jefe De Turno: Mauricio Aros Morales</h4></div>
            <h3>Registro De Periodo [1] (DEMO)</h3>
            <div>Fecha Actual: {{timestamp}}</div>
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
                          <div class="" v-if="data[0]" align="center">
                          <b-table-simple hover small caption-top responsive style='background-color: gray;'>
                              <b-thead>
                                  <b-tr align="center">
                                      <b-th sticky-column style='background-color: gray;color:white'>Estructura</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Tareas Intermedias</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Valor</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Actualizar</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Finalizar</b-th>
                                  </b-tr>
                              </b-thead>
                              <b-tbody>
                                    <b-tr v-for="(item, index_item) in data" :key="index_item" align="center">
                                      <b-td style='color:white'>{{item.estructura}}</b-td>
                                      <b-td style='color:white'>{{item.tarea_intermedia}}</b-td>
                                      <b-td style='color:white'>{{item.valor_tarea_intermedia}} {{item.unidad_tarea_intermedia}}</b-td>
                                      <b-td style='color:white'><b-button variant="info" @click="selectItem()" align="rigth">Actualizar</b-button></b-td>
                                      <b-td style='color:white'><b-button variant="warning" @click="selectItem()" >Terminar Tarea</b-button></b-td>
                                    </b-tr>
                              </b-tbody>
                          </b-table-simple>
                          </div>
                          <div v-else>
                            <b-td><span style='color:white'>No existen tareas asignadas a este item.</span></b-td>
                          </div>
                      </card>
                    </b-tab>
                    <b-tab title="Preparación" @click="selectTab('preparacion')">
                      <card class="strpied-tabled-with-hover" body-classes="table-full-width table-responsive" style='background-color: gray;'>
                          <div class="" v-if="data_2[0]" align="center">
                          <b-table-simple hover small caption-top responsive style='background-color: gray;'>
                              <b-thead>
                                  <b-tr align="center">
                                      <b-th sticky-column style='background-color: gray;color:white'>Estructura</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Tareas Intermedias</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Valor</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Actualizar</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Finalizar</b-th>
                                  </b-tr>
                              </b-thead>
                              <b-tbody>
                                    <b-tr v-for="(item, index_item) in data_2" :key="index_item" align="center">
                                      <b-td style='color:white'>{{item.estructura}}</b-td>
                                      <b-td style='color:white'>{{item.tarea_intermedia}}</b-td>
                                      <b-td style='color:white'>{{item.valor_tarea_intermedia}} {{item.unidad_tarea_intermedia}}</b-td>
                                      <b-td style='color:white'><b-button variant="info" @click="selectItem()" align="rigth">Actualizar</b-button></b-td>
                                      <b-td style='color:white'><b-button variant="warning" @click="selectItem()" >Terminar Tarea</b-button></b-td>
                                    </b-tr>
                              </b-tbody>
                          </b-table-simple>
                          </div>
                          <div v-else>
                            <b-td><span style='color:white'>No existen tareas asignadas a este item.</span></b-td>
                          </div>
                      </card>
                    </b-tab>
                    <b-tab title="Producción" @click="selectTab('produccion')">
                      <card class="strpied-tabled-with-hover" body-classes="table-full-width table-responsive" style='background-color: gray;'>
                          <div class="" v-if="data_3[0]" align="center">
                          <b-table-simple hover small caption-top responsive style='background-color: gray;'>
                              <b-thead>
                                  <b-tr align="center">
                                      <b-th sticky-column style='background-color: gray;color:white'>Estructura</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Tareas Intermedias</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Valor</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Actualizar</b-th>
                                      <b-th sticky-column style='background-color: gray;color:white'>Finalizar</b-th>
                                  </b-tr>
                              </b-thead>
                              <b-tbody>
                                    <b-tr v-for="(item, index_item) in data_3" :key="index_item" align="center">
                                      <b-td style='color:white'>{{item.estructura}}</b-td>
                                      <b-td style='color:white'>{{item.tarea_intermedia}}</b-td>
                                      <b-td style='color:white'>{{item.valor_tarea_intermedia}} {{item.unidad_tarea_intermedia}}</b-td>
                                      <b-td style='color:white'><b-button variant="info" @click="selectItem()" align="rigth">Actualizar</b-button></b-td>
                                      <b-td style='color:white'><b-button variant="warning" @click="selectItem()" >Terminar Tarea</b-button></b-td>
                                    </b-tr>
                              </b-tbody>
                          </b-table-simple>
                          </div>
                          <div v-else>
                            <b-td><span style='color:white'>No existen tareas asignadas a este item.</span></b-td>
                          </div>
                      </card>
                    </b-tab>
                  </b-tabs>
                </div>
                <div>
                  <b-button variant="danger" href="/#/admin/alertas" >Notificar Accidente</b-button>
                  <p></p>
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
          //ESTA INFORMACION DEBE CONSTRUIRSE LLAMANDO A LAS 4 TABLAS DE TAREAS, LA INFORMACION DE TAREAS CONTABLES ES CALCULADA POR TURNO CON EL DATOS MINA
          {
              estructura: "ACCESO MANTO 1",
              tarea_intermedia: "AISLAMIENTO DE SECTOR",
              valor_tarea_intermedia: 32,
              tarea_contable: "Perforación",
              valor_tarea_contable: 30,
              unidad_tarea_contable: "m",
              unidad_tarea_intermedia: "%"
          }
        ],
        data_2: [
          //ESTA INFORMACION DEBE CONSTRUIRSE LLAMANDO A LAS 4 TABLAS DE TAREAS, LA INFORMACION DE TAREAS CONTABLES ES CALCULADA POR TURNO CON EL DATOS MINA
          {
              estructura: "CASERON M 1 - 1",
              tarea_intermedia: "PROGRAMACIÓN DE LA MALLA DE PERFORACIÓN Y CARACTERISTICAS DE LOS TIROS A PERFORAR",
              valor_tarea_intermedia: 28,
              tarea_contable: "Perforación",
              valor_tarea_contable: 25,
              unidad_tarea_contable: "m",
              unidad_tarea_intermedia: "%"
          },
          {
              estructura: "CASERON M 1 - 2",
              tarea_intermedia: "PROGRAMACIÓN DE LA MALLA DE PERFORACIÓN Y CARACTERISTICAS DE LOS TIROS A PERFORAR",
              valor_tarea_intermedia: 0,
              tarea_contable: "Perforación",
              valor_tarea_contable: 0,
              unidad_tarea_contable: "m",
              unidad_tarea_intermedia: "%"
          }
        ],
        data_3: [
          //ESTA INFORMACION DEBE CONSTRUIRSE LLAMANDO A LAS 4 TABLAS DE TAREAS, LA INFORMACION DE TAREAS CONTABLES ES CALCULADA POR TURNO CON EL DATOS MINA
          
        ],
        timestamp: ""
      }
    },
    created() {
      setInterval(this.getNow, 1000);
    },
    methods: {
      getNow: function() {
        const today = new Date();
        const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
        const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        const dateTime = date +' Hora:'+ time;
        this.timestamp = dateTime;
      }
    }
  }
</script>
<style>
    .center {
        margin-left: auto;
        margin-right: auto;
    }
</style>
