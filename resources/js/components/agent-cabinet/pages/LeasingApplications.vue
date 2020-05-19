<template>
<div class="col-12">
  <v-dialog
    v-model="leasingApplicationForm"
    max-width="600">
    <v-card>
      <div class="complete-reg-form__title title">
        <div class="complete-reg-form__title-logo"></div>
        <span class="d-block title">Заявка вiд розрахунку на 
          <span class="font-weight-bold">{{ reqObj.leasing_object }}</span>
        </span>
      </div>
      <v-card-text class="pb-0">
        <v-row>
        <v-col cols="12" sm="6" md="6" lg="6" xl="6">
          <v-text-field
            :value="reqObj.last_name"
            label="Прiзвище"
            dense outlined readonly>
          </v-text-field>
          <v-text-field
            :value="reqObj.first_name"
            label="Iм`я"
            dense outlined readonly>
          </v-text-field>
          <v-text-field
            :value="reqObj.patronymic"
            label="По батьковi"
            dense outlined readonly>
          </v-text-field>
          <v-text-field
            :value="reqObj.region"
            label="Область"
            dense outlined readonly>
          </v-text-field>
          <v-text-field
            v-if="reqObj.phone"
            :value="reqObj.phone"
            label="Телефон"
            dense outlined readonly>
          </v-text-field>
          <v-text-field
            v-if="reqObj.email"
            :value="reqObj.email"
            label="Email"
            dense outlined readonly>
          </v-text-field>
        </v-col>
        <v-col cols="12" sm="6" md="6" lg="6" xl="6">
          <v-text-field
            :value="reqObj.legal_info.creditPayment"
            label="Щомісячний платіж (за міс. грн) по кредитам та ін."
            dense outlined readonly>
          </v-text-field>
          <div v-if="reqObj.client_type_id === 1">
            <v-text-field
              :value="reqObj.legal_info.inn"
              label="IПН"
              dense outlined readonly>
            </v-text-field>
            <v-text-field
              :value="reqObj.legal_info.monthlyIncome"
              label="Середньомісячний дохід (грн)"
              dense outlined readonly>
            </v-text-field>
            <v-text-field
              :value="reqObj.legal_info.acquisitionTargetId"
              label="Мета придбання авто"
              dense outlined readonly>
            </v-text-field>
          </div>
          <div v-if="reqObj.client_type_id === 2">
            <v-text-field
              :value="reqObj.legal_info.edrpou"
              label="ЄДРПОУ"
              dense outlined readonly>
            </v-text-field>
            <v-text-field
              :value="reqObj.legal_info.companyName"
              label="Назва компанії"
              dense outlined readonly>
            </v-text-field>
            <v-text-field
              :value="reqObj.legal_info.currencyBalance"
              label="Валютний баланс"
              dense outlined readonly>
            </v-text-field>
            <v-text-field
              :value="reqObj.legal_info.equity"
              label="Власный капiтал"
              dense outlined readonly>
            </v-text-field>
            <!-- <v-text-field
              v-model="legalInfo.balances"
              label="Мета придбання авто"
              dense outlined>
            </v-text-field> -->
          </div>
        </v-col>
        </v-row>
        <v-row>
          <v-col class="d-flex justify-center pb-6 pt-3">
            <span>
              <v-btn @click="leasingApplicationForm = false" 
                  class="d-block white--text" 
                  color="grey darken-3">
                <v-icon v-text="'mdi-check-bold'">
                </v-icon>
                &nbsp;Закрити
              </v-btn>
            </span>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-card class="pb-4" min-height="300">
  <v-card-title class="d-block grey darken-3 white--text">
    <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-clipboard-list-outline'"></v-icon>
    Заявки на лiзинг
    <v-divider></v-divider>
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
        :items-per-page="10"
        class="elevation-1">
        <template v-slot:item.actions="{ item }">
          <div class="d-flex">
            <v-icon
              @click="toDetail(item.id)"
              color="red lighten-1">
              mdi-file-find-outline
            </v-icon>
          </div>
        </template>
        <template v-slot:item.request_status="{ item }">
          <div class="d-flex align-center pr-1 flex-column">
            <div>
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <div>
                    <v-icon
                      v-for="key in progressDivision"
                      :key="key"
                      v-on="on"
                      small
                      :color="applyChanges(item.request_status, key).color">
                      mdi-brightness-1
                    </v-icon>
                  </div>
                </template>
                <span>{{ applyChanges(item.request_status).text }}</span>
              </v-tooltip>
            </div>
            <!-- <div>
              {{applyChanges(item.request_status, i).text}}
            </div> -->
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
    search: '',
    leasingApplicationForm: false,
    tableHeader: [
      { text: 'ФИО', value: 'initials', align: 'start'},
      { text: 'Об\'єкт лiзингу', value: 'leasing_object', align: 'center'},
      { text: 'Цiна', value: 'leasing_amount', align: 'center' },
      { text: 'Тип графiку', value: 'graph_type', align: 'center' },
      { text: 'Дата', value: 'data', align: 'center' },
      { text: 'Статус заявки', value: 'request_status', align: 'center', width: 200 },
      { text: 'Дiї', value: 'actions', align: 'center', sortable: false },
    ],
    tabledata: [],
    loading: false,
    progressDivision: 5,

    // request detail data
    reqObj: {
      calculation_id: null,
      email: null,
      first_name: null,
      last_name: null,
      legal_info: {
        acquisitionTargetId: null,
        creditPayment: null,
        inn: null,
        monthlyIncome: null,
      },
      patronymic: null,
      phone: null,
      region: null,
    },
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
    applyChanges(status, index) {
      console.log(index)
      switch(status) {
        case '1': return {text: 'в обробцi', color: `${index <= 1 ? 'orange' : 'grey'}`};
        case '2': return {text: 'схвалено', color: `${index <= 5 ? 'green' : 'grey'}`};
        case '3': return {text: 'вiдхилено', color: `${index <= 5 ? 'red' : 'grey'}`};
        case '4': return {text: 'iнший статус', color: `${index <= 2 ? 'purple' : 'grey'}` };
        case '5': return {text: 'скасовано клієнтом', color: `${index <= 5 ? 'red' : 'grey'}`};
      }
    },
    toEdit(id) {
      this.$router.push({name: 'Редагувати', params: {id: id, edit: true}})
    },
    toDetail(id) {
      this.leasingApplicationForm = true
      Object.assign(this.reqObj, this.getGraphById(id)[0])
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
          .get(`/leasing-reqeust/agent/${agentId}`)
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
    test(item) {
      console.log(item)
    },
    sortData(a, b) {
      return new Date(b.created_at) - new Date(a.created_at)
    },
    switchValue(val) {
      switch(val) {
        case 'even': return 'Класичний'
        case 'annuity': return 'Ануїтет'
        case 'irregular': return 'Індивідуальний'
      }
    },
    async createTableData(object) {
      let arr = []
      await object.map(val => {
        let dataObj = {
          'initials': `${val.last_name} ${val.first_name} ${val.patronymic}`,
          'leasing_object': val.leasing_object,
          'leasing_amount': val.leasing_amount,
          'graph_type': this.switchValue(val.graph_type),
          'data': val.created_at.substr(0, 10),
          'request_status': val.status_id,
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
    leasingApplicationForm(val) {
      if(val === false) this.request = false
    }
  },
  mounted() {
    this.$store.state.user.agent 
      ? this.getUserCalculations()
      : false
  },
}
</script>