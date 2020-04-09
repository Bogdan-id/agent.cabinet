require('bootstrap')

window.Vue = require('vue')

import router from './router/router'
import App from './components/App'

import PaperDashboard from "./components/agent-cabinet/plugins/paperDashboard"
import "vue-notifyjs/themes/default.css"
import Notify from 'vue-notifyjs'

/* eslint-disable */
Vue.use(PaperDashboard)
Vue.use(Notify)

new Vue({
	router,
	render: h => h(App)
}).$mount('#app')

