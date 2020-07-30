<template>
<v-row>
  <v-col cols="12" class="pt-0 pb-0">
  <div style="width: 100%;">
  <bread-scrumb></bread-scrumb>
  </div>
  <v-card class="usefull-material__detail" elevation="7" v-if="news !== null && $route.name === 'Новини'" style="min-height: 160px;">
    <v-card-title style="border-left: 5px solid #e75d57">
      <div style="width: 100%;">Всi новини сайту</div>
      <!-- <div style="width: 100%;"><v-divider class="mt-1 mb-1"></v-divider></div> -->
    </v-card-title>
    <v-card-text v-if="news.length === 0 || news === null">
      <div style="text-align: center;">(Матерiали для вiдображення вiдсутнi)</div>
    </v-card-text>
      <v-card-text>
        <div 
          v-for="(item, key) in news"
          :key="key">
          <v-hover 
            v-slot:default="{ hover }">
            <v-card
              @click="toNews(item)"
              :elevation="hover ? 4 : 1"
              class="d-flex ml-3 mr-3 mb-5">
              <v-row class="ml-1 mr-1">
                <v-col cols="12" xs="12" sm="4" md="3" xl="2" class="d-flex align-items-center">
                  <div class="d-flex justify-center" style="border-radius: 5px; overflow: hidden;">
                    <div v-if="item['news_title_image']">
                      <img :style="`width: 100%; max-width: ${$vuetify.breakpoint.xs ? '100%;' : '250px;'}`" :src="item['news_title_image']" />
                    </div>
                  </div>
                </v-col>
                <v-col cols="12" xs="12" sm="8" md="9" xl="10" style="display: flex; align-items: center;">
                  <div class="text-column pa-3 pt-0 pb-0">
                    <div>
                      <span class="mt-0 mb-1 presentation-card-paragraph" style="font-size: 1.1rem; line-height: 1.5rem; padding-bottom: 2px; display: inline-block;">{{ item.title}}</span>
                    </div>
                    <div class="text" v-html="item.content" style="position: relaitve">
                    </div>
                    <span style="letter-spacing: 0.065rem; font-size: 0.78rem; color: black; line-height: 2rem;">
                      {{`${item.updated_at.substring(0,10)}  ${item.updated_at.substring(12,19)}`}}
                    </span>
                  </div>
                </v-col>
              </v-row>
            </v-card>
          </v-hover>
        </div>
      </v-card-text>
    </v-card>
  <router-view></router-view>
  </v-col>
</v-row>
</template>

<script>
import axios from 'axios'
import breadScrumb from '../components/breadScrumb'

export default {
  components: {
    breadScrumb
  },
  data: () => ({
    routeItem: null,
    news: null,
  }),
  methods: {
    toNews(item) {
      this.$router.push({name: 'Детально ', path: `news/${item.slug}`, params: item})
    },
    getNews() {
      axios
        .get('/json/news')
        .then(response => {
          this.news = response.data
        })
        .catch(error => {
          console.log(error)
        })
    }
  },
  created() {
    if(this.$route.params.redirectFromDashboard === true) {
      this.news = this.$route.params.news
    } else {
      this.getNews()
    }
    
  }
}
</script>

<style scoped>
.text >>> * {
  display: none;
}
.text >>> p:first-of-type {
  padding: 0;
  margin: 0;
  max-height: 130px;
  max-width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
}
/* .text >>> *:first-child:after {
  content: 'sdflkj'
} */
</style>

<style lang="scss">
.text {
   overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 5; /* number of lines to show */
   -webkit-box-orient: vertical;
}
.presentation-card-paragraph {
  p {
    font-size: 0.85rem!important;
  }
}
.usefull-material__detail {
  width: 100%;
  // p {
  //   font-size: 16px;
  //   display: block;
  // }
  // .image-column {
  //   width: 100%;
  // }
  // .text-column {
  //   width: 100%;
  //   display: flex;
  //   flex-direction: column;
  // }
}
</style>