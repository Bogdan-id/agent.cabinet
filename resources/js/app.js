require('./bootstrap')

window.Vue = require('vue')

import Styles from './components/App.vue'
import Presentation from './components/present-block.vue'
import Authorization from './components/authorization/authorization.vue'

const styles = new Vue({
	render: h => h(Styles)
}).$mount('#styles')

const presentblock = new Vue({
	render: h => h(Presentation)
}).$mount('#presentation')

const authorization = new Vue({
	render: h => h(Authorization)
}).$mount('#authorization')
