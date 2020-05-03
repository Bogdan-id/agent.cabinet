<template>
<div class="dashboard-wrapper">
  <!-- Mobyle agent info -->
  <div class="mobile-agent-info">
    <div class="icon-wrapper">
      <v-icon size="60" v-text="'mdi-account-circle'" dark></v-icon>
    </div>
    <ul>
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
            <p class="content-paragraph">Сформуйте заявку в режимі онлайн у декiлька простих шагiв
            </p>
          </div>
          <v-btn
            to="calculator/new"
            color="red darken-2" 
            class="main-card__button white--text ml-1" 
            large>
            Хочу легкий лiзинг
          </v-btn>
        </div>
      </div>
    </v-card>
    <v-card 
      v-if="tabledata.length > 0"
      class="mt-10" elevation="7">
      <v-card-title class="subtitle-1">
        Заявки на лiзинг
        <v-spacer></v-spacer>
        <v-btn
          to="/leasing-requests"
          color="red"
          dark text small>
          Показати всі заявки
        </v-btn>
      </v-card-title>
      <v-data-table
        color="black"
        :headers="tableHeader"
        :items="tabledata"
        class="elevation-1 pb-3"
        :hide-default-footer="true"
        :items-per-page="5">
      </v-data-table>
    </v-card>
    <!-- Agent info -->
  </div>
  <v-card class="agent-info">
    <div class="agent-info__header">
      <v-card-title class="subtitle-2 text-center d-inline-block">
        Ваш куратор
      </v-card-title>
        <v-icon size="70" v-text="'mdi-account-circle'" dark></v-icon>
    </div>
      <v-card-text style="padding: 0!important">
        <v-list two-line>
          <v-list-item>
            <v-list-item-content>
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <v-list-item-title v-on="on" class="body-2">{{ agentData.name }}</v-list-item-title>
                </template>
                <span>{{ agentData.name }}</span>
              </v-tooltip>
              <v-list-item-subtitle class="caption">ФИО</v-list-item-subtitle>
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
        <!-- <v-btn @click="test()">test</v-btn> -->
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
    agentData: {
      id: null,
      name: null,
      phone: null,
      email: null
    }
  }),
  computed: {
    hasUser() {
      return Object.keys(this.$store.state.user).length > 0
    },
  },
  methods: {
    test() {
      console.log(this.hasUser)
    },  
    getManager() {
      let agent_id = this.$store.state.user.agent.id
      this.$store.commit('toggleSpinner', true)
      axios
        .get(`/agent/manager/${agent_id}`)
        .then(response => {
          this.$store.commit('toggleSpinner', false)
          // console.log(response)
          Object.assign(this.agentData, response.data)
        })
        .catch(error => {
          this.$store.commit('toggleSpinner', false)
          console.log(error.response)
        })
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
      this.getManager()
      this.getUserCalculcations()
    },
  },
  async mounted() {
    if(this.hasUser) {
      this.getManager()
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
}
.mobile-agent-info {
  border-radius: 4px; 
  margin-bottom: 12px; 
  overflow: hidden;
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
  ul {
    padding: 15px 15px 15px 0;
    list-style: none;
    margin-bottom: 0;
  }
  .agent-desc {
    font-size: 12px;
    padding-right: 4px;
  }
  .agent-data {
    transition: all 0.3s ease;
  }
  .agent-data {
    &:hover {
      text-decoration: underline;
      cursor: pointer;
    }
  }
}
.agent-info {
    width: 24%;
    background: #f4f3ef!important;
    display: flex;
    align-items: center;
    flex-direction: column;
    .agent-info__header {
      width: 100%; 
      display: flex; 
      background: #ef5350; 
      color: white; 
      flex-direction: column; 
      align-items: center;
    }
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
      .content-wrapper {
        .main-card__button {
          margin-top: 50px;
        }
      }
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
