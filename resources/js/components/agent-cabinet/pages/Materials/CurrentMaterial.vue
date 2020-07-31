<template>
<v-row>
  <v-col cols="12" class="pt-0">
    <v-card class="usefull-material__detail " elevation="7" v-if="article">
      <v-card-text style="text-align: center!important; font-size: 1.25rem;">
        <div style="color: black;">{{ article.title }}</div>
      </v-card-text>
      <v-divider class="ml-4 mr-4 mb-1"></v-divider>
      <div style="padding: 0 25px; text-align: left;">
        <span style="letter-spacing: 0.05rem; font-size: 0.79rem; display: inline-block;">
          {{`${article.updated_at.substring(0,10).replace(/-/g, '/')}`}}&nbsp; {{`${article.updated_at.substring(12,19)}`}}
        </span>
      </div>
      <div style="display: flex; justify-content: center; padding: 15px 22px 25px 22px;">
        <img :src="article.title_image" style="width: 100%; max-width: 990px;"/>
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
  mounted() {
    document.body.scrollTop = document.documentElement.scrollTop = 0
    axios
      .get(`/json${this.$router.currentRoute.path}`)
      .then(response => {
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
        } else {
          this.article = response.data
        }
      })
      .catch(error => {
        console.log(error.response)
      })
  },
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
    display: flex;
    justify-content: center;
    &.image-style-align-right {
      float: right;
    }
    &.image-style-align-left {
      float: left;
    }
  }
}
</style>