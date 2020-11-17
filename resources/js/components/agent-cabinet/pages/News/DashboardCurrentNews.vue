<template>
  <v-row>
    <v-col cols="12" class="pt-0">
      <v-card class="usefull-material__detail " elevation="7" v-if="currentNews">
        <v-card-text style="font-size: 1.25rem;">
          <div style="color: black;">{{ currentNews.title }}</div>
        </v-card-text>
        <v-divider class="ml-4 mr-4 mb-1 mt-0"></v-divider>
        <div style="padding: 0 25px; text-align: left;">
          <span style="letter-spacing: 0.05rem; font-size: 0.79rem; display: inline-block;">
            {{`${$formatDate(currentNews.updated_at)}`}}&nbsp; {{`${currentNews.updated_at.substring(11,19)}`}}
          </span>
        </div>
        <div style="display: flex; justify-content: center; padding: 15px 22px 25px 22px;">
          <img :src="currentNews['news_title_image']" style="width: 100%; max-width: 990px;"/>
        </div>
        <v-card-text v-html="currentNews.content" class="current-material" style="overflow: hidden;">
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import axios from 'axios'
export default {
  data: () => ({
    currentNews: null,
    news: [],
  }),
  methods: {
    getAllNews() {
      console.log('getAllNews')
      this.news.splice(0)
      axios
        .get('/json/news')
        .then(response => {
          this.news.push(...response.data)
          this.getCurrentNews()
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
        })
    },
    getCurrentNews() {
      console.log('getCurrentNews')
      if(this.news.length) {
        this.currentNews = this.news.filter(v => {
          return v.slug === this.$route.params.slug
        })[0]
        console.log({CurrentNews: this.currentNews})
      }
    }
  },
  created() {
    if(Object.keys(this.$route.params).length > 1) {
      this.currentNews = this.$route.params
    } else {
      this.getAllNews()
    }
  },
  mounted() {
    document.body.scrollTop = document.documentElement.scrollTop = 0
  }
}
</script>