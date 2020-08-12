<template>
    <v-app-bar absolute dark app dense short tile>
        <v-app-bar-nav-icon @click="$emit('listenDrawer', !drawer)"></v-app-bar-nav-icon>   
    </v-app-bar>
</template>

<script>
export default {
	props: ['drawer'],
	data: () => ({
    drawerState: null,
    loading: false,
  }),
	mounted() {
		this.drawerState = this.drawer
	},

}
</script>