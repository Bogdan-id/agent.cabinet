<template>
  <!-- @click addRouteToLocalStorage() -->
  <component 
    :is="tag"
    @click.native="hideSidebar"
    class="nav-item"
    v-bind="$attrs"
    tag="li">
    <a v-if="!$vuetify.breakpoint.smAndDown" class="nav-link">
      <slot>
        <component :is="icon"></component>
        <p class="nav-item-title">
          {{name}}
        </p>
      </slot>
    </a>
    <v-tooltip v-if="$vuetify.breakpoint.smAndDown" right color="grey darken-4">
      <template v-slot:activator="{ on }">
        <a v-on="on" class="nav-link">
          <slot>
            <component :is="icon"></component>
            <p class="nav-item-title">
              {{name}}
            </p>
          </slot>
        </a>
      </template>
      <span>{{ name }}</span>
    </v-tooltip>
  </component>
</template>
<script>
import homeSvg from '../../assets/svg-icons/home.vue'
import calculatorSvg from '../../assets/svg-icons/calculator.vue'
import leasingRequestSvg from '../../assets/svg-icons/leasing-requests.vue'
import usefulMaterialsSvg from '../../assets/svg-icons/useful-materials.vue'
import rewardRequests from '../../assets/svg-icons/rewardRequests.vue'
import fileChart from "../../assets/svg-icons/charts.vue"

export default {
  components: {
    homeSvg,
    calculatorSvg,
    leasingRequestSvg,
    usefulMaterialsSvg,
    rewardRequests,
    fileChart
  },
  name: "sidebar-link",
  inheritAttrs: false,
  data: () => ({
    path: null,
  }),
  // children component receive object from parrent SideBar.vue
  inject: {
    autoClose: {
      default: true
    },
    addLink: {
      default: ()=>{}
    },
    removeLink: {
      default: ()=>{}
    }
  },
  props: {
    name: String,
    icon: String,
    tag: {
      type: String,
      default: "router-link"
    }
  },
  methods: {
    addLinkToSideBar() {
      if (this.addLink) {
        // console.log(this.addLink(this))
        this.addLink(this)
      }
    },
    // addRouteToLocalStorage() {
    //   const route = this.$router.currentRoute.name
    //   const routeStorage = window.localStorage
    //   routeStorage.setItem('route', route)
    // },
    readRootFromStorage() {
      const routeStorage = window.localStorage
      const route = routeStorage.getItem('route')
      if(route !== 'home' || route !== 'dashboard') {
        this.$router.push({ name: route})
        // eslint-disable-next-line
          .catch(err => {
            // catch needed because routs rewrites each other
          })
        routeStorage.removeItem('route')
      }
    },
    hideSidebar() {
      if (this.autoClose) {
        this.$sidebar.displaySidebar(false);
      }
    },
    isActive() {
      return this.$el.classList.contains("active");
    }
  },
  created() {
    this.readRootFromStorage()
    this.addLinkToSideBar()
  },
  mounted() {
    this.addLinkToSideBar()
  },
  beforeDestroy() {
    if (this.$el && this.$el.parentNode) {
      this.$el.parentNode.removeChild(this.$el)
    }
    this.addLinkToSideBar()
  }
};
</script>
<style>
.dashboard-link-tooltip {
  /* position: relative; */
  /* display: inline-block; */
  overflow: auto;
  z-index: 1000;
}

.dashboard-link-tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 300;
  /* bottom: 125%; */
  left: 50%;
  margin-left: 30px;
  opacity: 0;
  transition: opacity 0.3s;
}

.dashboard-link-tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.dashboard-link-tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>
