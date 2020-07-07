<template>
<div>
  <v-dialog 
    v-model="dialogToSend"
    max-width="390">
    <v-card class="graphs-to-delete">
      <v-card-title style="background: #424242;" class="white--text">
        Оберiть тип збереження
      </v-card-title>
      <v-card-text :style="`display: flex; justify-content: space-around; margin-top: 35px; ${emailField ? 'min-height: 140px;' : 'min-height: 90px;'} position: relative;`">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn 
              @click="emailField = false"
              v-on="on"
              icon 
              v-show="emailField"
              style="position: absolute; left: 0; top: -32px;">
              <v-icon size="28" v-text="'mdi-arrow-left-bold'"></v-icon>
            </v-btn>
          </template>
          <span>Назад</span>
        </v-tooltip>
        <span style="position: relative" v-show="!pdfDownloadLoading">
          <v-tooltip bottom>
            <template #activator="{ on }">
              <v-btn 
                fab 
                @click="emailField = !emailField; 
                  formatToSave = 'email'" 
                v-on="on" 
                dark 
                color="#e94949">
                <v-icon v-text="'mdi-email-send-outline'"></v-icon>
              </v-btn>
            </template>
            <span>Вiдправити на email</span>
          </v-tooltip>
          <v-card elevation="7" style="position: absolute; min-width: 250px!important; bottom: -10px; left: 50%; transform: translate(-50%, -50%);">
            <v-text-field
              v-show="emailField"
              v-model="emailToSend"
              @input="$v.emailToSend.$touch()"
              @blur="$v.emailToSend.$touch()"
              class="send-email-field"
              label="email"
              dense outlined>
              <template v-slot:append>
                <v-tooltip bottom>
                  <template #activator="{ on }">
                    <v-btn 
                      v-show="!$v.$invalid"
                      :loading="emailDownloadLoading"
                      @click="sendDataToUser()"
                      v-on="on" 
                      width="50" 
                      height="50" 
                      icon 
                      style="position: absolute; top: -5px; bottom: 2px; left: 255px;">
                      <v-icon size="32" v-text="'mdi-send-check'"></v-icon>
                    </v-btn>
                  </template>
                  <span>Надiслати</span>
                </v-tooltip>
              </template>
              <template v-slot:prepend-inner>
                <span v-if="emailToSendErr && emailToSendErr.length > 0" style="min-width: 200px;
                  color: #424242;
                  position: absolute;
                  display: block!important;
                  bottom: -22px;
                  font-size: 0.79rem;
                  left: 0;">
                  {{ emailToSendErr[0] }}
                </span>
              </template>
            </v-text-field>
          </v-card>
        </span>
        <span v-show="!emailField">
          <v-tooltip bottom>
            <template #activator="{ on }">
              <v-btn 
                :loading="pdfDownloadLoading"
                fab 
                @click="emailField = false; 
                  formatToSave = 'pdf'; 
                  sendDataToUser()" 
                v-on="on" 
                dark 
                color="#e94949">
                <v-icon v-text="'mdi-download'"></v-icon>
              </v-btn>
            </template>
            <span>Завантажити</span>
          </v-tooltip>
        </span>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-dialog
    v-model="leasingApplicationForm"
    max-width="600">
    <v-card v-if="leasingApplicationForm">
      <div class="complete-reg-form__title title">
        <div class="complete-reg-form__title-logo"></div>
        <span class="d-block title">Заявка на лiзинг</span>
      </div>
      <v-card-text class="pb-0">
        <v-row>
        <v-col cols="12" sm="6" md="6" lg="6" xl="6">
          <v-text-field
            v-model="lastName"
            @blur="$v.lastName.$touch()"
            @input="$v.lastName.$touch()" 
            :error-messages="lastNameErr"
            label="Прiзвище"
            dense outlined>
          </v-text-field>
          <v-text-field
            v-model="firstName"
            @blur="$v.firstName.$touch()"
            @input="$v.firstName.$touch()" 
            :error-messages="firstNameErr"
            label="Им`я"
            dense outlined>
          </v-text-field>
          <v-text-field
            v-model="patronymic"
            @blur="$v.patronymic.$touch()"
            @input="$v.patronymic.$touch()" 
            :error-messages="patronymicErr"
            label="Побатьковi"
            dense outlined>
          </v-text-field>
          <v-select
            v-model="region"
            @blur="$v.region.$touch()"
            @change="$v.region.$touch()" 
            :error-messages="regionErr"
            :items="select.regions"
            label="Область"
            dense outlined>
          </v-select>
          <v-text-field
            v-model="phone"
            @blur="isLegalPerson ? false : $v.phone.$touch()"
            @input="isLegalPerson ? false : $v.phone.$touch();
              applyMask()" 
            id="number"
            :error-messages="isLegalPerson ? legalPhone : phoneErr"
            label="Телефон"
            dense outlined>
          </v-text-field>
          <v-text-field
            v-model="email"
            @blur="isLegalPerson ? false : $v.email.$touch()"
            @input="isLegalPerson ? false : $v.email.$touch()" 
            :error-messages="isLegalPerson ? legalEmail : emailErr"
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
            id="creditPayment"
            label="Щомісячний платіж (за міс. грн) по кредитам та ін."
            dense outlined>
          </v-text-field>

          <div v-if="clientTypeId === 1">
            <v-text-field
              @input="parseToInt('inn');
                $v.legalInfo.inn.$touch()"
              @blur="$v.legalInfo.inn.$touch()"
              v-model="legalInfo.inn"
              :error-messages="innErr"
              id="inn"
              max="10"
              label="Iдентифiкацiйний код"
              dense outlined>
            </v-text-field>
            <v-text-field
              @input="parseToInt('monthlyIncome');
                $v.legalInfo.monthlyIncome.$touch()"
              @blur="$v.legalInfo.monthlyIncome.$touch()"
              v-model="legalInfo.monthlyIncome"
              :error-messages="monthlyIncomeErr"
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
              label="Мета придбання авто"
              dense outlined>
            </v-select>
          </div>

          <div v-if="clientTypeId === 2">
            <v-text-field
              @input="parseToInt('edrpou');
                $v.legalInfo.edrpou.$touch()"
              @blur="$v.legalInfo.edrpou.$touch()"
              v-model="legalInfo.edrpou"
              :error-messages="edrpouErr"
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
              label="Назва компанії"
              dense outlined>
            </v-text-field>
            <v-text-field
              @input="parseToInt('currencyBalance');
                $v.legalInfo.currencyBalance.$touch()"
              v-model="legalInfo.currencyBalance"
              @blur="$v.legalInfo.currencyBalance.$touch()"
              :error-messages="currencyBalanceErr"
              id="currencyBalance"
              label="Валютний баланс"
              dense outlined>
            </v-text-field>
            <v-text-field
              v-model="legalInfo.equity"
              @blur="$v.legalInfo.equity.$touch()"
              @input="$v.legalInfo.equity.$touch()" 
              :error-messages="equityErr"
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
        </v-row>
        <v-row>
          <v-col class="d-flex justify-center pb-6 pt-3">
            <span>
              <v-btn @click="submit()" 
                  :loading="loading"
                  class="d-block white--text" 
                  color="grey darken-3">
                <v-icon v-show="validData" v-text="'mdi-check-bold'">
                </v-icon>
                &nbsp;Вiдправити заявку
              </v-btn>
            </span>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-card-text style="display: flex; justify-content: flex-end;">
    <div style="width: 165px; display: flex; justify-content: center;">
      <span>
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn
              v-on="on"
              @click="openForm()"
              color="grey darken-2" 
              icon large dark>
              <v-icon size="22" dark>mdi-file-document-edit-outline</v-icon>
            </v-btn>
          </template>
          <span>Подати заявку на лізинг</span>
        </v-tooltip>
      </span>
      <span>
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn 
              @click="openDialogToSend()"
              v-on="on"
              color="grey darken-2" 
              icon large dark>
              <v-icon size="22" dark v-text="'mdi-download'"></v-icon>
            </v-btn>
          </template>
          <span>Зберегти рузультат розрахунку</span>
        </v-tooltip>
      </span>
    </div>
  </v-card-text>
