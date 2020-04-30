<template>
<div class="col-12">
  <v-card class="pb-4" min-height="300">
  <v-card-title class="d-block">
    <div>Заявки на лiзинг</div>
    <v-divider></v-divider>
    </v-card-title>
    <v-progress-linear
      :height="1"
      :active="loading"
      :indeterminate="loading"
      absolute
      top
      color="grey lighten-1"
    ></v-progress-linear>
    <v-card-title
      v-if="!loading && !tableDataPresent"
      absolute
      class="headline d-block text-center grey--text">
      Iсторiя заявок на лiзинг порожня
    </v-card-title>
    <v-card-text 
      v-show="tableDataPresent" 
      class="calculations-table">
      <v-card-title class="headline">
        <v-spacer></v-spacer>
        <v-text-field
          v-show="tableDataPresent"
          color="black"
          v-model="search"
          append-icon="mdi-magnify"
          label="Пошук"
          single-line
          hide-details>
        </v-text-field>
      </v-card-title>
      <v-data-table
        :search="search"
        color="black"
        :headers="tableHeader"
        :items="tabledata"
        :items-per-page="5"
        class="elevation-1">
        <template v-slot:item.actions="{ item }">
          <div class="d-flex">
            <v-icon
              @click="toDetail(item.id)"
              color="red lighten-1"
              >
              mdi-file-find-outline
            </v-icon>
          </div>
        </template>
      </v-data-table>
      <!-- <v-btn @click="test()">test</v-btn> -->
    </v-card-text>
  </v-card>
</div>
</template>

<script>
import axios from 'axios'

export default {
  data:() => ({
    tableHeader: [
      { text: 'ФИО', value: 'initials', align: 'start'},
      { text: 'Об\'єкт лiзингу', value: 'leasing_object', align: 'center'},
      { text: 'Цiна', value: 'leasing_amount', align: 'center' },
      { text: 'Тип графiку', value: 'graph_type', align: 'center' },
      { text: 'Дата', value: 'data', align: 'center' },
      { text: 'Статус заявки', value: 'request_status', align: 'center' },
      { text: 'Дiї', value: 'actions', align: 'center', sortable: false },

    ],
    tabledata: [],
    search: '',
  }),
  computed: {
    user() {
      return this.$store.state.user.agent
    },
    userData() {
      return Object.keys(this.$store.state.user.agent).length > 0
    },
    tableDataPresent() {
      return this.tabledata.length > 0
    },
    loading() {
      return this.$store.state.loader === true
    }
  },
  methods: {
    toEdit(id) {
      this.$router.push({name: 'Редагувати', params: {id: id, edit: true}})
    },
    toDetail(id) {
      let graphs = this.getGraphById(id)[0]
      this.$router.push({name: 'Графiки ', params: {data: graphs}})
    },
    getGraphById(id) {
      return this.$store.state.graphs
        .filter(val => val.id === id)
    },
    getUserCalculations() {
      this.$store.commit('toggleSpinner', true)
      this.tabledata = []
      if(this.userData){
        const agentId = this.$store.state.user.agent.id
        axios
          .get(`/leasing-reqeust/agent/${agentId}`)
          .then(response => {
            this.$store.commit('toggleSpinner', false)
            if(response.data.length > 0)  {
              console.log('user has calculations')
              this.createTableData(response.data)
              this.$store.commit('deleteGraph')
              response.data.forEach(val => 
                this.$store.commit('addGraph', val)
              )
            } else {
              console.log('calculations epsent')
              this.tabledata = []
            }
          })
          .catch(error => {
            console.log(error.response)
            this.$store.commit('toggleSpinner', false)
            this.$notify({
              
            })
          })
        }
    },
    test() {
      // console.log(!this.loading)
      // console.log(!this.tableDataPresent)
    },
    async createTableData(object) {
      console.log(object)
      await object.map(val => {
        let dataObj = {
          'initials': `${val.last_name} ${val.first_name} ${val.patronymic}`,
          'leasing_object': val.leasing_object,
          'leasing_amount': val.leasing_amount,
          'graph_type': val.graph_type,
          'data': val.created_at.substr(0, 10),
          'request_status': val.status_id,
          'id': val.id
        }
        this.tabledata.push(dataObj)
      })
    },
  },
  watch: {
    user() {
      if(this.userData) this.getUserCalculations()
      return
    }
  },
  mounted() {
    this.$store.state.user.agent 
      ? this.getUserCalculations()
      : false
  },
}
</script>