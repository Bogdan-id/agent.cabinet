require('./bootstrap')

window.Vue = require('vue')

import Styles from './components/App.vue'
import Presentation from './components/present-block.vue'
import Registration from './components/registration/register.vue'
import Authorization from './components/authorization/authorize.vue'
import Recovery from './components/recovery-password/recovery.vue'

import Logout from './components/logout/log-out.vue'

new Vue({
	render: h => h(Styles)
}).$mount('#styles')

new Vue({
	render: h => h(Presentation)
}).$mount('#presentation')

new Vue({
	render: h => h(Registration)
}).$mount('#registration')

new Vue({
	render: h => h(Authorization)
}).$mount('#authorization')

new Vue({
	render: h => h(Recovery)
}).$mount('#recovery-password')

new Vue({
	render: h => h(Logout)
}).$mount('#log-out')
