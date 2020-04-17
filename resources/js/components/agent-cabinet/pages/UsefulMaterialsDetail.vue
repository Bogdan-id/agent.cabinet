<template>
<div class="row">
  <v-card class="usefull-material__detail">
    <div class="pt-2 pl-2">
      <span
        class="body-1"
        v-for="(item, key) in breadcrumbs"
        :key="key">
        <span v-if="key !== 0" class="slash">/</span>
        <router-link 
          :to="item.path"
          :class="item.disabled ? 'not-active' : ''"
          >
          {{ item.text }}
        </router-link>
      </span>
    </div>
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
  created() {
    Object.assign(this.article, this.$router.currentRoute.params.data)
    let path = this.$router.currentRoute.path.split('/')
    path.shift()
    const pathLength = path.length
    let i = 0
    let breadcrumbs = path.map(val => {
      i++
      if(i < pathLength) {
        return {'text': val, path: `/${val}`, disabled: false}
      } else {
        return {'text': val, path: `/${val}`, disabled: true}
      }
    })
    this.breadcrumbs = breadcrumbs
  }
}
</script>

<style lang="scss">
.usefull-material__detail {
  width: 100%;
  p {
    color: black;
  }
  .not-active {
    pointer-events: none;
    cursor: default;
    text-decoration: none;
    color: #c1c1c1;
  }
  .slash {
    padding: 0 5px 0 5px; 
    color: #c1c1c1;
  }
}
</style>