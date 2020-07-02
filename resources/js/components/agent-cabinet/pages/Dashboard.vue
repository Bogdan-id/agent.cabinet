<template>
<div ref="dashboard">
<div class="dashboard-wrapper">
  <!-- Mobyle agent info -->
  <div v-if="!hasUserManager && !loading && requestRecieved" class="mobile-agent-not-manager">
    <div class="pa-3 d-flex headline align-center">
      <div style="height: 100%; width: auto; display: flex;">
        <v-icon v-text="'mdi-information'" class="pr-3" size="50" color="red lighten-1"></v-icon>
      </div>
      <div style="display: flex; align-items: center;">
        <b>За Вами не закрiплений жоден з менеджерів!</b>
      </div>
    </div>
  </div>
  <div v-if="!loading && hasUserManager && requestRecieved" :class="hasAgent ? 'mobile-agent-info active' : 'mobile-agent-info'" style="position: relative">
    <span class="mobile-manager-title">
      {{ 'Ваш менеджер' }}
    </span>
    <div v-if="loading" class="d-flex justify-center align-center">
      <v-progress-circular
        class="ma-3"
        indeterminate
        color="red">
      </v-progress-circular>
    </div>
    <div style="width: 100%; min-height: 65px;">
      <div v-if="hasUserManager && !loading && requestRecieved" class="mobile-manager-content">
        <div style="display: inline-block; width: 70px; position: relaitve;">
          <div v-if="agentData.photo == null" class="empty-logo-mobile"><span class="logo-letter">{{ agentData.name }}</span></div>
          <div style="display: inline-flex; justify-content: center; align-items: center; position: absolute; top: 3px; left: 4px; width: 60px; height: 60px;">
            <img style="max-width: 100%; border-radius: 100%;" :src="agentData.photo" />
          </div>
        </div>
        <div class="manager-data-wrapper">
          <span style="font-size: 0.8rem; font-weight: bold; white-space: nowrap; padding-right: 0.8rem;">{{ agentData.name }}</span>
          <span style="font-weight: bold; white-space: nowrap; font-size: 0.76rem; display: inline-block; padding-right: 0.8rem;"><v-icon color="black" size="19" class="pr-1" v-text="'mdi-phone'"></v-icon>{{ agentData.phone }}</span>
          <span style="color: #bb433c; white-space: nowrap; display: inline-block;"><v-icon color="black" size="19" class="pr-1" v-text="'mdi-email'"></v-icon>{{ agentData.email }}</span>
        </div>
      </div>
    </div>
  </div>
  <!-- Dashboard container -->
  <div class="dashboard-container">
    <v-card elevation="9" height="400">
      <div v-if="!carouselVisibility" style="position: relative; width: 100%; height: 100%;">
        <v-skeleton-loader
          style="position: absolute; top:0; bottom: 0; right: 0; left: 0;"
          height="800"
          min-height="800"
          type="image">
        </v-skeleton-loader>
      </div>
      <v-carousel
        cycle
        height="400"
        name="dashboard-carousel"
        :class="`${carouselVisibility ? 'dashboard-carousel active' : 'dashboard-carousel'}`"
        id="dashboard-carousel"
        hide-delimiter-background
        :interval="7000"
        show-arrows-on-hover>
        <v-carousel-item
          v-for="(item, i) in items"
          @load="imageLoaded()"
          :src="item.image"
          :key="i"
          reverse-transition="fade-transition"
          transition="fade-transition">
          <div>
            <div style="height: 120px;">
              <div :class="xs ? 'actions-block-text small-screen' : 'actions-block-text'">
                <h3><b>{{ item.title}}</b></h3>
                <p style="font-size: 0.88rem"> {{ item.text }} </p>
              </div>
            </div>
            <div style="height: 240px; display: flex; width: 100%; justify-content: flex-end; align-items: flex-end;">
              <span style="display: inline-block; margin-right: 40px; margin-bottom: 15px">
                <v-btn class="vuetify_custom-btn white--text ml-1 mt-7" @click="">Ознайомитись</v-btn>
              </span>
            </div>
          </div>
        </v-carousel-item>
      </v-carousel>
    </v-card>
    <v-card 
      v-if="tabledata.length > 0"
      class="mt-10 mb-6 dashboard-table" elevation="9">
      <v-card-title class="headline pb-3 pt-3 mb-7" style="border-left: 5px solid #e75d57;">
        Заявки на лiзинг
      </v-card-title>
      <v-data-table
        color="black"
        :headers="tableHeader"
        :items="tabledata"
        class="elevation-1 pb-3"
        :hide-default-footer="true"
        :items-per-page="5">
        <template v-slot:item.leasing_amount="{ item }">
          <span style="white-space: nowrap">
            {{ 
              parseInt(item.leasing_amount.replace(/ /g, '' ))
                .toLocaleString()
                .replace(/,/g, ' ')
            }}
          </span>
        </template>
        <template v-slot:item.data="{item}">
          <span style="white-space: nowrap">
            {{ item.data }}
          </span>
        </template>
        <template v-slot:item.leasing_object="{ item }">
          <span style="white-space: nowrap">
            {{ item.leasing_object }}
          </span>
        </template>
        <template v-slot:item.initials="{ item }">
          <span style="white-space: nowrap">
            {{ item.initials }}
          </span>
        </template>
      </v-data-table>
      <div class="d-flex justify-center">
        <span>
          <v-btn
            class="vuetify_custom-btn ma-4"
            style="text-transform: none;"
            to="/leasing-requests"
            color="#e65048"
            dark>
            Показати всі заявки
          </v-btn>
        </span>
      </div>
    </v-card>
  </div>
  <div class="right-block-wrapper">
    <!-- Agent info -->
      <v-card :class="hasUserManager ? 'agent-info agent-info-active' : 'agent-info'" elevation="8">
        <div class="mt-4 mb-2 pl-4 mb-1 manager-title" style="position: relative; font-size: 0.95rem;">
          {{ !requestRecieved ? '' : hasUserManager && !loading ? 'Ваш менеджер' : 'За Вами не закрiплений жоден з менеджерів!'}}
        </div>
        <div v-if="loading" class="d-flex justify-center align-center">
          <v-progress-circular
            class="ma-3"
            indeterminate
            color="red">
          </v-progress-circular>
        </div>
        <div v-if="hasUserManager && !loading && requestRecieved" class="manager-content d-flex flex-column">
          <div style="display: flex; justify-content: center;">
            <div v-if="agentData.photo == null" style="display: flex; align-text: center; justify-content: center; width: 74px; height: 74px; border-radius: 100%; background-color: #dadada;">
              <span class="logo-letter">{{ agentData.name }}</span>
            </div>
            <div v-if="agentData.photo != null" style="display: flex; align-text: center; justify-content: center; width: 74px; height: 74px; border-radius: 100%; background-color: #dadada;">
              <img style="max-width: 100%; border-radius: 100%;" :src="agentData.photo" />
            </div>
          </div>
          <div class="manager-list-wrapper">
            <ul>
              <li style="font-size: 1.06rem; margin-bottom: 0.2rem;">{{ agentData.name }}</li>
              <li style="font-weight: bold; font-size: 0.76rem"><v-icon color="black" size="19" class="pr-1" v-text="'mdi-phone'"></v-icon>{{ agentData.phone }}</li>
              <li style="color: #bb433c; "><v-icon color="black" size="19" class="pr-1" v-text="'mdi-email'"></v-icon>{{ agentData.email }}</li>
            </ul>
          </div>
        </div>
      </v-card>
      <v-card class="dashboard__rigth-block" elevation="8">
        <v-card-title class="title pl-4 pb-2 pt-2" style="border-left: 4px solid #e75d57">
          Новини
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-hover v-slot:default="{ hover }">
            <v-card :elevation="hover ? 5 : 2" class="news">
              <v-img
                class="white--text align-end"
                style="width: 100%"
                :src="require('../assets/img/car1.jpg')">
                <v-card-title class="subtitle-1">Тайтл для новини</v-card-title>
              </v-img>
              <v-card-text>
                <span>Neque porro quisquam est qui dolorem ipsum</span>
                <div style="text-align: right">
                  <span>
                    <v-btn :small="mdAndDown" class="vuetify_custom-btn --small capitalize mt-2" text>
                      Детально
                    </v-btn>
                  </span>
                </div>
              </v-card-text>
            </v-card>
          </v-hover>
          <v-hover v-slot:default="{ hover }">
            <v-card :elevation="hover ? 5 : 2" class="news">
              <v-img
                class="white--text align-end"
                style="width: 100%"
                :src="require('../assets/img/car2.jpg')">
                <v-card-title class="subtitle-1">Тайтл для новини</v-card-title>
              </v-img>
              <v-card-text>
                <span>Neque porro quisquam est qui dolorem...</span>
                <div style="text-align: right">
                  <span>
                    <v-btn :small="mdAndDown" class="vuetify_custom-btn --small capitalize mt-2" text>
                      Детально
                    </v-btn>
                  </span>
                </div>
              </v-card-text>
            </v-card>
          </v-hover>
        </v-card-text>
      </v-card>
    </div>
  </div>
  <v-card class="dashboard-news">
    <v-card-title class="headline red--text">
      Новини
      <!-- <v-btn @click="test()"></v-btn> -->
    </v-card-title>
    <v-divider></v-divider>
    <v-card-text class="dashboard-news__wrapper">
      <v-hover v-slot:default="{ hover }">
        <v-card class="news-card" :elevation="hover ? 5 : 2">
          <v-img
            class="white--text align-end"
            style="width: 100%"
            :src="require('../assets/img/car1.jpg')">
            <v-card-title class="subtitle-1">Тайтл для новини</v-card-title>
          </v-img>
          <v-card-text>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="vuetify_custom-btn capitalize pl-4 pr-4">
              Детально
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-hover>
      <v-hover v-slot:default="{ hover }">
        <v-card class="news-card" :elevation="hover ? 5 : 2">
          <v-img
            class="white--text align-end"
            style="width: 100%"
            :src="require('../assets/img/car2.jpg')">
            <v-card-title class="subtitle-1">Тайтл для новини</v-card-title>
          </v-img>
          <v-card-text>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="vuetify_custom-btn capitalize  pl-4 pr-4">
              Детально
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-hover>
    </v-card-text>
  </v-card>
