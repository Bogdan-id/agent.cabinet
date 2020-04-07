<template>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">{{routeName}}</a>
      <button class="navbar-toggler navbar-burger"
              type="button"
              @click="toggleSidebar"
              :aria-expanded="$sidebar.showSidebar"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-bar"></span>
        <span class="navbar-toggler-bar"></span>
        <span class="navbar-toggler-bar"></span>
      </button>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="ti-panel"></i>
              <p>Stats</p>
            </a>
          </li>
          <drop-down class="nav-item"
                     title="5 Notifications"
                     title-classes="nav-link"
                     icon="ti-bell">
            <a class="dropdown-item" href="#">Notification 1</a>
            <a class="dropdown-item" href="#">Notification 2</a>
            <a class="dropdown-item" href="#">Notification 3</a>
            <a class="dropdown-item" href="#">Notification 4</a>
            <a class="dropdown-item" href="#">Another notification</a>
          </drop-down>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="ti-settings"></i>
              <p>
                Settings
              </p>
            </a>
          </li>
          <p-button 
            @click.native="signOut()" 
            type="primary" 
            size="sm"
            >
            <div v-if="btnLogOut" class="lds__wrapper">
              <div class="lds-dual-ring"></div>
            </div>
            <span v-if="!btnLogOut">Log out</span>
          </p-button>
        </ul>
      </div>
    </div></nav>
</template>
<script>
import {Button as PButton} from '../../components'
import axios from 'axios'

export default {
  components: {
    PButton
  },
  computed: {
    routeName() {
      const { name } = this.$route;
      return this.capitalizeFirstLetter(name);
    }
  },
  data:() => ({
    btnLogOut: false,
    activeNotifications: false
  }),
  methods: {
    signOut() {
      this.btnLogOut = true
      this.logOut(this.getCsrf())
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    logOut(token) {
      axios.post(`/logout`, token)
			.then(() => {
        this.btnLogOut = false
        this.$router.push({ name: 'authorization', params: { reload: true }})
			})
			.catch(e => {
        this.btnLogOut = false
        this.$notify({
          message: e.response.data.message,
          type: 'warning',
          horizontalAlign: 'center'
        })
				console.log(e.response.data)
        console.log(e.response.status)
        console.log(e.response.headers)
			})
		},
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    toggleNotificationDropDown() {
      this.activeNotifications = !this.activeNotifications;
    },
    closeDropDown() {
      this.activeNotifications = false;
    },
    toggleSidebar() {
      this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
    },
    hideSidebar() {
      this.$sidebar.displaySidebar(false);
    }
  },
};
</script>
<style>
</style>
