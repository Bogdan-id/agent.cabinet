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
    loader: false,
    adminLoader: false,
    // userCalculations: [],
    breadScrumb: {}
  },
  mutations: {
    addUserData(state, value) {
      state.user = Object.assign({}, value)
    },
    addAgentData(state, value) {
      state.agentData = Object.assign({}, value)
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
    addGraph(state, value) {
      state.graphs = value
    },
    deleteGraph(state) {
      state.graphs = []
    }
  },
  actions: {
    getCurrentUser({commit/*, state*/}) {
      commit('toggleSpinner', true)
      axios.get('/getUserAgent')
      .then(response => {
        console.log(response)
        commit('addUserData', response.data)
        commit('addAgentData', response.data.agent.manager)
        commit('toggleSpinner', false)
      })
      .catch(error => {
        console.log(error.response)
        commit('toggleSpinner', false)
      })
    },
  }
})