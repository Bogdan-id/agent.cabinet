<template>
<v-row>
  <v-col cols="12" class="pt-0">
    <v-card class="usefull-material__detail " elevation="7" v-if="article !== null">
      <v-card-title style="text-align: center!important">
        <div style="color: black;">{{ article.title }}</div>
      </v-card-title>
      <v-divider class="ml-4 mr-4 mb-1"></v-divider>
      <div style="padding: 0 25px; text-align: left;">
        <span style="letter-spacing: 0.05rem; font-size: 0.79rem; display: inline-block;">
          {{`${article.updated_at.substring(0,10).replace(/-/g, '/')}`}}&nbsp; {{`${article.updated_at.substring(12,19)}`}}
        </span>
      </div>
      <v-card-text v-html="article.content" class="current-material" style="overflow: hidden;">
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
    console.log(this.$router.currentRoute.path)
    // let material = this.$router.currentRoute.params.material 
    axios
      .get(`${this.$router.currentRoute.path}`)
      .then(response => {
        console.log(response)
        if(response.status == 500) {
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `Помилка серверу`,
          })
        } else if(response.status == 422) {
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `Матерiал не існує`,
          })
        }
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


<style lang="scss">
figure > img {
  max-width: 100%;
}
.current-material {
  figure {
    margin: 0 auto;
    padding: 0 15px 15px 15px;
    &.image-style-align-right {
      float: right;
    }
    &.image-style-align-left {
      float: left;
    }
  }
}
</style>