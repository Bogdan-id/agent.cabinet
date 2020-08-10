<template>
    <v-app-bar absolute dark app dense short tile>
        <v-app-bar-nav-icon @click="$emit('listenDrawer', !drawer)"></v-app-bar-nav-icon>
        <v-spacer></v-spacer>
        <v-btn 
          @click="logOut()"
          :loading="loading"
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
    loading: false,
  }),
  methods: {
    logOut() {
      this.loading = true
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/admin/logout', 
          // {
          //   headers: {
          //     'X-PJAX': true,
          //     'X-PJAX-Container': '#pjax-container',
          //     'X-Requested-Width': 'XMLHttpRequest'
          //   }
          // }
        )
        .then(() => {
          // this.$router.push({name: 'admin-authorization', params: {reload: true}})
          this.$store.commit('toggleAdminSpinner', false)
          this.loading = false
        })
        .catch(error => {
          this.loading = false
          console.log(error.response)
          this.$store.commit('toggleAdminSpinner', false)
          this.loading = false
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