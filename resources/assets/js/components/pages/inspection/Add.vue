 <template>
    <v-layout row wrap>
        <v-flex xs12>
          <v-card dark>
            <v-container fluid>
              <v-layout fill-height>
                <v-flex xs12 align-end flexbox>
                  <span class="headline">{{newBridge.route}}</span><br />
                  <span class="grey--text">{{ $t("bridge.point") }}: {{newBridge.point}} </span><br />
                  <span class="grey--text">{{ $t("bridge.component") }}: {{newBridge.component}} </span><br />
                </v-flex>
              </v-layout>
            </v-container>
            <v-divider></v-divider>
            <v-layout fill-height>
              <v-flex xs12 align-end flexbox>
                <div v-if="!isImageUploading" class="box">
                  <v-btn v-if="!isInspectionId" color="amber darken-2" dark @click.prevent="createInspection()">{{ $t("general.save") }}</v-btn>
                  <v-btn v-if="isInspectionId" color="green lighten-1" dark @click.prevent="updateInspection()">{{ $t("general.update") }}</v-btn>
                  <v-btn color="amber darken-2" dark @click.prevent="backToOverview()">{{ $t("general.backtooverview") }}</v-btn>
                </div>
              </v-flex>
            </v-layout>
            <v-stepper non-linear>
              <v-stepper-header>
                <v-stepper-step step="1" editable>{{ $t("inspection.imageupload") }}</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="2" editable>{{ $t("inspection.metadata") }}</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="3" editable>{{ $t("inspection.occasion") }}</v-stepper-step>
              </v-stepper-header>
              <v-stepper-content step="1">
                <div class="form">
                  <ImageUpload :bridgeId="this.id" :inspectionId="this.inspectionId"></ImageUpload>
                </div>
              </v-stepper-content>
              <v-stepper-content step="2">
                <div class="form">
                  <v-layout row wrap>
                    <v-flex xs12>
                      <v-dialog
                        persistent
                        v-model="modal"
                        lazy
                        full-width
                      >
                        <v-text-field
                          slot="activator"
                          :label="this.$t('inspection.inspectiondate')"
                          v-model="newInspection.inspectiondate"
                          prepend-icon="event"
                          readonly
                        ></v-text-field>
                        <v-date-picker locale="de-at" :first-day-of-week="1" v-model="newInspection.inspectiondate" scrollable >
                          <template scope="{ save, cancel }">
                            <v-card-actions>
                              <v-btn flat primary @click.native="cancel()">{{$t("general.cancel")}}</v-btn>
                              <v-btn flat primary @click.native="save()">{{$t("general.save")}}</v-btn>
                            </v-card-actions>
                          </template>
                        </v-date-picker>
                      </v-dialog>
                      <FormError v-if="errors.inspectiondate" :errors="errors">
                         {{ errors.inspectiondate[0] }}
                      </FormError>
                    </v-flex>
                    <v-flex xs12>
                      <v-dialog
                        persistent
                        v-model="modal2"
                        lazy
                        full-width
                      >
                        <v-text-field
                          slot="activator"
                          :label="this.$t('inspection.achievedate')"
                          v-model="newInspection.achievedate"
                          prepend-icon="event"
                          readonly
                        ></v-text-field>
                        <v-date-picker locale="de-at" :first-day-of-week="1" v-model="newInspection.achievedate" scrollable >
                          <template scope="{ save, cancel }">
                            <v-card-actions>
                              <v-btn flat primary @click.native="cancel()">{{$t("general.cancel")}}</v-btn>
                              <v-btn flat primary @click.native="save()">{{$t("general.save")}}</v-btn>
                            </v-card-actions>
                          </template>
                        </v-date-picker>
                      </v-dialog>
                    </v-flex>
                    <FormError v-if="errors.achievedate" :errors="errors">
                       {{ errors.achievedate[0] }}
                    </FormError>
                  </v-layout>
                </div>
             </v-stepper-content>
             <v-stepper-content step="3">
               <div class="form">
                 <v-layout row>
                   <v-flex xs6>
                     <v-switch :label="this.$t('inspection.lackadjustment')" v-model="newInspection.lackadjustment" dark></v-switch>
                     <v-switch :label="this.$t('inspection.monitoring')" v-model="newInspection.monitoring" dark></v-switch>
                     <v-switch :label="this.$t('inspection.operatingmeasure')" v-model="newInspection.operatingmeasure" dark></v-switch>
                   </v-flex>
                   <v-flex xs6>
                     <v-switch :label="this.$t('inspection.special_audit')" v-model="newInspection.special_audit" dark></v-switch>
                     <v-switch :label="this.$t('inspection.cut_deadline')" v-model="newInspection.cut_deadline" dark></v-switch>
                     <v-switch :label="this.$t('inspection.other')" v-model="newInspection.other" dark></v-switch>
                   </v-flex>
                 </v-layout>
                 <v-text-field v-if="newInspection.lackadjustment || newInspection.monitoring ||  newInspection.special_audit || newInspection.cut_deadline
                                    || newInspection.operatingmeasure || newInspection.other"
                   :label= "this.$t('inspection.description')"
                   v-model= "newInspection.description"
                   multi-line
                 >
                 </v-text-field>
                 <FormError v-if="errors.result" :errors="errors">
                    {{ errors.result[0] }}
                 </FormError>
               </div>
            </v-stepper-content>
            </v-stepper>
            <vue-up></vue-up>
          </v-card>
        </v-flex>
        <Modal v-bind:dialog="dialog">
            <div slot="headline" class="headline">{{ $t("bridge.routeinspection") }}</div>
            <v-card-text slot="text">{{ $t("bridge.inspection_text") }}</v-card-text>
            <div slot="button">
              <v-btn color="red--text darken-1" flat="flat" @click.native="dialog = false">{{ $t("general.cancel") }}</v-btn>
              <v-btn color="primary--text darken-1" flat="flat" @click.prevent="createInspection">{{ $t("general.create") }}</v-btn>
            </div>
        </Modal>
    </v-layout>
  </template>
