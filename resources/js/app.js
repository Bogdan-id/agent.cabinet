// require('bootstrap')

window.Vue = require('vue')

import router from './router/router'
import App from './components/App'

import PaperDashboard from "./components/agent-cabinet/plugins/paperDashboard"
// https://github.com/euvl/vue-notification
import Notifications from 'vue-notification'
import vuetify from './vuetify'
import VueMask from 'v-mask'


/* eslint-disable */
Vue.use(PaperDashboard)
Vue.use(VueMask)
Vue.use(Notifications)

new Vue({
	vuetify,
	router,
	render: h => h(App)
}).$mount('#app')

