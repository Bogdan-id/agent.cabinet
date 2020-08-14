// require('bootstrap')
import '@babel/polyfill';

window.Vue = require('vue')

import router from './router/router'
import App from './components/App'

import PaperDashboard from "./components/agent-cabinet/plugins/paperDashboard"
// https://github.com/euvl/vue-notification
import Notifications from 'vue-notification'
import vuetify from './vuetify'
import VueMask from 'v-mask'
import store from './store/store'
import CKEditor from '@ckeditor/ckeditor5-vue'

/* eslint-disable */

Vue.use(CKEditor)
Vue.use(PaperDashboard)
Vue.use(VueMask)
Vue.use(Notifications)

var errorHandler401 = (error) => {
  console.log(error)
  error === 401
    ? router.go()
    : false
}

Vue.prototype.$catchStatus = (error) => {
  return errorHandler401(error)
}
Vue.prototype.$formatDate = (data) => {
  const [year, month, day] = data.substring(0, 10).split('-')
  return `${day}.${month}.${year}`
}
Vue.prototype.$formatSum = (sum) => {
  if(typeof sum === 'string') {
    return parseInt(sum
      .replace(/\s/g, '' ))
      .toLocaleString("en-GB")
      .replace(/,/g, ' ')
  } else {
    return parseInt(sum
      .toString()
      .replace(/\s/g, '' ))
      .toLocaleString("en-GB")
      .replace(/,/g, ' ')
  }
}

new Vue({
	store,
	vuetify,
	router,
	render: h => h(App)
}).$mount('#app')

