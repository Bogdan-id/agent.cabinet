<template>
  <component :is="tag"
             @click.native="hideSidebar; getCurrentPath()"
             class="nav-item"
             v-bind="$attrs"
             tag="li">
    <a class="nav-link">
      <slot>
        <i v-if="icon" :class="icon"></i>
        <p>{{name}}</p>
      </slot>
    </a>
  </component>
</template>
<script>
export default {
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
    getCurrentPath() {
      const route = this.$router.currentRoute.name
      const routeStorage = window.localStorage
      routeStorage.setItem('route', route)
    },
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
</style>
