<template>
  <div class="wrapper">
    <side-bar>
      <template slot="links">
        <sidebar-link to="/dashboard" name="Головна" icon="homeSvg"/>
        <sidebar-link to="/calculator" name="Калькулятор лізингу" icon="calculatorSvg"/>
        <sidebar-link to="/leasing-requests" name="Заявки на лiзинг" icon="leasingRequestSvg"/>
        <sidebar-link v-if="showMenuCommision" to="/commission-requests" name="Заявки на винагороду" icon="rewardRequests"/>
        <sidebar-link to="/useful-materials-categories" name="Кориснi матерiали" icon="usefulMaterialsSvg"/>
        <sidebar-link to="/reporting" params name="Звiтнiсть" icon="fileChart"/>
      </template>
    </side-bar>
    <v-navigation-drawer
      style="z-index: 3000; background: repeating-linear-gradient(60deg, #e2746f, #eb443d 35%, #f31d13 50%);"
      v-show="$vuetify.breakpoint.smAndDown"
      v-model="drawer"
      app>
      <div class="mobile-sidebar__logo-wrapper">
        <div class="mobile__header-logo"></div>
      </div>
      <div style="padding: 10px 15px; position: relative;">
        <v-btn style="position: absolute; right: 5px;" dark small icon @click="drawer = !drawer">
          <v-icon v-text="'mdi-close'"></v-icon>
        </v-btn>
        <div
          v-for="(item, key) in showLinks"
          :key="key"
          class="mobile-nav-block"
          style="padding: 10px 0; display: flex;">
          <router-link
            class="mobile-nav-link"
            :to="item.path">
            <span style="display: inline-block; min-width: 40px;">
              <component class="mobile-nav-icon" :is="item.icon"></component>
            </span>{{ item.text.toUpperCase() }}
          </router-link>
        </div>
      </div>
    </v-navigation-drawer>

    <div class="main-panel" :style="$vuetify.breakpoint.smAndDown ? 'width: 100%' : ''" id="main-panel">
      <top-navbar @listenDrawer="drawer = $event" :drawer="drawerState"></top-navbar>
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

import 'es6-promise/auto'

import homeSvg from '../../assets/svg-icons/home'
import calculatorSvg from '../../assets/svg-icons/calculator'
import leasingRequestSvg from '../../assets/svg-icons/leasing-requests'
import rewardRequestsSvg from '../../assets/svg-icons/rewardRequests'
import usefulMaterialsSvg from '../../assets/svg-icons/useful-materials'
import ChartSvg from '../../assets/svg-icons/chart'


export default {
  components: {
    TopNavbar,
    ContentFooter,
    DashboardContent,
    MobileMenu,
    homeSvg,
    calculatorSvg,
    leasingRequestSvg,
    rewardRequestsSvg,
    usefulMaterialsSvg,
    ChartSvg
  },
  data: () => ({
    drawer: false,
    drawerState: null,
    mobileLinks: [
      {path: "/dashboard", text: "Головна", icon: "homeSvg", iconPresent: true},
      {path: "/calculator", text: "Калькулятор лізингу", icon: "calculatorSvg", iconPresent: true},
      {path: "/leasing-requests", text: "Заявки на лiзинг", icon: "leasingRequestSvg", iconPresent: true},
      {path: "/commission-requests", text: "Заявки на винагороду", icon: "rewardRequestsSvg", iconPresent: true},
      {path: "/useful-materials-categories", text: "Кориснi матерiали", icon: "usefulMaterialsSvg", iconPresent: true},
      {path: "/reporting", text: "Звiтнiсть", icon: 'ChartSvg'}
    ],
  }),
  methods: {
    toggleSidebar() {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false);
      }
    },
  },
  computed: {
    showMenuCommision() {
      return this.$store?.state?.user?.agent?.show_menu_commission
    },
    showLinks() {
      return this.mobileLinks.filter(v => {
        return v.text !== "Заявки на винагороду" || v.text === "Заявки на винагороду" && this.showMenuCommision
      })
    }
  },
  watch: {
    drawer(val) {
      this.drawerState = val
    },
  },
}
</script>

<style lang="scss">
  .mobile-sidebar__logo-wrapper {
    background: #f4f3ef; 
    width: 100%;
    z-index: 1; 
    display: flex;
    align-items: center;
    height: 64px;
  }
  .mobile__header-logo {
    transition: background 0.3s width 0.3s height 0.3s;
    margin: 0 0 0 20px;
    background: url('../../../../assets/images/best-leasing-logo.png');
    background-size: 143px 40px;
    width: 143px;
    height: 40px;
    z-index: 3;
  }
  .mobile-nav-block {
    .mobile-nav-link {
      font-family: 'Montserrat', sans-serif; 
      color: black; 
      font-size: 0.8rem;
      transition: color 0.2s ease-in;
      &:hover {
        color: #424242;
      }
      &.active {
        color: white;
      }
      .mobile-nav-icon {
        color: black;
        width: 18px; 
        height: 18px;
      }
    }
  }
</style>