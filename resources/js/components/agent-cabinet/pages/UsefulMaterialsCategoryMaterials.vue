<template>
<div class="row">
  <v-card class="usefull-material__detail" v-if="articles !== null">
    <v-card-title class="d-block">
      <!-- <div>{{ article.name }}</div> -->
      <v-divider></v-divider>
    </v-card-title>
    <material-content
      v-for="(item, key) in articles"
      :key="key"
      :item="item">
    </material-content>
  </v-card>
  <router-view></router-view>
</div>
</template>

<script>
import axios from 'axios'

import MaterialContent from './UsefulMaterialsPreviewCard'
export default {
  components: {
    MaterialContent
  },
  data: () => ({
    articles: null,
    breadcrumbs: null,
  }),
  created() {
    let id = this.$router.currentRoute.query.id
    if(this.articles === null) {
      // Else request has triggered on child component
      axios
        .get(`/materials/category/${id}`)
        .then(response => {
          console.log(response)
          this.articles = response.data
        })
        .catch(error => {
          console.log(error.response)
        })
      }
  },
}
</script>

<style lang="scss">
.usefull-material__detail {
  width: 100%;
  p {
    font-size: 16px;
    display: block;
  }
  .image-column {
    width: 100%;
    height: 200px;
  }
  .text-column {
    width: 100%;
    height: 200px;
    display: flex;
    flex-direction: column;
  }
}
</style>