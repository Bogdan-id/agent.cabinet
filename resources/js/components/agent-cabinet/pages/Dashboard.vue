<template>
<div>
<div class="dashboard-wrapper">
  <!-- Mobyle agent info -->
  <div v-if="!hasUserManager && !loading" class="mobile-agent-not-manager">
    <div class="pa-3 d-flex headline align-center">
      <div style="height: 100%; width: auto; display: flex;">
        <v-icon v-text="'mdi-information'" class="pr-3" size="50" color="red lighten-1"></v-icon>
      </div>
      <div style="display: flex; align-items: center;">
        <b>За Вами не закрiплений жоден з менеджерів!</b>
      </div>
    </div>
  </div>
  <div v-if="!loading && hasUserManager" :class="hasAgent ? 'mobile-agent-info active' : 'mobile-agent-info'">
    <div class="icon-wrapper">
      <v-icon size="60" style="min-width: 60px" v-text="'mdi-account-circle'" dark></v-icon>
    </div>
    <ul :class="hasAgent ? 'agent-list-info agent-list-active' : 'agent-list-info'">
      <li>
        <span class="agent-desc">Ваш куратор:</span>
        <span class="agent-data">{{ agentData.name }}</span>
      </li>
      <li>
        <span class="agent-desc">Мобiльний:</span>
        <span class="agent-data">{{ agentData.phone }}</span>
      </li>
      <li>
        <span class="agent-desc">email:</span>
        <span class="agent-data">{{ agentData.email }}</span>
      </li>
    </ul>
  </div>
  <!-- Dashboard container -->
  <div class="dashboard-container">
    <v-card class="main-card">
      <div class="main-card-banner">
        <div class="content-wrapper">
          <div class="content-text white--text">
            <h3>*ЗАОЩАДЖУЙ ЧАС ТА ГРОЩI — ЛЕГКО ПЕРЕХОДЬ НА ЛIЗИНГ ОНЛАЙН!*</h3>
            <p class="content-paragraph">Сформуйте заявку в режимі онлайн у декiлька простих крокiв
            </p>
          </div>
          <v-btn
            to="calculator/new"
            color="red darken-2" 
            class="vuetify_custom-btn white--text ml-1 mt-7" 
            large>
            Хочу легкий лiзинг
          </v-btn>
        </div>
      </div>
    </v-card>
    <v-card 
      v-if="tabledata.length > 0"
      class="mt-10 mb-6" elevation="7">
      <v-card-title class="headline mb-7 mt-3">
        Заявки на лiзинг
      </v-card-title>
      <v-data-table
        color="black"
        :headers="tableHeader"
        :items="tabledata"
        class="elevation-1 pb-3"
        :hide-default-footer="true"
        :items-per-page="5">
      </v-data-table>
      <div class="d-flex justify-center">
        <span>
          <v-btn
            class="vuetify_custom-btn capitalize ma-4"
            to="/leasing-requests"
            color="red"
            dark>
            Показати всі заявки
          </v-btn>
        </span>
      </div>
    </v-card>
  </div>
  <div class="right-block-wrapper">
    <!-- Agent info -->
    <v-card :class="hasUserManager ? 'agent-info agent-info-active' : 'agent-info'">
      <div v-if="!loading && hasUserManager" class="agent-info__header">
        <v-icon class="pa-3" size="70" v-text="'mdi-account-circle'" dark></v-icon>
      </div>
      <div v-if="loading" class="d-flex justify-center align-center">
        <v-progress-circular
          class="ma-3"
          indeterminate
          color="red">
        </v-progress-circular>
      </div>
      <!--  -->
        <div v-if="!hasUserManager && !loading" class="pa-3 d-flex align-center">
          <div style="height: 100%; width: auto; display: flex;">
            <v-icon v-text="'mdi-information'" class="pr-3" size="60" color="red lighten-1"></v-icon>
          </div>
          <div style="display: flex; align-items: center;" class="title">
            <b>За Вами не закрiплений жоден з менеджерів!</b>
          </div>
        </div>
        <v-card-text v-show="hasUserManager" :class="hasAgent ? 'agent-info-list agent-info-list-active' : 'agent-info-list'"> 
          <v-list two-line class="text-center">
            <v-list-item>
              <v-list-item-content>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <v-list-item-title v-on="on" class="body-2">{{ agentData.name }}</v-list-item-title>
                  </template>
                  <span>{{ agentData.name }}</span>
                </v-tooltip>
                <v-list-item-subtitle class="caption">Ваш куратор</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <v-list-item-title v-on="on" class="body-2">{{ agentData.phone }}</v-list-item-title>
                  </template>
                  <span>{{ agentData.phone }}</span>
                </v-tooltip>
                <v-list-item-subtitle class="caption">Мобiльний</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <v-list-item-title v-on="on" class="body-2">{{ agentData.email }}</v-list-item-title>
                  </template>
                  <span>{{ agentData.email }}</span>
                </v-tooltip>
                <v-list-item-subtitle class="caption">email</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card-text>
      </v-card>
      <v-card class="dashboard__rigth-block">
        <v-card-title class="headline red--text">
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
              <v-card-text>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit</v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="vuetify_custom-btn --small capitalize">
                  Детально
                </v-btn>
              </v-card-actions>
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
              <v-card-text>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit</v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="vuetify_custom-btn --small capitalize">
                  Детально
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-hover>
        </v-card-text>
      </v-card>
    </div>
  </div>
  <v-card class="dashboard-news">
    <v-card-title class="headline red--text">
      Новини
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
  data: () => ({
    tableHeader: [
      { text: 'ФИО', value: 'initials', align: 'start', sortable: false},
      { text: 'Об\'єкт лiзингу', value: 'leasing_object', align: 'center', sortable: false},
      { text: 'Цiна', value: 'leasing_amount', align: 'center', sortable: false },
      { text: 'Тип графiку', value: 'graph_type', align: 'center', sortable: false },
      { text: 'Дата', value: 'data', align: 'center', sortable: false },
      { text: 'Статус заявки', value: 'request_status', align: 'center', sortable: false },
      // { text: 'Дiї', value: 'actions', align: 'center', sortable: false },
    ],
    tabledata: [],
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
    }
  },
  methods: {
    test() {
      console.log(this.hasUser)
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
  },
  created() {
    if(this.hasUser) {
      this.getUserCalculcations()
    }
  },
}
</script>

<style lang="scss">
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
  border-radius: 4px; 
  margin-bottom: 12px; 
  overflow: hidden;
  max-width: 140px;
  max-height: 86px;
  border: 2px solid #ef5350;
  transition: max-width 0.5s ease-in;
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
  &.active {
    max-width: 100%;
    width: 100%;
  }
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
  border: 1px solid #ef5350!important;
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
  .agent-info__header {
    width: 100%; 
    display: flex; 
    background: #ef5350; 
    color: white; 
    flex-direction: column; 
    align-items: center;
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
  background: #f44336!important;
  color: white!important;
}
.dashboard-container{
  flex-direction: column;
  display: flex;
  width: 74%;
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
