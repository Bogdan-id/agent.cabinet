<template>
<div>
  <v-card class="usefull-material__detail" elevation="7" v-if="articles !== null " v-show="$route.name === 'Матерiали категорiї'" style="min-height: 160px; padding-top: 20px;">
    <!-- <v-card-title class="d-block pt-3 pb-1" style="border-left: 4px solid #e75d57;">
       {{currentCategory }}
    </v-card-title> -->
    <v-card-text v-if="articles === null || articles.length === 0">
      <div style="text-align: center; font-size: 0.95rem;">(Матерiали для вiдображення вiдсутнi)</div>
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
    
    loading: false
  }),
  mounted() {
    this.loading = true
    axios
      .get(`/json${this.$router.currentRoute.path}`)
      .then(response => {
        this.loading = false
        if(response.status == 500) {
          // Помилка серверу
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `Помилка серверу`,
          })
        } else if(response.status == 422) {
          // Невірна категорія
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `Невірна категорія`,
          })
        } else {
          this.articles = response.data
        }
      })
      .catch(error => {
        this.loading = false
        console.log(error.response)
      })
  },
}
</script>

