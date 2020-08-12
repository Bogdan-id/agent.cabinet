<template>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div style="position: relative" class="container-fluid d-flex justify-space-between">
      <toggleIcon
        v-show="!smAndDown"
        @click.native="toggleCustomSidebar()"
        :class="showSidebar ? 'sidebar__toggle-icon' : 'sidebar__toggle-icon --toggle-icon-active'">
      </toggleIcon>
      <div v-show="$vuetify.breakpoint.smAndDown" style="display: inline-flex">
        <v-btn 
          @click="$emit('listenDrawer', !drawer)"
          icon large class="pa-2 ma-2">
          <v-icon size="36" v-text="'mdi-menu'"></v-icon>
        </v-btn>
      </div>
      <div style="display: inline-block"></div>
      <div class="notification-wrapper">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn 
              v-on="on"
              v-show="$vuetify.breakpoint.xs" 
              to="/agent-profile" 
              x-large icon>
              <v-icon x-large v-text="'mdi-account-circle-outline'"></v-icon>
            </v-btn>
          </template>
          <span>Профiль</span>
        </v-tooltip>
        <div
          v-show="!$vuetify.breakpoint.xs"
          class="user-brief-profile-wrapper">
          <v-icon x-large v-text="'mdi-account-circle-outline'"></v-icon>
          <div class="agent-name">
            {{ currentAgent }}
          </div>
          <v-menu 
            bottom 
            :offset-y="true" 
            :nudge-left="200" 
            :nudge-bottom="25"
            :close-on-content-click="false">
            <template v-slot:activator="{ on }">
              <v-btn 
                ref="briefProfileBtn" 
                v-on="on" @click="showBriefUserProfile = !showBriefUserProfile" 
                small icon>
                <v-icon v-text="'mdi-menu-down'"></v-icon>
              </v-btn>
            </template>
            <v-card :min-width="330">
              <v-card-text 
                class="pb-0 pt-2"
                style="
                  border-left: 4px solid #e75d57; 
                  font-size: 1.1rem; 
                  color: black; 
                  font-family: montserrat;">
                {{ currentAgent }}
              </v-card-text>
              <!-- <v-divider></v-divider> -->
              <v-card-text class="pt-3 pb-1">
                <!-- <div>
                  <span>Компанiя:</span>
                  <div 
                    class="pl-3" 
                    style="font-size: 1rem; color: black; font-family: montserrat">
                    Best leasing
                  </div>
                </div> -->
                <div>
                  <div 
                    class="pt-1 pb-1"
                    style="font-size: 1rem; color: black; font-family: montserrat">
                    <v-icon color="black" size="19" class="pr-1" v-text="'mdi-phone'"></v-icon>+38 (095) 741 92 42
                  </div>
                </div>
                <div>
                  <div 
                    class="pb-2"
                    style="font-size: 0.97rem; color: black; font-family: montserrat">
                    <v-icon color="black" size="19" class="pr-1" v-text="'mdi-email'"></v-icon>user332145@gmail.com
                  </div>
                </div>
                <div>
                  <router-link 
                    class="profile-link"
                    @click.native="$refs.briefProfileBtn.$el.click()"
                    style="color: #bb433c; font-size: 1rem; padding: 3px 5px; font-family: montserrat; display: inline-block; " 
                    to="/agent-profile">
                    Перейти у профiль
                  </router-link>
                </div>
              </v-card-text>
            </v-card>
          </v-menu>
        </div>
        <v-tooltip bottom>
          <template #activator="{ on }">
            <!-- <span style="display: inline-block; height: 52px; width: 52px;"> -->
              <v-btn style="position: relative;" v-on="on" @click="toggleNotifyCard($event)" x-large icon>
                <div class="notification-count" v-if="notificationCount > 0">
                  {{ notificationCount > 3 ? '3+' :  notificationCount === 0 ? '' : notificationCount}}
                </div>
                <v-icon large v-text="'mdi-bell-outline'" id="notify-btn"></v-icon>
              </v-btn>
              <span style="position: relative;">
              <v-card id="cadr-notification" :class="`${$vuetify.breakpoint.xs ? 'small' : ''}`" elevation="8">
                <v-card-title style="border-left: 4px solid #e57373" class="subtitle-1">
                  Повiдомлення
                </v-card-title>
                <v-card-text v-if="notificationCount == 0">
                  <div><span>(Повiдомлення вiдсутнi)</span></div>
                </v-card-text>
                <v-timeline dense v-if="notificationCount > 0">
                  <v-timeline-item
                    v-for="(item, key) in notifications"
                    v-if="key <= maxNotificationsToShow -1"
                    :key="key"
                    :color="item.status === 'checked' ? 'grey lighten-2' : 'red lighten-2'"
                    small
                    right>
                    <div class="time-line-content">
                      <div style="font-size: 1rem" color="red">{{ item.title }}</div>
                      <span style="font-size: 0.85rem; color: #808080">{{ $formatDate(item.updated_at) }}</span>
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
            <!-- </span> -->
          </template>
          <span>Повiдомлення</span>
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
  props: ['drawer'],
  components: {
    toggleIcon,
  },
  data:() => ({
    showBriefUserProfile: false,
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
    currentAgent() {
      if(!this.hasUser) return
      return `${this.$store.state.user.agent.last_name} ${this.$store.state.user.agent.first_name} ${this.$store.state.user.agent.patronymic}`
    }
  },
  methods: {
    changeNotificationsStatus(object) {
      axios
        .post(`/agent/notifications/checking`, object)
        .then(() => {
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
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
      body.addEventListener('click', event => {
        if(notifyCard.classList.contains('show-card') && event.target.id !== "notify-btn") {
          this.toggleNotifyCard()
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
			.catch(error => {
        this.$catchStatus(error.response.status)
        this.$notify({
          message: error.response.data.message,
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
          this.notifications = response.data.sort((a, b) => {
            return ('' + b.status).localeCompare(a.status)
          })
          console.log(this.notifications)
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
        })
    },
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1)
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
.profile-link:hover {
  color: #e57373;
  text-decoration: underline;
  transition: color 0.2s ease-in; 
}
.v-timeline--dense:not(.v-timeline--reverse):before {
  right: auto;
  left: 25px!important;
}
.agent-name {
  font-size: 1rem;
  display: inline-block;
  color: #727170;
  max-width: 160px;
  text-transform: uppercase;
  line-height: 1.2rem;
  padding: 3px 5px;
  overflow: hidden;
  text-overflow: ellipsis;
}
.user-brief-profile-wrapper {
  display: flex; 
  align-items: center; 
  margin: 0 10px; 
  font-family: montserrat;
}
.notification-wrapper {
  display: flex;
  align-items: center;
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
  right: -42px;
  &.small {
    right: -120px;
    width: 190px;
  }
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
