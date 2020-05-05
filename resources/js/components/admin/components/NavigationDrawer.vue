<template>
  <v-navigation-drawer
    v-model="drawerState"
    app
    dark
    >
    <v-list-item class="pt-4">
        <v-list-item-icon>
            <v-icon x-large color="success" v-text="'mdi-trello'"></v-icon>
        </v-list-item-icon>
        <v-list-item-title class="title">
            Адмiн панель
        </v-list-item-title>
    </v-list-item>
    <v-divider dark class="ma-2"></v-divider>
    <v-list-item-group>
        <v-list nav dense>
            <v-list-item 
              tag="a"
              class="pl-3 pa-1"
              v-for="(item, key) in nav"
              :key="key"
              @click.stop="test()">
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

export default {
  props: ['drawer'],
	data: () => ({
    drawerState: null,
    nav: [
			{subj: 'Агенти'},
      {subj: 'Кориснi матерiалы'},
      {subj: 'Заявки на винагороду'},
		]
	}),
	computed: {
  },
  methods: {
    test() {
      this.drawerState = !this.drawerState
    }
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
	components: {
	},
}
</script>