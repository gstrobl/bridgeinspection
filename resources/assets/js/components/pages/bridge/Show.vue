<template>
    <v-layout row wrap>
        <v-flex xs12>
          <v-card dark>
            <v-container fill-height fluid>
              <v-layout fill-height>
                <v-flex xs12 align-end flexbox>
                  <span class="headline">{{newBridge.route}}</span><br />
                  <span class="grey--text">{{ $t("bridge.point") }}: {{newBridge.point}} </span><br />
                  <span class="grey--text">{{ $t("bridge.component") }}: {{newBridge.component}} </span><br />
                </v-flex>
              </v-layout>
            </v-container>
            <v-divider></v-divider>
            <v-btn color="amber darken-2" dark @click.prevent="addInspection">{{ $t("inspection.add") }}</v-btn>
            <v-btn color="amber darken-2" dark @click.prevent="updateBridge">{{ $t("bridge.edit") }}</v-btn>
            <v-btn color="red darken-1"  dark @click.prevent="deleteBridge">{{ $t("bridge.delete") }}</v-btn>
            <vue-up></vue-up>
          </v-card>
          <v-expansion-panel>
           <v-expansion-panel-content v-for="(inspection, index) in inspections">
             <div slot="header">{{$t("inspection.inspectiondate")}}: {{inspection.inspectiondate}}</div>
             <v-card>
               <v-card-text class="grey darken-3">
                 <p v-if="inspection.description">
                   {{inspection.description}}
                 </p>
                 <ul class="data" v-for="(marker, index) in markers">
                   <li v-if="marker.inspection_id == inspection._id">
                     {{ marker.damage_description }}
                   </li>
                 </ul>
                 <v-btn color="amber darken-2" dark @click.prevent="editInspection(inspection._id)">{{ $t("inspection.edit") }}</v-btn>
                 <v-btn color="red darken-1"  dark @click.prevent="dialogdeleteInspection(inspection._id)">{{ $t("inspection.delete") }}</v-btn>
                 <v-btn color="green lighten-1" dark @click.prevent="exportInspection">{{ $t("general.export") }}</v-btn>
               </v-card-text>
             </v-card>
           </v-expansion-panel-content>
          </v-expansion-panel>
          <!-- <v-list>
            <template v-for="(inspection, index) in inspections">
              <v-list-tile
                avatar
                ripple
                @click="toggle(index)"
                :key="inspection.inspectiondate"
              >
                <v-list-tile-content>
                  <v-list-tile-title>{{ inspection.inspectiondate }}</v-list-tile-title>
                  <v-list-tile-sub-title>{{ inspection.headline }}</v-list-tile-sub-title>
                  <v-list-tile-sub-title>{{ inspection.subtitle }}</v-list-tile-sub-title>
                </v-list-tile-content>
                <v-list-tile-action>
                  <v-list-tile-action-text>{{ inspection.action }}</v-list-tile-action-text>
                </v-list-tile-action>
              </v-list-tile>
              <v-divider v-if="index + 1 < inspections.length" :key="inspection.title"></v-divider>
            </template>
          </v-list> -->
        </v-flex>
        <Modal v-bind:dialog="dialog">
            <div slot="headline" class="headline">{{ $t("bridge.routeinspection_delete") }}</div>
            <v-card-text slot="text">{{ $t("bridge.inspection_text_delete") }}</v-card-text>
            <div slot="button">
              <v-btn class="red--text darken-1" flat="flat" @click.prevent="closeModal()">{{ $t("general.cancel") }}</v-btn>
              <v-btn color="red darken-1" dark @click.prevent="deleteInspection()">{{ $t("general.delete") }}</v-btn>
            </div>
        </Modal>
        <Modal v-bind:dialog="dialogBridge">
            <div slot="headline" class="headline">{{ $t("bridge.routeinspection_delete") }}</div>
            <v-card-text slot="text">{{ $t("bridge.inspection_text_delete") }}</v-card-text>
            <div slot="button">
              <v-btn class="red--text darken-1" flat="flat" @click.prevent="closeModal()">{{ $t("general.cancel") }}</v-btn>
              <v-btn color="red darken-1" dark @click.prevent="deleteBridge()">{{ $t("general.delete") }}</v-btn>
            </div>
        </Modal>
    </v-layout>
  </template>
<script>
import Vue from 'vue'
import FormError from '../../FormError.vue'
import Modal from '../../Modal.vue'
import axios from 'axios'

export default {
  data () {
    return {
      newBridge: {
      },
      bridgeId: '',
      inspections: [],
      submitted: false,
      errors: [],
      dialog: false,
      dialogBridge: false,
      currentInspectionId: '',
      markers: [],
    }
  },
  props: ['id'],
  components: {
    FormError,
    Modal
 },
 created () {
   axios.get('/api/bridge/' + this.id)
     .then(response => {
       this.newBridge = response.data
       console.log(response.data)
     }).catch(e => { console.log(e) })

    axios.get('/api/bridge/' + this.id + '/allInspections' )
      .then(response => {
        this.inspections = response.data.inspectionItems
        this.markers = response.data.inspectionMarkers
        console.log(this.markers)
      }).catch(e => { console.log(e) })
 },
 methods: {
   dialogdeleteBridge (){
     this.dialogBridge = true;
   },
   deleteBridge (){
     axios.post('/api/bridge/' + this.id + '/delete')
      .then(response => {
        if(response.data.success){
          this.$popup({
              message         : this.$t('inspection.deletedimage'),
              color           : '#fff',
              backgroundColor : 'rgba(0, 0, 0, 0.7)',
              delay           : 5
          })

          this.$router.push({ name: 'bridges.show', params: {}})
          this.dialogBridge = false;
        }

        this.errors = response.data
      })
      .catch(e => {
        console.log(e)
      })
   },
   dialogdeleteInspection (inspectionId){
     this.dialog = true;
     this.currentInspectionId = inspectionId;
   },
   deleteInspection (){
     axios.post('/api/inspection/' + this.currentInspectionId + '/delete')
      .then(response => {
        if(response.data.success){
          this.$popup({
              message         : this.$t('inspection.deletedimage'),
              color           : '#fff',
              backgroundColor : 'rgba(0, 0, 0, 0.7)',
              delay           : 5
          })

          this.inspections = []
          // this.$router.push({ name: 'bridge.show', params: { id: this.id }})

          axios.get('/api/bridge/' + this.id + '/allInspections' )
            .then(response => {
              this.inspections = response.data.inspectionItems
              this.markers = response.data.inspectionMarkers
              console.log(this.inspections)
            }).catch(e => { console.log(e) })

          this.dialog = false;
        }

        this.errors = response.data
      })
      .catch(e => {
        console.log(e)
      })
   },
   closeModal(){
     this.dialog = false;
     this.dialogBridge = false;
     this.currentInspectionId = '';
   },
   updateBridge () {
     this.$router.push({ name: 'bridge.edit', params: { id: this.id }})
   },
   addInspection () {
     this.$router.push({ name: 'bridge.addinspection', params: { id: this.id }})
   },
   editInspection (inspectionId) {
     console.log(inspectionId)
     this.$router.push({ name: 'inspection.edit', params: { id: this.id, inspectionId: inspectionId }})
   },
   backToOverview () {
     this.$router.push({ name: 'inspection.edit', params: { inspectionId: this.$store.getters.isInspectionId, id: this.id }})
   },
   createInspection () {
     $('#modal').modal("hide")
   }
 }
}
</script>
