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

const baseURL = new URL (location.href).origin

function errorHandler401(error) {
  console.log({globalErrHandler: error})
  if(error?.response?.status === 404) {
    let pathName = new URL (location.href).pathname
    pathName.includes('admin')
      ? setTimeout(() => {location.replace(baseURL + '/login')}, 2000)
      : false

    return notify('Увага!', 'З\'єднання з сервером втрачено!')
  }

  switch (error?.response?.status) {
    case 401: { 
      notify('Увага!', 'З\'єднання з сервером втрачено!'); 
      setTimeout(() => { router.go() }, 2000)}; break;

    case 500: notify('Помилка! ', 'Зверніться до технічної підтримки!'); 
      break;

    case 422: notify('Помилка!', 'Данні введено невірно! Зверніться до технічної підтримки!');
      break;

    case 419: {
      notify('Увага', 'Сторiнку буде оновлено')
      router.go(); break;
    }
    
    default: notify(error?.response?.status || 'Помилка', error?.response?.data?.message || error?.response?.statusText || '')
  }
}

function notify(title, text) {
  Vue.notify({
    group: 'error',
    title: title,
    text: text,
  })
}

Vue.prototype.$catchStatus = (error) => {
  return errorHandler401(error)
}

Vue.prototype.$chLocal = (route) => {
  let url = new URL (location.href).origin
  location.replace(url + route)
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
  if(!sum) return 0
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

Vue.prototype.$toggleAdvance = (object, state) => {
  let temp = object
  let avoid = 'requestId'

  let objToEmail = JSON.parse(JSON.stringify(temp))
  let shiftedArr = []

  if(state) {
    Object.keys(objToEmail).forEach((v, i) => {
      if(v !== avoid) shiftedArr[i] = objToEmail[v].graph.shift()
    }) 

  } else {
    Object.keys(objToEmail).forEach((v, i) => {
      if(v !== avoid) shiftedArr.length > 0 
        ? objToEmail[v].graph.unshift(shiftedArr[i]) 
        : false
    }) 

    objToEmail = null
  }

  return {objToEmail, shiftedArr}
},

new Vue({
	store,
	vuetify,
	router,
	render: h => h(App)
}).$mount('#app')

