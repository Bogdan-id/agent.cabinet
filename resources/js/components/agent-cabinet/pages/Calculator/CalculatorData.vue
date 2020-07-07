<template>
<div class="col-12">
  <v-dialog
    v-model="dialogToSwitchBetweenTypesOfSave"
    max-width="530">
    <v-card>
      <v-card-title style="background: #424242; position: relative" class="white--text">
        <v-btn @click="dialogToSwitchBetweenTypesOfSave = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon color="white" v-text="'mdi-close'"></v-icon></v-btn>
        Оберiть тип збереження
      </v-card-title>
      <v-card-text class="calculator-data__custom-btn-wrapper" style="display: flex; justify-content: space-around; margin-top: 25px;">
        <span><v-btn @click="openDialogToSave('email')" dark color="#e94949">Вiдправити на email</v-btn></span>
        <span><v-btn @click="openDialogToSave('pdf')" dark color="#e94949">Зберегти у форматi .pdf</v-btn></span>
      </v-card-text>
    </v-card>
  </v-dialog>
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
            <span :style="graphName == 'even' ? 'color: black;' : ''" class="graph-label-to-download">Класичний</span>
          </template>
        </v-checkbox>
        <v-checkbox
          v-if="currentGraphToDownload && currentGraphToDownload.result_data && currentGraphToDownload.result_data.hasOwnProperty('annuity')"
          v-model="graphName"
          value="annuity"
          color="red darken-4">
          <template v-slot:label>
            <span :style="graphName == 'annuity'? 'color: black;' : ''" class="graph-label-to-download">Ануїтет</span>
          </template>
        </v-checkbox>
        <v-checkbox
          v-if="currentGraphToDownload && currentGraphToDownload.result_data && currentGraphToDownload.result_data.hasOwnProperty('irregular')"
          value="irregular"
          v-model="graphName"
          color="red darken-4">
          <template v-slot:label>
            <span :style="graphName == 'irregular' ? 'color: black;' : ''" class="graph-label-to-download">Iндивiдуальний</span>
          </template>
        </v-checkbox>
        <v-text-field 
          v-if="formatToSave === 'email'"
          class="email-to-send"
          @input="$v.emailToSend.$touch()"
          @blur="$v.emailToSend.$touch()"
          :error-messages="emailToSendErr"
          v-model="emailToSend"
          label="email"
          outlined dense>
        </v-text-field>
        <v-divider class="mt-0"></v-divider>
        <v-btn 
          style="border-radius: 0; text-transform: capitalize; border-color: rgb(224, 77, 69); font-size: 1rem;"
          class="send-graph-btn"
          @click="test()" dark color="#e04d45"
          :loading="loading">
          {{ formatToSave === 'email' ? 'Вiдправити' : 'Зберегти' }}
        </v-btn>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-dialog
    v-model="leasingApplicationForm"
    max-width="600">
    <v-card>
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
  <v-dialog
    v-model="deleteCalculationDialog"
    max-width="420">
    <v-card>
      <v-card-title style="color: white; background: #424242; position: relative">
        Видалення
        <v-btn @click="deleteCalculationDialog = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon v-text="'mdi-close'" color="white"></v-icon></v-btn>
      </v-card-title>
      <v-card-text>
        <div style="line-height: 1.5rem; font-size: 1.15rem; padding: 35px 10px 25px 10px; color: black">
          Розрахунок буде видалено назавжди. <b style="color: black;">Продовжити?</b>
        </div>
        <div class="d-flex justify-space-between">
          <span><v-btn @click="deleteCalculation()" dark color="#e94949" :loading="btnLoading">Так</v-btn></span>
          <span><v-btn @click="deleteCalculationDialog = false" dark color="#333333">Нi</v-btn></span>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-card class="pb-4" min-height="300" elevation="12">
    <v-card-title class="d-block grey darken-3 white--text">
      <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-calculator-variant'"></v-icon>
      Калькулятор лiзингу
    </v-card-title>
    <v-card-title
      class="calculator-custom-title pb-0 pt-7"
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
              parseInt(item.request_data.leasingAmount.replace(/\s/g, '' ))
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
                      color="red darken-2">
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
                    @click="openDeleteCalculationDialog(item.id)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="red darken-2"
                      >
                      mdi-delete-forever-outline
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Видалити</span>
            </v-tooltip>
            <v-tooltip bottom>
              <!-- <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click="openDialogToSave(item, 'email')"
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
              <span>Вiдправити на пошту</span>  -->
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <!-- @click="openDialogToSave(item, 'pdf')" -->
                  <v-btn 
                    @click="openDialogToSwitchBetweenTypesOfSave(item)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="grey darken-2">
                      mdi-download
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Зберегти розрахунок</span>
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
import { saveAs } from 'file-saver'