<script>
import Vue from 'vue'
import FormError from '../../FormError.vue'
import ImageUpload from '../../ImageUpload.vue'
import Modal from '../../Modal.vue'
import axios from 'axios'

export default {
  data () {
    return {
      newBridge: {
      },
      newInspection: {
        bridge_id: this.id,
        result: '',
        description: '',
        inspectiondate: null,
        achievedate: null,
        lackadjustment: false,
        monitoring: false,
        operatingmeasure: false,
        special_audit: false,
        cut_deadline: false,
        other: false
      },
      submitted: false,
      errors: [],
      menu: false,
      modal2: false,
      inspectionId: ''
    }
  },
  props: ['id'],
  components: {
    ImageUpload,
    FormError,
    Modal
 },
 mounted (){

 },
 created () {
   this.$store.dispatch('setEditInspectionId', '');
   axios.get('/api/bridge/' + this.id)
     .then(response => {
       this.newBridge = response.data
       console.log(response.data)
     }).catch(e => { console.log(e) })
 },
 computed: {
   isImageUploading () {
     console.log(this.$store.getters.isImageUploading)
     return this.$store.getters.isImageUploading
   },
   isInspectionId () {
     this.inspectionId = this.$store.getters.isInspectionId
     return this.$store.getters.isInspectionId
   }
 },
 methods: {
   backToOverview () {
     this.$router.push({ name: 'bridge.show', params: { id: this.id }})
   },
   updateInspection () {
     axios.post('/api/inspection/' + this.isInspectionId + '/edit', this.newInspection)
      .then(response => {
        console.log(this.newInspection)
        if(response.data.success){
          this.$popup({
              message         : this.$t('general.updated'),
              color           : '#fff',
              backgroundColor : 'rgba(0, 0, 0, 0.7)',
              delay           : 5
          })
          this.errors = ''
          // this.$router.push({ name: 'bridges.show' })
        } else {
          this.errors = response.data
          this.$popup({
              message         : this.$t('general.error'),
              color           : '#fff',
              backgroundColor : 'rgba(139,0,0, 0.7)',
              delay           : 8
          })
        }

      })
      .catch(e => {
        console.log(e)
      })
   },
   createInspection () {
     axios.post('/api/inspection/add', this.newInspection)
      .then(response => {
        if(response.data.success){
          this.$popup({
              message         : this.$t('general.added'),
              color           : '#fff',
              backgroundColor : 'rgba(0, 0, 0, 0.7)',
              delay           : 5
          })
          this.$store.dispatch('setInspectionId', response.data.inspectionId)
          this.errors = ''
        } else {
          this.errors = response.data
          this.$popup({
              message         : this.$t('general.error'),
              color           : '#fff',
              backgroundColor : 'rgba(139,0,0, 0.7)',
              delay           : 8
          })
        }
      })
      .catch(e => {
        console.log(e)
      })
   }
 }
}
</script>
