<template>
  <div class="sidebar"
       :data-background-color="backgroundColor"
       :data-active-color="activeColor">
    <!--
            Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black | darkblue"
            Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
        -->
    <!-- -->
    <!-- below id="style-3" -->
    <div class="sidebar-wrapper"> 
      <div class="vue-paper-sidebar__logo-wrapper">
        <div class="app__header-logo" style="margin: 0 0 0 25px;">
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
          this.activeLinkIndex = index;
        }
      });
    },
    addLink(link) {
      // console.log(this.$slots)
      const index = this.$slots.links.indexOf(link.$vnode);
      this.links.splice(index, 0, link);
    },
    removeLink(link) {
      const index = this.links.indexOf(link);
      if (index > -1) {
        this.links.splice(index, 1);
      }
    }
  },
  mounted() {
    this.$watch("$route", this.findActiveLink, {
      immediate: true
    });
  }
};
</script>
<style lang="scss">
  /* sidebar */
  :root {
    --sidebar-collapsed: 85px;
    --sidebar-expanded: 260px;
  }
  .sidebar {
    transition: all 0.5s!important;
    overflow-y: scroll!important;
    background: #f4f3ef;
  }
  ::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 5px;
  }
  ::-webkit-scrollbar-thumb {
      border-radius: 4px;
      background-color: rgba(0,0,0,.5);
      -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
  }
  .sidebar-wrapper {
    transition: all 0.5s !important;
    overflow-y: visible!important;
    background: #212120;
  }

  /* modifiers */
  .sidebar-wrapper--hide {
    transition: all 0.5s;
    width: var(--sidebar-collapsed)!important;
  }
  .sidebar--hide {
    transition: all 0.5s;
    width: var(--sidebar-collapsed)!important;
  }

  /* navigations */
  .nav {
    
  }
  .nav-link {
    margin: 4px 0px!important;
  }
  .nav-item {
    position: relative!important;
    padding: 8px 0 11px 0!important;
  }
  .nav-item-title {
    transition: all 1s!important;
    opacity: 1;
    transition: opacity 0.3s;
    white-space: nowrap;
    position: absolute;
    padding-left: 42px;
  }
  .nav-paragraph--hide {
    transition: all 0.3s;
    display: opacity 0.3s;
    opacity: 0;
  }
  .moving-arrow {
    transition: all 0.5s;
  }
  .moving-arrow--collapse {
    transition: all 0.5s;
    left: 73px!important;
  }

  /* main-panel */
  .main-panel {
    transition: all 0.5s!important;
    width: calc(100% - var(--sidebar-expanded))!important;

  }
  .main-panel--expand { 
    transition: all 0.5s!important;
    width: calc(100% - var(--sidebar-collapsed))!important;
  }

  .app__header-logo {
    transition: all 0.8s ease-out!important;
    opacity: 1!important;
  }
  .header-logo--hide {
    transition: all 0.4s!important;
    opacity: 0!important;
  }

  /* other styles */
  .vue-paper-sidebar__logo-wrapper {
    background: #f4f3ef; 
    z-index: 1; 
    padding: 45px 0 22px 0; 
  }
  .sidebar__toggle-icon {
    transition: all 0.5s ease-in-out;
  }
  .--toggle-icon-active {
    transition: all 0.5s ease-in-out;
    transform: rotate(540deg)
  }
  .sidebar__toggle-icon:hover {
    cursor: pointer;
  }
</style>
