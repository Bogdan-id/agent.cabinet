<template>
    <v-app-bar absolute dark app dense short tile>
        <v-app-bar-nav-icon @click="$emit('listenDrawer', !drawer)"></v-app-bar-nav-icon>
        <v-spacer></v-spacer>
        <v-btn 
          @click="logOut()"
          small dense outlined>
          Вихiд
        </v-btn>    
    </v-app-bar>
</template>

<script>
import axios from 'axios'

export default {
	props: ['drawer'],
	data: () => ({
		drawerState: null,
  }),
  methods: {
    logOut() {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/admin/auth/logout?_pjax=%23pjax-container', 
          {
            headers: {
              'X-PJAX': true,
              'X-PJAX-Container': '#pjax-container',
              'X-Requested-Width': 'XMLHttpRequest'
            }
          }
        )
        .then(response => {
          console.log(response)
          this.$store.commit('toggleAdminSpinner', false)
        })
        .catch(error => {
          console.log(error.response)
          this.$store.commit('toggleAdminSpinner', false)
        })
    },
    getCsrf() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
  },
	mounted() {
		this.drawerState = this.drawer
	},

}
</script>