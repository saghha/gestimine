/*!

 =========================================================
 * Vue Light Bootstrap Dashboard - v2.0.0 (Bootstrap 4)
 =========================================================

 * Product Page: http://www.creative-tim.com/product/light-bootstrap-dashboard
 * Copyright 2019 Creative Tim (http://www.creative-tim.com)
 * Licensed under MIT (https://github.com/creativetimofficial/light-bootstrap-dashboard/blob/master/LICENSE.md)

 =========================================================

 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 */
window.axios = require('axios');
window._ = require('lodash');
import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'

// LightBootstrap plugin
import LightBootstrap from './light-bootstrap-main'
import { ValidationObserver, ValidationProvider, extend, localize } from 'vee-validate';
import { rutValidator } from "vue-dni";
import * as rules from 'vee-validate/dist/rules';
// router setup
import routes from './routes/routes'
import store from './store/index'
import vSelect from 'vue-select'
import { rutInputDirective } from 'vue-dni';
import './registerServiceWorker'
import VueSweetalert2 from 'vue-sweetalert2';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import VueDatePicker from '@mathieustan/vue-datepicker';
import VueCurrencyInput from 'vue-currency-input'
import '@mathieustan/vue-datepicker/dist/vue-datepicker.min.css';
// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'sweetalert2/dist/sweetalert2.min.css';
import 'vue-select/dist/vue-select.css';
// plugin setup
// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
Vue.use(VueSweetalert2);
Vue.use(VueCurrencyInput)
Vue.use(VueRouter)
Vue.use(LightBootstrap)
Vue.directive('rut', rutInputDirective);
Vue.component('v-select', vSelect)
Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
//Vue.use(VueDatePicker);
Vue.use(VueDatePicker, {
  lang: 'es'
});
extend('rut', rutValidator)
Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});
axios.defaults.baseURL = 'http://localhost/public/api'
//axios.defaults.baseURL = 'http://localhost/api'
console.log(axios.defaults.baseURL)
// configure router
const router = new VueRouter({
  routes, // short for routes: routes
  linkActiveClass: 'nav-item active',
  scrollBehavior: (to) => {
    if (to.hash) {
      return {selector: to.hash}
    } else {
      return { x: 0, y: 0 }
    }
  }
})
router.beforeEach((to, from, next) => {
  store.dispatch('authenticate').then(() => {
    console.log("aca deberia ser resolve")
    axios.defaults.headers.common = {
      'X-Requested-With': 'XMLHttpRequest',
      'Authorization': 'Bearer ' + store.getters.token
    }
    if(!to.matched.some(record => record.meta.requiresAuth)){
      next()
    }
    else {
      next()
    }
  }).catch((err) =>{
    console.log("aca deberia ser reject")
    if(to.matched.some(record => record.meta.requiresAuth)){
      next('/auth/login')
    } else {
      next()
    }
  })
})
/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App),
  router,
  store
})
