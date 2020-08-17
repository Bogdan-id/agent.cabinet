import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
	state: {
    user: {},
    agentData: {
      id: null,
      name: null,
      phone: null,
      email: null
    },
    graphs: [],
    userHasNeccessaryFields: false,
    loader: false,
    adminLoader: false,
    // userCalculations: [],
    breadScrumb: {},
    notifications: [],
    leasingRequests: [],
    agentCommissions: []
  },
  mutations: {
    addUserData(state, value) {
      state.user = Object.assign({}, value)
    },
    addNotifications(state, val) {
      state.notifications.splice(0)
      state.notifications.push(...val)
      // Vue.prototype.$sortNotificaions(state.notifications)
    },
    addLeasingRequests(state, val) {
      state.leasingRequests.splice(0)
      state.leasingRequests.push(
        ...Vue.prototype.$changeLeasingRequestsObj(val)
      )
      Vue.prototype.$sortData(state.leasingRequests)
    },
    addAgentCommissions(state, val) {
      console.log(val)
      state.agentCommissions.splice(0)
      state.agentCommissions.push(...val)
    },
    addAgentData(state, val) {
      state.agentData = Object.assign({}, val)
    },
    toggleSpinner(state, value) {
      state.loader = value
    },
    toggleAdminSpinner(state, value) {
      state.adminLoader = value
    },
    setBreadScrumb(state, links) {
      state.breadScrumb = Object.assign({}, links)
    },
    addGraph(state, val) {
      state.graphs.splice(0)
      state.graphs.push(...val)
    },
    deleteGraph(state) {
      state.graphs = []
    },
    checkIfUserHasAllNeccessaryFields(state) {
      let emptyFields = Object.keys(state.user.agent)
        .filter(value => {
          return !state.user.agent[value] && value !== 'status' && value !== 'bitrix_id'
        })
      state.userHasNeccessaryFields = emptyFields.length === 0
    }
  },
  actions: {
    getCurrentUser({commit/*, state*/}) {
      commit('toggleSpinner', true)
      axios.get('/getUserAgent')
        .then(response => {
          console.log(response.data)
          commit('addUserData', response.data)
          commit('addAgentData', response.data.agent.manager)
          commit('toggleSpinner', false)
          commit('checkIfUserHasAllNeccessaryFields')
        })
        .catch(error => {
          console.log(error.response)
          commit('toggleSpinner', false)
        })
    },
    // add sorting
    dataFromSocket({commit, state}, data) {
      commit('addNotifications', data.notifications)
      commit('addLeasingRequests', data.leasingRequests)
      commit('addAgentCommissions', data.agentCommissions)
      console.log(state)
    }
  }
})