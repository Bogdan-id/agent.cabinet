<template>
<div class="col-12">
  <v-dialog
    v-model="leasingApplicationForm"
    max-width="600">
    <v-card v-if="hasObject">
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
              dense outlined>
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
    search: '',
    leasingApplicationForm: false,
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

    // request detail data
    reqObj: {
      calculation_id: null,
      email: null,
      first_name: null,
      last_name: null,
      legal_info:{
        acquisitionTargetId: null,
        creditPayment: null,
        inn: null,
        monthlyIncome: null,
      },
      patronymic: null,
      phone: null,
      region: null,
    },
    request: false,
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
    },
    hasObject() {
      return this.request
    }
  },
  methods: {
    toEdit(id) {
      this.$router.push({name: 'Редагувати', params: {id: id, edit: true}})
    },
    toDetail(id) {
      this.leasingApplicationForm = true
      this.$store.commit('toggleSpinner', true)
      axios
        .get(`/leasing-reqeust/${id}`)
        .then(response => {
          console.log(response)
          Object.assign(this.reqObj, response.data)
          this.$store.commit('toggleSpinner', false)
          this.request = true
        })
        .catch(error => {
          console.log(error)
          this.$store.commit('toggleSpinner', false)
          this.leasingApplicationForm = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
        })
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
              this.createTableData(response.data)
              this.$store.commit('deleteGraph')
              response.data.forEach(val => 
                this.$store.commit('addGraph', val)
              )
            } else {
              this.tabledata = []
            }
          })
          .catch(error => {
            console.log(error.response)
            this.$store.commit('toggleSpinner', false)
            this.$notify({
              group: 'error',
              title: 'Помилка',
              text: `${error.response.status} \n ${error.response.data.message}`,
            })
          })
        }
    },
    test() {
      // console.log(!this.loading)
      // console.log(!this.tableDataPresent)
    },
    async createTableData(object) {
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