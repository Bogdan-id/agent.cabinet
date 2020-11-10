<template>
  <v-card>
    <v-card-title>
      Роздiл - Заблокованi користувачi
    </v-card-title>
    <v-card-text v-if="$store.state.adminLoader">
      <v-progress-circular
        color="red"
        indeterminate>
      </v-progress-circular>
    </v-card-text>
    <v-card-title 
      v-if="!tabledata.length > 0 && !$store.state.adminLoader"
      class="d-flex justify-center grey--text">
      ( користувачi вiдсутнi )
    </v-card-title>
    <v-card-text>
      <v-data-table
        v-if="tabledata.length > 0"
        color="black"
        :headers="tableHeader"
        :items="tabledata"
        :hide-default-footer="true"
        class="elevation-1 mr-3 ml-3">
        <template v-slot:item.created_at="{ item }">
          {{ $formatDate(item.created_at) }}
        </template>
        <template v-slot:item.actions="{ item }">
          <div class="d-flex justify-center">
            <v-btn
              @click="toggleUser(`/activate/${item.id}`)"
              color="#e65048" x-small
              class="white--text"
              :style="btnStyle">
              Розбл. так активувати
            </v-btn>
            <v-btn
              @click="toggleUser(`/deactivate/${item.id}`)"
              color="grey darken-3" x-small
              class="white--text"
              :style="btnStyle">
              Розблокувати
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
      { text: 'Им`я', value: 'name', align: 'start'},
      { text: 'Телефон', value: 'phone', align: 'center'},
      { text: 'Email', value: 'email', align: 'center' },
      { text: 'Дата подання', value: 'created_at', align: 'center' },
      { text: 'Дiї', value: 'actions', align: 'center', sortable: false },
    ],
    tabledata: [],
  }),
  methods: {
    getRejectedUsers() {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/admin/user/getBanned')
        .then(response => {
          console.log(response)
          this.createTableData(response.data)
          this.$store.commit('toggleAdminSpinner', false)
        })
        .catch(error => {
          this.$catchStatus(error.response.status, error)
          this.$store.commit('toggleAdminSpinner', false)
        })
    },

    async createTableData(object) {
      let arr = []
      await object.map(val => {
        let dataObj = {
          'created_at': val.created_at.substr(0, 10),
          'email': val.email,
          'name': val.name,
          'phone': val.phone,
          'id': val.id
        }
        arr.push(dataObj)
      })
      this.tabledata = arr
        .sort(this.sortData)
        .reverse()
    },

    sortData(a, b) {
      return new Date(b.created_at) - new Date(a.created_at)
    },

    toggleUser(url) {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/admin/user' + url)
        .then(response => {
          this.getRejectedUsers()
          this.$store.commit('toggleAdminSpinner', false)
          if(response.status === 200) {
            this.$notify({
              group: 'success',
              title: 'Успiшно',
              text: ''
            })
          }
        })
        .catch(error => {
          this.$catchStatus(error.response.status, error)
          this.$store.commit('toggleAdminSpinner', false)
        })
    },
  },


  computed: {
    btnStyle() {
      return 'border-radius: 0; margin: 5px 10px;'
    }
  },


  created() {
    this.getRejectedUsers()
  }
}
</script>