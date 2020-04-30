<template>
<div>
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
    <v-card class="agent-info">
        <div style="width: 100%; display: flex; background: #ef5350; color: white; flex-direction: column; align-items: center;">
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
      </v-card-text>
    </v-card>
  </div>
</div>
</template>
<script>
import axios from 'axios'

export default {
  data: () => ({
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
    }
  },
  methods: {
    getManager() {
      let agent_id = this.$store.state.user.agent.id
      this.$store.commit('toggleSpinner', true)
      axios
        .get(`/agent/manager/${agent_id}`)
        .then(response => {
          this.$store.commit('toggleSpinner', false)
          Object.assign(this.agentData, response.data)
          console.log(response)
        })
        .catch(error => {
          this.$store.commit('toggleSpinner', false)
          console.log(error.response)
        })
    }
  },
  watch: {
    hasUser() {
      console.log(this.$store.state.user)
      this.getManager()
    }
  },
  mounted() {
    if(this.hasUser) {
      console.log(this.$store.state.user)
      this.getManager()
    }
  }
}
</script>

<style lang="scss">
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
.dashboard-container{
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  .agent-info {
    width: 24%;
    background: #f4f3ef!important;
    display: flex;
    align-items: center;
    flex-direction: column;
  }
  .main-card {
    width: 74%;
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
