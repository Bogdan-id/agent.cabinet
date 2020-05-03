<template>
    <div class="row">
      <bread-scrumb />  
      <div class="col-md-12 useful-materials">
        <router-view ref="rv"></router-view>
        <v-card v-show="$route.name === 'Кориснi матерiали'">
          <v-card-title class="d-block">
            <div>
              Кориснi матерiали
            </div>
          <v-divider></v-divider>
          </v-card-title>
          <v-card-text v-if="routes !== null">
            <div
              v-for="(item) in sections"
              :key="item.id"
              class="useful-materials__article">
              <div class="headline">
                <router-link 
                  :to="routeObject(item.slug, item)"
                  >{{ item.name }}
                </router-link>
              </div>
              <!-- <p>{{ item.text }}
                <router-link 
                  :to="routeObject(item.route, item)">
                </router-link>
              </p> -->
              <!-- <p>{{ item.text }}<a :href="item.link"></a></p> -->
            </div>
          </v-card-text>
        </v-card>
      </div>
    </div>
</template>
<script>
import BreadScrumb from '../components/breadScrumb'
import axios from 'axios'

export default {
  components: {
    BreadScrumb
  },
  data: () => ({
    // sections: [
    //   {
    //     title: 'Інформація про нас', 
    //     text: 'Текст генератор для роздiлу Інформація про нас',
    //     route: 'about-us'
    //   },
    //   {
    //     title: 'Тренінги', 
    //     text: 'Генератор тексту для роздулу Тренінги',
    //     route: 'trainings'
    //   },
    //   {
    //     title: 'Відеоогляди', 
    //     text: 'Текст генератор для роздiлу Відеоогляди',
    //     route: 'video-review'
    //   },
    //   {
    //     title: 'Законодавство', 
    //     text: 'Текст генератор для роздiлу Законодавство',
    //     route: 'legislation'
    //   },
    //   {
    //     title: 'Страхові пакети', 
    //     text: 'Генератор тексту для роздулу Страхові пакети',
    //     route: 'insurance-packages'
    //   },
    //   {
    //     title: 'Асистанс', 
    //     text: 'Текст генератор для роздiлу Асистанс',
    //     route: 'assistance'
    //   },
    // ],
    sections: null,
    routes: null,
  }),
  methods: {
    routeObject(route, data) {
      return {name: 'Кориснi матерiали вiд Best-leasing', params: {detail: route, data: data}}
    },
    test() {
      // console.log(this.$route)
    }
  },
  computed: {
    isExactActive() {
      return this.$refs.rv === 'undefined' || this.$refs.rv === null
    }
  },
  mounted() {
    this.routes = this.$router.currentRoute.path
    console.log(this.routes)
  },
  created() {
    axios
      .get('/useful-materials-categories/all/')
      .then(response => {
        console.log(response)
        this.sections = response.data
      })
      .catch(error => {
        console.log(error.reponse)
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Код помилки: ${error.response.status} \n ${error.response.data.message}`,
        })
      })
  }
}
</script>
<style lang="scss">
  .useful-materials {
    p {
      display: none;
      color: black;
    }
    .useful-materials__article {
      padding-bottom: 15px;
      p:first-of-type {
        display: inline-block;
        & ::after {
          content: "читати далi...";
          color: #d32f2f;
          margin-left: 8px;
        }
      }
      & .article-title {
        display: block;
        margin-bottom: 5px;
        font-size: 15px;
        color: #666666;
      }
      .headline {
        & a {
          color: #d32f2f
        }
      }
    }
  }
</style>
