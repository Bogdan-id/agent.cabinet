import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
	state: {
    user: {},
    loader: false,
    userCalculations: []
  },
  mutations: {
    addUser(state, value) {
      state.user = Object.assign({}, value)
    },
    toggleSpinner(state, value) {
      state.loader = value
    },
    addCalculation(state, value) {
      state.userCalculations.push(value) 
    }
  },
  actions: {
    add_user({commit}, userObj) {
      commit('addUser', userObj)
    }
  }
})