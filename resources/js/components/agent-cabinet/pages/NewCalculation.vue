<template>
  <v-card>
    <v-tabs 
      v-model="currentTab"
      slider-color="grey darken-3"
      background-color="red lighten-1"
      ref="calcTab"
      grow
      slider-size="7"
      dark>
      <v-tab value="1">Cторiнка 1</v-tab>
      <v-tab value="2">Cторiнка 2</v-tab>
    </v-tabs>
    <v-tabs-items v-model="currentTab">
      <v-tab-item key="1">
        <v-card-text>
          <div>
            <v-row>
              <v-col cols="12" md="7">
                <v-radio-group 
                  class="calculator-radio" 
                  v-model="calcObj.leasingClientType"
                  :error-messages="leasingClientTypeErr"
                  row>
                  <v-radio label="Фiзична особа" :value="2" color="black"></v-radio>
                  <v-radio label="Юридична особа" :value="1" color="black"></v-radio>
                </v-radio-group>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md=3>
                <v-select 
                  v-model="calcObj.isNew"
                  :items="selects.itemConditions"
                  :error-messages="isNewErr"
                  label="Тип авто"
                  color="red darken-4"
                  dense outlined :disabled="!isClientType">
                </v-select>
              </v-col>
              <v-col cols="12" md="3">
                <v-select
                  v-model="calcObj.currency"
                  :items="selects.currencys"
                  :error-messages="currencyErr"
                  label="Валюта договору лізингу"
                  color="red darken-4"
                  dense outlined :disabled="!isClientType">
                </v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="3">
                <v-select
                  @change="getMarksByType()"
                  v-model="calcObj.leasingObjectType"
                  :items="selects.itemTypes"
                  :error-messages="leasingObjectTypeErr"
                  item-text="label"
                  item-value="text"
                  return-object
                  label="Тип предмета лізингу"
                  :disabled="!typeOfCar"
                  color="red darken-4"
                  dense outlined>
                </v-select>
              </v-col>
              <v-col cols="12" md="4">
                <v-autocomplete
                  @change="getModelByMark()"
                  v-model="calcObj.leasedAssertMark"
                  :items="brandItems"
                  :error-messages="leasedAssertMarkErr"
                  item-text="name"
                  item-value="value"
                  return-object
                  label="Марка"
                  :disabled="!leasedOfAssetType || noBrandItems"
                  color="red darken-4"
                  dense outlined>
                </v-autocomplete>
              </v-col>
              <v-col cols="12" md="4">
                <v-autocomplete
                  v-model="calcObj.leasedAssertModel"
                  :items="modelItems"
                  :error-messages="leasedAssertModelErr"
                  item-text="name"
                  item-value="value"
                  return-object
                  label="Модель"
                  :disabled="!modelOfItem || noModelItems"
                  color="red darken-4"
                  dense outlined>
                </v-autocomplete>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="3" v-if="calcObj.isNew !== null">
                <v-select
                  v-model="calcObj.leasingObjectYear" 
                  :items=" calcObj.isNew ? selects.itemYears : selects.oldItemYears"
                  :error-messages="leasingObjectYearErr"
                  label="Рік"
                  :disabled="!typeOfModel"
                  color="red darken-4"
                  dense outlined>
                </v-select>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="calcObj.leasedAssertEngine"
                  @input="parseToInt('leasedAssertEngine')"
                  id="leasedAssertEngine"
                  :error-messages="leasedAssertEngineErr"
                  label="Об'єм двигуна" 
                  :disabled="!yearOfModel"
                  color="red darken-4"
                  dense outlined>
                </v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  @input="$v.calcObj.leasingAmount.$touch(); 
                    parseToInt('leasingAmount')"
                  :error-messages="itemCostErrors"
                  v-model="calcObj.leasingAmount"
                  id="leasingAmount"
                  label="Вартість"
                  color="red darken-4"
                  :disabled="calcObj.leasedAssertEngine === null"
                  dense outlined>
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="3">
                <v-select
                  :items="selects.currencys"
                  :error-messages="leasingCurrencyErr"
                  label="Валюта"
                  v-model="calcObj.leasingCurrency"
                  :disabled="calcObj.leasingAmount === null"
                  color="red darken-4"
                  dense>
                </v-select>
              </v-col>
              <v-col cols="12" md="3" 
                v-if="calcObj.leasingCurrency !== null 
                && hasForeignCurrency 
                && currentTab === 0">
                <!-- float -->
                <v-text-field
                  v-model="calcObj.leasingCurrencyCourse"
                  :error-messages="leasingCurrencyCourseErr"
                  :disabled="calcObj.leasingCurrency === null"
                  @input="parseToFloat('leasingCurrencyCourse')"
                  id="leasingCurrencyCourse"
                  label="Курс"
                  color="red darken-4"
                  dense>
                </v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="calcObj.leasingQuantity" 
                  :error-messages="leasingQuantityErr"
                  :disabled="calcObj.leasingCurrency === null"
                  @input="parseToInt('leasingQuantity')"
                  id="leasingQuantity"
                  label="Кiлькiсть"
                  min="1"
                  color="red darken-4"
                  dense>
                </v-text-field>
              </v-col>
            </v-row>
          </div>
        </v-card-text>
      </v-tab-item>
      <v-tab-item key="2">
        <v-card-text>
          <div>
            <v-row>
              <v-col cols="12" md="9" class="pb-0">
                <v-select
                  v-model="calcObj.graphType"
                  :items="selects.chartTypes"
                  :error-messages="graphTypeErr"
                  label="Тип графіка"
                  color="red darken-4"
                  dense outlined multiple>
                </v-select>
              </v-col>
            </v-row>
            <v-card v-show="hasAnnuity" class="d-inline-block mb-3">
            <v-card-title v-show="hasAnnuity" class="headline">Посилання для рiвномiрного графiку</v-card-title>
            <v-card-text class="pb-0" v-show="hasAnnuity">
              <v-row v-show="hasAnnuity">
                <v-col cols="9" md="4">
                  <v-text-field
                    v-model="calcObj.gainEvenGraphicMonths" 
                    :error-messages="gainEvenGraphicMonthsErr"
                    label="Кiлькiсть мiсяцiв"
                    @input="parseToInt('gainEvenGraphicMonths')"
                    id="gainEvenGraphicMonths"
                    color="red darken-4"
                    dense outlined>
                  </v-text-field>
                </v-col>
                <v-col cols="9" md="4">
                  <v-text-field
                    v-model="calcObj.gainEvenGraphicPercent" 
                    :error-messages="gainEvenGraphicPercentErr"
                    @input="parseToFloat('gainEvenGraphicPercent')"
                    id="gainEvenGraphicPercent"
                    label="Вiдсоток посилення"
                    color="red darken-4"
                    dense outlined>
                  </v-text-field>
                </v-col>
                <v-col cols="9" md="4">
                  <v-text-field
                    v-model="calcObj.UnsrMonths"
                    :error-messages="UnsrMonthsErr"
                    @input="parseToInt('UnsrMonths')"
                    id="UnsrMonths"
                    label="УНСПР, місяцiв"
                    color="red darken-4"
                    dense outlined>
                  </v-text-field>
                </v-col>
              </v-row>
              </v-card-text>
            </v-card>
            <v-row>
              <v-col cols="12" md="10">
              <div :class="`slider-wrapper d-flex flex-${smAndDown ? 'column' : 'row'}`">
                <div class="body-1 mr-2"><span class="title">Авансовий платiж&nbsp;</span>(<b>%</b>):</div>
                  <v-slider
                    v-model="calcObj.advance"
                    :error-messages="advanceErr"
                    min="20"
                    max="70"
                    color="black"
                    track-color="grey"
                    persistent-hint
                    thumb-label>
                    <template #prepend>
                      <span class="slider-prepend">20&nbsp;%</span>
                    </template>
                    <template #append>
                      <span class="slider-prepend">70&nbsp;%</span>
                    </template>
                  </v-slider>
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="10">
                <div :class="`slider-wrapper d-flex flex-${smAndDown ? 'column' : 'row'}`">
                  <div class="body-1 mr-2"><span class="title">Термiн лiзингу&nbsp;</span>(<b>мic</b>):</div>
                  <v-slider
                    v-model="calcObj.leasingTerm"
                    :error-messages="leasingTermErr"
                    min="12"
                    max="72"
                    color="black"
                    track-color="grey"
                    persistent-hint
                    thumb-label>
                    <template v-slot:prepend>
                      <span class="slider-prepend">12&nbsp;<span class="body-2">мiс</span></span>
                    </template>
                    <template v-slot:append>
                      <span class="slider-prepend">72&nbsp;<span class="body-2">мiс</span></span>
                    </template>
                  </v-slider>
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="8">
                <div class="content__switch">
                  <div class="content__switch-title-wrapper">
                    <span class="content__switch-title body-1">Податок з власникiв транспортних засобiв:</span>
                  </div>
                  <div class="content__switch-wrapper">
                    <v-switch
                        v-model="calcObj.vehicleOwnerTax"
                        :error-messages="vehicleOwnerTaxErr"
                        :false-value="2"
                        :true-value="1"
                        dense
                        >
                        <template #prepend>
                          <span class="switch-label-prepend">Нi</span>
                        </template>
                        <template #append>
                          <span class="switch-label-prepend">Так</span>
                        </template>
                    </v-switch>
                  </div>
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="8">
                <div class="content__switch">
                  <div class="content__switch-title-wrapper">
                    <span class="content__switch-title body-1">Податок в пенсiйний фонд:</span>
                  </div>
                  <div class="content__switch-wrapper">
                    <v-switch
                        v-model="calcObj.paymentPf"
                        :error-messages="paymentPfErr"
                        dense
                        >
                        <template #prepend>
                          <span class="switch-label-prepend">Нi</span>
                        </template>
                        <template #append>
                          <span class="switch-label-prepend">Так</span>
                        </template>
                    </v-switch>
                  </div>
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="3">
                <v-select
                    v-model="calcObj.gpsTrackerModel" 
                    :items="selects.gpsTrackers"
                    :error-messages="gpsTrackerModelErr"
                    label="GPS-трекер"
                    color="red darken-4"
                    dense outlined>
                </v-select>
              </v-col>
            </v-row>
            <v-card-title class="headline">Страхування</v-card-title>
            <v-row>
              <v-col cols="12" md="4">
                <v-select
                    v-model="insuranceProgram" 
                    :items="selects.insurancePrograms"
                    :error-messages="insuranceProgramErr"
                    item-text="text"
                    item-value="text"
                    return-object=""
                    label="Програма страхування"
                    color="red darken-4"
                    dense outlined>
                </v-select>
              </v-col>
              <v-col cols="12" md="4">
                <v-select
                    v-model="calcObj.insuranceFranchise" 
                    :items="selects.franchises"
                    :error-messages="insuranceFranchiseErr"
                    label="Франшиза"
                    color="red darken-4"
                    dense outlined>
                </v-select>
              </v-col>
            </v-row>
          </div>
        </v-card-text>
      </v-tab-item>
    </v-tabs-items>
    <v-card-actions class="d-flex justify-center new-calculation-btn">
      <span>
        <v-btn @click="submit()" class="mb-3" dark color="grey darken-3">
        {{ currentTab === 0 ? 'Далi' : 'Розрахувати'}}
        </v-btn>
      </span>
    <!-- <v-btn @click="test()">test</v-btn> -->
    </v-card-actions>
  </v-card>
