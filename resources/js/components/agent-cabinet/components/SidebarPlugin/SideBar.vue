<template>
  <div class="sidebar"
       :data-background-color="backgroundColor"
       :data-active-color="'black'">
      <!-- Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black | darkblue" -->
      <!-- Tip 2: you can change the color of the active button using the data-active-color="primary | (now also black) | info | success | warning | danger" -->
    <!-- below id="style-3" -->
    <div class="sidebar-wrapper"> 
      <div class="vue-paper-sidebar__logo-wrapper">
        <div class="sidebar__header-logo">
        </div>
      </div>
      <slot>

      </slot>
      <ul class="nav">
        <!--By default vue-router adds an active class to each route link. This way the links are colored when clicked-->
        <slot name="links">
          <sidebar-link 
            v-for="(link, index) in sidebarLinks"
            :key="index"
            :to="link.path"
            :name="link.name"
            :icon="link.icon">
          </sidebar-link>
        </slot>
      </ul>
      <moving-arrow :move-y="arrowMovePx"></moving-arrow>
    </div>
  </div>
</template>
<script>
import MovingArrow from "./MovingArrow.vue"
import SidebarLink from "./SidebarLink"


export default {
  data:() => ({
      linkHeight: 30.8,
      activeLinkIndex: 0,
      windowWidth: 0,
      isWindows: false,
      hasAutoHeight: false,
      links: [],
  }),
  props: {
    headerLogo: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: "Paper Dashboard"
    },
    backgroundColor: {
      type: String,
      default: "black",
      validator: value => {
        let acceptedValues = ["white", "black", "darkblue"];
        return acceptedValues.indexOf(value) !== -1;
      }
    },
    activeColor: {
      type: String,
      default: "success",
      validator: value => {
        let acceptedValues = [
          "primary",
          "info",
          "black",
          "success",
          "warning",
          "danger"
        ];
        return acceptedValues.indexOf(value) !== -1;
      }
    },
    sidebarLinks: {
      type: Array,
      default: () => []
    },
    autoClose: {
      type: Boolean,
      default: true
    }
  },
  // parent component give access for all children component to object below
  provide() {
    return {
      autoClose: this.autoClose,
      addLink: this.addLink,
      removeLink: this.removeLink
    };
  },
  components: {
    MovingArrow,
    SidebarLink,
  },
  computed: {
    /**
     * Styles to animate the arrow near the current active sidebar link
     * @returns {{transform: string}}
     */
    arrowMovePx() {
      return this.linkHeight * this.activeLinkIndex;
    }
  },
  methods: {
    findActiveLink() {
      this.links.forEach((link, index) => {
        if (link.isActive()) {
          this.activeLinkIndex = index
        }
      })
    },
    addLink(link) {
      console.log(this.$slots)
      const index = this.$slots.links.indexOf(link.$vnode)
      this.links.splice(index, 0, link)
    },
    removeLink(link) {
      const index = this.links.indexOf(link)
      if (index > -1) {
        this.links.splice(index, 1)
      }
    }
  },
  mounted() {
    this.$watch("$route", this.findActiveLink, {
      immediate: true
    })
  }
};
</script>
<style lang="scss">
  /* sidebar-wrapper nav items */
  .sidebar-wrapper {
    padding-bottom: 22px;
    .nav-item-title {
      opacity: 1;
      transition: opacity 0.3s;
      white-space: nowrap;
      position: absolute;
      padding-left: 42px;
    }
    .sidebar__header-logo {
      transition: all 0.3s;
      margin: 0 0 0 25px;
      background: url('../../../../assets/images/best-leasing-logo.png');
      background-size: 160px 45px;
      width: 160px;
      height: 45px;
      z-index: 3;
    }
    .vue-paper-sidebar__logo-wrapper {
      background: #f4f3ef; 
      width: 100%;
      z-index: 1; 
      padding: 35px 0 32px 0; 
    }
  }

  /* toggle icon */
  .main-panel {
    .--toggle-icon-active {
      transform: rotate(180deg)
    }
    .sidebar__toggle-icon {
      transition: transform 0.25s ease;
    }
    .sidebar__toggle-icon:hover {
      cursor: pointer;
    }
  }
</style>
