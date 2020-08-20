<template>
  <v-card style="min-height: 200px; position: relative" elevation="8">
    <img style="width: 100%; filter: blur(7px);" src="../assets/img/graphs.png" />
    <div style="position: absolute; top: 24%; position: absolute; left: 50%; text-align: center; transform: translate(-50%, 0);">
      <span style="margin: 0 auto; color: #555555; font-size: 1.8rem">Скоро тут буде звiт...</span>
    </div>
    <!-- <v-card-title>Title here</v-card-title>
    <div class="ct-chart ct-perfect-fourth"></div> -->
  </v-card>
</template>

<script>
import Chartist from 'chartist'

export default {
  data: () => ({
    // data: {
    //   labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
    //   series: [
    //     [5, 2, 4, 2, 0]
    //   ]
    // }
  }),
  // mounted() {
  //   new Chartist.Line('.ct-chart', this.data);
  // }
}
</script>

<style lang="scss">
// @import "_my-chartist-settings.scss";
@import "chartist/dist/scss/chartist.scss";
</style>