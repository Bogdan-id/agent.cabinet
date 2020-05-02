<template>
<div class="col-12">
  <v-card class="pb-4" min-height="300">
  <v-card-title class="d-block">
    <div>Калькулятор лiзингу</div>
    <v-divider></v-divider>
    </v-card-title>
    <v-card-actions
      :style="`transition: all 0.5s; opacity: ${!loading ? '1' : '0'}`">
      <v-tooltip right>
        <template v-slot:activator="{ on }">
          <v-btn
            v-on="on"
            to="/calculator/new"
            color="error" 
            fab dark>
            <v-icon dark>mdi-plus-thick</v-icon>
          </v-btn>
        </template>
        <span>Новий розрахунок</span>
      </v-tooltip>
    </v-card-actions>
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
      Iсторiя розрахункiв порожня
    </v-card-title>
    <v-card-text 
      v-show="tableDataPresent" 
      class="calculations-table">
      <v-card-title class="headline">
        Iсторiя розрахункiв
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
              @click="toEdit(item.id)"
              color="red lighten-1"
              class="mr-2"
              >
              mdi-pencil
            </v-icon>
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
      { text: 'Тип об`єкту лiзингу', value: 'Тип', align: 'start'},
      { text: 'Марка', value: 'Марка', align: 'center'},
      { text: 'Модель', value: 'Модель', align: 'center' },
      { text: 'Сума', value: 'Сума', align: 'center' },
      { text: 'Дата', value: 'Дата', align: 'center' },
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
          .get(`calculations/agent/${agentId}`)
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
          'Тип': val.request_data.leasingObjectType.label,
          'Марка': val.request_data.leasedAssertMark.name,
          'Модель': val.request_data.leasedAssertModel.name,
          'Сума': val.request_data.leasingAmount,
          'Дата': val.created_at.substr(0, 10),
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