</div>
</template>
<script>
import axios from 'axios'

export default {
  name: 'Головна',
  data: () => ({
    tableHeader: [
      { text: 'Клієнт', value: 'initials', align: 'start', sortable: false},
      { text: 'Предмет лiзингу', value: 'leasing_object', align: 'center', sortable: false},
      { text: 'Цiна, грн', value: 'leasing_amount', align: 'center', sortable: false },
      { text: 'АВ, %', value: 'agency_remuneration', align: 'center' },
      { text: 'Тип графiку', value: 'graph_type', align: 'center', sortable: false },
      { text: 'Дата подачi', value: 'data', align: 'center', sortable: false },
      { text: 'Статус заявки', value: 'request_status', align: 'center', sortable: false },
      // { text: 'Дiї', value: 'actions', align: 'center', sortable: false },
    ],
    carouselVisibility: false,
    items: [
      {
        image: require('../assets/img/carousel-1.jpg'),
        title: 'Subaru в лiзинг',
        text: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.'
      },
      {
        image: require('../assets/img/carousel-2.jpg'),
        title: 'Another car в лiзинг',
        text: 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.'
      },
      {
        image: require('../assets/img/carousel-4.jpg'),
        title: 'Mercedes в лiзинг',
        text: 'Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. '
      },
    ],
    tabledata: [],
    requestRecieved: false,
  }),
  computed: {
    loading() {
      return this.$store.state.loader === true
    },
    hasUser() {
      return Object.keys(this.$store.state.user).length > 0
    },
    hasAgent() {
      return this.$store.state.agentData.id !== null
    },
    agentData() {
      return this.$store.state.agentData
    },
    hasUserManager() {
      if(!this.$store.state.user.agent) return false
      return this.$store.state.user.agent.manager_id !== null
    },
    mdAndDown() {
      return this.$vuetify.breakpoint.mdAndDown
    },
    xs() {
      return this.$vuetify.breakpoint.xs
    }
  },
  methods: {
    test() {
      console.log(this.$store.state.user.agent.manager_id)
    },
    imageLoaded() {
      console.log('onload event')
      setTimeout(() => {
        this.carouselVisibility = true
      }, 300)
    },
    getUserCalculcations() {
      this.$store.commit('toggleSpinner', true)
      this.tabledata = []
      const agentId = this.$store.state.user.agent.id
      axios
        .get(`/leasing-reqeust/agent/${agentId}`)
        .then(response => {
          this.$store.commit('toggleSpinner', false)
          if(response.data.length > 0)  {
            this.createTableData(response.data)
            this.$store.commit('addGraph', response.data)
          } else {
            this.tabledata = []
          }
        })
        .catch(error => {
          this.$store.commit('toggleSpinner', false)
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
        })
    },
    switchValue(val) {
      switch(val) {
        case 'even': return 'Класичний'
        case 'annuity': return 'Ануїтет'
        case 'irregular': return 'Індивідуальний'
      }
    },
    sortData(a, b) {
      return new Date(b.created_at) - new Date(a.created_at)
    },
    async createTableData(object) {
      let arr = []
      await object
        .map(val => {
          let dataObj = {
            'initials': `${val.last_name} ${val.first_name} ${val.patronymic}`,
            'leasing_object': val.leasing_object,
            'leasing_amount': val.leasing_amount,
            'graph_type': this.switchValue(val.graph_type),
            'data': val.created_at.substr(0, 10),
            'request_status': val.status_id,
            'agency_remuneration': this.$store.state.user.agent.ab_size,
            'id': val.id
          }
          arr.push(dataObj)
        })
      this.tabledata = arr
        .sort(this.sortData)
        .reverse()
    },
  },
  watch: {
    hasUser() {
      this.getUserCalculcations()
    },
    loading(val) {
      if(val === false){
        setTimeout(() => {
          this.requestRecieved = true
        }, 900)
      }
    }
  },
  created() {
    if(this.hasUser) {
      this.getUserCalculcations()
    }
  },
  mounted() {
    setTimeout(() => {
      console.log(this.agentData)
    }, 2000)
  }
}
</script>

