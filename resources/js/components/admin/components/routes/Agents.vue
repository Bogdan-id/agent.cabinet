<template>
  <v-card>
    <v-dialog
      max-width="390"
      v-model="agentOperationsDialog">
      <v-card>
        <v-card-title class="white--text grey darken-4">
          <v-icon large class="mr-2" color="white" v-text="'mdi-information'"></v-icon>
          Операції
        </v-card-title>
        <v-card-text class="mt-8 pb-1 subtitle-1">
          <v-text-field
            v-model="userSettings.abSize"
            label="Розмiр АВ"
            id="ab-size"
            @input="parseToInt('ab-size')"
            maxlength="3"
            dense outlined>
          </v-text-field>
          <v-select
            v-model="userSettings.status"
            :items="status"
            item-text="text"
            item-value="value"
            label="Статус"
            dense outlined>
          </v-select>
          <v-select
            v-show="managers.length > 0"
            v-model="userSettings.managerId"
            :items="managers"
            label="Менеджер"
            item-text="name"
            item-value="id"
            dense outlined>
          </v-select>
        </v-card-text>
        <v-card-actions class="pb-5 pr-5 pl-5 pt-0">
          <v-btn
            @click="sendUserSettings()"
            :loading="loading"
            small
            color="error">
            Зберегти
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            @click="agentOperationsDialog = false"
            small 
            class="grey darken-2 white--text">
            Вiдмiнити
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      max-width="390"
      v-model="deactivateDialog">
      <v-card>
        <v-card-title class="white--text grey darken-4">
          <v-icon large color="white"  class="mr-2" v-text="'mdi-information'"></v-icon>
          Деактивацiя
        </v-card-title>
        <v-card-text class="mt-4 subtitle-1">
          Агенту - <b>{{ currentUser.initials }}</b> 
          буде заборонений доступ до особистого кабiнету.
          <b style="font-size: 18px">&nbsp;Продовжити?</b>
        </v-card-text>
        <v-card-actions>
          <v-btn 
            class="ml-3"
            color="error" 
            :disabled="currentUser.id === null"
            :loading="loading"
            @click="sendRequest(currentUser.user_id)"
            small>Так
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn 
            @click="deactivateDialog = false" 
            class="mr-3 grey darken-2 white--text" 
            small>Нi
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card-title>
      Роздiл - Агенти
    </v-card-title>
    <v-card-text v-if="$store.state.adminLoader">
      <v-progress-circular
        color="red"
        indeterminate>
      </v-progress-circular>
    </v-card-text>
    <v-card-text>
      <v-data-table
        v-if="tabledata.length > 0"
        color="black"
        :headers="tableHeader"
        :items="tabledata"
        :hide-default-footer="true"
        class="elevation-1 mr-3 ml-3">
        <template v-slot:item.actions="{ item }">
          <div class="d-flex justify-center">
            <v-btn
              @click="getAgentOperations(item.id);
                findAgent(item.id)"
              color="primary" x-small>
              Змiнити
            </v-btn>
            &nbsp;
            <v-btn
              @click="deactivateUser(item.id)"
              color="error" x-small>
              Деактивувати
            </v-btn>
          </div>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'

export default {
  data: () => ({
    tableHeader: [
      { text: 'ПІБ', value: 'initials', align: 'start'},
      { text: 'Компанiя', value: 'company_name', align: 'center'},
      { text: 'Посада', value: 'position', align: 'center' },
      { text: 'Дiї', value: 'actions', align: 'center' },
    ],
    tabledata: [],
    managers: [],
    status: [
      {text: 'Активний', value: 'active'},
      {text: 'Неактивний', value: 'notActive'}
    ],
    
    currentUser: {
      initials: null,
      id: null
    },
    userSettings: {
      abSize: null,
      status: null,
      managerId: null,
      _token: null
    },
    loading: false,
    deactivateDialog: false,
    agentOperationsDialog: false
  }),
  methods: {
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		},
    getAgents() {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/admin/agent/all')
        .then(response => {
          this.createTableData(response.data)
          this.$store.commit('toggleAdminSpinner', false)
        })
        .catch(error => {
          console.log(error.response)
          this.$store.commit('toggleAdminSpinner', false)
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: ''
          })
        })
    },
    async createTableData(object) {
      let arr = []
      await object.map(val => {
        let dataObj = {
          initials: `${val.last_name} ${val.first_name} ${val.patronymic}`,
          company_name: val.company_name,
          position: val.position,
          id: val.id,
          user_id: val.user_id,
          abSize: val.ab_size,
          managerId: val.manager_id,
          status: val.status
        }
        arr.push(dataObj)
      })
      this.tabledata = arr
    },
    findAgent(id) {
      let data = this.tabledata
        .find(value => {
          return value.id === id
        })
      if(Object.keys(data).length > 0) {
        Object.assign(this.currentUser, data)
        Object.assign(this.userSettings, data)
      }
    },
    deactivateUser(userId) {
      this.deactivateDialog = true
      this.findAgent(userId)
    },
    getManagers() {
      axios
        .get('/getManagers')
        .then(response => {
          this.managers = response.data
        })
        .catch(error => {
          console.log(error.response)
        })
    },
    sendRequest(id) {
      this.loading = true
      axios
        .get(`/admin/user/deactivate/${id}`)
        .then(() => {
          this.loading = false
          this.$notify({
            group: 'success',
            title: 'Успiшно',
            text: ''
          })
          setTimeout(() => {
            this.deactivateDialog = false
          }, 800)
          this.getAgents()
        })
        .catch(error => {
          console.log(error.response)
          this.loading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `Код: ${error.response.status} ${error.response.data.message}`
          })
        })
    },
    getAgentOperations() {
      this.agentOperationsDialog = true
    },
    parseToInt(id) {
      let input = new Event('input', {bubbles: true})
      let el = document.getElementById(id)
      let value = el.value
      let intVal = el.value.replace(/[^\d]/g, '')
      if(value !== intVal || parseInt(intVal) > 100) {
        if(parseInt(intVal) > 100) {
          el.value = 100
          this.userSettings.abSize = 100
        } else {
          el.value = intVal
          this.userSettings.abSize = intVal
        }
        el.dispatchEvent(input)
      }
    },
    // checkObject(object) {
    //   Object.keys(object)
    //     .forEach(propertie => {
    //       if(object[propertie] === null) {
    //         delete object[propertie]
    //       }
    //     })
    // },
    sendUserSettings() {
      this.loading = true
      this.userSettings._token = this.getCsrf()
      // this.checkObject(this.userSettings)
      axios
        .post(`/admin/agent/update/${this.currentUser.id}`, this.userSettings)
        .then(() => {
          this.loading = false
          this.$notify({
            group: 'success',
            title: 'Успiшно',
            text: ''
          })
          setTimeout(() => {
            this.agentOperationsDialog = false
          }, 800)
          this.getAgents()
        })
        .catch(error => {
          console.log(error.response)
          this.loading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `Код: ${error.response.status} ${error.response.data.message}`
          })
        })
    }
    // sortData(a, b) {
    //   return new Date(b.created_at) - new Date(a.created_at)
    // },
  },
  watch: {
    'userSettings.managerId': function (val) {
      if(val === null || val === 'undefined') return
      else this.userSettings.managerId = parseInt(val)
    },
    agentOperationsDialog(val) {
      if(val === false) {
        Object.keys(this.userSettings)
          .forEach(propertie => {
            this.userSettings[propertie] = null
          })
      }
    },
  },
  created() {
    this.getAgents()
    this.getManagers()
  }
}
</script>