</div>
</template>

<script>
import selectItems from './Calculator/selectItems.js'
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'
import axios from 'axios'
import { saveAs } from 'file-saver'

export default {
  props: ['graph', 'data'],
  mixins: [validationMixin],
  data: () => ({
    select: selectItems,
    leasingApplicationForm: false,
    commonErr: ['Обов`язкове поле'],
    pasteEvent: false,
    loading: false,
    creditPayment: null,
    dialogToSend: false,
    emailField: false,
    emailToSend: null,
    formatToSave: null,

    emailDownloadLoading: false,
    pdfDownloadLoading: false,

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
    _token: null
  }),
  validations() {
    return this.validationRules
  },
  computed: {
    validData() {
      return !this.$v.$invalid && this.$v.$dirty
    },
    validationRules() {
      let validateObj = {}

      this.emailField === true
        ? validateObj = {
          emailToSend: {required, email}
        }
        : false
      this.clientTypeId === 1 && this.leasingApplicationForm
        ? validateObj = Object.assign({},
          this.commonRules,
          this.individualPerson
        )
        : false

      this.clientTypeId === 2 && this.leasingApplicationForm
        ? validateObj = Object.assign({},
          this.commonRules,
          this.legalPerson
        )
        : false
      return validateObj
    },
    commonRules() {
      return {
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
    emailToSendErr() {
      let errors = []
      if (!this.$v.emailToSend || !this.$v.emailToSend.$error) return
      !this.$v.emailToSend.required && errors.push('Вкажiть email')
      !this.$v.emailToSend.email && errors.push('Невiрний email')
      return errors
    },
    lastNameErr() {
      if (!this.$v.lastName.$error) return
      return this.commonErr
    },
    firstNameErr() {
      if (!this.$v.firstName.$error) return
      return this.commonErr
    },
    patronymicErr() {
      if (!this.$v.patronymic.$error) return
      return this.commonErr
    },
    regionErr() {
      if (!this.$v.region.$error) return
      return this.commonErr
    },
    phoneErr() {
      if (!this.$v.phone.$error) return
      return ['Невiрний номер']
    },
    emailErr() {
      if (!this.$v.email.$error) return
      return ['Невiрний email']
    },
    creditPaymentErr() {
      if (!this.$v.creditPayment.$error) return
      return this.commonErr
    },
    innErr() {
      if (!this.$v.legalInfo.inn.$error) return
      return ['Повинно бути 10 цифр']
    },
    monthlyIncomeErr() {
      if (!this.$v.legalInfo.monthlyIncome.$error) return
      return this.commonErr
    },
    acquisitionTargetIdErr() {
      if (!this.$v.legalInfo.acquisitionTargetId.$error) return
      return this.commonErr
    },
    edrpouErr() {
      if (!this.$v.legalInfo.edrpou.$error) return
      return ['Повинно бути 8 цифр']
    },
    companyNameErr() {
      if (!this.$v.legalInfo.companyName.$error) return
      return this.commonErr
    },
    currencyBalanceErr() {
      if (!this.$v.legalInfo.currencyBalance.$error) return
      return this.commonErr
    },
    equityErr() {
      if (!this.$v.legalInfo.equity.$error) return
      return this.commonErr
    },
    legalEmail() {
      return ''
    },
    legalPhone() {
      return ''
    }
  },
  methods: {
    sendDataToUser() {
      let graph = this.data.result_data[this.switchGraphName(this.graph)]
      let calcData = this.data.request_data
      let rootCalcData = this.data

      let dataToSave = {
        mark: calcData.leasedAssertMark.name,
        model: calcData.leasedAssertModel.name,
        price: parseInt(calcData.leasingAmount.replace(/\s/g, '' )),
        term: calcData.leasingTerm,
        advance: calcData.advance,
        prepaid: graph['offer-advance'],
        avg: graph['offer-month-payment'],
        currency: calcData.currency,
        leasingRest: graph['offer-rest'],
        table: graph.graph,
        agg: {
          'payment-principal': graph['total-payment-principal'],
          'interest': graph['total-interest'],
          'payment': graph['total-payment'],
        },
        requestId: rootCalcData.request_id,
        _token: this.getCsrf()
      }
      if(this.formatToSave === 'email') {
        dataToSave.email = this.emailToSend
      }
      !this.$v.$invalid
        ? this.sendData(dataToSave)
        : this.highlightErrors()
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
    sendData(dataToSave) {
      if(this.formatToSave === 'email') {
        this.emailDownloadLoading = true
      } else if(this.formatToSave === 'pdf') {
        this.pdfDownloadLoading = true
      }
      this.loading = true
      axios
        .post('/calculation/getPdf', dataToSave, { responseType: 'blob' })
        .then(response => {
          console.log(response)
          if(this.formatToSave === 'pdf') saveAs(response.data, 'graph.pdf')
          if(this.formatToSave === 'email') {
            this.$notify({
              group: 'success',
              title: 'Графiк успiшно вiдправлено',
              text: '',
            })
          }
          if(this.formatToSave === 'email') {
            this.emailDownloadLoading = false
          } else if(this.formatToSave === 'pdf') {
            this.pdfDownloadLoading = false
          }
          setTimeout(() => {
            this.dialogToSend = false
          }, 1200)
        })
        .catch(error => {
          console.log(error.response)
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
          if(this.formatToSave === 'email') {
            this.emailDownloadLoading = false
          } else if(this.formatToSave === 'pdf') {
            this.pdfDownloadLoading = false
          }
        })
    },
    openDialogToSend() {
      this.dialogToSend = true
    },
    submit() {
      !this.$v.$invalid
      && this.$v.$dirty
        ? this.sendRequest()
        : this.highlightErrors()
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
        graphType: this.graphType,
        legalInfo: {
          creditPayment: this.legalInfo.creditPayment,

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
    requestObj(object) {
      let finalObj = null
      this.isLegalPerson === false
        ? finalObj = this.deleteLegalFields(object)
        : finalObj = this.deleteIndividualFields(object)
      return finalObj
    },
    sendRequest() {
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
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    openForm() {
      this.leasingApplicationForm = true
      this.getListItem()
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
    test() {
      console.log(this.$v)
    },
    getDefaultProperties() {
      this.agentId = this.$store.state.user.agent.id
      this.calculationId = this.data.id
      this.leasingObject = `${this.data.request_data.leasedAssertMark.name} ${this.data.request_data.leasedAssertModel.name}`
      this.clientTypeId = this.data.request_data.leasingClientType
      this.advance = this.data.request_data.advance
      this.leasingTerm = this.data.request_data.leasingTerm
      this.leasingAmount = this.data.request_data.leasingAmount
      this._token = this.getCsrf()
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
    switchGraphName(graphID) {
      switch (graphID) {
        case '1': return 'annuity'
        case '2': return 'even'
        case '3': return 'irregular'
      }
    }
  },
  watch: {
    dialogToSend(value) {
      if(value === false) {
        this.formatToSave = null
        this.currentGraphToDownload = null
      }
    },
    'legalInfo.currencyBalance': function(value) {
      if(!value) return value
      this.legalInfo.currencyBalance = parseInt(value)
    },
    'legalInfo.monthlyIncome': function(value) {
      if(!value) return value
      this.legalInfo.monthlyIncome = parseInt(value)
    },
    'creditPayment': function(value) {
      if(!value) return value
      this.legalInfo.creditPayment = parseInt(value)
    },
    graph(val) {
      this.graphType = val
      console.log(val)
    }
  },
  created() {
    this.getDefaultProperties()
  },
  mounted() {
    this.graphType = this.graph
    Object.keys(this.data.result_data).forEach(object => {
      if(this.data.result_data[object].graph) {
        this.data.result_data[object].graph[0].n = 0
      }
    })
  }
}
</script>

<style lang="scss">
  .send-email-field {
    .v-text-field__details {
      display: none!important;
    }
  }
</style>