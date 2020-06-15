<template>
<div class="d-inline-block" v-if="hasLinks">
  <div class="breadScrumb">
    <span
      v-for="(item, key) in breadscrumbData"
      :key="key">
      <span v-if="key != 0" class="slash">/</span>
      <router-link 
        :to="item.path"
        :class="item.disabled ? 'not-active' : ''">
        {{ item.text }}
      </router-link>
    </span>
  </div>
</div>
</template>

<script>
export default {
  computed: {
    hasLinks() {
      return Object.keys(this.$store.state.breadScrumb).length > 1
    },
    breadscrumbData() {
      return this.$store.state.breadScrumb
    },
    currentRoute() {
      return this.$route.matched
    }
  },
  methods: {
    getBreadsCrumbData() {
      let routeData = this.$route.matched
      let paths = this.$route.matched.length
      let i = 0
      let breadsData = routeData
        .filter((val, key) => key !== 0 )
        .map(val => {
          i++
          if(i + 1 < paths) {
            return { path: val.path, text: val.name, disabled: false }
          } else {
            return { path: val.path, text: val.name, disabled: true }
          }
        })
      this.$store.commit('setBreadScrumb', breadsData)
    }
  },
  watch: {
    currentRoute() {
      this.getBreadsCrumbData()
    }
  },
  created() {
    this.getBreadsCrumbData()
  },
}
</script>

<style lang="scss">
.breadScrumb {
  display: block;
  padding: 0 10px 10px 10px;
  font-size: 16px;
  a {
    color: #424242!important;
    text-decoration: underline;
  }
  .not-active {
    pointer-events: none;
    cursor: default;
    text-decoration: none;
    color: #c1c1c1!important;
  }
  .slash {
    padding: 0 5px 0 5px; 
    color: #c1c1c1;
  }
}
</style>