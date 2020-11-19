<template>
  <v-container>
    <v-row>
      <v-col cols="12 md-6" class="d-flex flex-column justify-center align-center">
        <h1>
          {{ pageNotFound }}
        </h1>
        <router-link to="/">
          Перейти на головну
        </router-link>
      </v-col>
    </v-row>
  </v-container>
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

<style scoped>
h1 {
  font-size: 17px;
}
</style>
