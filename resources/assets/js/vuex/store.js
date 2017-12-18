import Vuex from 'vuex'
import Vue from 'vue'

import * as actions from './actions'

import inspection from './modules/inspection'

Vue.use(Vuex)

export default new Vuex.Store({
  actions,
  modules: {
    inspection
  }
})
