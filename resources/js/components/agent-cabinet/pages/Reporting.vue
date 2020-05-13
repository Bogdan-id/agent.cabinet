<template>
 <div class="row">
  <div class="col-md-12">
    <v-card style="min-height: 350px;">
      <v-card-title class="d-block grey darken-3 white--text">
        <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-clipboard-check'"></v-icon>
          Звiтнiсть
        <v-divider></v-divider>
        <v-progress-linear
          :height="3"
          :active="loading"
          :indeterminate="loading"
          absolute
          top
          color="red lighten-1">
        </v-progress-linear>
      </v-card-title>
      <v-card-text>
        Роздiл звiтнiсть тимчасово недоступний
      </v-card-text>
    </v-card>
  </div>
 </div>
</template>
<script>
export default {
  data: () => ({
    loading: false,
  })
}
</script>

<style>
</style>
