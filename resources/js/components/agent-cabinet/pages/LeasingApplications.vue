<template>
<div class="col-12">
  <v-dialog
    v-model="dialogToAgentReward"
    max-width="390">
    <v-card>
      <v-card-title class="grey darken-3 white--text" style="position: relative;">
        Агентська винагорода
        <v-btn dark @click="dialogToAgentReward = false" style="position: absolute; right: 5px; top: 5px" icon><v-icon v-text="'mdi-close'"></v-icon></v-btn>
      </v-card-title>
      <v-card-text class="mt-8" style="font-size: 1.2rem; text-align: center;">
        Пiдтвердити виплату агентської винагороди?
      </v-card-text>
      <v-card-text style="display: flex; justify-content: space-around">
        <span><v-btn color="#e94949" dark @click="">Так</v-btn></span>
        <span><v-btn color="#333333" dark @click="dialogToAgentReward = false">Нi</v-btn></span>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-dialog
    v-model="leasingApplicationForm"
    max-width="600">
    <v-card style="position: relative;">

      <v-card-title style="background: #424242; position: relative" class="white--text">
        <span class="d-block title">Заявка вiд розрахунку на 
          <span class="font-weight-bold">{{ reqObj.leasing_object }}</span>
        </span>
        <v-btn @click="leasingApplicationForm = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon v-text="'mdi-close'" color="white"></v-icon></v-btn>
      </v-card-title>
      <div class="complete-reg-form__title title">
        <div class="complete-reg-form__title-logo"></div>
      </div>

      <v-card-text class="pb-0">
        <v-row>
          <v-col cols="12" class="pt-0 pb-0 calculator-data-graph">
            <div style="font-size: 1.1rem; font-weight: bold; letter-spacing: 0.03rem">Тип графiку платежiв</div>
            <v-radio-group 
              v-model="reqObj.graph_type" 
              readonly
              row>
              <v-radio  label="Класичний" value="even"></v-radio>
              <v-radio label="Ануїтет" value="annuity"></v-radio>
              <v-radio label="Індивідуальний" value="irregular"></v-radio>
            </v-radio-group>
          </v-col>
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
          <v-col cols="12" class="pt-0 pb-0 calculator-data-graph">
            <div style="font-size: 1.1rem; font-weight: bold; letter-spacing: 0.03rem; padding-bottom: 0.5rem;">Доданi файли</div>
            <div 
              v-for="(item, key) in reqObj.documents"
              :key="key">
              <span>{{ returnDocumentName(item) }}</span>
            </div>
          </v-col>
        </v-row>
        <v-row>
          <v-col class="d-flex justify-center pb-6 pt-3">
            <span>
              <v-btn @click="leasingApplicationForm = false" 
                class="d-block white--text" 
                color="grey darken-3">
                Закрити
              </v-btn>
            </span>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-card class="pb-4" min-height="300" elevation="12">
  <v-card-title class="d-block grey darken-3 white--text">
    <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-clipboard-list-outline'"></v-icon>
    Заявки на лiзинг
  </v-card-title>
    <v-progress-linear
      :height="3"
      :active="loading"
      :indeterminate="loading"
      absolute
      top
      color="red lighten-1">
    </v-progress-linear>
    <v-card-title
      v-if="!loading && !tableDataPresent"
      absolute
      class="headline d-block text-center grey--text">
      Iсторiя заявок на лiзинг порожня
    </v-card-title>
    <v-card-text 
      v-show="tableDataPresent" 
      class="calculations-table">
      <v-card-title class="headline mb-7">
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
        :custom-sort="customSort"
        :items-per-page="10"
        class="elevation-1">
        <template v-slot:item.graph="{ item }">
          <span>{{ item.graph_type }}</span>
        </template>
        <template v-slot:item.initials="{ item }">
          <span style="white-space: nowrap;"> {{ `${item.first_name} ${item.last_name} ${item.patronymic}` }}</span>
        </template>
        <template v-slot:item.leasing_object="{ item }">
          <span style="white-space: nowrap">{{ item.leasing_object}}</span><!-- <router-link tag="a" style="white-space: nowrap; text-transform: lowercase;" small dark color="grey darken-3" :to="{ name: 'Графiки', params: {data: item.calculation, graph: item.graph_type, preview: true} }">{{ item.graph_type }}</router-link> -->
        </template>
        <template v-slot:item.data="{ item }">
          <span style="white-space: nowrap;"> {{ item.updated_at.substr(0, 10) }}</span>
        </template>
        <template v-slot:item.leasing_amount="{ item }">
          <span style="white-space: nowrap">
            {{ 
              parseInt(item.leasing_amount.replace(/\s/g, '' ))
                .toLocaleString()
                .replace(/,/g, ' ')
            }}
          </span>
        </template>
        <template v-slot:item.agents_reward="{ item }">
          <span style="white-space: nowrap">
            {{ 
              (parseInt(item.leasing_amount.replace(/\s/g, '' )) / 100)
                .toLocaleString()
                .replace(/,/g, ' ')
            }}
          </span>
        </template>
        <template v-slot:item.graph="{ item }">
          <v-btn x-small style="white-space: nowrap; text-transform: lowercase; display: flex; text-align: center;" small dark color="grey darken-3" :to="{ name: 'Графiки', params: {data: item.calculation, graph: item.graph_type, preview: true} }">{{ switchValue(item.graph_type) }}</v-btn>
        </template>
        <template #item.actions="{ item }">
          <div class="d-flex justify-center">
            <v-tooltip bottom>
              <template #activator="{ on }">
                <v-btn 
                  v-if="item.status_id == 5"
                  @click="showDialogToAgentReward(item)"
                  v-on="on"
                  icon>
                  <v-icon
                    color="red lighten-1">
                    mdi-sack-percent
                  </v-icon>
                </v-btn>
                <v-icon
                  v-if="item.status_id != 5"
                  v-on="on"
                  style="padding: 6px"
                  >
                  mdi-sack-percent
                </v-icon>
              </template>
              <span v-if="item.status_id == 5">Подати заявку на виплату АВ</span>
              <span v-if="item.status_id != 5">Ви не можете подати заявку на винагороду з даним статусом заявки на лізинг</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <v-btn 
                  @click="toDetail(item.id)"
                  v-on="on"
                  icon>
                  <v-icon
                    color="red lighten-1">
                    mdi-file-find-outline
                  </v-icon>
                </v-btn>
              </template>
              <span>Переглянути</span>
            </v-tooltip>
          </div>
        </template>
        <template #item.request_status="{ item }">
          <div class="d-flex justify-center">
            <div style="display: inline-block">
              <v-tooltip bottom>
                <template #activator="{ on }">
                  <div v-on="on"  style="display: inline-block">
                    <v-icon
                      v-for="key in progressDivision"
                      :key="key"
                      small
                      :color="applyChanges(item.status_id, key).color">
                      mdi-brightness-1
                    </v-icon>
                  </div>
                </template>
                <span>{{ applyChanges(item.status_id).text }}</span>
              </v-tooltip>
            </div>
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
    dialogToAgentReward: false,
    tableHeader: [
      { text: 'Клієнт', value: 'initials', align: 'start'},
      { text: 'Предмет лiзингу', value: 'leasing_object', align: 'center'},
      { text: 'Цiна, грн', value: 'leasing_amount', align: 'center' },
      { text: 'АВ, грн', value: 'agents_reward', align: 'center' },
      { text: 'Тип графiку', value: 'graph', align: 'center' },
      { text: 'Дата', value: 'data', align: 'center' },
      { text: 'Статус', value: 'request_status', align: 'center', width: 120 },
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
      graph_type: null,
      documents: null,
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
    returnDocumentName(url) {
      let index = url.lastIndexOf('/') + 1
      return url.substr(index)
    },
    showDialogToAgentReward() {
      this.dialogToAgentReward = true
    },
    customSort(items) {
      console.log(items)
      items
        .sort((a, b) => {
          return new Date(b.updated_at) - new Date(a.updated_at)
      })
      return items
    },
    getCalculationById(id) {
      console.log(id)
      // let temp = this.userCalculations
      //   .filter()
      console.log(this.$store.state.graphs)
    },
    applyChanges(status, index) {
      switch(status) {
        case '1': return {text: 'в обробцi', color: `${index <= 1 ? 'orange' : 'grey'}`};
        case '2': return {text: 'скасовано клієнтом', color: `${index <= 5 ? 'red' : 'grey'}`};
        case '3': return {text: 'вiдхилено', color: `${index <= 5 ? 'red' : 'grey'}`};
        case '4': return {text: 'iнший статус', color: `${index <= 2 ? 'purple' : 'grey'}` };
        case '5': return {text: 'схвалено', color: `${index <= 5 ? 'green' : 'grey'}`};
        
      }
    },
    toEdit(id) {
      this.$router.push({name: 'Редагувати', params: {id: id, edit: true}})
    },
    toDetail(id) {
      console.log(this.getGraphById(id)[0])
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
            console.log(response)
            this.loading = false
            if(response.data.length > 0)  {
              this.tabledata = response.data
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
    // async createTableData(object) {
    //   let arr = []
    //   await object.map(val => {
    //     let dataObj = {
    //       'initials': `${val.last_name} ${val.first_name} ${val.patronymic}`,
    //       'leasing_object': val.leasing_object,
    //       'leasing_amount': val.leasing_amount,
    //       'graph_type': this.switchValue(val.graph_type),
    //       'data': val.created_at.substr(0, 10),
    //       'request_status': val.status_id,
    //       'id': val.id
    //     }
    //     arr.push(dataObj)
    //   })
    //   this.tabledata = arr
    //     .sort(this.sortData)
    //     .reverse()
    // },
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