</template>

<script>
import axios from 'axios'
import selectsItemAndValue from './selectItems'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  data:() => ({
    selects: selectsItemAndValue,
    currentTab: 0,
    brandItems: [],
    modelItems: [],
    
    minCarCost: 150000,
    insuranceProgram: null,

    commonErr: ['Обов`язкове поле'],

    calcObj: {
      gpsTrackerQuantity: 1,
      urkAssistService: 1,
      gainEvenGraphicMonths: null,
      gainEvenGraphicPercent: null,
      UnsrMonths: null,
      agentId: null,
      leasedAssertMark: null,
      leasedAssertModel: null,
      isNew: null,
      leasingObjectType: null,
      leasingQuantity: null,
      leasingObjectYear: null,
      leasedAssertEngine: null,
      leasingClientType: null,
      currency: null,
      leasingCurrency: null,
      leasingCurrencyCourse: null,
      leasingAmount: null,
      graphType: null,
      advance: 20,
      leasingTerm: 12,
      vehicleOwnerTax: "2",
      paymentPf: false,
      insuranceProgram: null,
      gpsTrackerModel: null,
      insuranceFranchise: null,
      _token: null
    }
  }),
  validations() {
    return {
      calcObj: this.validationRules
    }
  },
  computed: {
    validationRules() {
      let validateObj = null
      this.currentTab === 0 && !this.hasForeignCurrency
        ? validateObj = this.validateFirstPage
        : false

      this.currentTab === 0 && this.hasForeignCurrency
        ? validateObj = Object.assign({},
          this.validateFirstPage,
          this.foreignCurrency
        )
        : false

      this.currentTab === 1 && !this.hasAnnuity
        ? validateObj = Object.assign({},
          this.validateFirstPage, 
          this.validateSecondPage
        )
        : false

      this.currentTab === 1 && this.hasAnnuity
        ? validateObj = Object.assign({},
          this.validateFirstPage,
          this.validateSecondPage,
          this.validateHasAnnuity
        )
        : false
      return validateObj
    },

    /* Validation rules for different pages */

    validateFirstPage() {
      return {
        leasingClientType: { required },
        leasingObjectYear: { required },
        isNew: { required },
        currency: { required },
        leasingObjectType: { required },
        leasedAssertMark: { required },
        leasedAssertModel: { required },
        leasedAssertEngine: { required }, 
        leasingCurrency: { required },
        leasingQuantity: { required },
        leasingAmount: {
          minCost: val => {
            if(val == null) return false
            return parseInt(val.replace(/[^\d]/g, '')) >= this.minCarCost
          },
        },
      }
    },
    foreignCurrency() {
      return {
        leasingCurrencyCourse: { required }
      }
    },
    validateSecondPage() {
      return {
        vehicleOwnerTax: { required },
        paymentPf: { required },
        leasingTerm: { required },
        graphType: {
          hasIndex: (v) => {
            if(v === null) return false
            return v.length > 0
          }
        },
        gpsTrackerModel: { required },
        advance: { required },
        insuranceProgram: { required },
        insuranceFranchise: { required },
      }
    },
    validateHasAnnuity() {
      return {
        gainEvenGraphicMonths: { required },
        gainEvenGraphicPercent: { required },
        UnsrMonths: { required },
      }
    },

    /* boolean */

    smAndDown() {
      return this.$vuetify.breakpoint.smAndDown
    },
    typeOfCar() {
      return this.calcObj.isNew !== null
    },
    leasedOfAssetType() {
      return this.calcObj.leasingObjectType !== null
    },
    modelOfItem() {
      return this.calcObj.leasedAssertMark !== null
    },
    typeOfModel() {
      return this.calcObj.leasedAssertModel !== null
    },
    yearOfModel() {
      return this.calcObj.leasingObjectYear !== null
    },
    noBrandItems() {
      return Object.keys(this.brandItems).length === 0
    },
    noModelItems() {
      return Object.keys(this.modelItems).length === 0
    },
    hasAnnuity() {
      if(this.calcObj.graphType === null) return false
      return this.calcObj.graphType.indexOf('annuity') > -1
    },
    hasForeignCurrency() {
      if(this.calcObj.leasingCurrency === null) return false
      return this.calcObj.leasingCurrency.indexOf('EUR') > -1
        || this.calcObj.leasingCurrency.indexOf('USD') > - 1
    },
    isClientType() {
      return this.calcObj.leasingClientType !== null
    },

    /* vuelidate error handlers */
    itemCostErrors() {
      const errors = []
      if (!this.$v.calcObj.leasingAmount.$error) return errors
      !this.$v.calcObj.leasingAmount.minCost && errors.push(`Вартiсть має бути бильше нiж 150 000грн`)
			return errors
    },
    leasingClientTypeErr() {
      if (!this.$v.calcObj.leasingClientType.$error) return
      return this.commonErr
    },
    isNewErr() {
      if (!this.$v.calcObj.isNew.$error) return
      return this.commonErr
    },
    currencyErr() {
      if (!this.$v.calcObj.currency.$error) return
      return this.commonErr
    },
    leasingObjectTypeErr() {
      if (!this.$v.calcObj.leasingObjectType.$error) return
      return this.commonErr
    },
    leasedAssertMarkErr() {
      if (!this.$v.calcObj.leasedAssertMark.$error) return
      return this.commonErr
    },
    leasedAssertModelErr() {
      if (!this.$v.calcObj.leasedAssertModel.$error) return
      return this.commonErr
    },
    leasingObjectYearErr() {
      if (!this.$v.calcObj.leasingObjectYear.$error) return
      return this.commonErr
    },
    leasedAssertEngineErr() {
      if (!this.$v.calcObj.leasedAssertEngine.$error) return
      return this.commonErr
    },
    leasingCurrencyErr() {
      if (!this.$v.calcObj.leasingCurrency.$error) return
      return this.commonErr
    },
    leasingCurrencyCourseErr() {
      if(!this.hasForeignCurrency) return
      if (!this.$v.calcObj.leasingCurrencyCourse.$error) return
      return this.commonErr
    },
    leasingQuantityErr() {
      if (!this.$v.calcObj.leasingQuantity.$error) return
      return this.commonErr
    },
    graphTypeErr() {
      if(this.currentTab === 0) return
      if (!this.$v.calcObj.graphType.$error) return
      return this.commonErr
    },
    gainEvenGraphicMonthsErr() {
      if(this.currentTab === 0 || !this.hasAnnuity) return
      if (!this.$v.calcObj.gainEvenGraphicMonths.$error) return
      return this.commonErr
    },
    gainEvenGraphicPercentErr() {
      if(this.currentTab === 0 || !this.hasAnnuity) return
      if (!this.$v.calcObj.gainEvenGraphicPercent.$error) return
      return this.commonErr
    },
    UnsrMonthsErr() {
      if(this.currentTab === 0 || !this.hasAnnuity) return
      if (!this.$v.calcObj.UnsrMonths.$error) return
      return this.commonErr
    },
    advanceErr() {
      if(this.currentTab === 0) return
      if (!this.$v.calcObj.advance.$error) return
      return this.commonErr
    },
    leasingTermErr() {
      if(this.currentTab === 0) return
      if (!this.$v.calcObj.leasingTerm.$error) return
      return this.commonErr
    },
    vehicleOwnerTaxErr() {
      if(this.currentTab === 0) return
      if (!this.$v.calcObj.vehicleOwnerTax.$error) return
      return this.commonErr
    },
    paymentPfErr() {
      if(this.currentTab === 0) return
      if (!this.$v.calcObj.paymentPf.$error ) return
      return this.commonErr
    },
    gpsTrackerModelErr() {
      if(this.currentTab === 0) return
      if (!this.$v.calcObj.gpsTrackerModel.$error ) return
      return this.commonErr
    },
    insuranceProgramErr() {
      if(this.currentTab === 0) return
      if (!this.$v.calcObj.insuranceProgram.$error ) return
      return this.commonErr
    },
    insuranceFranchiseErr() {
      if(this.currentTab === 0) return
      if (!this.$v.calcObj.insuranceFranchise.$error ) return
      return this.commonErr
    }
  },
  methods: {
    getMarksByType() {
      this.$store.commit('toggleSpinner', true)
      axios.get(`/mark?category=${this.calcObj.leasingObjectType.value}`)
        .then(response => {
          this.brandItems = response.data
          this.$store.commit('toggleSpinner', false)
        })
        .catch(error => {
          let message = error.response.statusText
          this.notify('Помилка', message, 'error')
          this.$store.commit('toggleSpinner', false)
        })
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
    parseToFloat(id) {
      let input = new Event('input', {bubbles: true})
      let el = document.getElementById(id)
      let value = el.value
      let intVal = el.value.replace(/\.\d\.|\.+\d+\.+|\.{2,2}|[^\d.]/g, '')
      if(value !== intVal) {
        el.value = intVal
        el.dispatchEvent(input)
      }
    },
    getModelByMark() {
      this.$store.commit('toggleSpinner', true)
      let categorieId = this.calcObj.leasingObjectType.value
      axios.get(`/models?category=${categorieId}&mark=${this.calcObj.leasedAssertMark.value}`)
        .then(response => {
          this.modelItems = response.data
          this.$store.commit('toggleSpinner', false)
        })
        .catch(error => {
          let message = error.response.statusText
          this.notify('Помилка', message, 'error')
          this.$store.commit('toggleSpinner', false)
        })
    },
    notify(title, text, group) {
			this.$notify({
				group: group || 'standard',
				title: title,
				text: text,
			})
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
    submit() {
      console.log(this.calcObj)
      this.highlightErrors()
      !this.$v.$invalid
      && this.$v.$dirty
      && this.currentTab === 0
        ? this.currentTab = 1
        : this.currentTab === 1
          && !this.$v.$invalid
          && this.$v.$dirty
            ? this.sendRequest()
            : this.notify('', 'Заповнiть даннi', 'error')
      
    },
    sendRequest() {
      this.$store.commit('toggleSpinner', true)
      axios
        .post('/calculate', this.calcObj)
          .then(response => {
            console.log(response)
            this.$store.commit('toggleSpinner', false)
            this.$router.push('/calculator/chart')
          })
          .catch(error => {
            console.log(error.response)
            const message = error.response.statusText
            this.notify('Помилка', message, 'error')
            this.$store.commit('toggleSpinner', false)
            setTimeout(() => {
              this.$router.go()
            },4000)
          })
    },
  },
  watch: {
    insuranceProgram(val) {
      this.calcObj.insuranceProgram = val.value
    },
    'calcObj.leasingCurrencyCourse': function (course) {
      if(course == null){ 
        !this.hasForeignCurrency
          ? this.calcObj.leasingCurrencyCourse = "1"
          : false 
      } else {
        if(Number.isNaN(parseFloat(course))) return
        this.calcObj.leasingCurrencyCourse = parseFloat(course).toString()
      }
    },
    'calcObj.leasingCurrency': function(currency) {
      if(currency === 'UAH') this.calcObj.leasingCurrencyCourse = 1
      else if(currency === "EUR" || currency === "USD") {
        this.calcObj.leasingCurrencyCourse = ''
      }
    },
    'calcObj.gainEvenGraphicMonths': function(value) {
      if(!value) return value
      this.calcObj.gainEvenGraphicMonths = parseInt(value)
    },
    'calcObj.gainEvenGraphicPercent': function(value) {
      if(!value) return value
      else if(Number.isNaN(parseFloat(value))) return value
      this.calcObj.gainEvenGraphicPercent = parseFloat(value)
    },
    'calcObj.UnsrMonths': function(value) {
      if(!value) return value
      this.calcObj.UnsrMonths = parseInt(value)
    },
    'calcObj.leasingQuantity': function(value) {
      if(!value) return value
      this.calcObj.leasingQuantity = parseInt(value)
    },
    'calcObj.leasedAssertEngine': function(value) {
      if(!value) return value
      this.calcObj.leasedAssertEngine = parseInt(value)
    }
  },
  mounted() {
    this.calcObj._token = this.getCsrf()
    this.calcObj.agentId = this.$store.state.user.user_id
  }
}
</script>

<style lang="scss">
  .calculation__tab-btn {
    background: grey;
    color: white;
    padding: 0 18px;
    line-height: 40px;
  }
  .content .v-card__title {
    padding-bottom: 0;
  }
  .content .v-card__actions {
    padding-left: 20px;
  }
  .tab-btn--active {
    background: black;
    color: white;
  }
  .content__switch {
    display: flex;
    align-items: center;
    border: 2px solid #9e9e9e;
    border-radius: 8px;
    padding: 0 8px;
  }
  .content__switch-title-wrapper {
    width: 70%;
  }
  .content__switch-title {
    display: block;
    padding: 0 5px 0 8px;
  }
  .content__switch-wrapper {
    display: flex; 
    width: 30%; 
    justify-content: flex-start;
  }
  .switch-label-prepend {
    color: black!important; 
    padding: 4px 6px 0 8px;
    font-size: 17px;
  }
  .slider-prepend {
    font-size: 17px;
    // color: black!important; 
  }
  .slider-wrapper {
    // .slider-prepend {
    //   text-decoration: underline;
    // }
  }
  .slider-wrapper .v-input__append-outer {
    margin: 0 4px;
    padding: 5px;
    background: #6f6f6f;
    color: white!important;
    border-radius: 4px;
  }
  .slider-wrapper .v-input__prepend-outer {
    margin: 0 4px;
    padding: 5px;
    background: #6f6f6f;
    color: white!important;
    border-radius: 4px;
  }
  .calculator-radio label {
    color: black!important;
  }
  @media(max-width: 716px) {
    .content__switch {
      flex-direction: column;
      justify-content: flex-start;
      align-items: start;
    }
    .content__switch-wrapper,
    .content__switch-title-wrapper {
      width: 100%;
    }
  }
  /* hide input number field arrows */
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  input[type=number] {
    -moz-appearance: textfield;
  }
  // .v-input--selection-controls__input label {
  //   color: black!important;
  // }
</style>