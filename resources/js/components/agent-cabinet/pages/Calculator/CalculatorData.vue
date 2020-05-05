<template>
<div class="col-12">
  <v-card class="pb-4" min-height="300">
    <v-card-title class="d-block grey darken-3 white--text">
      <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-calculator-variant'"></v-icon>
      Калькулятор лiзингу
      <v-divider></v-divider>
    </v-card-title>
    <v-card-title
      class="calculator-custom-title"
      :style="`transition: all 0.5s; opacity: ${!loading ? '1' : '0'}`">
        <v-btn
          to="/calculator/new"
          color="error" 
          dark>
          Новий розрахунок
        </v-btn>
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
      <v-card-title class="headline black--text d-block text-center mb-8 mt-3 ">
        Iсторiя розрахункiв
      </v-card-title>
      <v-data-table
        :search="search"
        color="black"
        :headers="tableHeader"
        :items="tabledata"
        :items-per-page="10"
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
      { text: 'Дiї', value: 'actions', align: 'start', sortable: false },
    ],
    tabledata: [],
    search: '',
    loading: false
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
      this.loading = true
      this.tabledata = []
      if(this.userData){
        const agentId = this.$store.state.user.agent.id
        axios
          .get(`calculations/agent/${agentId}`)
          .then(response => {
            this.loading = false
            if(response.data.length > 0)  {
              this.createTableData(response.data)
              this.$store.commit('addGraph', response.data)
            } else {
              this.tabledata = []
            }
          })
          .catch(error => {
            this.loading = false
            this.$notify({
              group: 'error',
              title: 'Помилка',
              text: `${error.response.status} \n ${error.response.data.message}`,
            })
          })
        }
    },
    test() {
      console.log(this.tabledata)
    },
    sortData(a, b) {
      return new Date(b.created_at) - new Date(a.created_at)
    },
    async createTableData(object) {
      let arr = []
      await object.map(val => {
        let dataObj = {
          'Тип': val.request_data.leasingObjectType.label,
          'Марка': val.request_data.leasedAssertMark.name,
          'Модель': val.request_data.leasedAssertModel.name,
          'Сума': val.request_data.leasingAmount,
          'Дата': val.created_at.substr(0, 10),
          'id': val.id
        }
        arr.push(dataObj)
      })
      this.tabledata = arr
        .sort(this.sortData)
        .reverse()
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

<style lang="scss">
  .calculator-custom-title {
    .v-text-field {
      padding: 0!important;
    }
    .v-btn {
      border-radius: 0!important;
    }
  }
</style>