<template>
  <div class="wrapper">
    <agreement @closeDialog="showAgreement = false" 
      :showAgreement="showAgreement">
    </agreement>
    <side-bar>
      <template slot="links">
        <sidebar-link to="/dashboard" name="Головна" icon="ti-panel"/>
        <sidebar-link to="/calculator" name="Калькулятор лізингу" icon="ti-agenda"/>
        <sidebar-link to="/leasing-requests" name="Заявки на лiзинг" icon="ti-clipboard"/>
        <sidebar-link to="/reporting" name="Звітність" icon="ti-widget-alt"/>
        <sidebar-link to="/useful-materials" name="Корисні матеріали" icon="ti-bookmark-alt"/>
        <sidebar-link to="/agent-profile" name="профiль" icon="ti-user"/>
        <!-- <sidebar-link to="/maps" name="Map" icon="ti-map"/> -->
        <!-- <sidebar-link to="/notifications" name="Notifications" icon="ti-bell"/> -->
      </template>
      <mobile-menu>
        <li class="nav-item">
          <a class="nav-link">
            <i class="ti-panel"></i>
            <p>Stats</p>
          </a>
        </li>
        <drop-down class="nav-item"
                   title="5 Notifications"
                   title-classes="nav-link"
                   icon="ti-bell">
          <a class="dropdown-item">Notification 1</a>
          <a class="dropdown-item">Notification 2</a>
          <a class="dropdown-item">Notification 3</a>
          <a class="dropdown-item">Notification 4</a>
          <a class="dropdown-item">Another notification</a>
        </drop-down>
        <li class="nav-item">
          <a class="nav-link">
            <i class="ti-settings"></i>
            <p>Settings</p>
          </a>
        </li>
        <li class="divider"></li>
      </mobile-menu>
    </side-bar>
    <div class="main-panel">
      <!-- <v-btn @click="test()">test</v-btn> -->
      <top-navbar></top-navbar>
      <dashboard-content @click.native="toggleSidebar">
      </dashboard-content>
      <content-footer></content-footer>
    </div>
  </div>
</template>

<script>
import TopNavbar from "./TopNavbar.vue"
import ContentFooter from "./ContentFooter.vue"
import DashboardContent from "./Content.vue"
import MobileMenu from "./MobileMenu"
import Agreement from "../../pages/Agreement.vue"

import axios from "axios"
import 'es6-promise/auto'

export default {
  components: {
    TopNavbar,
    ContentFooter,
    DashboardContent,
    MobileMenu,
    Agreement
  },
  data: () => ({
    showAgreement: false
  }),
  methods: {
    // test() {
    //   this.showAgreement = true
    // },
    toggleSidebar() {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false);
      }
    },
    getCurrentUser() {
      this.$store.commit('toggleSpinner', true)
      axios.get('/getUserAgent')
      .then(response => {
        let user = response.data.user
        let {userData, ...userAgentdata} = response.data.agent
        this.$store.dispatch('add_user', Object.assign(user, userAgentdata, userData))
        this.$store.commit('toggleSpinner', false)
      })
      .catch(error => {
        console.log(error.response)
        this.$store.commit('toggleSpinner', false)
      })
    },
  },
  created() {
    this.getCurrentUser()
  }
}
</script>
