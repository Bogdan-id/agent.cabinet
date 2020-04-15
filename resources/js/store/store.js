import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
	state: {
    user: {}
  },
  mutations: {
    addUser(state, value) {
      state.user = value
    }
  },
  actions: {
    add_user({commit}, userObj) {
      commit('addUser', userObj)
    }
  }
})