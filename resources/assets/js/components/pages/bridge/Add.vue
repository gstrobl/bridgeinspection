<template>
  <v-layout row wrap>
      <v-flex xs12>
        <v-card dark>
          <v-container fill-height fluid>
            <v-layout fill-height>
              <v-flex xs12 align-end flexbox>
                <span class="headline" v-if="newBridge.route">{{ $t("bridge.name") }}: {{newBridge.route}}</span>
                <span class="headline" v-if="!newBridge.route">{{ $t("bridge.add") }}</span>
              </v-flex>
            </v-layout>
          </v-container>
          <v-divider></v-divider>
          <div class="form">
            <v-text-field box
              :label= "this.$t('bridge.name')"
              v-model= "newBridge.route"
            >
            </v-text-field>
            <FormError v-if="errors.route" :errors="errors">
               {{ errors.route[0] }}
            </FormError>
            <v-text-field box
              :label= "this.$t('bridge.point')"
              v-model= "newBridge.point"
            >
            </v-text-field>
            <FormError v-if="errors.point" :errors="errors">
               {{ errors.point[0] }}
            </FormError>
            <v-text-field box
              :label= "this.$t('bridge.component')"
              v-model= "newBridge.component"
            >
            </v-text-field>
            <FormError v-if="errors.component" :errors="errors">
               {{ errors.component[0] }}
            </FormError>
          </div>
          <v-btn color="amber darken-2" dark @click.prevent="createBridge">{{ $t("general.create") }}</v-btn>
          <vue-up></vue-up>
        </v-card>
      </v-flex>
      <Modal v-bind:dialog="dialog">
          <div slot="headline" class="headline">{{ $t("bridge.routeinspection") }}</div>
          <v-card-text slot="text">{{ $t("bridge.inspection_text") }}</v-card-text>
          <div slot="button">
            <v-btn class="red--text darken-1" flat="flat" @click.native="dialog = false">{{ $t("general.cancel") }}</v-btn>
            <v-btn class="green--text darken-1" flat="flat" @click.prevent="createInspection">{{ $t("general.create") }}</v-btn>
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
        route: '',
        point: '',
        component: ''
      },
      bridgeId: '',
      dialog: false,
      errors: []
    }
  },
  components: {
    FormError,
    Modal
 },
 methods: {
   createBridge() {
     axios.post('/api/bridge/add', this.newBridge)
      .then(response => {
        if(response.data.success) {
          this.newBridge = {
            route: '',
            point: '',
            component: ''
          }
          this.$popup({
              message         : this.$t('general.add'),
              color           : '#fff',
              backgroundColor : 'rgba(0, 0, 0, 0.7)',
              delay           : 5
          })
          this.dialog = true
          this.bridgeId = response.data.id
        }
        this.errors = response.data
      })
      .catch(e => {
        console.log(e)
      })
    },
    createInspection () {
      this.dialog = false
      this.$router.push({ name: 'bridge.addinspection', params: { id: this.bridgeId }})
    }
  }
}
</script>

<style lang="css">
</style>
