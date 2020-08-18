<template>
<div>
  <v-dialog 
    v-model="dialogToSend"
    max-width="490">
    <v-card class="graphs-to-delete">
      <v-card-title style="background: #424242; position: relative" class="white--text">
        <v-btn @click="dialogToSend = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon color="white" v-text="'mdi-close'"></v-icon></v-btn>
        {{ formatToSave === 'email' ? 'Вiдправити на email' : 'Зберегти'}}
      </v-card-title>
      <v-card-text>
        <div style="margin-top: 23px;">
          <span style="line-height: 2rem; font-size: 0.93rem; color: black;">Оберiть тип графiку</span>
        </div>
        <v-checkbox
          v-if="currentGraphToDownload && currentGraphToDownload.result_data && currentGraphToDownload.result_data.hasOwnProperty('even')"
          value="even"
          v-model="graphName"
          color="red darken-4">
          <template v-slot:label>
            <span :style="graphName.indexOf('even') !== -1 ? 'color: black;' : ''" class="graph-label-to-download">Класичний</span>
          </template>
        </v-checkbox>
        <v-checkbox
          v-if="currentGraphToDownload && currentGraphToDownload.result_data && currentGraphToDownload.result_data.hasOwnProperty('annuity')"
          v-model="graphName"
          value="annuity"
          color="red darken-4">
          <template v-slot:label>
            <span :style="graphName.indexOf('annuity') !== -1 ? 'color: black;' : ''" class="graph-label-to-download">Ануїтет</span>
          </template>
        </v-checkbox>
        <v-checkbox
          v-if="currentGraphToDownload && currentGraphToDownload.result_data && currentGraphToDownload.result_data.hasOwnProperty('irregular')"
          value="irregular"
          v-model="graphName"
          color="red darken-4">
          <template v-slot:label>
            <span :style="graphName.indexOf('irregular') !== -1 ? 'color: black;' : ''" class="graph-label-to-download">Iндивiдуальний</span>
          </template>
        </v-checkbox>
        <v-text-field 
          v-if="formatToSave === 'email'"
          class="email-to-send"
          @input="$v.emailToSend.$touch()"
          @blur="$v.emailToSend.$touch()"
          :error-messages="emailToSendErr"
          v-model="emailToSend"
          label="Email"
          outlined dense>
        </v-text-field>
        <v-divider class="mt-0"></v-divider>
        <v-btn 
          style="border-radius: 0; text-transform: capitalize; border-color: rgb(224, 77, 69); font-size: 1rem; color: white;"
          class="send-graph-btn"
          @click="sendGraph()" 
          color="#e04d45"
          :loading="loading"
          :disabled="graphName.length === 0">
          {{ formatToSave === 'email' ? 'Вiдправити' : 'Зберегти' }}
        </v-btn>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-dialog
    v-model="leasingApplicationForm"
    max-width="550">
    <v-card v-if="leasingApplicationForm">
      <v-card-title style="background: #424242; position: relative" class="white--text">
        Заявка на лiзинг
        <v-btn @click="leasingApplicationForm = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon v-text="'mdi-close'" color="white"></v-icon></v-btn>
      </v-card-title>
      <div class="complete-reg-form__title title">
        <div class="complete-reg-form__title-logo"></div>
      </div>
      <v-card-text class="pb-0">
        <v-row>
          <v-col cols="12" class="pt-0 pb-0 calculator-data-graph">
            <div style="font-size: 0.9rem; font-weight: bold; letter-spacing: 0.03rem">Оберiть тип графiку платежiв</div>
            <v-radio-group 
              v-model="selectedGraph" 
              :error-messages="selectedGraphErr"
              row>
              <v-radio v-if="data && data.result_data && data.result_data && data.result_data.hasOwnProperty('even')" label="Класичний" value="even"></v-radio>
              <v-radio v-if="data && data.result_data && data.result_data && data.result_data.hasOwnProperty('annuity')"  label="Ануїтет" value="annuity"></v-radio>
              <v-radio v-if="data && data.result_data && data.result_data && data.result_data.hasOwnProperty('irregular')"  label="Індивідуальний" value="irregular"></v-radio>
            </v-radio-group>
          </v-col>
          <v-row>
            <v-col 
              cols="12" md="6" 
              v-if="clientTypeId === 2 && leasingApplicationForm"
              class="pt-0 pb-0">
              <v-text-field
                @input="parseToInt('edrpou');
                  $v.legalInfo.edrpou.$touch(); getEdropu()"
                @blur="$v.legalInfo.edrpou.$touch();"
                v-model="legalInfo.edrpou"
                :error-messages="edrpouErr"
                v-mask="'########'"
                id="edrpou"
                max="8"
                label="ЄДРПОУ"
                dense outlined>
                <template v-slot:append>
                  <v-btn 
                    :loading="edrpouLoading"
                    icon>
                  </v-btn>
                </template>
              </v-text-field>
            </v-col>
            <!-- <v-btn @click="test()">test</v-btn> -->
            <v-col 
              cols="12" md="6" 
              v-if="clientTypeId === 2 && leasingApplicationForm"
              class="pt-0 pb-0">
              <v-text-field
                v-model="legalInfo.companyName"
                @blur="$v.legalInfo.companyName.$touch()"
                @input="$v.legalInfo.companyName.$touch()" 
                :error-messages="companyNameErr"
                label="Назва компанії"
                dense outlined readonly>
                <template v-slot:append>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-icon 
                        v-on="on"
                        style="cursor: pointer;" 
                        color="#d24a43" 
                        v-text="'mdi-information-outline'">
                      </v-icon>
                    </template>
                    <span>Поле заповнюється автоматично за вказаним ЄДРПОУ</span>
                  </v-tooltip>
                </template>
              </v-text-field>
            </v-col>
            <v-col 
              cols="12" md="6" 
              v-if="clientTypeId === 1 && leasingApplicationForm"
              class="pt-0 pb-0">
              <v-text-field
                @input="parseToInt('inn');
                  $v.legalInfo.inn.$touch()"
                @blur="$v.legalInfo.inn.$touch()"
                v-model="legalInfo.inn"
                :error-messages="innErr"
                v-mask="'##########'"
                id="inn"
                max="10"
                label="ІПН"
                dense outlined>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="lastName"
                @blur="$v.lastName.$touch()"
                @input="$v.lastName.$touch()" 
                :error-messages="lastNameErr"
                label="Прiзвище"
                dense outlined>
              </v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="firstName"
                @blur="$v.firstName.$touch()"
                @input="$v.firstName.$touch()" 
                :error-messages="firstNameErr"
                label="Ім'я"
                dense outlined>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="patronymic"
                @blur="$v.patronymic.$touch()"
                @input="$v.patronymic.$touch()" 
                :error-messages="patronymicErr"
                label="По батькові"
                dense outlined>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="phone"
                @blur="$v.phone.$touch()"
                @input="$v.phone.$touch();
                  applyMask()" 
                id="number"
                :error-messages="phoneErr"
                label="Телефон"
                dense outlined>
              </v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="email"
                @blur="$v.email.$touch()"
                @input="$v.email.$touch()" 
                :error-messages="emailErr"
                label="Email"
                dense outlined>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="pt-0 pb-0">
              <input id="insurance" class="toggle" type="checkbox">
              <label for="insurance" class="lbl-toggle" style="background: white; color: #5f6368; padding-top: 0rem; margin-bottom: 0;">Документи</label>
                <div class="collapsible-content">
                  <div class="content-inner">
                    <div class="document-list" v-if="clientTypeId === 2">
                      <div 
                        v-for="(item, key) in legalDocs"
                        :key="key">
                        <span :style="documentUrls[item.prop] ? 'color: black;' : ''">{{ 
                          documentUrls[item.prop] && documentUrls[item.prop].text 
                            ? documentUrls[item.prop].text + ' ' + `(${(documentUrls[item.prop].size / 1000).toFixed(2)} - kb)` 
                            : item.text 
                          }}
                        </span>&nbsp;
                        <v-btn
                          v-show="!documentUrls[item.prop]"
                          @click="$refs[item.prop][0].click()" 
                          icon small>
                          <v-icon small v-text="'mdi-plus-thick'"></v-icon>
                        </v-btn>
                        <div 
                          v-if="documentUrls[item.prop]"
                          style="display: inline-block; min-width: 15px; position: relative;">
                          <v-tooltip top>
                            <template v-slot:activator="{ on }">
                              <v-icon 
                                style="position: absolute; left: -10px; top: -15px;" 
                                v-text="'mdi-information-variant'" 
                                color="#d24a43"
                                v-on="on"
                                small>
                              </v-icon>
                            </template>
                            <span>{{ item.text }}</span>
                          </v-tooltip>
                        </div>
                        <v-btn
                          v-show="documentUrls[item.prop]"
                          @click="deleteDoc(item.prop)" 
                          color="#d24a43"
                          class="document-delete-icon"
                          icon small>
                          <v-icon small v-text="'mdi-close'"></v-icon>
                        </v-btn>
                      </div>
                      <!-- <v-btn @click="test()">test</v-btn> -->
                    </div>
                    <div class="document-list" v-if="clientTypeId === 1">
                      <div 
                        v-for="(item, key) in personDocs"
                        :key="key">
                        <span :style="documentUrls[item.prop] ? 'color: black;' : ''">{{ 
                          documentUrls[item.prop] && documentUrls[item.prop].text 
                            ? documentUrls[item.prop].text + ' ' + `(${(documentUrls[item.prop].size / 1000).toFixed(2)} - kb)` 
                            : item.text 
                          }}
                        </span>&nbsp;
                        <v-btn
                          v-show="!documentUrls[item.prop]"
                          @click="$refs[item.prop][0].click()" 
                          icon small>
                          <v-icon small v-text="'mdi-plus-thick'"></v-icon>
                        </v-btn>
                        <div 
                          v-if="documentUrls[item.prop]"
                          style="display: inline-block; min-width: 15px; position: relative;">
                          <v-tooltip top>
                            <template v-slot:activator="{ on }">
                              <v-icon 
                                style="position: absolute; left: -10px; top: -15px;" 
                                v-text="'mdi-information-variant'" 
                                color="#d24a43"
                                v-on="on"
                                small>
                              </v-icon>
                            </template>
                            <span>{{ item.text }}</span>
                          </v-tooltip>
                        </div>
                        <v-btn
                          v-show="documentUrls[item.prop]"
                          @click="deleteDoc(item.prop)" 
                          color="#d24a43"
                          class="document-delete-icon"
                          icon small>
                          <v-icon small v-text="'mdi-close'"></v-icon>
                        </v-btn>
                      </div>
                    </div>
                    <div v-if="clientTypeId === 2">
                      <input
                        v-for="(item, key) in legalDocs"
                        :key="key"
                        style="visibility: hidden; position: absolute; left: 0; top: 0;"
                        type="file"
                        accept="image/x-png,image/gif,image/jpeg,image/jpg,image/svg+xml,application/msword,application/vnd.ms-excel,application/pdf"
                        :ref="item.prop"
                        :class="item.prop"
                        @change="listenFileInput(item.prop)">
                    </div>
                    <div v-if="clientTypeId === 1">
                      <input
                        v-for="(item, key) in personDocs"
                        :key="key"
                        style="visibility: hidden; position: absolute; left: 0; top: 0;"
                        type="file"
                        accept="image/x-png,image/gif,image/jpeg,image/jpg,image/svg+xml,application/msword,application/vnd.ms-excel,application/pdf"
                        :ref="item.prop"
                        :class="item.prop"
                        @change="listenFileInput(item.prop)">
                    </div>
                    <div v-if="clientTypeId === 1">
                      <v-row>
                        <v-col class="pb-0">
                          <span style="color: black; padding: 0 15px;">
                            Довідка про фінансові документи
                          </span>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col class="pt-2">
                          <v-file-input
                            v-model="certificateOfFinancials"
                            label="додати файли"
                            color="black"
                            small-chips
                            multiple
                            dense
                            regular
                            show-size>
                          </v-file-input>
                        </v-col>
                      </v-row>
                    </div>
                    <!-- <v-file-input
                      @change="uploadDoc()"
                      v-model="docs"
                      :rules="rules"
                      accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, image/*"
                      label="Натиснiть"
                      multiple
                      outlined show-size dense chips>
                    </v-file-input> -->
                  </div>
                </div>
            </v-col>
          </v-row>
        </v-row>
        <v-row>
          <v-col class="d-flex justify-center pb-6">
            <span>
              <v-btn @click="submit()" 
                :loading="loading"
                class="d-block white--text" 
                color="grey darken-3">
                Вiдправити заявку
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
              @click="openDialogToSave(); formatToSave = 'pdf'" 
              v-on="on"
              color="grey darken-2" 
              icon large dark>
              <v-icon size="22" dark v-text="'mdi-download'"></v-icon>
            </v-btn>
          </template>
          <span>Зберегти результат розрахунку</span>
        </v-tooltip>
      </span>
      <span>
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn 
              @click="openDialogToSave();
                formatToSave = 'email'; emailField = true"
              v-on="on"
              color="grey darken-2"
              :disabled="pdfDownloadLoading"
              icon large dark>
              <v-icon size="22" dark v-text="'mdi-email-send'"></v-icon>
            </v-btn>
          </template>
          <span>Вiдправити результат розрахунку на email</span>
        </v-tooltip>
      </span>
    </div>
  </v-card-text>
</div>
</template>

<script>
import selectItems from '../Calculator/selectItems.js'
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'
import axios from 'axios'
import { saveAs } from 'file-saver'

export default {
  props: ['graph', 'data'],
  mixins: [validationMixin],
  data: () => ({
    selectedGraph: null,
    documentUrls: {},
    legalDocs: [
      {text: 'Копія свідоцтва про державну реєстрацію та / або виписка з ЄДР', prop: 'state_registration_certificate'},
      {text: 'Статут', prop: 'regulations'},
      {text: 'Баланс та звіт про фінансові результати (Ф1 та Ф2)', prop: 'balance'},
      {text: 'Протокол засновників про обрання керівника (підписанта)', prop: 'protocol'},
      {text: 'Наказ про призначення керівника (підписанта)', prop: 'order'},
      {text: 'Паспорт / ID-карта керівника (підписанта)', prop: 'passport'},
      {text: 'Довідка про присвоєння ІПН керівника (підписанта)', prop: 'taxNumber'},
    ],
    personDocs: [
      {text: 'Паспорт громадянина України / ID-карта', prop: 'passport'},
      {text: 'Довідка про присвоєння ІПН', prop: 'taxNumber'},
      {text: 'Довідка про заробітну плату', prop: 'salary_certificate'},
      {text: 'Паспорт дружини (чоловіка) позичальника', prop: 'relatives_passport'},
    ],
    rules: [
      value => {
        if(value.length === 0) return true
        if(value.every(val => val.size < 5000000)) return true
        return 'Розмiр документу не повинен перевищувати 5 MB!'
      }
    ],
    certificateOfFinancials: [],
    graphName: [],
    currentGraphToDownload: null,

    select: selectItems,
    leasingApplicationForm: false,
    commonErr: ['Обов`язкове поле'],
    pasteEvent: false,
    loading: false,
    // creditPayment: null,
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
    legalInfo: {
      // creditPayment: null,
      inn: null,
      // monthlyIncome: null,
      // acquisitionTargetId: null,
      edrpou: null,
      companyName: null,
      // currencyBalance: null,
      // equity: null,
      // balances: null, // - waiting... (пока не отправляй)
    },
    _token: null,
    edrpouLoading: false,
  }),
  validations() {
    return this.validationRules
  },
  computed: {
    validData() {
      return !this.$v.$invalid
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
        selectedGraph: { required },
        lastName: { required },
        firstName: { required },
        patronymic: { required },
        phone: { 
          required,
          minLength: value => {
            if(value == null) return false
            return value.replace(/[^\d]/g, '').length === 12
          }
        },
        email: { email, required },
        // creditPayment: { required },
      }
    },
    individualPerson() {
      return {
        legalInfo: {
          inn: { 
            required,
            minLength: value => {
              if(value == null) return false
              return value.length === 10
            }
           }, 
          // monthlyIncome: { required },
          // acquisitionTargetId: { required },
        }
      }
    },
    legalPerson() {
      return {
        legalInfo: {
          edrpou: { 
            required,
            minLength: value => {
              if(value == null) return false
              return value.length === 8
            }
          },
          companyName: { required },
          // currencyBalance: { required },
          // equity: { required },
        }
      }
    },
    isLegalPerson() {
      return this.clientTypeId === 2
    },
    /* error handlers */
    selectedGraphErr() {
      if (!this.$v.selectedGraph) return
      if (!this.$v.selectedGraph.$error) return
      return this.commonErr
    },
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
    // creditPaymentErr() {
    //   if (!this.$v.creditPayment.$error) return
    //   return this.commonErr
    // },
    innErr() {
      if (!this.$v.legalInfo.inn.$error) return
      return ['Повинно бути 10 цифр']
    },
    // monthlyIncomeErr() {
    //   if (!this.$v.legalInfo.monthlyIncome.$error) return
    //   return this.commonErr
    // },
    // acquisitionTargetIdErr() {
    //   if (!this.$v.legalInfo.acquisitionTargetId.$error) return
    //   return this.commonErr
    // },
    edrpouErr() {
      if (!this.$v.legalInfo.edrpou.$error) return
      return ['Має містити 8 цифр']
    },
    companyNameErr() {
      if (!this.$v.legalInfo.companyName.$error) return
      return this.commonErr
    },
    // currencyBalanceErr() {
    //   if (!this.$v.legalInfo.currencyBalance.$error) return
    //   return this.commonErr
    // },
    // equityErr() {
    //   if (!this.$v.legalInfo.equity.$error) return
    //   return this.commonErr
    // },
    legalEmail() {
      return ''
    },
    legalPhone() {
      return ''
    }
  },
  methods: {
    getEdropu() {
      if(!this.$v.legalInfo.edrpou.$invalid && !this.edrpouLoading){
        this.edrpouLoading = true
        axios
          .get(`https://open-data-332145.herokuapp.com/get-company-name/${this.legalInfo.edrpou}`)
          .then(response => {
            console.log(response)
            this.edrpouLoading = false
            this.legalInfo.companyName = response.data.companyName
          })
          .catch(error => {
            console.log(error.response)
            this.edrpouLoading = false
            this.$notify({
              group: 'error',
              title: `Помилка - ${error.response.status}`,
              text: `${error.response.data.message || error.response.data.errors[0].msg}`,
            })
          })
          // .get(`/leasing-reqeust/company/${this.legalInfo.edrpou}`)
          // .then(response => {
          //   this.edrpouLoading = false
          //   if(response.status === 200 && response.data.companyShortName) {
          //     this.legalInfo.companyName = response.data.companyShortName
          //   } else if (response.data.status !== 200) {
          //     this.getEdrpouErrorHandler(response.data.status)
          //   }
          // })
          // .catch(error => {
          //   this.$catchStatus(error.response.status)
          //   console.log(error.response)
          //   this.getEdrpouErrorHandler(error.response.status)
          //   this.edrpouLoading = false
          // })
      } else return
    },
    getEdrpouErrorHandler(status) {
      if(status === 403 || status === 503) {
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Зверніться в технічну підтримку`,
        })
      } else if (status === 404) {
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Компанію не знайдено`,
        })
      } else {
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Зверніться в технічну підтримку`,
        })
      }
    },
    deleteDoc(property) {
      this.$delete(this.documentUrls, property)
      let el = document.querySelector(`.${property}`)
      el.value = null
    },
    async uploadDoc(document, selector) {
      let formData = new FormData()
      formData.append('doc', document)
      axios
        .post('/leasing-reqeust/document/upload', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
          this.documentUrls = Object.assign({}, this.documentUrls, {[selector]: {url: response.data.url, text: document.name, size: document.size}})
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
          this.$notify({
            message: 'Помилка',
            type: 'error',
          })
        })
    },
    async listenFileInput(selector) {
      let file = document.querySelector(`.${selector}`).files[0]
      if(file.size <= 5242880) {
        this.uploadDoc(file, selector)
      } else {
        this.$notify({
            group: 'error',
            title: 'Помилка розiр файлу не повинен перевищувати 5 mb',
            text: ``,
          })
        document.querySelector(`.${selector}`).value = null
        return
      }
    },
    sendGraph() {
      let graphs = this.currentGraphToDownload.result_data
      let graph = graphs[Object.keys(graphs)[0] !== 'requestId' ? Object.keys(graphs)[0] : Object.keys(graphs)[1]]
      // console.log(Object.keys(graphs)[0])
      let calcData = this.currentGraphToDownload.request_data
      let rootCalcData = this.currentGraphToDownload

      let dataToSave = {
        request_id: this.currentGraphToDownload.request_id,
        agentId: this.$store.state.user.agent.id,
        mark: calcData.leasedAssertMark.name,
        model: calcData.leasedAssertModel.name,
        leasingObjType: calcData.leasingObjectType.label,
        // price: parseInt(calcData.leasingAmount.replace(/\s/g, '' )),
        term: calcData.leasingTerm,
        advance: calcData.advance,
        prepaid: graph['offer-advance'],
        offerBrutto: graph['offer-price-brutto'],
        offerNetto: graph['offer-price-netto'],
        oneTimeComission: (graph['offer-administrative-payment-per'] * 100).toFixed(2),
        currency: calcData.leasingCurrency,
        leasingRest: graph['offer-rest'],
        requestId: rootCalcData.request_id,
        date: this.currentGraphToDownload.created_at,
        _token: this.getCsrf()
      }
      this.graphName
        .forEach(val => {
          dataToSave[val] = graphs[val]
          dataToSave[val].agg = {}
          dataToSave[val].agg['avg'] = graphs[val]['offer-month-payment']
          dataToSave[val].agg['payment-principal'] = graphs[val]['total-payment-principal']
          dataToSave[val].agg['interest'] = graphs[val]['total-interest']
          dataToSave[val].agg['payment'] = graphs[val]['total-payment']
        })

      if(this.formatToSave === 'email') dataToSave.email = this.emailToSend

      !this.$v.$invalid
        ? this.sendData(dataToSave)
        : this.highlightErrors()
    },
    sendData(dataToSave) {
      this.loading = true
      axios
        .post('/calculation/getPdf', dataToSave, this.formatToSave === 'pdf' ? { responseType: 'blob' } : false)
        .then(response => {
          if(this.formatToSave === 'pdf') {
            let index = response.headers['content-disposition'].indexOf('"') + 1
            saveAs(response.data, response.headers['content-disposition'].substr(index).split('"')[0])
          }
          if(this.formatToSave === 'email') {
            this.$notify({
              group: 'success',
              title: 'Графiк успiшно вiдправлено',
              text: '',
            })
          }
          this.loading = false
          setTimeout(() => {
            this.dialogToSend = false
          }, 1200)
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
          this.loading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`
          })
        })
    },
    openDialogToSave(format){
      this.formatToSave = format
      this.dialogToSend = true
      this.currentGraphToDownload = this.data
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
    submit() {
      !this.$v.$invalid
        ? this.sendRequest()
        : this.highlightErrors()
    },
    object() {
      return {
        agent_id: this.agentId,
        calculation_id: this.calculationId,
        client_type_id: this.clientTypeId,
        last_name: this.lastName,
        first_name: this.firstName,
        patronymic: this.patronymic,
        // region: this.region,
        phone: this.phone,
        email: this.email,
        leasing_object: this.leasingObject,
        advance: this.advance,
        leasing_term: this.leasingTerm,
        leasing_amount: this.leasingAmount,
        graph_type: this.selectedGraph,
        documents: this.documentUrls,
        legal_info: {
          // creditPayment: this.creditPayment,

          inn: this.legalInfo.inn,
          // monthlyIncome: this.legalInfo.monthlyIncome,
          // acquisitionTargetId: this.legalInfo.acquisitionTargetId,
          
          edrpou: this.legalInfo.edrpou,
          company_name: this.legalInfo.companyName,
          // currencyBalance: this.legalInfo.currencyBalance,
          // equity: this.legalInfo.equity,
          // balances: this.legalInfo.balances, // - waiting... (пока не отправляй)
        },
        _token: this._token
      }
    },
    requestObj(object) {
      let finalObj = null
      if(Object.keys(this.documentUrls).length === 0 && this.documentUrls.constructor === Object) {
        delete object.documents
      }
      this.isLegalPerson === false
        ? finalObj = this.deleteLegalFields(object)
        : finalObj = this.deleteIndividualFields(object)
      return finalObj
    },
    sendRequest() {
      this.loading = true
      let object = this.object()
      axios
        .post('/leasing-reqeust/create', this.requestObj(object))
        .then(() => {
          this.$notify({
            group: 'success',
            title: 'Заявку успiшно вiдправленно',
            text: '',
          })
          this.loading = false
          setTimeout(() => {
            this.leasingApplicationForm = false
            this.$router.push({name: 'Заявки на лiзинг'})
          }, 2000);
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
    },
    deleteIndividualFields(object) {
      if(object.phone === null || object.phone === 'undefined') delete object.phone
      if(object.email === null || object.email === 'undefined') delete object.email
      delete object.legal_info.inn
      // delete object.legalInfo.monthlyIncome
      // delete object.legalInfo.acquisitionTargetId
      return object
    },
    deleteLegalFields(object) {
      delete object.legal_info.edrpou,
      delete object.legal_info.companyName
      // delete object.legalInfo.currencyBalance
      // delete object.legalInfo.equity
      return object
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    openForm() {
      console.log(this.data)
      if(this.data.is_send_request === 1) {
        this.$notify({
            group: 'warning',
            title: '',
            text: 'Заявку на лізинг з цього розрахунку вже подано',
          })
        return
      }
      this.selectedGraph = this.switchGraphName(this.graph)
      this.getDefaultProperties()
      this.leasingApplicationForm = true
    },
    getDefaultProperties() {
      // chart diagram btn
      this.agentId = this.$store.state.user.agent.id
      this.calculationId = this.data.id
      this.leasingObject = `${this.data.request_data.leasedAssertMark.name} ${this.data.request_data.leasedAssertModel.name}`
      this.clientTypeId = this.data.request_data.leasingClientType
      this.advance = this.data.request_data.advance
      this.leasingTerm = this.data.request_data.leasingTerm
      this.leasingAmount = this.data.request_data.offerNetto
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
          if(this.phone[6] == 0) {
            this.phone = this.phone.substr(0, 6) + this.phone.substr(6) 
          } else {
            indexes[i] ? splitMask[indexes[i]] = val : false
          }
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
    },
    clearObject() {
      // this.creditPayment = null,
      // this.selectedGraph = null,
      this.lastName = null,
      this.firstName = null,
      this.patronymic = null,
      this.region = null,
      this.phone = null,
      this.email = null,
      this.documentUrls = {},
      this.legalInfo = {
        // creditPayment: null,
        inn: null,
        // monthlyIncome: null,
        // acquisitionTargetId: null,
        edrpou: null,
        companyName: null,
        // currencyBalance: null,
        // equity: null,
      }
    },
  },
  watch: {
    leasingApplicationForm(val) {
      this.$v.$reset()
      if(val === false) {
        this.clearObject()
      }
    },
    dialogToSend(value) {
      if(value === false) {
        this.formatToSave = null
        this.graphName = []
        // this.currentGraphToDownload = null
        this.emailField = false
      }
    },
    // 'legalInfo.currencyBalance': function(value) {
    //   if(!value) return value
    //   this.legalInfo.currencyBalance = parseInt(value)
    // },
    // 'legalInfo.monthlyIncome': function(value) {
    //   if(!value) return value
    //   this.legalInfo.monthlyIncome = parseInt(value)
    // },
    // 'creditPayment': function(value) {
    //   if(!value) return value
    //   this.legalInfo.creditPayment = parseInt(value)
    // },
    graph(val) {
      if(!Number.isNaN(parseInt(val))) {
        this.selectedGraph = this.switchGraphName(val)
      }
    }
  },
  mounted() {
    this.selectedGraph = this.switchGraphName(this.graph)
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