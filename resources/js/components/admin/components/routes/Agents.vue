<template>
  <v-card>
    <v-dialog
      max-width="390"
      v-model="agentOperationsDialog">
      <v-card>
        <v-card-title style="border-bottom: 1px solid grey!important">
          <v-icon large class="mr-2" v-text="'mdi-information'"></v-icon>
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
            small dark>
            Зберегти
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            @click="agentOperationsDialog = false"
            small dark>
            Вiдмiнити
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      max-width="390"
      v-model="deactivateDialog">
      <v-card>
        <v-card-title style="border-bottom: 1px solid grey!important">
          <v-icon large class="mr-2" v-text="'mdi-information'"></v-icon>
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
            :disabled="currentUser.id === null"
            :loading="loading"
            @click="sendRequest(currentUser.id)"
            dark small>Так
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn 
            @click="deactivateDialog = false" 
            class="mr-3" 
            dark small>Нi
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card-title>
      Роздiл - Агенти
    </v-card-title>
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
              Операції
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
      { text: 'ФИО', value: 'initials', align: 'start'},
      { text: 'Компанiя', value: 'companyName', align: 'center'},
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
        .get('/agent/all')
        .then(response => {
          console.log(response)
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
          companyName: val.company_name,
          position: val.position,
          id: val.id
        }
        arr.push(dataObj)
      })
      this.tabledata = arr
        // .sort(this.sortData)
        // .reverse()
    },
    findAgent(id) {
      console.log('findAgent')
      return this.tabledata
        .find(value => {
          value.id === id
          console.log(value)
          Object.assign(this.currentUser, value)
          Object.assign(this.userSettings, value)
        })
    },
    deactivateUser(userId) {
      this.deactivateDialog = true
      this.findAgent(userId)
      console.log(userId)
      console.log(this.tabledata)
    },
    getManaers() {
      axios
        .get('/getManagers')
        .then(response => {
          console.log(response)
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
        .then(response => {
          console.log(response)
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
    getAgentOperations(id) {
      console.log(id)
      this.agentOperationsDialog = true
    },
    parseToInt(id) {
      console.log('parseToInt')
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
    sendUserSettings() {
      this.loading = true
      this.userSettings._token = this.getCsrf()
      axios
        .post(`/admin/agent/update/${this.currentUser.id}`, this.userSettings)
        .then(response => {
          console.log(response)
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
    }
  },
  created() {
    this.getAgents()
    this.getManaers()
  }
}
</script>