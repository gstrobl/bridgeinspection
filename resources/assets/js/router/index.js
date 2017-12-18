import Vue from 'vue'
import VueRouter from 'vue-router'

//Define route components
const home = { template: '<div>homes1</div>' }
// const foo = { template: '<div>Foo</div>' }
// const bar = { template: '<div>Bar</div>' }
const bridgeAdd = require('../components/pages/bridge/Add.vue')
const bridgeOverview = require('../components/pages/bridge/Overview.vue')
const bridgeEdit = require('../components/pages/bridge/Edit.vue')
const bridgeShow = require('../components/pages/bridge/Show.vue')
const inspectionAdd = require('../components/pages/inspection/Add.vue')
const inspectionEdit = require('../components/pages/inspection/Edit.vue')
const imageMarker = require('../components/pages/inspection/ImageMarker.vue')
const auth = require('../components/pages/auth/Authentication.vue')
// lazy load components
// const Room = require('../components/Room.vue')

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    base: __dirname,
      routes: [
        { path: '/', name: 'home', component: home },
        { path: '/auth', name: 'authentication', component: auth },
        { path: '/bridges', name: 'bridges.show', component: bridgeOverview },
        { path: '/bridge/add', name:'bridge.add', component: bridgeAdd },
        { path: '/bridge/:id', name: 'bridge.show', props: true, component: bridgeShow },
        { path: '/bridge/:id/edit', name: 'bridge.edit', props: true, component: bridgeEdit },
        { path: '/bridge/:id/addinspection', name: 'bridge.addinspection', props: true, component: inspectionAdd },
        { path: '/inspection/:inspectionId/:fileId/addmarker', name: 'inspection.addmarker', props: true, component: imageMarker },
        { path: '/bridge/:id/inspection/:inspectionId/edit', name: 'inspection.edit', props: true, component: inspectionEdit },
        // {
        // 	path: '/bridge',
        // 	name:'bridge',
        // 	component: require('../components/Room.vue'),
        // 	children: [
        // 	        {
        // 	          props:true,
        // 	      	  name:'rooms_read',
        // 	          path: ':room',
        // 	          component: require('../components/roomTest.vue')
        // 	        }
        //
        // 	      ]
        //
        // }, // example of route with a seperate component

      ]
});
