<template>
<div class="row">
  <v-card class="usefull-material__detail" elevation="7" v-if="articles !== null " v-show="$route.name === 'Матерiали категорiї'">
    <v-card-title class="d-block pt-3 pb-1" style="border-left: 4px solid #e75d57;">
       {{currentCategory }}
      <!-- <v-divider style="margin-bottom: 0.5rem!important"></v-divider> -->
    </v-card-title>
    <v-card-text v-if="articles.length === 0">
      <div style="text-align: center;">(Матерiали для вiдображення вiдсутнi)</div>
    </v-card-text>
    <useful-materials-preview-card
      v-for="(item, key) in articles"
      :key="key"
      :item="item">
    </useful-materials-preview-card>
  </v-card>
  <router-view></router-view>
</div>
</template>

<script>
import axios from 'axios'

import UsefulMaterialsPreviewCard from './UsefulMaterialsPreviewCard'
export default {
  components: {
    UsefulMaterialsPreviewCard
  },
  data: () => ({
    currentCategory: null,
    articles: null,
    breadcrumbs: null,
  }),
  created() {
    console.log(this.$router.currentRoute)
    let params = this.$router.currentRoute.params
    this.currentCategory = params.name
    let id = params.id
    axios
      .get(`/materials/category/${id}`)
      .then(response => {
        console.log(response)
        this.articles = response.data
      })
      .catch(error => {
        console.log(error.response)
      })
  },
}
</script>