export default {
  mixins: [validationMixin],
  data:() => ({
    /* v-dialog data  */
    dialogToSend: false,
    graphName: 'even',
    currentGraphToDownload: null,
    formatToSave: null,
    emailToSend: null,
    graphObj: null,
    dialogToSwitchBetweenTypesOfSave: false,

    select: selectItems,
    leasingApplicationForm: false,
    calculationToDelete: null,
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
    deleteCalculationDialog: false,
    _token: null,

    tableHeader: [
      { text: 'Код розрахунку', value: 'request_id', align: 'start'},
      { text: 'Марка', value: 'request_data.leasedAssertMark.name', align: 'center'},
      { text: 'Авто', value: 'request_data.leasedAssertModel.name', align: 'center' },
      { text: 'Цiна, грн', value: 'request_data', align: 'center' },
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
      this.formatToSave == 'email'
        ? validateObj = {emailToSend: { email, required }}
        : !this.leasingApplicationForm 
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
    // verifyEmailToSend() {
    //   return {
    //     emailToSend: { required }
    //   }
    // },
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
    emailToSendErr() {
      let errors = []
      if (!this.$v.emailToSend || !this.$v.emailToSend.$error) return
      !this.$v.emailToSend.required && errors.push('Вкажiть ваш email')
      !this.$v.emailToSend.email && errors.push('Невiрний email')
      return errors
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
    openDialogToSwitchBetweenTypesOfSave(item) {
      this.graphObj = item
      this.dialogToSwitchBetweenTypesOfSave = true
    },
    openDialogToSave(format){
      this.formatToSave = format
      this.dialogToSend = true
      this.currentGraphToDownload = this.graphObj
    },
    openDeleteCalculationDialog(id) {
      this.deleteCalculationDialog = true
      this.calculationToDelete = id
    },
    deleteCalculation() {
      this.btnLoading = true
      axios
        .delete(`/calculation/delete/${this.calculationToDelete}`)
        .then(response => {
          console.log(response)
          this.$notify({
            group: 'success',
            title: 'Розрахунок видалено',
            text: '',
          })
          this.btnLoading = false
          this.getUserCalculations()
          setTimeout(() => {
            this.deleteCalculationDialog = false
          }, 800)
        })
        .catch(error => {
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
          console.log(error.response)
          this.btnLoading = false
        })
    },
    customSort(items) {
      items
        .sort((a, b) => {
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
    },
    submit() {
      !this.$v.$invalid
      && this.$v.$dirty
        ? this.sendRequest()
        : this.highlightErrors()
    },
    sendRequest() {
      this.loading = true
      let object = this.object()
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
      let graph = this.currentGraphToDownload.result_data[this.graphName]
      let calcData = this.currentGraphToDownload.request_data
      let rootCalcData = this.currentGraphToDownload

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
    sendData(dataToSave) {
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
          this.loading = false
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
          this.loading = false
        })
    },
    sortData(a, b) {
      return new Date(b.created_at) - new Date(a.created_at)
    },
  },
  watch: {
    dialogToSend(value) {
      if(value === false) {
        this.formatToSave = null
        this.currentGraphToDownload = null
      }
    },
    deleteCalculationDialog(value) {
      if(value === false) {
        this.calculationToDelete = null
      }
    },
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
    this.getListItem()
    this.$store.state.user.agent 
      ? this.getUserCalculations()
      : false
  },
}
</script>

<style lang="scss">
  .email-to-send {
    .v-text-field__details {
      margin-bottom: 0!important;
    }
  }
  .calculator-data-leasing-obj-type {
    font-weight: bold;
    font-size: 0.91rem!important;
  }
  .graphs-to-delete {
    .v-input--selection-controls {
      margin: 0!important;
    }
    .v-input__slot {
      margin: 0!important;
    }
  }
  .send-graph-btn {
    transition: box-shadow 0.5s;
    &:hover {
      -webkit-box-shadow: 1px 3px 17px -6px rgba(0,0,0,0.75);
      -moz-box-shadow: 1px 3px 17px -6px rgba(0,0,0,0.75);
      box-shadow: 1px 3px 17px -6px rgba(0,0,0,0.75);
    }
  }
  .graph-label-to-download {
    font-size: 1.05rem;
    letter-spacing: 0.09rem;
    margin-bottom: 4px;
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
  .calculator-data__custom-btn-wrapper {
    .v-btn__content {
      text-transform: none!important;
      font-size: 1rem;
    }
  }
</style>