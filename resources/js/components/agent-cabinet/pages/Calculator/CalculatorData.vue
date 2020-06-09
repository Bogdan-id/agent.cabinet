<template>
<div class="col-12">
  <v-dialog
    v-model="showDocumentAttachment"
    max-width="370">
    <v-card>
      <v-card-title class="mb-5" style="font-size: 1.55rem">
        Додати документи
      </v-card-title>
      <v-card-text>
        <v-file-input 
          v-model="attachedFiles"
          accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps"
          multiple 
          label="Додати файл"
          outlined dense small-chips counter-size-string show-size>
        </v-file-input>
        <v-card-actions>
        <v-btn
          small
          dark
          color="grey darken-3"
          @click="sendRequest()"
          :loading="btnLoading">
          Подати заявку на лiзинг
        </v-btn>
      </v-card-actions>
      </v-card-text>
    </v-card>
  </v-dialog>
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
      :height="3"
      :active="loading"
      :indeterminate="loading"
      absolute
      top
      color="red lighten-1"
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
          <div style="display: flex; justify-content: center">
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click="toEdit(item.id)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="red lighten-1"
                      >
                      mdi-pencil
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Редагувати</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn
                    @click="toDetail(item.id)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="red lighten-1"
                      >
                      mdi-file-find-outline
                    </v-icon>
                  </v-btn>
                </span>
                </template>
              <span>Переглянуты</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click.stop="showDocumentAttachment = true"
                    v-on="on"
                    icon>
                    <v-icon
                      color="red lighten-1"
                      >
                      mdi-plus-circle
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Подати заявку на лiзинг</span>
            </v-tooltip>
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
      { text: 'Цiна', value: 'Сума', align: 'center' },
      { text: 'Розмiр АВ', value: '', align: 'center' },
      { text: 'Дата', value: 'Дата', align: 'center' },
      { text: 'Дiї', value: 'actions', align: 'center', sortable: false },
    ],
    tabledata: [],
    search: '',
    attachedFiles: null,
    loading: false,
    btnLoading: false,
    showDocumentAttachment: false
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
    sendRequest() {
      this.btnLoading = true
      setTimeout(() => {
        this.btnLoading = false
      }, 800)
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
    },
    showDocumentAttachment(val) {
      if(val === false) {
        this.attachedFiles = null
      }
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