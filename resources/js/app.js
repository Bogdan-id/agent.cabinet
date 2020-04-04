require('./bootstrap')

window.Vue = require('vue')

import router from './router/index'
import App from './components/App'

new Vue({
	router,
	render: h => h(App)
}).$mount('#app')

