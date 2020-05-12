<template>
  <v-app class="admin-panel">
    <v-progress-linear
      :active="loading"
      :indeterminate="loading"
      absolute
      :height="4"
      top
      style="z-index: 5;"
      color="red">
    </v-progress-linear>
    <v-content>
      <router-view></router-view>
    </v-content>
    <NavigationDrawer
      @listenDrawer="drawer = $event" 
      :drawer="drawer"/>
    <AppBar 
      :drawer="drawer"
      @listenDrawer="drawer = $event"/>
    </v-app>
</template>

<script>
import AppBar from './components/AppBar.vue'
import NavigationDrawer from './components/NavigationDrawer.vue'

export default {
  components: {
		AppBar,
    NavigationDrawer,
  },
  data: () => ({
    drawer: true,
  }),
  computed: {
    loading() {
      return this.$store.state.adminLoader
    }
  },
  mounted() {
    this.$router.push({name: 'application-for-registration'})
  }
}
</script>