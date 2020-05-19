<template>
  <v-navigation-drawer
    v-model="drawerState"
    app
    dark>
    <v-list-item 
      class="pa-4" 
      style="background: lightgrey!important;">
      <logo width="100%" height="" />
    </v-list-item>
    <v-divider dark class="ma-2"></v-divider>
    <v-list-item-group>
        <v-list nav dense>
            <v-list-item 
              tag="a"
              class="pl-3 pa-1"
              v-for="(item, key) in nav"
              :key="key"
              :to="{path: item.link, name: item.link}">
              <v-list-item-icon class="mr-2">
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-item-icon>
              <v-list-item-content color="green">
                {{item.subj}}
              </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-list-item-group>
  </v-navigation-drawer>
</template>

<script>
import Logo from '../../agent-cabinet/assets/svg-icons/best-leasing-logo.vue'

export default {
  props: ['drawer'],
  components: {
    Logo
  },
	data: () => ({
    drawerState: null,
    nav: [
      {subj: 'Заявки на реєстрацію', link: 'application-for-registration', icon: 'mdi-account-check'},
			{subj: 'Агенти', link: 'agents-edit', icon: 'mdi-face-agent'},
      {subj: 'Кориснi матерiалы', link: 'Кориснi матерiали', icon: 'mdi-bookmark'},
      {subj: 'Заявки на винагороду', link: 'reward-applications', icon: 'mdi-format-list-checks'},
		]
	}),
	computed: {
  },
  methods: {
  },
  watch: {
    drawer(val) {
      this.drawerState = val
    },
    drawerState(val) {
      this.$emit('listenDrawer', val)
    }
  },
  mounted() {
    this.drawerState = this.drawer
  },
}
</script>