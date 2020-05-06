import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
	state: {
    user: {},
    graphs: [],
    loader: false,
    adminLoader: false,
    // userCalculations: [],
    breadScrumb: {}
  },
  mutations: {
    addUser(state, value) {
      state.user = Object.assign({}, value)
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
    add_user({commit}, userObj) {
      commit('addUser', userObj)
    }
  }
})