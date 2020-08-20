<template>
<div class="col-12 pl-0 pr-0 pt-0">
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
      <v-card-text style="display: flex; justify-content: space-between">
        <span><v-btn :loading="requestToRewardLoading" color="#e94949" dark @click="sendRequestToReward()">Так</v-btn></span>
        <span><v-btn color="#333333" dark @click="dialogToAgentReward = false">Нi</v-btn></span>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-dialog
    v-model="leasingApplicationForm"
    max-width="520">
    <v-card style="position: relative;">
      <v-card-title style="background: #424242; position: relative" class="white--text">
        <span 
          :style="`max-width: 90%;${$vuetify.breakpoint.xs ? 'font-size: 1rem;' : ''}`"
          :class="`d-block ${$vuetify.breakpoint.xs? '' : 'title'}`">Заявка вiд розрахунку на 
          <span class="font-weight-bold">{{ reqObj.leasing_object }}</span>
        </span>
        <v-btn @click="leasingApplicationForm = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon v-text="'mdi-close'" color="white"></v-icon></v-btn>
      </v-card-title>
      <div class="complete-reg-form__title title">
        <div class="complete-reg-form__title-logo"></div>
      </div>

      <v-card-text class="pb-0">
        <v-row>
          <v-row>
            <v-col cols="12" class="pt-0 pb-0 calculator-data-graph">
              <div style="font-size: 1.1rem; font-weight: bold; letter-spacing: 0.03rem">Тип графiку платежiв</div>
              <v-radio-group 
                v-model="reqObj.graph_type" 
                :column="$vuetify.breakpoint.xs"
                :dense="$vuetify.breakpoint.xs">
                <v-radio 
                  class="pl-2" 
                  label="Класичний" 
                  value="even" 
                  :disabled="reqObj.graph_type !== 'even'">
                </v-radio>
                <v-radio 
                  class="pl-2" 
                  label="Ануїтет" 
                  value="annuity" 
                  :disabled="reqObj.graph_type !== 'annuity'">
                </v-radio>
                <v-radio 
                  class="pl-2" 
                  label="Індивідуальний" 
                  value="irregular" 
                  :disabled="reqObj.graph_type !== 'irreular'">
                </v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row style="width: 100%;">
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-if="reqObj.client_type_id === 2"
                :value="reqObj.legal_info.company_name"
                label="Назва компанії"
                dense outlined readonly>
              </v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-if="reqObj.client_type_id === 2"
                :value="reqObj.legal_info.edrpou"
                label="ЄДРПОУ"
                dense outlined readonly>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row style="width: 100%;">
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                :value="reqObj.last_name"
                label="Прiзвище"
                dense outlined readonly>
              </v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                :value="reqObj.first_name"
                label="Iм`я"
                dense outlined readonly>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row style="width: 100%;">
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                :value="reqObj.patronymic"
                label="По батьковi"
                dense outlined readonly>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row style="width: 100%;">
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <!-- <v-text-field
                :value="reqObj.legal_info.creditPayment"
                label="Щомісячний платіж (за міс. грн) по кредитам та ін."
                dense outlined readonly>
              </v-text-field> -->
                <v-text-field
                  v-if="reqObj.client_type_id === 1"
                  :value="reqObj.legal_info.inn"
                  label="IПН"
                  dense outlined readonly>
                </v-text-field>
                <!-- <v-text-field
                  :value="reqObj.legal_info.monthlyIncome"
                  label="Середньомісячний дохід (грн)"
                  dense outlined readonly>
                </v-text-field> -->
                <!-- <v-text-field
                  :value="reqObj.legal_info.acquisitionTargetId"
                  label="Мета придбання авто"
                  dense outlined readonly>
                </v-text-field> -->
                <!-- <v-text-field
                  :value="reqObj.legal_info.currencyBalance"
                  label="Валютний баланс"
                  dense outlined readonly>
                </v-text-field> -->
                <!-- <v-text-field
                  :value="reqObj.legal_info.equity"
                  label="Власный капiтал"
                  dense outlined readonly>
                </v-text-field> -->
                <!-- <v-text-field
                  v-model="legalInfo.balances"
                  label="Мета придбання авто"
                  dense outlined>
                </v-text-field> -->
            </v-col>
          </v-row>
          <v-row style="width: 100%;">
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <!-- <v-text-field
                :value="reqObj.region"
                label="Область"
                dense outlined readonly>
              </v-text-field> -->
              <v-text-field
                v-if="reqObj.phone"
                :value="reqObj.phone"
                label="Телефон"
                dense outlined readonly>
              </v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-if="reqObj.email"
                :value="reqObj.email"
                label="Email"
                dense outlined readonly>
              </v-text-field>
            </v-col>
          </v-row>
          
          <v-row 
            v-if="reqObj.documents && reqObj.documents.constructor === Object"
            style="width: 100%;">
            <v-col cols="12" class="pt-0 pb-0 calculator-data-graph">
              <input id="insurance" class="toggle" type="checkbox">
              <label for="insurance" class="lbl-toggle" style="background: white; color: #5f6368; padding-top: 0rem; margin-bottom: 0;">
                Документи
              </label>
                <div class="collapsible-content">
                  <div class="content-inner">

                  <div class="document-list" v-if="reqObj.client_type_id === 2">
                    <div 
                      v-for="(item, key) in legalDocs"
                      :key="key">
                      <span v-if="reqObj.documents[item.prop]" :style="reqObj.documents[item.prop] ? 'color: black;' : ''">
                        {{ item.text }}
                      </span>
                      <div v-if="reqObj.documents[item.prop] && reqObj.documents[item.prop].length > 0">
                        <div 
                          class="document-line-wrapper"
                          style="padding: 0 10px; font-size: 0.72rem;"
                          v-for="(v, key) in reqObj.documents[item.prop]"
                          :key="key">
                          <span>
                            {{ v.text }}
                          </span>
                          <span style="color: black;">{{ `(${(v.size / 1000).toFixed(2)} - kb)` }}</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="document-list" v-if="reqObj.client_type_id === 1">
                    <div 
                      v-for="(item, key) in personDocs"
                      :key="key">
                      <span v-if="reqObj.documents[item.prop]" :style="reqObj.documents[item.prop] ? 'color: black;' : ''">
                        {{ item.text }}
                      </span>
                      <div v-if="reqObj.documents[item.prop] && reqObj.documents[item.prop].length > 0">
                        <div 
                          class="document-line-wrapper"
                          style="padding: 0 10px; font-size: 0.72rem;"
                          v-for="(v, key) in reqObj.documents[item.prop]"
                          :key="key">
                          <span>
                            {{ v.text }}
                          </span>
                          <span style="color: black;">{{ `(${(v.size / 1000).toFixed(2)} - kb)` }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>
          <v-row style="width: 100%;">
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
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-card class="pb-4" min-height="300" elevation="12">
  <v-card-text class="d-block grey darken-3 white--text" style="font-size: 1.25rem">
    <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-clipboard-list-outline'"></v-icon>
    Заявки на лiзинг
  </v-card-text>
    <v-progress-linear
      :height="3"
      :active="loading"
      :indeterminate="loading"
      absolute
      top
      color="red lighten-1">
    </v-progress-linear>
    <v-card-text
      v-if="!loading && !tableDataPresent"
      absolute
      style="font-size: 1.3rem; padding-top: 90px; text-align: center;"
      class="d-block text-center">
      (Iсторiя заявок на лiзинг порожня)
    </v-card-text>
    <v-card-text 
      v-show="tableDataPresent" 
      class="calculations-table">
      <v-card-title v-if="!$vuetify.breakpoint.xs" class="headline mb-7">
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
        :items="$store.state.leasingRequests"
        item-key="updated_at"
        :custom-sort="customSort"
        :items-per-page="10"
        :class="`elevation-1 leasing-application-table ${$vuetify.breakpoint.xs ? 'small' : ''}`">
        <template v-slot:item.graph="{ item }">
          <v-btn x-small style="white-space: nowrap; text-transform: lowercase; display: flex; text-align: center;" small dark color="grey darken-3" :to="{ name: 'Графiки', params: {data: item.calculation, graph: item.graph_type, preview: true} }">{{ switchValue(item.graph_type) }}</v-btn>
        </template>
        <template v-slot:item.leasing_amount="{ item }">
          <span style="white-space: nowrap"> {{ item.leasing_amount }} </span>
        </template>
        <template v-slot:item.client="{ item }">
          <span style="white-space: nowrap;">{{ item.client }}</span>
        </template>
        <template #item.actions="{ item }">
          <div class="d-flex justify-center">
            <v-tooltip bottom>
              <template #activator="{ on }">
                <v-btn
                  v-show="parseInt(item.status_id) === 5 && $store.state.userHasNeccessaryFields || parseInt(item.status_id) === 5 && !$store.state.userHasNeccessaryFields"
                  @click="showDialogToAgentReward(item)"
                  v-on="on"
                  icon>
                  <v-icon
                    color="red lighten-1">
                    mdi-sack-percent
                  </v-icon>
                </v-btn>
                <v-icon
                  v-show="item.status_id != 5"
                  v-on="on"
                  style="padding: 6px"
                  >
                  mdi-sack-percent
                </v-icon>
              </template>
              <span v-show="parseInt(item.status_id) === 5 && $store.state.userHasNeccessaryFields">Подати заявку на виплату АВ</span>
              <span v-show="parseInt(item.status_id) !== 5">Ви не можете подати заявку на винагороду з даним статусом заявки на лізинг</span>
              <span v-show="parseInt(item.status_id) === 5 && !$store.state.userHasNeccessaryFields">Для отримання АВ необхiдно заповнити всi данi профiлю</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <v-btn 
                  @click="toDetail(item.id)"
                  v-on="on"
                  icon>
                  <v-icon
                    color="grey darken-2">
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
import { legalDocs, personDocs } from './utils/doc-props.js'

export default {
  data:() => ({
    search: '',
    leasingApplicationForm: false,
    dialogToAgentReward: false,
    requestToRewardLoading: false,
    tableHeader: [
      { text: 'Клієнт', value: 'client', align: 'start'},
      { text: 'Предмет лiзингу', value: 'leasing_object', align: 'center'},
      { text: 'Цiна, грн', value: 'leasing_amount', align: 'center' },
      { text: 'АВ, грн', value: 'agent_reward', align: 'center' },
      { text: 'Тип графiку', value: 'graph', align: 'center' },
      { text: 'Дата', value: 'created_at', align: 'center' },
      { text: 'Статус', value: 'request_status', align: 'center', width: 120 },
      { text: 'Дiї', value: 'actions', align: 'center', sortable: false },
    ],
    
    legalDocs: legalDocs,
    personDocs: personDocs,
    loading: false,
    progressDivision: 5,

    requestIdToReward: null,
    agentIdToReward: null,

    // request detail data
    reqObj: {
      calculation_id: null,
      email: null,
      first_name: null,
      last_name: null,
      graph_type: null,
      documents: null,
      legal_info: {
        company_name: null,
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
      return this.$store.state.leasingRequests.length > 0
    },
  },
  methods: {
    sendRequestToReward() {
      this.requestToRewardLoading = true
      let obj = {agentId: this.agentIdToReward, leasingRequestId: this.requestIdToReward}
      axios
        .post('/agent-commission/create', obj)
        .then(() => {
          this.requestToRewardLoading = false
          this.$notify({
            group: 'success',
            title: 'Заявку успiшно вiдправлено',
            text: '',
          })
          setTimeout(() => {
            this.dialogToAgentReward = false
          }, 700)
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          this.requestToRewardLoading = false
          console.log(error.response)
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.data.errors.leasingRequestId}`,
          })
        })
    },
    showDialogToAgentReward(item) {
      if(this.$store.state.userHasNeccessaryFields) {
        this.requestIdToReward = item.id
        this.agentIdToReward = item.agent_id
        this.dialogToAgentReward = true
      } else return
    },
    customSort(items) {
      items
        .sort((a, b) => {
          return new Date(b.updated_at) - new Date(a.updated_at)
      })
      return items
    },
    applyChanges(status, index) {
      switch(status) {
        case '0': return {text: 'Відхилена', color: `${index <= 5 ? 'red' : 'grey'}`};
        case '1': return {text: 'В роботі', color: `${index <= 1 ? 'orange' : 'grey'}`};
        case '2': return {text: 'Погоджено', color: `${index <= 2 ? 'orange' : 'grey'}`};
        case '3': return {text: 'Договір підписано', color: `${index <= 3 ? 'green' : 'grey'}`};
        case '4': return {text: 'Отримано аванс', color: `${index <= 4 ? 'green' : 'grey'}` };
        case '5': return {text: 'Відвантажено', color: `${index <= 5 ? 'green' : 'grey'}`};
      }
    },
    toEdit(id) {
      this.$router.push({name: 'Редагувати', params: {id: id, edit: true}})
    },
    toDetail(id) {
      this.leasingApplicationForm = true
      let object = this.getGraphById(id)[0]
      console.log(object)
      this.reqObj = Object.assign({}, this.reqObj, object)
      console.log(this.reqObj)
    },
    getGraphById(id) {
      return this.$store.state.graphs
        .filter(val => val.id === id)
    },
    getUserCalculations() {
      this.loading = true
      if(this.userData){
        const agentId = this.$store.state.user.agent.id
        axios
          .get(`/leasing-reqeust/agent/${agentId}`)
          .then(response => {
            this.loading = false
            if(response.data.length > 0)  {
              this.$store.commit('addLeasingRequests', response.data)
              this.$store.commit('addGraph', response.data)
            } else {
              this.tabledata = []
            }
          })
          .catch(error => {
            this.$catchStatus(error.response.status)
            console.log(error.response)
            this.loading = false
            this.$notify({
              group: 'error',
              title: 'Помилка',
              text: `${error.response.status} \n ${error.response.data.message}`,
            })
          })
        }
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

<style lang="scss">
  .leasing-application-table {
    &.small {
      td {
        min-height: 28px;
      }
      td:last-child {
        margin-bottom: 23px;
      }
    }
  }
</style>