<style lang="scss">
.theme--light.v-skeleton-loader .v-skeleton-loader__avatar, 
.theme--light.v-skeleton-loader .v-skeleton-loader__button, 
.theme--light.v-skeleton-loader .v-skeleton-loader__chip, 
.theme--light.v-skeleton-loader .v-skeleton-loader__divider, 
.theme--light.v-skeleton-loader .v-skeleton-loader__heading, 
.theme--light.v-skeleton-loader .v-skeleton-loader__image, 
.theme--light.v-skeleton-loader .v-skeleton-loader__text {
    background: white!important;
}
.v-skeleton-loader__image {
  height: 400px!important;
}
.theme--light.v-skeleton-loader .v-skeleton-loader__bone:after {
  background: linear-gradient(90deg,transparent,hsla(187, 0%, 81%, 0.82),transparent)!important;
}

.dashboard-carousel {
  visibility: hidden;
  transition: opacity 1.5s;
  opacity: 0;
  &.active {
    visibility: visible;
    opacity: 1;
  }
}
.manager-list-wrapper {
  display: inline-block; 
  width: 100%; 
  padding: 0 25px;
  margin-top: 15px;
  ul {
    padding-left: 0!important;
    list-style: none!important;
    li {padding-bottom: 0.28rem}
    li{
      &:hover {
        cursor: pointer;
      }
    }
  }
}
.logo-letter {
  font-size: 0;
}
.logo-letter:first-letter {
  font-size: 1.4rem;
  font-weight: bold;
}
.mobile-manager-content {
  padding: 1.6rem 1rem 0.3rem 1rem; 
  display: flex;
  flex-direction: row;
  span, div:hover {
    cursor: pointer;
  }
  .manager-data-wrapper {
    display: inline-flex; 
    flex-wrap: wrap; 
    justify-content: space-around; 
    width: 90%; 
    padding-top: 10; 
    align-items: center;
  }
  .empty-logo {
    margin-right: 0.5rem; 
    display: inline-flex; 
    justify-content: center; 
    align-items: center; 
    width: 40px; 
    height: 40px; 
    border-radius: 100%; 
    background-color: #dadada;
  }
  .empty-logo-mobile {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 3px;
    left: 4px;
    width: 60px;
    height: 60px;
    border-radius: 100%;
    background-color: #dadada;
  }
}

