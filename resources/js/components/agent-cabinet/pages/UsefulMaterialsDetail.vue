<template>
<div class="row">
  <v-card class="usefull-material__detail">
    <v-card-title class="d-block">
      <div>{{ article.title }}</div>
      <v-divider></v-divider>
    </v-card-title>
    <v-card-text>
      <p>{{ article.text }}</p>
    </v-card-text>
  </v-card>
</div>
</template>

<script>
export default {
  data: () => ({
    article: {},
    breadcrumbs: null
  }),
  computed: {
    hasLinks() {
      return Object.keys(this.$store.state.breadScrumb).length > 0
    },
    breadscrumbData() {
      return this.$store.state.breadScrumb
    }
  },
  created() {
    Object.assign(this.article, this.$router.currentRoute.params.data)
  }
}
</script>

<style lang="scss">
.usefull-material__detail {
  width: 100%;
  p {
    font-size: 16px;
    display: block;
  }
}
</style>