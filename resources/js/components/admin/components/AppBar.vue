<template>
    <v-app-bar absolute dark app dense short tile>
      <v-app-bar-nav-icon @click="$emit('listenDrawer', !drawer)"></v-app-bar-nav-icon>
      <v-spacer></v-spacer>
      <v-menu  offset-y :close-on-content-click="false">
          <template v-slot:activator="{ on }">
              <v-btn small dense outlined v-on="on">Вихiд</v-btn>
          </template>
          <v-card class="mt-1">
              <v-list>
                  <v-list-item>
                      <v-list-item-icon>
                          <v-icon>fas fa-user-check</v-icon>
                      </v-list-item-icon>
                      <v-list-item-content>
                          <v-list-item-title>Title</v-list-item-title>
                      </v-list-item-content>
                  </v-list-item>
                  <v-divider></v-divider>
                  <v-list-item>
                      <v-btn @click="" text color="warning" small>Log Out</v-btn>
                      <v-spacer></v-spacer>
                  </v-list-item>
              </v-list>
          </v-card>
      </v-menu>      
    </v-app-bar>
</template>

<script>
export default {
	props: ['drawer'],
	data: () => ({
		drawerState: null,
  }),
  methods: {
  },
	mounted() {
		this.drawerState = this.drawer
	},

}
</script>