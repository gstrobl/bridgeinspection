<template>
  <v-card>
    <v-card-title>
      {{ $tc('bridge.title', 2) }}
      <v-spacer></v-spacer>
      <v-text-field
        append-icon="search"
        :label="this.$t('general.search')"
        single-line
        hide-details
        v-model="search"
      ></v-text-field>
    </v-card-title>
    <v-data-table
        v-bind:headers="headers"
        v-bind:items="items"
        v-bind:search="search"
      >
      <template slot="items" scope="props">
        <!-- <td>
          <v-edit-dialog
            lazy
          > {{ props.item.name }}
            <v-text-field
              slot="input"
              label="Edit"
              v-model="props.item.name"
              single-line
              counter
              :rules="[max25chars]"
            ></v-text-field>
          </v-edit-dialog>
        </td> -->
        <router-link class="text-xs-left row-link" tag="td" :to="{ name: 'bridge.show', params: { id: props.item._id} }">{{ props.item.route }}</router-link>
        <router-link class="text-xs-left row-link" tag="td" :to="{ name: 'bridge.show', params: { id: props.item._id} }">{{ props.item.point }}</router-link>
        <router-link class="text-xs-left row-link" tag="td" :to="{ name: 'bridge.show', params: { id: props.item._id} }">{{ props.item.component }}</router-link>
        <router-link class="text-xs-left row-link" tag="td" :to="{ name: 'bridge.show', params: { id: props.item._id} }">{{ props.item.updated_at }}</router-link>
        <router-link class="text-xs-left row-link" tag="td" :to="{ name: 'bridge.show', params: { id: props.item._id} }">{{ props.item.created_at }}</router-link>
        <td class="text-xs-left">
          <router-link :to="{ name: 'bridge.edit', params: { id: props.item._id} }" class="btn btn--raised primary theme--dark" tag="button">{{$t('general.edit')}}</router-link>
          <router-link :to="{ name: 'bridge.edit', params: { id: props.item._id} }" class="btn btn--raised success theme--dark" tag="button">{{$t('general.export')}}</router-link>
        </td>
      </template>
      <template slot="pageText" scope="{ pageStart, pageStop }">
        From {{ pageStart }} to {{ pageStop }}
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'

export default {
  data () {
    return {
      max25chars: (v) => v.length <= 25 || 'Input too long!',
      tmp: '',
      search: '',
      items: [],
      pagination: {},
      headers: [
        { text: this.$t('bridge.name'), align: 'left', value: 'route' },
        { text: this.$t('bridge.point'), align: 'left',value: 'point' },
        { text: this.$t('bridge.component'), align: 'left', value: 'component' },
        { text: this.$t('general.updated_at'), align: 'left', value: 'updated_at' },
        { text: this.$t('general.created_at'), align: 'left', value: 'created_at' },
        { text: this.$t('bridge.edit'), align: 'left', sortable: false}
      ]
    }
  },
  created () {
    axios.get('/api/bridge/all')
      .then(response => {
        this.items = response.data
        console.log(response.data)
      }).catch(e => { console.log(e) })
    }
  }
</script>
