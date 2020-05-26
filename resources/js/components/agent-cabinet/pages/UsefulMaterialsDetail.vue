<template>
<div class="row">
  <v-card class="usefull-material__detail">
    <v-card-title class="d-block">
      <div>{{ article.name }}</div>
      <v-divider></v-divider>
    </v-card-title>
    <material-content
      v-for="(item, key) in article"
      :key="key"
      :item="item">
    </material-content>
  </v-card>
</div>
</template>

<script>
import MaterialContent from './UserfulMaterialsContent'
export default {
  components: {
    MaterialContent
  },
  data: () => ({
    article: [
      {title: 'Тайтл для материала', text: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. ', image: require('D:/Bogdan/Image/10781.jpg'), route: 'test'},
      {title: 'Тайтл для материала', text: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. ', image: require('D:/Bogdan/Image/10781.jpg'), route: 'test'}
    ],
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
    console.log(this.$router.currentRoute.params.data)
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
  .image-column {
    width: 100%;
    height: 200px;
  }
  .text-column {
    width: 100%;
    height: 200px;
    display: flex;
    flex-direction: column;
  }
}
</style>