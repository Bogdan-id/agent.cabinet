<template>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div style="position: relative" class="container-fluid d-flex justify-space-between">
      <toggleIcon
        @click.native="toggleCustomSidebar()"
        :class="showSidebar ? 'sidebar__toggle-icon' : 'sidebar__toggle-icon --toggle-icon-active'"
        >
      </toggleIcon>
      <!-- <i><a style="position: absolute; left: 4rem; color: #727170; font-size: 1.4rem; top: 0.69rem; font-weight: bold;" href="#">{{routeName}}</a></i> -->
      <!-- <button class="navbar-toggler navbar-burger"
              type="button"
              @click="toggleSidebar"
              :aria-expanded="$sidebar.showSidebar"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-bar"></span>
        <span class="navbar-toggler-bar"></span>
        <span class="navbar-toggler-bar"></span>
      </button> -->
      <!-- <div class="collapse navbar-collapse">
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
      </div> -->
      <div>
        <v-tooltip bottom>
          <template #activator="{ on }">
            <!-- to="/agent-profile" -->
            <span style="display: inline-block; height: 52px; width: 52px;">
              <v-btn v-on="on" @click="toggleNotifyCard($event)" large icon><v-icon v-text="'mdi-bell-outline'" color="black" id="notify-btn"></v-icon></v-btn>
              <span style="position: relative;">
              <v-card id="cadr-notification" elevation="8">
                <v-card-title style="border-left: 4px solid #e57373" class="subtitle-1">
                  Повiдомлення
                </v-card-title>
                <v-timeline dense>
                  <v-timeline-item
                    color="red lighten-2"
                    small
                    right>
                    <div>
                      <div style="font-size: 1rem" color="red">Перше повiдомлення</div>
                      <span style="font-size: 0.85rem; color: #808080">25.07.20 20:35</span>
                    </div>
                  </v-timeline-item>
                  <v-timeline-item
                    color="red lighten-2"
                    small
                    right>
                    <div>
                      <div style="font-size: 1rem" color="red">Друге повiдомлення</div>
                      <span style="font-size: 0.85rem; color: #808080">11.04.20 13:01</span>
                    </div>
                  </v-timeline-item>
                  <v-timeline-item
                    color="red lighten-2"
                    small
                    right>
                    <div>
                      <div style="font-size: 1rem" color="red">Третє повiдомлення</div>
                      <span style="font-size: 0.85rem; color: #808080">23.07.20 10:35</span>
                    </div>
                  </v-timeline-item>
                </v-timeline>
                <div class="pt-2 pb-2 pl-6" style="border-top: 1px solid #e9ecef">
                  <v-hover v-slot:default="{ hover }">
                    <router-link @click.native.stop="toggleNotifyCard()" class="notification-card-link" to="/notifications">
                      Дивитись всi 
                      <v-icon :color="hover ? '#e57373' : '#bb433c'" v-text="'mdi-arrow-right-bold'"></v-icon>
                    </router-link>
                  </v-hover>
                </div>
              </v-card>
              </span>
            </span>
          </template>
          <span>Повiдомлення</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template #activator="{ on }">
            <v-btn v-on="on" to="/agent-profile" x-large icon><v-icon large v-text="'mdi-account'"></v-icon></v-btn>
          </template>
          <span>Профiль</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template #activator="{ on }">
            <v-btn @click="signOut()" v-on="on" icon x-large>
              <v-icon large v-text="'mdi-logout'"></v-icon>
            </v-btn>
          </template>
          <span>Вихiд</span>
        </v-tooltip>
      </div>
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
    toggleNotifyCard() {
      setTimeout(() => {
        let card = document.querySelector('#cadr-notification')
        if(!card.classList.contains('show-card')) {
          card.classList.add('show-card')
        } else card.classList.remove('show-card')
      }, 150);
    },
    listenCardState() {
      let body = document.querySelector('body')
      let notifyCard = document.querySelector('#cadr-notification')
      body.addEventListener('click', event => {
        if(notifyCard.classList.contains('show-card') && event.target.id !== "notify-btn") {
          notifyCard.classList.remove('show-card')
        }
      })
      notifyCard.addEventListener('click', event => {
        event.stopPropagation()
      })
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
			})
		},
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
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
    this.listenCardState()
  }
}
</script>
<style lang="scss">
.v-timeline--dense:not(.v-timeline--reverse):before {
    right: auto;
    left: 25px!important;
  }
.v-timeline {
  .v-timeline-item {
    .v-timeline-item__divider {
      min-width: 45px!important;
      margin-top: 10px;
      position: absolute;
      left: 3px!important;
    }
    .v-timeline-item__body {
      max-width: 180px!important;
      position: relative!important;
      margin-right: 0.5rem!important;
    }
  }
}
#cadr-notification {
  position: absolute; 
  min-width: 240px; 
  right: 0;
  top: 80px!important;
  transition: opacity ease-out 0.15s, 
    top ease-out 0.15s, 
    visibility 0.15s ease-out;
  opacity: 0;
  visibility: hidden;
  &.show-card {
    visibility: visible;
    opacity: 1;
    top: 38px!important;
  }
  .notification-card-link {
    color: #bb433c; 
    font-size: 1rem;
    transition: color 0.2s ease-in!important;
    &:hover {
      color: #e57373;
    }
  }
}
</style>
