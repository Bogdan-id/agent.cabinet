require('./bootstrap')

window.Vue = require('vue')

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

import Styles from './components/App.vue'
import Presentation from './components/present-block.vue'
import Registration from './components/registration/register.vue'
import Authorization from './components/authorization/authorize.vue'
import Recovery from './components/recovery-password/recovery.vue'

const styles = new Vue({
	render: h => h(Styles)
}).$mount('#styles')

const presentblock = new Vue({
	render: h => h(Presentation)
}).$mount('#presentation')

const registration = new Vue({
	render: h => h(Registration)
}).$mount('#registration')

const authorization = new Vue({
	render: h => h(Authorization)
}).$mount('#authorization')

const recover = new Vue({
	render: h => h(Recovery)
}).$mount('#recovery-password')
