<template>
  <v-card>
    <v-card-title>
      Роздiл - Заявки на реєстрацію
    </v-card-title>
    <v-card-title 
      v-if="!tabledata.length > 0 && !$store.state.adminLoader"
      class="d-flex justify-center grey--text">
      ( заявки вiдсутнi )
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
          <div class="d-flex">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-icon
                  @click="addUser(item.id)"
                  v-on="on"
                  color="success"
                  v-text="'mdi-checkbox-marked'">
                </v-icon>
              </template>
              <span>Пiдтвердити реєстрацію</span>
            </v-tooltip>
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
      { text: 'Дiї', value: 'actions', align: 'start', sortable: false },
    ],
    tabledata: [],
  }),
  methods: {
    getApplicationsToRegister() {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/getDontActiveUsers')
        .then(response => {
          console.log(response)
          this.createTableData(response.data)
          this.$store.commit('toggleAdminSpinner', false)
        })
        .catch(error => {
          console.log(error.reponse)
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
    addUser(id) {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get(`/admin/user/activate/${id}`)
        .then(response => {
          console.log(response)
          this.getApplicationsToRegister()
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
          console.log(error.response)
          this.$store.commit('toggleAdminSpinner', false)
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: ''
          })
        })
    }
  },
  created() {
    this.getApplicationsToRegister()
  }
}
</script>