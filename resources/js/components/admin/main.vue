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
    <Footer />
  </v-app>
</template>

<script>
import AppBar from './components/AppBar.vue'
import NavigationDrawer from './components/NavigationDrawer.vue'
import Footer from './components/Footer.vue'

export default {
  components: {
		AppBar,
    NavigationDrawer,
    Footer
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
    console.log(this.$route.name)
    if(this.$route.name === 'Aдмiн'){
      this.$router.push({name: 'application-for-registration'})
    }
  }
}
</script>