<template>
  <div class="app">
    <authorization :reload="forceReload"></authorization>
    <presentation></presentation>
  </div>
</template>

<script>
import Authorization from './authorize'
import Presentation from './presentation'

export default {
  data: () => ({
    forceReload: false,
  }),
  components: {
    Authorization,
    Presentation
  },
  created() {
    const obj = this.$router.currentRoute.params
    obj.reload != null
      ? this.forceReload = obj.reload
      : false
  }
}
</script>