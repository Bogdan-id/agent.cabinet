<template>
  <v-card>
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
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
// УТОЧНИ КАК СОРТИРОВАТЬ АГЕНТОВ
import axios from 'axios'

export default {
  data: () => ({
    tableHeader: [
      { text: 'ФИО', value: 'initials', align: 'start'},
      { text: 'Компанiя', value: 'companyName', align: 'center'},
      { text: 'Посада', value: 'position', align: 'center' },
    ],
    tabledata: [],
  }),
  methods: {
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
          position: val.position
        }
        arr.push(dataObj)
      })
      this.tabledata = arr
        // .sort(this.sortData)
        // .reverse()
    },
    // sortData(a, b) {
    //   return new Date(b.created_at) - new Date(a.created_at)
    // },
  },
  created() {
    this.getAgents()
  }
}
</script>