<template>
  <v-card elevation="8">
    <v-card-text  class="d-block grey darken-3 white--text" style="font-size: 1.25rem">
      <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-bookmark'"></v-icon>
      Повiдомлення
    <v-progress-linear
      :height="3"
      :active="loading"
      :indeterminate="loading"
      absolute
      top
      color="red lighten-1">
    </v-progress-linear>
    </v-card-text>
    <v-card-text 
      v-if="agentNotifications && agentNotifications.length === 0"
      style="font-size: 1.3rem; padding-top: 90px; padding-bottom: 90px;">
      <div style="text-align: center;">
        <span>(Повiдомлення вiдсутнi)</span>
      </div>
    </v-card-text>
    <v-card-text class="user-notification-page" style="min-height: 100vh" v-if="agentNotifications && agentNotifications.length > 0">
      <v-timeline dense>
        <v-timeline-item
          v-for="(item, key) in agentNotifications"
          :key="key"
          :color="item.status === 'checked' ? 'grey lighten-1' : 'red lighten-2' "
          small
          right>
          <div 
            class="time-line-content" 
            :style="$vuetify.breakpoint.xs ? 'flex-direction: column; width: 88%; display: inline-block; float: left;' : 'flex-direction: row;'">
            <div style="font-size: 1rem; color: black; display: inline-block;">
              {{ item.title}}&nbsp;
            </div>
            <div style="display: inline-block; min-width: 150px;">
              <span style="font-size: 0.85rem; color: #808080">{{ $formatDate(item.updated_at) + ' ' + item.updated_at.substr(11, 8) }}</span>
              <div style="display: inline-block; position: relative; min-width: 20px;">
                <span class="notification-delete-btn">
                  <v-btn v-show="!$vuetify.breakpoint.xs" small :loading="btnLoading && item.id === itemId" @click="deleteNotification(item.id)" icon>
                    <v-icon small color="grey">mdi-close</v-icon>
                  </v-btn>
                </span>
              </div>
            </div>
          </div>
          <div v-show="$vuetify.breakpoint.xs" style="display: inline-block; width: 7%; float: right">
            <v-btn small :loading="btnLoading && item.id === itemId" @click="deleteNotification(item.id)" icon>
              <v-icon small color="grey">mdi-close</v-icon>
            </v-btn>
          </div>
        </v-timeline-item>
      </v-timeline>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'
export default {
  data: () => ({
    itemId: null,
    loading: false,
    btnLoading: false,
    currentTab: '1',
    notifications: [],
    tableHeader: [
      { text: 'Дата', value: 'created_at', align: 'start'},
      { text: 'Контент', value: 'title', align: 'center'},
      { text: 'Видалити', value: 'actions', align: 'center' },
    ],
  }),
  methods: {
    deleteNotification(id) {
      this.itemId = id
      this.btnLoading = true
      axios
        .delete(`/agent/notifications/delete/${id}`)
        .then(() => {
          this.getAgentNotifications()
          this.$notify({
            group: 'success',
            title: 'Повiдомлення видалено',
            text: '',
          })
          this.btnLoading = false
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
          this.getAgentNotifications()
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
          this.btnLoading = false
        })
    },
    changeNotificationsStatus(object) {
      axios
        .post(`/agent/notifications/checking`, object)
        .then(() => {
          /* */
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
        })
    },
    changeActive(event) {
      let tabs = document.querySelectorAll('#section .notification-tabs-input')
      tabs.forEach(element => element.classList.remove('active'))
      event.target.parentNode.classList.add('active')
    },
    getAgentNotifications() {
      axios
        .get(`/agent/notifications/${this.$store.state.user.agent.id}`)
        .then(response => {
          this.$store.commit('addNotifications', response.data)

          let notificationsArrIds = this.notifications.map(val => {
            return val.id
          })

          this.changeNotificationsStatus({notifications :notificationsArrIds})
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
        })
    },
  },
  computed: {
    agentNotifications() {
      return this.$sortByDate(this.$store.state.notifications)
    }
  },
  mounted() {
    this.$store.commit('addNotifications', this.$route.params.notifications) 
    if(this.$store.state.notifications.length === 0) {
      this.getAgentNotifications()
    } else {
      let notificationsArrIds = this.agentNotifications.map(val => {
        return val.id
      })
      this.changeNotificationsStatus({notifications :notificationsArrIds})
    }
  }
}
</script>
<style lang="scss">
  .user-notification-page {
    .v-timeline-item {
      justify-content: flex-end;
    }
    .v-timeline-item__divider {
      min-width: 56px;
    }
    .v-timeline-item__dot {
      position: absolute!important;
      left: 14px!important;
    }
    .time-line-content {
      display: flex;
      justify-content: space-between;
    }
    .v-timeline-item__inner-dot {
      height: 13px!important;
      margin: 2px!important;
      width: 13px!important;
    }
    .v-timeline-item__dot--small {
      height: 17px;
      left: calc(50% - 12px);
      width: 17px;
    }
    .v-timeline-item__dot {
      left: 17px !important;
    }
    .v-timeline {
      &:before {
        display: none;
      }
    }
  }
  .notification-delete-btn {
    white-space: nowrap;
    top: -18px;
    position: absolute;
    opacity: 1;
    transition: 0.3s ease;
  }
</style>
<style scoped lang="scss">
  .notification-tabs {
    border-bottom: 2px solid #d85151;
  }
  .notification-tabs .notification-tabs-input {
    opacity: 1;
    input {
      display: none;
    }
    .content {
      position: absolute;
      left: 100%;
      opacity: 0;
      transition: left 0.5s, opacity 0.2s;
    }
    &.active {
      .content {
        left: 0;
        opacity: 1;
      }
    }
  }
  .notification-tabs .notification-tabs-input label {
    transition: background 0.25s ease-in, color 0.25s ease-in;
    font-size: 1rem; 
    margin-bottom: auto; 
    padding: 6px 10px;
  }
  .notification-tabs .notification-tabs-input.active label {
    background: #d85151!important;
    color: white!important;
  }
</style>