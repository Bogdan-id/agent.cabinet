<template>
<v-row>
  <v-col cols="12" class="pt-0">
    <v-card class="usefull-material__detail " elevation="7" v-if="article !== null">
      <v-card-title style="text-align: center!important">
        <div>{{ article.title }}</div>
      </v-card-title>
      <v-divider class="ml-3 mr-3 mb-1"></v-divider>
      <v-card-text v-html="article.content">
      </v-card-text>
    </v-card>
  </v-col>
</v-row>
</template>

<script>
import axios from 'axios'

export default {
  data: () => ({
    item: null,
    article: null,
  }),
  created() {
    console.log(this.$router.currentRoute)
    let material = this.$router.currentRoute.params.id 
    axios
      .get(`/material/${material}`)
      .then(response => {
        console.log(response)
        this.article = response.data
      })
      .catch(error => {
        console.log(error.response)
      })
    // this.item = this.$router.currentRoute.params
    // console.log(this.item)
  }
}
</script>

<style>
</style>