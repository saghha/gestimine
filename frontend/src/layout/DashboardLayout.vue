<template>
  <div class="wrapper">
    <side-bar>
      <mobile-menu slot="content"></mobile-menu>
      <sidebar-link to="/admin/overview">
        <i class="nc-icon nc-chart-pie-35"></i>
        <p>Dashboard</p>
      </sidebar-link>
      <sidebar-link to="/admin/datos_mina">
        <i class="nc-icon nc-preferences-circle-rotate"></i>
        <p>Datos de Mina</p>
      </sidebar-link>
      <sidebar-link to="/admin/cronograma">
        <i class="nc-icon nc-notes"></i>
        <p>Cronograma</p>
      </sidebar-link>
      <sidebar-link to="/admin/plan_minero">
        <i class="nc-icon nc-chart-bar-32"></i>
        <p>Plan Minero</p>
      </sidebar-link>
      <sidebar-link to="/admin/perforaciones">
        <i class="nc-icon nc-circle"></i>
        <p>Perforación</p>
      </sidebar-link>
      <sidebar-link to="/admin/tronaduras">
        <i class="nc-icon nc-explore-2"></i>
        <p>Tronadura</p>
      </sidebar-link>
      <sidebar-link to="/admin/carguios">
        <i class="nc-icon nc-app"></i>
        <p>Carguío</p>
      </sidebar-link>
      <sidebar-link to="/admin/transportes">
        <i class="nc-icon nc-delivery-fast"></i>
        <p>Transporte</p>
      </sidebar-link>
      <sidebar-link to="/admin/alertas">
        <i class="fa fa-warning"></i>
        <p>Alertas</p>
      </sidebar-link>
      <sidebar-link to="/admin/eventos">
        <i class="nc-icon nc-bullet-list-67"></i>
        <p>Ver Eventos</p>
      </sidebar-link>
      <hr style="width:50%;text-align:left;margin-left:0;color:white;">
      <p style="width:50%;text-align:right;margin-left:0;color:white;">Jefe de Turno</p>
      <sidebar-link to="/turno_anterior/jefe_turno">
        <i class="nc-icon nc-notes"></i>
        <p>Turno Anterior</p>
      </sidebar-link>
      <sidebar-link to="/registro_avance/registro">
        <i class="nc-icon nc-paper-2"></i>
        <p>Registro Avance</p>
      </sidebar-link>
      <sidebar-link to="/tarea_realizar/jefe_turno">
        <i class="nc-icon nc-paper-2"></i>
        <p>Tarea a Realizar</p>
      </sidebar-link>
      <hr style="width:50%;text-align:left;margin-left:0;color:white;">
      <p style="width:50%;text-align:right;margin-left:0;color:white;">Operador</p>
      <sidebar-link to="/tarea_realizar/operador">
        <i class="nc-icon nc-paper-2"></i>
        <p>Tarea a Realizar</p>
      </sidebar-link>
    </side-bar>
    <div class="main-panel">
      <top-navbar></top-navbar>

      <dashboard-content @click="toggleSidebar">

      </dashboard-content>

      <content-footer></content-footer>
    </div>
  </div>
</template>
<style lang="scss">

</style>
<script>
  import TopNavbar from './TopNavbar.vue'
  import ContentFooter from './ContentFooter.vue'
  import DashboardContent from './Content.vue'
  import MobileMenu from './MobileMenu.vue'
  export default {
    components: {
      TopNavbar,
      ContentFooter,
      DashboardContent,
      MobileMenu
    },
    methods: {
      toggleSidebar () {
        if (this.$sidebar.showSidebar) {
          this.$sidebar.displaySidebar(false)
        }
      }
    }
  }

</script>
