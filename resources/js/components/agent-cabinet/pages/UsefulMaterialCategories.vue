<template>
<v-card style="min-height: 350px;" elevation="8">
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
  <v-card-text>
    <v-hover
      v-for="item in sections"
      :key="item.id"
      v-slot:default="{ hover }" 
      open-delay="10">
      <v-card
        :elevation="hover ? 7 : 3"
        :class="hover ? `useful-materials__article mt-2 mb-2 ml-2` : `useful-materials__article mt-2 mb-2`"
        :style="hover ? 'border-left: 7px solid #e64833;' : 'border-left: 7px solid white'"
        :to="{name: 'Матерiали категорiї', params: {id: item.id, name: item.name}}"
        >
        <!-- @click="toRoute(item.slug, item.id)" -->
        <v-card-title class="custom-title" style="justify-content: center; font-size: 27px;">
          <a>{{ item.name }}</a>
        </v-card-title>
      </v-card>
    </v-hover>
  </v-card-text>
</v-card>
</template>

<script>
import axios from 'axios'
export default {
  data: () => ({
    sections: null,
    loading: false,
  }),
  methods: {
    test() {
      // console.log(this.$route)
    },
    toRoute(id, name) {
      this.$router.push({name: 'Матерiали категорiї', params: {id: id, name: name}})
    }
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
      // display: none;
      color: #343a40;
      letter-spacing: 0.02rem;
      font-size: 14px;
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