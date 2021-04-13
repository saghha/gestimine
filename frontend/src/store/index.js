import Vue from 'vue'
import Vuex from 'vuex'
import general from './modules/general'
import * as getters from './getters'

Vue.use(Vuex)

export default new Vuex.Store({
  getters,
  modules: {
    general,
  }
})
