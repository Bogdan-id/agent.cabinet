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
    breadScrumb: {},
    notifications: [],
    leasingRequests: [],
    agentCommissions: [],

    userHasNeccessaryFields: false,
    loader: false,
    adminLoader: false,
    showSidebar: true 
  },


  mutations: {
    addUserData(state, value) {
      state.user = Object.assign({}, value)
    },

    addNotifications(state, val) {
      state.notifications.splice(0)
      state.notifications.push(...val)
      // Vue.prototype.$sortByDate(state.notifications)
    },

    addLeasingRequests(state, val) {
      state.leasingRequests.splice(0)
      state.leasingRequests.push(
        ...Vue.prototype.$changeLeasingRequestsObj(val, state)
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
      let emptyFields
      let neccessaryFields

      let checkFields = () => {
        emptyFields = Object.keys(state.user.agent)
          .filter(value => {
            return !state.user.agent[value] && neccessaryFields.indexOf(value) !== -1
          })

        console.log({emptyARNeccessaryFields: emptyFields})
        state.userHasNeccessaryFields = emptyFields.length === 0
        
        return
      }
      if(state.user.agent.company_type === 'freelance') {
        neccessaryFields = ['bitrix_id', 'status']

        checkFields()
      } else {

        neccessaryFields = ['bitrix_id', 'status', 'company_name', 'position']
        checkFields()
      }
    },

    toggleSidebar(state, value) {
      state.showSidebar = value || !state.showSidebar
    }
  },

  
  actions: {
    getCurrentUser({commit, state}) {
      commit('toggleSpinner', true)
      axios.get('/json/getUserAgent')
        .then(response => {
          console.log({'Main response': response.data})

          commit('addUserData', response.data)
          commit('addAgentData', response.data.agent.manager)
          commit('toggleSpinner', false)
          commit('checkIfUserHasAllNeccessaryFields')
          console.log({'Main state': state})
        })
        .catch(error => {
          console.log({'User anent response error': error.response})
          
          commit('toggleSpinner', false)
        })
    },

    // add sorting
    dataFromSocket({commit}, data) {
      commit('addNotifications', data.notifications)
      commit('addLeasingRequests', data.leasingRequests)
      commit('addAgentCommissions', data.agentCommissions)
    }
  }
})