<template>
    <div class="row">
      <bread-scrumb />  
      <div class="col-md-12 useful-materials">
        <router-view></router-view>
        <v-card style="min-height: 350px;" v-show="$route.name === 'Кориснi матерiали'">
          <v-card-title class="d-block grey darken-3 white--text">
            <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-bookmark'"></v-icon>
            Кориснi матерiали
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
          <v-card-text v-if="routes !== null">
            <v-hover
              v-for="(item) in sections"
              :key="item.id"
              v-slot:default="{ hover }" 
              open-delay="10">
              <v-card
                :elevation="hover ? 7 : 3"
                :class="hover ? `useful-materials__article mt-2 mb-2 ml-2` : `useful-materials__article mt-2 mb-2`"
                :style="hover ? 'border-left: 7px solid #e64833;' : 'border-left: 7px solid white'"
                tag="a"
                :to="routeObject(item.slug, item)">
                <v-card-title class="custom-title" style="justify-content: center; font-size: 27px;">
                  <a>{{ item.name }}</a>
                </v-card-title>
                <!-- <p>{{ item.text }}
                  <router-link 
                    :to="routeObject(item.route, item)">
                  </router-link>
                </p> -->
                <!-- <p>{{ item.text }}<a :href="item.link"></a></p> -->
              </v-card>
            </v-hover>
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
    sections: null,
    routes: null,
    loading: false,
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
    this.loading = true
    this.$store.commit('toggleSpinner', true)
    axios
      .get('/useful-materials-categories/all/')
      .then(response => {
        console.log(response)
        this.sections = response.data
        this.$store.commit('toggleSpinner', false)
        this.loading = false
      })
      .catch(error => {
        console.log(error.reponse)
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Код помилки: ${error.response.status} \n ${error.response.data.message}`,
        })
        this.loading = false
        this.$store.commit('toggleSpinner', false)
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
    .elevation-7 {
      a {
        color: #d32f2f!important;
        text-decoration: underline;
      }
    }
    .useful-materials__article {
      transition: all 0.3s ease;
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
      .custom-title {
        & a {
          transition: all 0.3s ease;
          color: #70706e;
          &:hover {
            text-decoration: underline;
          }
        }
      }
    }
  }
</style>
