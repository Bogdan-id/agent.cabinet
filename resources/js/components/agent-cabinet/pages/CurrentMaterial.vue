<template>
  <v-card>
    {{ 'test' }}
  </v-card>
</template>

<script>
export default {
  data: () => ({
    item: null,
  }),
  created() {
    console.log('CurrentMaterial.vue - Created')
    // this.item = this.$router.currentRoute.params
    // console.log(this.item)
  }
}
</script>

<style>
</style>