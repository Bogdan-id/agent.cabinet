<template>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div style="position: relative" class="container-fluid d-flex justify-space-between">
      <toggleIcon
        @click.native="toggleCustomSidebar()"
        :class="showSidebar ? 'sidebar__toggle-icon' : 'sidebar__toggle-icon --toggle-icon-active'">
      </toggleIcon>
      <div class="notification-wrapper">
        <v-tooltip bottom>
          <template #activator="{ on }">
            <span style="display: inline-block; height: 52px; width: 52px;">
              <v-btn style="position: relative;" v-on="on" @click="toggleNotifyCard($event)" large icon>
                <div class="notification-count" v-if="notificationCount > 0">
                  {{ notificationCount > 3 ? '3+' :  notificationCount === 0 ? '' : notificationCount}}
                </div>
                <v-icon large v-text="'mdi-bell-outline'" id="notify-btn"></v-icon>
              </v-btn>
              <span style="position: relative;">
              <v-card id="cadr-notification" elevation="8">
                <v-card-title style="border-left: 4px solid #e57373" class="subtitle-1">
                  Повiдомлення
                </v-card-title>
                <v-card-text v-if="notificationCount == 0">
                  <div><span>(Повiдомлення вiдсутнi)</span></div>
                </v-card-text>
                <v-timeline dense v-if="notificationCount > 0">
                  <v-timeline-item
                    v-for="(item, key) in notifications"
                    v-if="key <= maxNotificationsToShow -1 && item.status !== 'checked'"
                    :key="key"
                    color="red lighten-2"
                    small
                    right>
                    <div class="time-line-content">
                      <div style="font-size: 1rem" color="red">{{ item.title }}</div>
                      <span style="font-size: 0.85rem; color: #808080">{{ item.updated_at.substr(0, 10) }}</span>
                    </div>
                  </v-timeline-item>
                </v-timeline>
                <div class="pt-2 pb-2 pl-6" style="border-top: 1px solid #e9ecef">
                  <router-link 
                    @click.native.stop="toggleNotifyCard()" 
                    class="notification-card-link" 
                    :to="{name: 'Повiдомлення', path: '/notifications', params: {notifications: notifications}}">
                    Дивитись всi 
                  </router-link>
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
    </div>
  </nav>
</template>
<script>
import toggleIcon from "../../assets/svg-icons/arrow.vue"
import axios from 'axios'

export default {
  components: {
    toggleIcon,
  },
  data:() => ({
    activeNotifications: false,
    showSidebar: true,
    notifications: null,
    notificationKeys: [],
    maxNotificationsToShow: 3,
  }),
  computed: {
    notificationCount() {
      if(!this.notifications) return 0
      let index = this.notifications
        .filter(val => {
          return val.status === 'not_checked'
        })
      return index.length
    },
    routeName() {
      const { name } = this.$route;
      return this.capitalizeFirstLetter(name)
    },
    smAndDown() {
      return this.$vuetify.breakpoint.smAndDown
    },
    mdAndUp() {
      return this.$vuetify.breakpoint.mdAndUp
    },
    hasUser() {
    if(!this.$store.state.user.agent) return false
      return this.$store.state.user.agent.id !== null
    },
  },
  methods: {
    changeNotificationsStatus(object) {
      axios
        .post(`/agent/notifications/checking`, object)
        .then(response => {
          console.log(response)
        })
        .catch(error => {
          console.log(error.response)
        })
    },
    toggleCustomSidebar() {
      let sidebar = document.getElementById('sidebar')
      let mainPanel = document.getElementById('main-panel')
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
      let card = document.getElementById('cadr-notification')
      if(!card.classList.contains('show-card')) {
        card.classList.add('show-card')
        if(this.notifications.length === 0) return
        this.notifications.length < this.maxNotificationsToShow
          ? this.maxNotificationsToShow = this.notifications.length
          : false
        for(let i = 0; i <= this.maxNotificationsToShow -1; i ++) {
          this.notifications[i].status === 'not_checked'
          ? this.notificationKeys.push(this.notifications[i].id)
          : false
        }
      } else {
        card.classList.remove('show-card')
        if(this.notificationKeys.length === 0) return
        this.changeNotificationsStatus({notifications: this.notificationKeys})
        this.notificationKeys = []
        this.getAgentNotifications()
      }
    },
    listenCardState() {
      let body = document.getElementById('app')
      let notifyCard = document.getElementById('cadr-notification')
      console.log(notifyCard)
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
    getAgentNotifications() {
      axios
        .get(`/agent/notifications/${this.$store.state.user.agent.id}`)
        .then(response => {
          console.log(response)
          this.notifications = response.data.sort((a, b) => {
            return new Date(b.updated_at) - new Date(a.updated_at);
          })
        })
        .catch(error => {
          console.log(error.response)
        })
    },
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
  },
  watch: {
    hasUser() {
      this.getAgentNotifications()
    },
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
  .notification-wrapper {
    .v-timeline {
      .v-timeline-item {
        .v-timeline-item__divider {
          min-width: 45px!important;
          margin-top: 10px;
          position: absolute;
          left: 3px!important;
        }
        .v-timeline-item__body {
          max-width: 220px!important;
          position: relative!important;
          margin-right: 0.5rem!important;
        }
      }
    }
  }

.time-line-content {
  position: relative;
  &:hover {
    cursor: pointer;
  }
}
#cadr-notification {
  position: absolute; 
  min-width: 290px; 
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
.notification-count {
  color: white; 
  background: #ff5252; 
  display: inline-flex; 
  align-items: center; 
  justify-content: center; 
  border-radius: 100%; 
  padding: 3px;
  min-height: 20px;
  min-width: 20px;
  position: absolute; 
  font-size: 0.68rem;
  right: -10px;
  top: -10px;
}
</style>
