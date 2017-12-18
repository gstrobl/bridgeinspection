<template>
  <v-layout row wrap>
      <v-flex xs12>
        <v-card dark>
          <v-container fill-height fluid>
            <v-layout fill-height>
              <v-flex xs12 align-end flexbox>
                <span class="headline">{{ $t("general.login") }}</span>
              </v-flex>
            </v-layout>
          </v-container>
          <v-divider></v-divider>
          <div class="form">
            <v-text-field box
              :label= "this.$t('general.email')"
              v-model= "loginAccount.email"
            >
            </v-text-field>
            <FormError v-if="errors.email" :errors="errors">
               {{ errors.email[0] }}
            </FormError>
            <v-text-field box
              :label= "this.$t('general.password')"
              v-model= "loginAccount.password"
            >
            </v-text-field>
            <FormError v-if="errors.point" :errors="errors">
               {{ errors.point[0] }}
            </FormError>
          </div>
          <v-btn color="amber darken-2" dark @click.prevent="createBridge">{{ $t("general.login") }}</v-btn>
          <vue-up></vue-up>
        </v-card>
      </v-flex>

      <v-flex xs12>
        <v-card dark>
          <v-container fill-height fluid>
            <v-layout fill-height>
              <v-flex xs12 align-end flexbox>
                <span class="headline">{{ $t("general.login") }}</span>
              </v-flex>
            </v-layout>
          </v-container>
          <v-divider></v-divider>
          <div class="form">
            <v-text-field box
              :label= "this.$t('general.email')"
              v-model= "account.email"
            >
            </v-text-field>
            <FormError v-if="errors.email" :errors="errors">
               {{ errors.email[0] }}
            </FormError>
            <v-text-field box
              :label= "this.$t('general.password')"
              v-model= "account.password"
            >
            </v-text-field>
            <FormError v-if="errors.password" :errors="errors">
               {{ errors.password[0] }}
            </FormError>
            <v-text-field box
              :label= "this.$t('general.password_confirmation')"
              v-model= "account.password_confirmation"
            >
            </v-text-field>
            <FormError v-if="errors.password_confirmation" :errors="errors">
               {{ errors.password_confirmation[0] }}
            </FormError>
          </div>
          <v-btn color="amber darken-2" dark @click.prevent="createAccount">{{ $t("general.login") }}</v-btn>
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
      account: {
        email: '',
        password: '',
        password_confirmation: ''
      },
      loginAccount: {
        email: '',
        password: ''
      },
      dialog: false,
      errors: []
    }
  },
  components: {
    FormError,
    Modal
 },
 methods: {
   createAccount() {
     axios.post('/auth/user/createaccount', this.account)
      .then(response => {
        if(response.data.success) {
          this.account = {
            email: '',
            password: '',
            password_confirmation: ''
          }
          this.$popup({
              message         : this.$t('general.add'),
              color           : '#fff',
              backgroundColor : 'rgba(0, 0, 0, 0.7)',
              delay           : 5
          })
          this.dialog = true
        }
        this.errors = response.data
      })
      .catch(e => {
        console.log(e)
      })
    },
    login() {
      axios.post('/auth/login', this.loginAccount)
       .then(response => {
         if(response.data.success) {
           this.loginAccount = {
             email: '',
             password: '',
           }
           this.$popup({
               message         : this.$t('general.add'),
               color           : '#fff',
               backgroundColor : 'rgba(0, 0, 0, 0.7)',
               delay           : 5
           })
           this.dialog = true
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
