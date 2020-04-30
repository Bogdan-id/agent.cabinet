import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
	state: {
    user: {},
    graphs: [],
    loader: false,
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
    // addCalculation(state, value) {
    //   state.userCalculations.push(value) 
    // },
    setBreadScrumb(state, links) {
      state.breadScrumb = Object.assign({}, links)
    },

    addGraph(state, value) {
      state.graphs.push(value)
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