<template lang="html">
  <div>
    <div click.native="setDamages()">
      <img class="img-marker":src="uploadedFile.filepath" :alt="uploadedFile._id">
    </div>
    <v-layout fill-height>
      <v-flex xs12 align-end flexbox>
        <div v-if="!inspectionForms" class="box-down">
          <v-btn color="amber darken-2" dark @click.prevent="updateInspectionForms()">{{ $t("inspection.adddamagemarks") }}</v-btn>
        </div>
      </v-flex>
    </v-layout>
    <div class="form" v-if="inspectionForms">
      <v-text-field box
        :label= "this.$t('inspection.damage_description')"
        v-model= "newImageMarker.damage_description"
      >
      </v-text-field>
      <FormError v-if="errors.damage_description" :errors="errors">
         {{ errors.route[0] }}
      </FormError>
      <v-text-field box
        :label= "this.$t('inspection.classification')"
        v-model= "newImageMarker.classification"
      >
      </v-text-field>
      <FormError v-if="errors.classification" :errors="errors">
         {{ errors.point[0] }}
      </FormError>
      <v-btn v-if="!currentId"color="green lighten-1" dark @click.prevent="addMarker()">{{ $t("general.add") }}</v-btn>
      <v-btn v-if="currentId" color="green lighten-1" dark @click.prevent="updateMarker(currentId)">{{ $t("general.update") }}</v-btn>
      <v-btn color="amber darken-2" dark @click.prevent="updateInspectionForms()">{{ $t("general.cancel") }}</v-btn>
    </div>
    <v-expansion-panel v-if="!inspectionForms">
    <v-expansion-panel-content v-for="(inspectionMarker, index) in inspectionMarkers">
      <div slot="header">{{inspectionMarker.damage_description}}</div>
      <v-card>
        <v-card-text class="grey darken-3">
          <div class="description">
            {{$t('inspection.classification')}}: {{inspectionMarker.classification}}
          </div>
          <v-btn color="amber darken-2" dark @click.prevent="editMarker(inspectionMarker._id)">{{ $t("inspection.marker_edit") }}</v-btn>
          <v-btn color="red darken-1"  dark @click.prevent="deleteMarker(inspectionMarker._id)">{{ $t("inspection.marker_delete") }}</v-btn>
        </v-card-text>
      </v-card>
    </v-expansion-panel-content>
   </v-expansion-panel>
  <vue-up></vue-up>
  </div>
</template>

<script>
import axios from 'axios'
import $ from 'jquery'
import FormError from '../../FormError.vue'
// import { Taggd, Tag } from 'taggd'


$(document).ready(function() {
  const image = $('img');
  // const options = {};
  // const tags = [];
  // var taggd = image.taggd(options, tags);
  // const taggd = new Taggd(image, options, tags);


  image.on('mousedown', function(e) {
    // Get parent offset to calculate relative position
    const poffset = $(this).parent().offset();
    const x = e.pageX - poffset.left;
    const y = e.pageY - poffset.top;
    //
    // console.log(x);
    // console.log(y);

    // By dividing the x and y coordinates with the
    // image’s width and height, you get a number
    // between 0 and 1, which is safer for scaling

    // taggd.addTag({
    //   x: x / image.width(),
    //   y: y / image.height(),
    //   text: Math.random()
    // });
  });
});

export default {
  data () {
    return {
      uploadedFile: {},
      currentId: '',
      inspectionForms: false,
      newImageMarker : {
        damageDescription: '',
        classification: ''
      },
      errors: [],
      inspectionMarkers: []
    }
  },
  props: ['fileId', 'inspectionId'],
  components: {
    FormError
 },
  mounted() {
    axios.get('/api/inspection/' + this.inspectionId + '/' + this.fileId + '/getPhoto')
      .then(response => {
        this.uploadedFile = response.data
        console.log(this.uploadedFile)
      }).catch(e => { console.log(e) })

    axios.get('/api/inspection/' + this.inspectionId + '/' + this.fileId +'/showMarkers')
        .then(response => {
          this.inspectionMarkers = response.data
          console.log(this.inspectionMarkers)
        }).catch(e => { console.log(e) })
  },
  methods: {
    updateInspectionForms (){
      this.inspectionForms ? this.inspectionForms = false : this.inspectionForms = true
    },
    editMarker (markerId){
      axios.get('/api/inspection/' + markerId + '/getMarker')
          .then(response => {
            this.currentId = markerId
            this.inspectionForms = true
            this.newImageMarker = response.data
            console.log(response.data)
          }).catch(e => { console.log(e) })
    },
    updateMarker (markerId){
      axios.post('/api/inspection/' + this.inspectionId + '/' + this.fileId + '/' + markerId + '/updateMarker', this.newImageMarker)
       .then(response => {
         if(response.data.success){
           this.newImageMarker = {
             damageDescription: '',
             classification: ''
           }

           this.$popup({
               message         : this.$t('general.updated'),
               color           : '#fff',
               backgroundColor : 'rgba(0, 0, 0, 0.7)',
               delay           : 5
           })

           this.inspectionForms = false

           axios.get('/api/inspection/' + this.inspectionId + '/' + this.fileId +'/showMarkers')
               .then(response => {
                 this.inspectionMarkers = response.data
                 console.log(this.inspectionMarkers)
               }).catch(e => { console.log(e) })
          currentId = ''
          this.inspectionForms = false
           // this.$router.push({ name: 'bridges.show' })
         }
         this.errors = response.data
       })
       .catch(e => {
         console.log(e)
       })
    },
    addMarker (){
      axios.post('/api/inspection/' + this.inspectionId + '/' + this.fileId +'/addMarker', this.newImageMarker)
       .then(response => {
         if(response.data.success){
           this.newImageMarker = {
             damageDescription: '',
             classification: ''
           }

           this.$popup({
               message         : this.$t('general.add'),
               color           : '#fff',
               backgroundColor : 'rgba(0, 0, 0, 0.7)',
               delay           : 5
           })

           this.inspectionForms = false

           axios.get('/api/inspection/' + this.inspectionId + '/' + this.fileId +'/showMarkers')
               .then(response => {
                 this.inspectionMarkers = response.data
                 console.log(this.inspectionMarkers)
               }).catch(e => { console.log(e) })

          this.inspectionForms = false
           // this.$router.push({ name: 'bridges.show' })
         }
         this.errors = response.data
       })
       .catch(e => {
         console.log(e)
       })
    },
    deleteMarker (markerId){
      axios.post('/api/inspection/' + markerId + '/deleteMarker')
       .then(response => {
         if(response.data.success){
           this.newImageMarker = {
             damageDescription: '',
             classification: ''
           }

           this.$popup({
               message         : this.$t('inspection.marker_deleted'),
               color           : '#fff',
               backgroundColor : 'rgba(0, 0, 0, 0.7)',
               delay           : 5
           })

           this.inspectionForms = false

           axios.get('/api/inspection/' + this.inspectionId + '/' + this.fileId +'/showMarkers')
               .then(response => {
                 this.inspectionMarkers = response.data
                 console.log(this.inspectionMarkers)
               }).catch(e => { console.log(e) })

          this.inspectionForms = false
           // this.$router.push({ name: 'bridges.show' })
         }
         this.errors = response.data
       })
       .catch(e => {
         console.log(e)
       })
    },
    setDamages (){
      console.log(this.fileId);
      console.log(x);
      console.log(y);
    }
  }
}


</script>

<style lang="css">
</style>
