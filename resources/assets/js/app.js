
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import router from './router/index'
import i18n from './i18n/index'
import Vue from 'vue'
import VueUp from 'vueup'
import Vuetify from 'vuetify'
import store from './vuex/store'

Vue.use(Vuetify)
Vue.use(VueUp)

const App = require('./App.vue')

new Vue({
  i18n: i18n,
  router,
  store,
  components : { App }
}).$mount('#app')
