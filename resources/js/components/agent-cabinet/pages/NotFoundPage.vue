<template>
<div class="error-page-wrapper">
  <v-container class="error-page">
    <v-row>
      <v-col cols="12 md-6" class="d-flex flex-column justify-center align-center">
        <h4>
          {{ pageNotFound }}
        </h4>
        <router-link class="home-link" to="/">
          Перейти на головну
        </router-link>
      </v-col>
    </v-row>
  </v-container>
</div>
</template>

<script>
export default {
  data () {
    return {
      pageNotFound: '404 Сторiнку не знайдено',
      otherError: 'Виникла помилка'
    }
  }
}
</script>

<style lang="scss">
  .error-page-wrapper {
    background: ghostwhite;
  }
  .container {
    &.error-page {
      height: 100vh;
      .home-link {
        transition: color 0.1s ease-out;
        &:hover {
          color: black;
          text-decoration: underline;
        }
      }
    }
  }
</style>
