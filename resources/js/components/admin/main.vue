<template>
  <v-app class="admin-panel">
    <router-view></router-view>
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
}
</script>