.manager-title {
  border-left: 4px solid #e75d57; 
  border-radius: 0!important; 
  font-weight: bold; 
  display: flex; 
  align-items: center; 
  height: 2.2rem; 
  line-height: 1rem;
}

.dashboard-wrapper {
  display: flex; 
  align-items: flex-start; 
  flex-direction: row; 
  justify-content: space-between;
  transition: all 0.3s ease;
}
.dashboard__custom-btn {
  border-radius: 0!important;
}
.right-block-wrapper {
  width: 24%; 
  display: flex; 
  flex-direction: column;
}
.dashboard-news {
  display: none!important;
  width: 100%!important; 
  margin-top: 35px; 
  box-shadow: 0px 1px 23px 0px #c2c0c0!important;
  .dashboard-news__wrapper {
    flex-direction: row; 
    display: flex; 
    flex-wrap: wrap; 
    justify-content: space-around;
    .news-card {
      width: 320px!important; 
      margin: 0 25px 15px 0
    }
  }
}
.mobile-agent-not-manager {
  border-radius: 4px; 
  margin-bottom: 12px;
  width: 100%;
  overflow: hidden;
  display: none;
  border: 2px solid #ef5350;
  transition: max-width 0.5s ease-in;
}
.mobile-agent-info {
  position: relative;
  border-radius: 4px; 
  // border-left: 3px solid #e75d57;
  margin-bottom: 12px; 
  overflow: hidden;
  transition: max-width 0.5s ease-in;
  width: 100%;
  
  .mobile-manager-title {
    top: 0.8rem; 
    font-size: 1rem;
    position: absolute; 
    left: 50%; transform: 
    translate(-50%, -50%); 
    background: #e75d57; 
    padding: 1px 10px; 
    border-radius: 0 0 5px 5px; 
    color: white;
  }
  ul {
    padding: 15px;
    list-style: none;
    margin-bottom: 0;
  }
  .agent-list-info {
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.2s ease-in 0.3s;
    &.agent-list-active {
      visibility: visible;
      opacity: 1;
    }
  }
  // &.active {
  //   max-width: 100%;
  //   width: 100%;
  // }
  .icon-wrapper {
    align-items: stretch;
    background: #ef5350;
    padding: 15px;
  }
  // padding: 15px; 
  display: none;
  box-shadow: 0 3px 1px -2px rgba(0,0,0,.2), 
    0 2px 2px 0 rgba(0,0,0,.14), 
    0 1px 5px 0 rgba(0,0,0,.12);
  
  .agent-desc {
    font-size: 12px;
    padding-right: 4px;
  }
  .agent-data {
    transition: all 0.3s ease;
    &:hover {
      text-decoration: underline;
      cursor: pointer;
    }
  }
}
.agent-info {
  display: flex;
  align-items: center;
  flex-direction: column;
  max-height: 160px;
  width: 100%!important;
  transition: max-height 1s ease!important;
  .agent-info-list {
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease-in 0.3s;
    &.agent-info-list-active {
      visibility: visible;
      opacity: 1;
    }
  }
  &.agent-info-active {
    max-height: 650px;
  }
}
.dashboard__rigth-block {
  margin-top: 15px;
  display: block;
  .v-divider {
    margin-bottom: 0!important;
  }
  .news {
    margin-bottom: 20px;
  }
}
.vuetify_custom-btn {
  &.capitalize {
    text-transform: capitalize!important;
  }
  &.--small {
    height: 28px!important;
  }
  border-radius: 0!important;
  background: #e65048!important;
  color: white!important;
}
.dashboard-container{
  flex-direction: column;
  display: flex;
  width: 74%;
  .v-window {
  position: relative!important;
  .v-window-item  {
    transition: opacity .45s ease-in!important;
    position: absolute!important;
    top: 0!important;
    left: 0!important;
    right: 0!important;
    bottom: 0!important;
    &.fade-transition-leave, .fade-transition-leave-active {
      opacity: 0!important;
    }
    &.fade-transition-enter, .fade-transition-enter-active {
      opacity: 0!important;
    }
  }
}
  .actions-block-text {
    backdrop-filter: blur(5px); 
    padding: 0 15px; 
    border-radius: 3px;
    background-color: rgba(252, 252, 252, 0.7); 
    max-width: 48%;
    margin: 25px 0 5px 25px; 
    display: inline-block;
    &.small-screen {
      max-width: 90%;
    }
  }
  .main-card {
    width: 100%;
    min-height: 400px;
    .main-card-banner {
      background-image: url('../assets/img/specoffer_detail_bg.jpg');
      background-repeat: no-repeat;
      background-position: 50%;
      background-size: cover;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 30px;
      min-height: 500px;
      .content-text {
        backdrop-filter: blur(5px);
        padding: 40px;
        background-color: rgba(0,46,103,.7);
        .content-paragraph {
          font-size: 19px;
        }
      }
    }
  }
}
</style>
