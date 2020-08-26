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

Vue.prototype.$sortByStatus = (data) => { 
  return data.sort((a, b) => {
    return ('' + b.status).localeCompare(a.status)
  })
}

Vue.prototype.$sortByDate = (notifications) => {
  return notifications.sort((a, b) => {
    return new Date(b.created_at) - new Date(a.created_at)
  })
}

Vue.prototype.$formatDate = (data) => {
  if(data.substring(0, 10).split('-').length === 3) {
    const [year, month, day] = data.substring(0, 10).split('-')

    return `${day}.${month}.${year}`

  } else return data
}

Vue.prototype.$changeLeasingRequestsObj = (obj, state) => {
  console.log(state)
  return Object.keys(obj)
    .map(val => {
      obj[val].client = obj[val].client_type_id == 2 
        ? obj[val].legal_info.company_name 
        : obj[val].last_name + ' ' + obj[val].first_name[0] + '. ' + obj[val].patronymic[0] + '.' 
      obj[val].leasing_amount = parseInt(obj[val].leasing_amount
        .replace(/\s/g, '' ))
        .toLocaleString("en-GB")
        .replace(/,/g, ' ')
      obj[val].created_at = Vue.prototype.$formatDate(obj[val].created_at)
      obj[val].agent_reward = ((parseInt(obj[val].leasing_amount.replace(/\s/g, '' )) / 100) * state.user.agent.ab_size)
        .toLocaleString("en-GB")
        .replace(/,/g, ' ')
      return obj[val]
    })
}

Vue.prototype.$sortData = (items) => {
  items
    .sort((a, b) => {
      return new Date(b.created_at) - new Date(a.created_at)
  })
  return items.reverse()
},

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

