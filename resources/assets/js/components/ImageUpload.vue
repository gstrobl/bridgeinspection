<template>
  <div class="photo-upload">
    <div class="container">
      <!--UPLOAD-->
      <form enctype="multipart/form-data" novalidate v-if="isInitial || isSaving">
        <div class="dropbox">
          <input type="file" multiple :name="uploadFieldName" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files); fileCount = $event.target.files.length"
            accept="image/*" class="input-file">
            <p v-if="isInitial">
              {{ $t("inspection.dropbox") }}
            </p>
            <p v-if="isSaving">
              Uploading {{ fileCount }} files...
            </p>
        </div>
      </form>
      <!--SUCCESS-->
      <div v-if="isSuccess">
        <h6>{{ $t("inspection.uploadedimages") }} {{ uploadedFiles.length }}</h6>
        <p>
          <v-btn color="amber darken-2" dark @click="reset()">{{ $t("inspection.imageupload") }}</v-btn>
        </p>
        <v-layout v-for="item in uploadedFiles">
          <v-flex xs12 sm12>
            <v-card>
              <v-card-media :src="item.filepath" :alt="item.filename" height="200px">
              </v-card-media>
              <v-card-actions>
                <v-btn flat color="amber darken-2" @click="setMarker(item._id)">{{ $t("inspection.adddamagemarks") }}</v-btn>
                <v-btn flat color="amber darken-2" @click="deleteImage(item._id)">{{ $t("inspection.deleteimage") }}</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </div>
      <!--FAILED-->
      <div v-if="isFailed">
        <h2>Uploaded failed.</h2>
        <p>
          <a href="javascript:void(0)" @click="reset()">Try again</a>
        </p>
        <pre>{{ uploadError }}</pre>
      </div>
    </div>
    <vue-up></vue-up>
  </div>
</template>

<script>
  import axios from 'axios'

  const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;
  export default {
    data() {
      return {
        uploadedFiles: [],
        uploadError: null,
        currentStatus: null,
        uploadFieldName: 'photos'
      }
    },
    props: ['bridgeId', 'inspectionId'],
    computed: {
      isInitial() {
        return this.currentStatus === STATUS_INITIAL;
      },
      isSaving() {
        return this.currentStatus === STATUS_SAVING;
      },
      isSuccess() {
        return this.currentStatus === STATUS_SUCCESS;
      },
      isFailed() {
        return this.currentStatus === STATUS_FAILED;
      }
    },
    methods: {
      reset() {
        // reset form to initial state
        this.currentStatus = STATUS_INITIAL;
        this.uploadedFiles = [];
        this.uploadError = null;
      },
      setMarker(fileId) {
        this.$router.push({ name: 'inspection.addmarker', params: { inspectionId: this.$store.getters.isInspectionId, fileId: fileId, id: this.bridgeId }})
      },
      deleteImage(fileId) {
        axios.post('/api/inspection/' + fileId + '/deleteImage')
          .then(response => {
            if (response.data.success) {
              this.currentStatus = STATUS_SUCCESS
              this.$popup({
                  message         : this.$t('inspection.deletedimage'),
                  color           : '#fff',
                  backgroundColor : 'rgba(0, 0, 0, 0.7)',
                  delay           : 5
              })
              axios.get('/api/inspection/' + this.$store.getters.isInspectionId + '/getImageItems')
                .then(response => {
                  this.uploadedFiles = response.data
                  if(this.uploadedFiles.length < 1){
                    this.currentStatus = STATUS_INITIAL;
                  } else {
                    this.currentStatus = STATUS_SUCCESS
                  }
                }).catch(e => { console.log(e) })
            }
          }).catch(e => { console.log(e) })
      },
      save(formData) {
        // upload data to the server
        this.currentStatus = STATUS_SAVING;
        this.$store.dispatch('setInspectionsImages', true)
        axios.post('/api/inspection/photos/upload', formData)
          .then(response => {
            if (response.data.error) {
              this.currentStatus = STATUS_FAILED
            }

            if (response.data.success) {
              this.currentStatus = STATUS_SUCCESS
              this.$store.dispatch('setInspectionId', response.data.inspectionId)
              this.$store.dispatch('setInspectionsImages', false)

              this.$popup({
                  message         : this.$t('inspection.imagesadd'),
                  color           : '#fff',
                  backgroundColor : 'rgba(0, 0, 0, 0.7)',
                  delay           : 5
              })

              console.log(this.$store.getters.isInspectionId)

              axios.get('/api/inspection/' + this.$store.getters.isInspectionId + '/getImageItems')
                .then(response => {
                  this.uploadedFiles = response.data
                  console.log(response.data)
                }).catch(e => { console.log(e) })
            }

          }).catch(e => {
            console.log(e)
            this.uploadError = e.response
            this.currentStatus = STATUS_FAILED
          })
      },
      filesChange(fieldName, fileList) {

        const formData = new FormData();
        if (!fileList.length) return;
        for (var x = 0; x < fileList.length; x++) {
          formData.append("uploadedFiles[]", fileList[x]);
        }

        formData.append('bridgeId', this.bridgeId)
        formData.append('inspectionId', this.$store.getters.isInspectionId)
        this.save(formData);
      }
    },
    mounted() {
      this.$store.dispatch('setInspectionId', '')
      this.$store.dispatch('setInspectionsImages', false)
      this.reset();

      if(this.$store.getters.isEditInspectionId){
        this.$store.dispatch('setInspectionId', this.$store.getters.isEditInspectionId)
        axios.get('/api/inspection/' + this.$store.getters.isEditInspectionId + '/getImageItems')
          .then(response => {
            this.uploadedFiles = response.data
            if(this.uploadedFiles.length < 1){
              this.currentStatus = STATUS_INITIAL;
            } else {
              this.currentStatus = STATUS_SUCCESS
            }
          }).catch(e => { console.log(e) })
      }
    }
  }
</script>
