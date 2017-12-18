<template>
  <v-app
    dark
    id="inspire"
    >
    <v-navigation-drawer
        persistent
        clipped
        enable-resize-watcher
        v-model="drawer"
        app
      >
        <v-list dense>
          <template v-for="(item, i) in items">
            <v-layout
              row
              v-if="item.heading"
              align-center
              :key="i"
            >
              <v-flex xs6>
                <v-subheader v-if="item.heading">
                  {{ item.heading }}
                </v-subheader>
              </v-flex>
            </v-layout>
            <v-divider v-else-if="item.divider" :key="i"></v-divider>
            <router-link :key="i" v-else class="list__tile list__tile--link" tag="a" :to="{ name: item.path }">
              <v-list-tile-action>
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ item.text }}
                </v-list-tile-title>
              </v-list-tile-content>
            </router-link>
          </template>
        </v-list>
    </v-navigation-drawer>
    <v-toolbar dense fixed clipped-left app color="amber darken-1">
      <v-toolbar-title>
        <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      </v-toolbar-title>
    </v-toolbar>
    <main>
      <v-content>
        <v-container fluid>
          <router-view></router-view>
        </v-container>
      </v-content>
    </main>
  </v-app>
</template>

<script>
// import FooterContainer from './components/Footer.vue'

export default {
  name: 'app',
  data () {
    return {
      drawer: true,
      items: [
        { heading: this.$tc('bridge.title', 2)},
        { icon: 'build', text: this.$tc('bridge.title', 2), path: 'bridges.show' },
        { icon: 'add', text: this.$t('bridge.add'), path: 'bridge.add' },
        { divider: true }
      ]
    }
  },
  beforeCreate () {
  },
  components: {
  }
  // store
  // ready: function () {
  //   this.$vueLocalizeInit(this.$route)
  // }
}
</script>
