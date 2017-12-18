<template lang="html">
  <v-dialog v-model="this.dialog" lazy absolute>
    <v-card>
      <v-card-title>
        <slot name="headline"></slot>
      </v-card-title>
      <slot name="text"></slot>
      <v-card-actions>
        <v-spacer></v-spacer>
        <slot name="button"></slot>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: ['dialog']
}
</script>

<style lang="css">
</style>
