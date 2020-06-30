<template>
<div class="col-12">
  <v-dialog
    v-model="leasingApplicationForm"
    max-width="600">
    <v-card>
      <v-card-title style="background: #424242;" class="white--text">Заявка на лiзинг</v-card-title>
      <div class="complete-reg-form__title title">
        <div class="complete-reg-form__title-logo"></div>
      </div>
      <v-card-text class="pb-0">
        <v-row>
          <v-col cols="12" class="pt-0 pb-0 calculator-data-graph">
            <div style="font-size: 1.1rem; font-weight: bold; letter-spacing: 0.03rem">Оберiть тип графiку платежiв</div>
            <v-radio-group 
              v-model="selectedGraph" 
              :error-messages="selectedGraphErr"
              row>
              <v-radio label="Класичний" value="even"></v-radio>
              <v-radio label="Ануїтет" value="annuity"></v-radio>
              <v-radio label="Індивідуальний" value="irregular"></v-radio>
            </v-radio-group>
          </v-col>
          <v-col cols="12" sm="6" md="6" lg="6" xl="6">
            <v-text-field
              v-model="lastName"
              @blur="$v.lastName.$touch()"
              @input="$v.lastName.$touch()"
              :error-messages="lastNameErr"
              itemColor="red darken-4"
              color="red darken-4"
              label="Прiзвище"
              dense outlined>
            </v-text-field>
            <v-text-field
              v-model="firstName"
              @blur="$v.firstName.$touch()"
              @input="$v.firstName.$touch()" 
              :error-messages="firstNameErr"
              itemColor="red darken-4"
              color="red darken-4"
              label="Им`я"
              dense outlined>
            </v-text-field>
            <v-text-field
              v-model="patronymic"
              @blur="$v.patronymic.$touch()"
              @input="$v.patronymic.$touch()" 
              :error-messages="patronymicErr"
              itemColor="red darken-4"
              color="red darken-4"
              label="Побатьковi"
              dense outlined>
            </v-text-field>
            <v-select
              v-model="region"
              @blur="$v.region.$touch()"
              @change="$v.region.$touch()" 
              :error-messages="regionErr"
              itemColor="red darken-4"
              color="red darken-4"
              :items="select.regions"
              label="Область"
              dense outlined>
            </v-select>
            <v-text-field
              v-model="phone"
              @blur="isLegalPerson ? false : $v.phone.$touch()"
              @input="isLegalPerson ? false : $v.phone.$touch();
                applyMask()" 
              :error-messages="isLegalPerson ? legalPhone : phoneErr"
              itemColor="red darken-4"
              color="red darken-4"
              id="number"
              label="Телефон"
              dense outlined>
            </v-text-field>
            <v-text-field
              v-model="email"
              @blur="isLegalPerson ? false : $v.email.$touch()"
              @input="isLegalPerson ? false : $v.email.$touch()" 
              :error-messages="isLegalPerson ? legalEmail : emailErr"
              itemColor="red darken-4"
              color="red darken-4"
              label="email"
              dense outlined>
            </v-text-field>
          </v-col>
          <v-col cols="12" sm="6" md="6" lg="6" xl="6">
            <v-text-field
              v-model="creditPayment"
              :error-messages="creditPaymentErr"
              @blur="$v.creditPayment.$touch()"
              @input="parseToInt('creditPayment');
                $v.creditPayment.$touch()"
              itemColor="red darken-4"
              color="red darken-4"
              id="creditPayment"
              label="Щомiсячний платіж (за міс. грн) по кредитам та ін."
              dense outlined>
            </v-text-field>
            <div v-if="clientTypeId === 1 && leasingApplicationForm">
              <v-text-field
                v-model="legalInfo.inn"
                @input="parseToInt('inn');
                  $v.legalInfo.inn.$touch()"
                @blur="$v.legalInfo.inn.$touch()"
                :error-messages="innErr"
                itemColor="red darken-4"
                color="red darken-4"
                id="inn"
                max="10"
                label="Iдентифiкацiйний код"
                dense outlined>
              </v-text-field>
              <v-text-field
                v-model="legalInfo.monthlyIncome"
                @input="parseToInt('monthlyIncome');
                  $v.legalInfo.monthlyIncome.$touch()"
                @blur="$v.legalInfo.monthlyIncome.$touch()"
                :error-messages="monthlyIncomeErr"
                itemColor="red darken-4"
                color="red darken-4"
                id="monthlyIncome"
                label="Середньомісячний дохід (грн)"
                dense outlined>
              </v-text-field>
              <v-select
                v-model="legalInfo.acquisitionTargetId"
                :items="listItem"
                item-text="target"
                item-value="id"
                @blur="$v.legalInfo.acquisitionTargetId.$touch()"
                @change="$v.legalInfo.acquisitionTargetId.$touch()" 
                :error-messages="acquisitionTargetIdErr"
                itemColor="red darken-4"
                color="red darken-4"
                label="Мета придбання авто"
                dense outlined>
              </v-select>
            </div>
            <div v-if="clientTypeId === 2 && leasingApplicationForm">
              <v-text-field
                v-model="legalInfo.edrpou"
                @input="parseToInt('edrpou');
                  $v.legalInfo.edrpou.$touch()"
                @blur="$v.legalInfo.edrpou.$touch()"
                :error-messages="edrpouErr"
                itemColor="red darken-4"
                color="red darken-4"
                id="edrpou"
                max="8"
                label="ЄДРПОУ"
                dense outlined>
              </v-text-field>
              <v-text-field
                v-model="legalInfo.companyName"
                @blur="$v.legalInfo.companyName.$touch()"
                @input="$v.legalInfo.companyName.$touch()" 
                :error-messages="companyNameErr"
                itemColor="red darken-4"
                color="red darken-4"
                label="Назва компанії"
                dense outlined>
              </v-text-field>
              <v-text-field
                v-model="legalInfo.currencyBalance"
                @input="parseToInt('currencyBalance');
                  $v.legalInfo.currencyBalance.$touch()"
                @blur="$v.legalInfo.currencyBalance.$touch()"
                :error-messages="currencyBalanceErr"
                itemColor="red darken-4"
                color="red darken-4"
                id="currencyBalance"
                label="Валютний баланс"
                dense outlined>
              </v-text-field>
              <v-text-field
                v-model="legalInfo.equity"
                @blur="$v.legalInfo.equity.$touch()"
                @input="$v.legalInfo.equity.$touch()" 
                :error-messages="equityErr"
                itemColor="red darken-4"
                color="red darken-4"
                label="Власный капiтал"
                dense outlined>
              </v-text-field>
              <!-- <v-text-field
                v-model="legalInfo.balances"
                label="Мета придбання авто"
                dense outlined>
              </v-text-field> -->
            </div>
          </v-col>
          <v-col cols="12" class="pt-0 pb-0 calculator-data-graph">
            <div style="font-size: 1.1rem; font-weight: bold; letter-spacing: 0.03rem; padding-bottom: 0.5rem;">Додати файли</div>
            <v-file-input
              v-model="docs"
              accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, image/*"
              label="Натиснiть"
              multiple
              outlined show-size dense>
            </v-file-input>
          </v-col>
        </v-row>
        <v-row>
          <v-col class="d-flex justify-center pb-6 pt-3">
            <span>
              <v-btn @click="submit()" 
                  :loading="loading"
                  class="d-block white--text" 
                  color="grey darken-3">
                <v-icon v-if="validData" v-text="'mdi-check-bold'"></v-icon>
                &nbsp;Вiдправити заявку
              </v-btn>
            </span>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-card class="pb-4" min-height="300" elevation="12">
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
          color="#e65048" 
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
        :custom-sort="customSort"
        class="elevation-1">
        <template v-slot:item.test>
          <span style="white-space: nowrap">{{ $store.state.user.agent.ab_size }}</span>
        </template>
        <template v-slot:item.request_data.leasedAssertModel.name="{ item }">
          <span style="white-space: nowrap">{{ item.request_data.leasedAssertModel.name }}</span>
        </template>
        <template v-slot:item.request_data="{ item }">
          <span style="white-space: nowrap">
            {{ 
              parseInt(item.request_data.leasingAmount.replace(/ /g, '' ))
                .toLocaleString()
                .replace(/,/g, ' ')
            }}
          </span>
        </template>
        <template v-slot:item.created_at="{ item }">
          <span style="white-space: nowrap">
            {{ item.created_at.substring(0, 10) }}
          </span>
        </template>
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
                      color="red darken-2"
                      >
                      mdi-pencil-outline
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
                      color="red darken-2"
                      >
                      mdi-file-find-outline
                    </v-icon>
                  </v-btn>
                </span>
                </template>
              <span>Переглянути</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click="openForm(item.id)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="red darken-2"
                      >
                      mdi-file-document-edit-outline
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Подати заявку на лiзинг</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click=""
                    v-on="on"
                    icon>
                    <v-icon
                      color="grey darken-2"
                      >
                      mdi-email-send-outline
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Вiдправити на пошту</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click=""
                    v-on="on"
                    icon>
                    <v-icon
                      color="grey darken-2"
                      >
                      mdi-download
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Завантажити</span>
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
import selectItems from './selectItems.js'
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'
import axios from 'axios'

export default {
  mixins: [validationMixin],
  data:() => ({
    /* v-dialog data  */
    select: selectItems,
    leasingApplicationForm: false,
    commonErr: ['Обов`язкове поле'],
    pasteEvent: false,
    loading: false,
    creditPayment: null,
    selectedGraph: null,

    docs: null,

    listItem: null,

    agentId: null,
    calculationId: null,
    clientTypeId: null,
    lastName: null,
    firstName: null,
    patronymic: null,
    region: null,
    phone: null,
    email: null,
    leasingObject: null,
    advance: null,
    leasingTerm: null,
    leasingAmount: null,
    graphType: null,
    legalInfo: {
      creditPayment: null,
      inn: null,
      monthlyIncome: null,
      acquisitionTargetId: null,
      edrpou: null,
      companyName: null,
      currencyBalance: null,
      equity: null,
      // balances: null, // - waiting... (пока не отправляй)
    },
    _token: null,

    tableHeader: [
      { text: 'Код розрахунку', value: 'request_id', align: 'start'},
      { text: 'Марка', value: 'request_data.leasedAssertMark.name', align: 'center'},
      { text: 'Модель', value: 'request_data.leasedAssertModel.name', align: 'center' },
      { text: 'Цiна', value: 'request_data', align: 'center' },
      { text: 'АВ, %', value: 'test', align: 'center' },
      { text: 'Дата', value: 'created_at', align: 'center' },
      { text: 'Дiї', value: 'actions', align: 'center', sortable: false },
    ],
    tabledata: [],
    search: '',
    attachedFiles: null,
    btnLoading: false,
    showDocumentAttachment: false
  }),
  validations() {
    return this.validationRules
  },
  computed: {
    validData() {
      return !this.$v.$invalid && this.$v.$dirty
    },
    validationRules() {
      let validateObj = null
      !this.leasingApplicationForm 
        ? validateObj = {}
        : this.clientTypeId === 1 
          ? validateObj = Object.assign({},
            this.commonRules,
            this.individualPerson
          )
          : this.clientTypeId === 2
            ? validateObj = Object.assign({},
              this.commonRules,
              this.legalPerson
            )
            : false
      return validateObj
    },
    commonRules() {
      return {
        selectedGraph: { required },
        lastName: { required },
        firstName: { required },
        patronymic: { required },
        region: { required },
        creditPayment: { required },
      }
    },
    individualPerson() {
      return {
        phone: { 
          minLength: value => {
            if(value == null) return false
            return value.replace(/[^\d]/g, '').length === 12
          }
        },
        email: { email, required },
        legalInfo: {
          inn: { 
            minLength: value => {
              if(value == null) return false
              return value.length === 10
            }
           }, 
          monthlyIncome: { required },
          acquisitionTargetId: { required },
        }
      }
    },
    legalPerson() {
      return {
        legalInfo: {
          edrpou: { 
            minLength: value => {
              if(value == null) return false
              return value.length === 8
            }
          },
          companyName: { required },
          currencyBalance: { required },
          equity: { required },
        }
      }
    },
    isLegalPerson() {
      return this.clientTypeId === 2
    },
    /* error handlers */
    lastNameErr() {
      if (!this.$v.lastName) return
      if (!this.$v.lastName.$error) return
      return this.commonErr
    },
    selectedGraphErr() {
      if (!this.$v.selectedGraph) return
      if (!this.$v.selectedGraph.$error) return
      return this.commonErr
    },
    firstNameErr() {
      if (!this.$v.firstName) return
      if (!this.$v.firstName.$error) return
      return this.commonErr
    },
    patronymicErr() {
      if (!this.$v.patronymic) return
      if (!this.$v.patronymic.$error) return
      return this.commonErr
    },
    regionErr() {
      if (!this.$v.region) return
      if (!this.$v.region.$error) return
      return this.commonErr
    },
    phoneErr() {
      if (!this.$v.phone) return
      if (!this.$v.phone.$error) return
      return ['Невiрний номер']
    },
    emailErr() {
      if (!this.$v.email) return
      if (!this.$v.email.$error) return
      return ['Невiрний email']
    },
    creditPaymentErr() {
      if (!this.$v.creditPayment) return
      if (!this.$v.creditPayment.$error) return
      return this.commonErr
    },
    innErr() {
      if (!this.$v.legalInfo.inn) return
      if (!this.$v.legalInfo.inn.$error) return
      return ['Повинно бути 10 цифр']
    },
    monthlyIncomeErr() {
      if (!this.$v.legalInfo.monthlyIncome) return
      if (!this.$v.legalInfo.monthlyIncome.$error) return
      return this.commonErr
    },
    acquisitionTargetIdErr() {
      if (!this.$v.legalInfo.acquisitionTargetId) return
      if (!this.$v.legalInfo.acquisitionTargetId.$error) return
      return this.commonErr
    },
    edrpouErr() {
      if (!this.$v.legalInfo.edrpou) return
      if (!this.$v.legalInfo.edrpou.$error) return
      return ['Повинно бути 8 цифр']
    },
    companyNameErr() {
      if (!this.$v.legalInfo.companyName) return
      if (!this.$v.legalInfo.companyName.$error) return
      return this.commonErr
    },
    currencyBalanceErr() {
      if (!this.$v.legalInfo.currencyBalance) return
      if (!this.$v.legalInfo.currencyBalance.$error) return
      return this.commonErr
    },
    equityErr() {
      if (!this.$v.legalInfo.equity) return
      if (!this.$v.legalInfo.equity.$error) return
      return this.commonErr
    },
    legalEmail() {
      return ''
    },
    legalPhone() {
      return ''
    },

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
    customSort(items) {
      items
        .sort((a, b) => {
          console.log(a.created_at, b.created_at)
          return new Date(b.updated_at) - new Date(a.updated_at)
      })
      return items
    },
    object() {
      return {
        agentId: this.agentId,
        calculationId: this.calculationId,
        clientTypeId: this.clientTypeId,
        lastName: this.lastName,
        firstName: this.firstName,
        patronymic: this.patronymic,
        region: this.region,
        phone: this.phone,
        email: this.email,
        leasingObject: this.leasingObject,
        advance: this.advance,
        leasingTerm: this.leasingTerm,
        leasingAmount: this.leasingAmount,
        graphType: this.selectedGraph,
        legalInfo: {
          creditPayment: this.creditPayment,

          inn: this.legalInfo.inn,
          monthlyIncome: this.legalInfo.monthlyIncome,
          acquisitionTargetId: this.legalInfo.acquisitionTargetId,
          
          edrpou: this.legalInfo.edrpou,
          companyName: this.legalInfo.companyName,
          currencyBalance: this.legalInfo.currencyBalance,
          equity: this.legalInfo.equity,
          // balances: this.legalInfo.balances, // - waiting... (пока не отправляй)
        },
        _token: this._token
      }
    },
    clearObject() {
      this.creditPayment = null,
      this.selectedGraph = null,
      this.lastName = null,
      this.firstName = null,
      this.patronymic = null,
      this.region = null,
      this.phone = null,
      this.email = null,
      this.graphType = null,
      this.legalInfo = {
        creditPayment: null,
        inn: null,
        monthlyIncome: null,
        acquisitionTargetId: null,
        edrpou: null,
        companyName: null,
        currencyBalance: null,
        equity: null,
      }
    },
    applyMask() {
			const el = document.getElementById('number')
			const event = new Event('input', {bubbles: true})
			const mask = '+38 (0##) ### - ## - ##'
			const sign = '#'

			const numLengthRe = /[^#\d+]/g
			const notDigit = /[^\d]/g

			const firstIndex = mask.indexOf(sign)
			const countryCode = mask.slice(0, firstIndex)
			const numLength = mask.slice(firstIndex).replace(numLengthRe, '').length
			const number = this.phone.replace(countryCode, '').replace(numLengthRe, '')
			const cCpresent = this.phone.indexOf(countryCode)

			let splitMask = mask.split('')
			let indexes = []

			splitMask.forEach((val, i) => {
				val === sign ? indexes.push(i) : false
			})
			let fillMask = (val) => {
				val.split('').forEach((val, i) => {
						indexes[i] ? splitMask[indexes[i]] = val : false
				})
			}
			if(number.length >= numLength && cCpresent !== -1 && this.pasteEvent) {
				fillMask(
					this.phone
						.replace(countryCode, '')
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if(number.length >= numLength && cCpresent === -1) {
				fillMask(
					this.phone
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if (this.phone.length > 1){
				fillMask(
					this.phone
						.slice(firstIndex)
						.replace(notDigit, '')
				)
			} else if (this.phone.length <= 1) {
				fillMask(
					this.phone
						.replace(notDigit, '')
				)
			}
			const joinMask = splitMask.join('').replace(/[^\d]+$/, '')
			if(el.value !== joinMask) {
				el.value = joinMask
				el.setSelectionRange(splitMask.indexOf(sign), splitMask.indexOf(sign))
				el.dispatchEvent(event)
			}
			this.pasteEvent = false
		},
    parseToInt(id) {
      let input = new Event('input', {bubbles: true})
      let el = document.getElementById(id)
      let value = el.value
      let intVal = el.value.replace(/[^\d]/g, '')
      if(value !== intVal) {
        el.value = intVal
        el.dispatchEvent(input)
      }
    },
    openForm(id) {
      this.graphType = this.getGraphById(id)[0]
      this.getDefaultProperties()
      this.leasingApplicationForm = true
      console.log(this.$v)
    },
    submit() {
      console.log(!this.$v.$invalid)
      console.log(this.$v.$dirty)
      !this.$v.$invalid
      && this.$v.$dirty
        ? this.sendRequest()
        : this.highlightErrors()
    },
    sendRequest() {
      console.log('send request')
      this.loading = true
      let object = this.object()
      console.log(this.requestObj(object))
      axios.
        post('/leasing-reqeust/create', this.requestObj(object))
        .then(response => {
          console.log(response)
          this.$notify({
            group: 'success',
            title: 'Заявку успiшно вiдправленно',
            text: '',
          })
          this.loading = false
          setTimeout(() => {
            this.clearObject()
            this.$v.$reset()
            this.leasingApplicationForm = false
          }, 5000);
        })
        .catch(error => {
          console.log(error.response)
          this.loading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
        })
    },
    requestObj(object) {
      let finalObj = null
      this.isLegalPerson === false
        ? finalObj = this.deleteLegalFields(object)
        : finalObj = this.deleteIndividualFields(object)
      return finalObj
    },
    deleteIndividualFields(object) {
      if(object.phone === null || object.phone === 'undefined') delete object.phone
      if(object.email === null || object.email === 'undefined') delete object.email
      delete object.legalInfo.inn
      delete object.legalInfo.monthlyIncome
      delete object.legalInfo.acquisitionTargetId
      return object
    },
    deleteLegalFields(object) {
      delete object.legalInfo.edrpou,
      delete object.legalInfo.companyName
      delete object.legalInfo.currencyBalance
      delete object.legalInfo.equity
      return object
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
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
    getDefaultProperties() {
      this.agentId = this.$store.state.user.agent.id
      this.calculationId = this.graphType.id
      this.leasingObject = `${this.graphType.request_data.leasedAssertMark.name} ${this.graphType.request_data.leasedAssertModel.name}`
      this.clientTypeId = this.graphType.request_data.leasingClientType
      this.advance = this.graphType.request_data.advance
      this.leasingTerm = this.graphType.request_data.leasingTerm
      this.leasingAmount = this.graphType.request_data.leasingAmount
      this._token = this.getCsrf()
    },
    getListItem() {
      axios
        .get('/getAcquisitionTargets')
        .then(response => {
          console.log(response)
          this.listItem = response.data
        })
        .catch(error => {
          console.log(error.response)
        })
    },

    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    getUserCalculations() {
      this.loading = true
      this.tabledata = []
      if(this.userData){
        const agentId = this.$store.state.user.agent.id
        axios
          .get(`calculations/agent/${agentId}`)
          .then(response => {
            console.log('*************')
            console.log(response)
            console.log('*************')
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
    test() {
      console.log(this.tabledata)
    },
    sortData(a, b) {
      return new Date(b.created_at) - new Date(a.created_at)
    },
    // async createTableData(object) {
    //   let arr = []
    //   await object.map(val => {
    //     let dataObj = {
    //       'Тип': val.request_data.leasingObjectType.label,
    //       'Марка': val.request_data.leasedAssertMark.name,
    //       'Модель': val.request_data.leasedAssertModel.name,
    //       'amount': val.request_data.leasingAmount,
    //       'Дата': val.created_at.substr(0, 10),
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
    leasingApplicationForm() {
      this.clearObject()
      this.$v.$reset()
    },
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
    console.log(this.$v)
    this.getListItem()
    this.$store.state.user.agent 
      ? this.getUserCalculations()
      : false
  },
}
</script>

<style lang="scss">
  .calculator-data-leasing-obj-type {
    font-weight: bold;
    font-size: 0.91rem!important;
  }
  .calculator-custom-title {
    .v-text-field {
      padding: 0!important;
    }
    .v-btn {
      border-radius: 0!important;
    }
  }
  .calculator-data-graph {
    .v-input--selection-controls {
      margin-top: 8px!important;
    }
  }
</style>