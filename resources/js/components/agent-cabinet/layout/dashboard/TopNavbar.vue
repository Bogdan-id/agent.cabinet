<template>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
      <toggleIcon
        @click.native="toggleCustomSidebar()"
        :class="showSidebar ? 'sidebar__toggle-icon' : 'sidebar__toggle-icon --toggle-icon-active'"
        >
      </toggleIcon>
      <a class="navbar-brand" href="#">{{routeName}}</a>
      <!-- <button class="navbar-toggler navbar-burger"
              type="button"
              @click="toggleSidebar"
              :aria-expanded="$sidebar.showSidebar"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-bar"></span>
        <span class="navbar-toggler-bar"></span>
        <span class="navbar-toggler-bar"></span>
      </button> -->
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <drop-down class="nav-item"
                     title="Повiдомлення"
                     title-classes="nav-link"
                     icon="ti-bell">
            <a class="dropdown-item" href="#">Повiдомлення 1</a>
            <a class="dropdown-item" href="#">Повiдомлення 2</a>
            <a class="dropdown-item" href="#">Повiдомлення 3</a>
            <a class="dropdown-item" href="#">Повiдомлення 4</a>
          </drop-down>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="ti-settings"></i>
              <p>
                Налаштування
              </p>
            </a>
          </li>
        </ul>
      </div>
      <v-tooltip bottom>
        <template #activator="{ on }">
          <v-btn @click="signOut()" v-on="on" icon x-large>
            <v-icon large v-text="'mdi-logout'"></v-icon>
          </v-btn>
        </template>
        <span>Вихiд</span>
      </v-tooltip>
    </div></nav>
</template>
<script>
import toggleIcon from "../../assets/svg-icons/arrow.vue"
import axios from 'axios'

export default {
  components: {
    toggleIcon
  },
  computed: {
    routeName() {
      const { name } = this.$route;
      return this.capitalizeFirstLetter(name)
    },
    smAndDown() {
      return this.$vuetify.breakpoint.smAndDown
    },
    mdAndUp() {
      return this.$vuetify.breakpoint.mdAndUp
    }
  },
  data:() => ({
    activeNotifications: false,
    showSidebar: true
  }),
  methods: {
    toggleCustomSidebar() {
      let sidebar = document.querySelector('.sidebar')
      let mainPanel = document.querySelector('.main-panel')
      if(this.showSidebar) {
        mainPanel.classList.add('main-panel--expand')
        sidebar.classList.add('sidebar--hide')
        this.showSidebar = false
      } else if(!this.showSidebar) {
        mainPanel.classList.remove('main-panel--expand')
        sidebar.classList.remove('sidebar--hide')
        this.showSidebar = true
      }
    },
    signOut() {
      this.logOut(this.getCsrf())
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    logOut(token) {
      this.$store.commit('toggleSpinner', true)
      axios.post(`/logout`, token)
			.then(() => {
        this.$router.go()
        const routeStorage = window.localStorage
        routeStorage.setItem('user', false)
        this.$store.commit('toggleSpinner', false)
			})
			.catch(e => {
        this.$notify({
          message: e.response.data.message,
          type: 'warning',
          horizontalAlign: 'center'
        })
        this.$store.commit('toggleSpinner', false)
				console.log(e.response.data)
        console.log(e.response.status)
        console.log(e.response.headers)
			})
		},
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    // toggleNotificationDropDown() {
    //   this.activeNotifications = !this.activeNotifications;
    // },
    // closeDropDown() {
    //   this.activeNotifications = false
    // },
    // toggleSidebar() {
    //   this.$sidebar.displaySidebar(!this.$sidebar.showSidebar)
    // },
    // hideSidebar() {
    //   this.$sidebar.displaySidebar(false);
    // },
  },
  watch: {
    smAndDown(val) {
      val && this.showSidebar
        ? this.toggleCustomSidebar()
        : false
    },
    mdAndUp(val) {
      val && !this.showSidebar
        ? this.toggleCustomSidebar()
        : false
    }
  },
  mounted() {
    this.smAndDown ? this.toggleCustomSidebar() : false
  }
}
</script>
<style>
</style>
