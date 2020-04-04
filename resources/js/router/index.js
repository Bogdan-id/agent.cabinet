import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Authorization from '../components/authentication/Authorization'
import Registration from '../components/authentication/Registration'
import LogOut from '../components/authentication/TEMP-logout' // this component is temporary exist

const routes = [
  { path: '/', component: Authorization },
	{ path: '/login', component: Authorization },
	{ path: '/register', component: Registration},
	{ path: '/home', component: LogOut}
]

export default new VueRouter({ 
	routes,
	mode: 'history'
})