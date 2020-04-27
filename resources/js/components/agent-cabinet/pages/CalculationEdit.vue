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
                  <v-radio :value="2" color="black">
                    <template #label>
                      <span>Фiзична особа</span>
                    </template>
                  </v-radio>
                  <v-radio :value="1" color="black">
                    <template #label>
                      <span>Юридична особа</span>
                    </template>
                  </v-radio>
                </v-radio-group>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md=3>
                <v-select 
                  v-model="calcObj.isNew"
                  :items="selects.itemConditions"
                  itemColor="red darken-4"
                  :error-messages="isNewErr"
                  :placeholder="calcObj.isNew !== null 
                    ? `${calcObj.isNew}` 
                    : 'Тип авто'"
                  label="Тип авто"
                  color="red darken-4"
                  dense outlined>
                </v-select>
              </v-col>
              <v-col cols="12" md="3">
                <v-select
                  v-model="calcObj.currency"
                  :items="selects.currencys"
                  itemColor="red darken-4"
                  :error-messages="currencyErr"
                  :placeholder="calcObj.currency !== null 
                    ? `${calcObj.currency}` 
                    : 'Валюта договору лізингу'"
                  label="Валюта договору лізингу"
                  color="red darken-4"
                  dense outlined>
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
                  item-value="label"
                  return-object
                  :placeholder="calcObj.leasingObjectType !== null 
                    ? `${calcObj.leasingObjectType}` 
                    : 'Тип предмета лізингу'"
                  label="Тип предмета лізингу"
                  itemColor="red darken-4"
                  ref="refLoading"
                  color="red darken-4"
                  dense outlined>
                </v-select>
              </v-col>
              <v-col cols="12" md="4">
                <v-autocomplete
                  @change="getModelByMark()"
                  v-model="calcObj.leasedAssertMark"
                  :items="brandItems"
                  itemColor="red darken-4"
                  :error-messages="leasedAssertMarkErr"
                  item-text="name"
                  item-value="value"
                  return-object
                  :placeholder="calcObj.leasedAssertMark !== null 
                    ? `${calcObj.leasedAssertMark}` 
                    : 'Марка'"
                  label="Марка"
                  loaderHeight="1"
                  :loading="!leasedOfAssetType && $store.state.loader 
                    || noBrandItems && $store.state.loader"
                  color="red darken-4"
                  dense outlined>
                </v-autocomplete>
              </v-col>
              <v-col cols="12" md="4">
                <v-autocomplete
                  v-model="calcObj.leasedAssertModel"
                  :items="modelItems"
                  itemColor="red darken-4"
                  :error-messages="leasedAssertModelErr"
                  item-text="name"
                  item-value="value"
                  return-object
                  :placeholder="calcObj.leasedAssertModel !== null 
                    ? `${calcObj.leasedAssertModel}` 
                    : 'Модель'"
                  label="Модель"
                  loaderHeight="1"
                  :loading="$store.state.loader && modelOfItem"
                  color="red darken-4"
                  dense outlined>
                </v-autocomplete>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="3" v-if="calcObj.isNew !== null">
                <v-select
                  v-model="calcObj.leasingObjectYear" 
                  :items="calcObj.isNew ? selects.itemYears : selects.oldItemYears"
                  itemColor="red darken-4"
                  :error-messages="leasingObjectYearErr"
                  :placeholder="calcObj.leasingObjectYear !== null 
                    ? `${calcObj.leasingObjectYear}` 
                    : 'Рік'"
                  label="Рiк"
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
                  :placeholder="calcObj.leasedAssertEngine !== null 
                    ? `${calcObj.leasedAssertEngine}` 
                    : 'Об\'єм двигуна'"
                  label="Об'єм двигуна" 
                  color="red darken-4"
                  dense outlined>
                </v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <!-- @input="$v.calcObj.leasingAmount.$touch(); 
                    parseToInt('leasingAmount')" 
                    :error-messages="itemCostErrors" -->
                <v-text-field
                  v-model="calcObj.leasingAmount"
                  id="leasingAmount"
                  :placeholder="calcObj.leasingAmount !== null 
                    ? `${calcObj.leasingAmount}` 
                    : 'Вартість'"
                  label="Вартість"
                  color="red darken-4"
                  dense outlined>
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="3">
                <v-select
                  :items="selects.currencys"
                  itemColor="red darken-4"
                  :error-messages="leasingCurrencyErr"
                  :placeholder="calcObj.leasingCurrency !== null 
                    ? `${calcObj.leasingCurrency}` 
                    : 'Валюта'"
                  label="Валюта"
                  v-model="calcObj.leasingCurrency"
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
                  @input="parseToFloat('leasingCurrencyCourse')"
                  id="leasingCurrencyCourse"
                  :placeholder="calcObj.leasingCurrencyCourse !== null 
                    ? `${calcObj.leasingCurrencyCourse}` 
                    : 'Курс'"
                  label="Курс"
                  color="red darken-4"
                  dense>
                </v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="calcObj.leasingQuantity" 
                  :error-messages="leasingQuantityErr"
                  @input="parseToInt('leasingQuantity')"
                  id="leasingQuantity"
                  :placeholder="calcObj.leasingQuantity 
                    ? `${calcObj.leasingQuantity}` 
                    : 'Кiлькiсть'"
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
                  itemColor="red darken-4"
                  :error-messages="graphTypeErr"
                  ref="graphType"
                  :placeholder="calcObj.graphType !== null 
                    ? `${calcObj.graphType}` 
                    : 'Тип графіку'"
                  label="Тип графіку"
                  color="red darken-4"
                  multiple deletable-chips chips>
                  <template #append-item>
                    <v-btn class="ml-2 mt-1 mb-1 d-block" small dark @click="closeSelect()">Ok</v-btn>
                  </template>
                </v-select>
              </v-col>
            </v-row>
            <!-- v-show="hasAnnuity" -->
            <v-card v-show="hasAnnuity" class="d-inline-block mb-3">
            <v-card-title class="headline">Посилання для рiвномiрного графiку</v-card-title>
            <v-card-text class="pb-0">
              <v-row>
                <v-col cols="9" md="4">
                  <v-text-field
                    v-model="calcObj.gainEvenGraphicMonths" 
                    :error-messages="gainEvenGraphicMonthsErr"
                    :placeholder="calcObj.gainEvenGraphicMonths !== null 
                      ? `${calcObj.gainEvenGraphicMonths}` 
                      : 'Кiлькiсть мiсяцiв'"
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
                    :placeholder="calcObj.gainEvenGraphicPercent !== null 
                      ? `${calcObj.gainEvenGraphicPercent}` 
                      : 'Вiдсоток посилення'"
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
                    :placeholder="calcObj.UnsrMonths !== null 
                      ? `${calcObj.UnsrMonths}` 
                      : 'УНСПР, місяцiв'"
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
                <v-chip class="custom-chip ma-1 font-weight-thin" color="white" text-color="black">Авансовий платiж</v-chip>
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
                      <span class="slider-prepend">
                        20<v-icon small class="ml-1" color="white" v-text="'mdi-percent-outline'"></v-icon>
                      </span>
                    </template>
                    <template #append>
                      <span class="slider-prepend">
                        70<v-icon small class="ml-1" color="white" v-text="'mdi-percent-outline'"></v-icon>
                      </span>
                    </template>
                  </v-slider>
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="10">
                <div :class="`slider-wrapper d-flex flex-${smAndDown ? 'column' : 'row'}`">
                  <v-chip class="custom-chip ma-1" color="white" text-color="black">Термiн лiзингу</v-chip>
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
                      <span class="slider-prepend">12&nbsp;мiс</span>
                    </template>
                    <template v-slot:append>
                      <span class="slider-prepend">72&nbsp;мiс</span>
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
                    itemColor="red darken-4"
                    :error-messages="gpsTrackerModelErr"
                    :placeholder="calcObj.gpsTrackerModel !== null 
                      ? `${calcObj.gpsTrackerModel}` 
                      : 'GPS-трекер'"
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
                    v-model="calcObj.insuranceProgram" 
                    :items="selects.insurancePrograms"
                    :error-messages="insuranceProgramErr"
                    item-text="text"
                    item-value="text"
                    return-object=""
                    :placeholder="calcObj.insuranceProgram !== null 
                      ? `${calcObj.insuranceProgram}` 
                      : 'Програма страхування'"
                    label="Програма страхування"
                    itemColor="red darken-4"
                    color="red darken-4"
                    dense outlined>
                </v-select>
              </v-col>
              <v-col cols="12" md="4">
                <v-select
                    v-model="calcObj.insuranceFranchise" 
                    :items="selects.franchises"
                    item-text="text"
                    item-value="value"
                    itemColor="red darken-4"
                    :error-messages="insuranceFranchiseErr"
                    :placeholder="calcObj.insuranceFranchise !== null 
                      ? `${calcObj.insuranceFranchise}` 
                      : 'Франшиза'"
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
      urkAssistService: 1,
      gainEvenGraphicMonths: null,
      gainEvenGraphicPercent: null,
      UnsrMonths: null,
      agentId: null,
      leasedAssertMark: null,
      leasedAssertModel: null,
      gpsTrackerModel: null,
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
    user() {
      return Object.keys(this.$store.state.user.agent).length > 0
    },
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
        // leasingAmount: {
        //   minCost: val => {
        //     if(val == null) return false
        //     return parseInt(val.replace(/[^\d]/g, '')) >= this.minCarCost
        //   },
        // },
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
      return this.calcObj['UNSPR-month'] !== null 
        || this.calcObj['UNSPR-month'] !== 'undefined'
    },
    hasForeignCurrency() {
      if(this.calcObj.leasingCurrency === null) return false
      return true
    },
    isClientType() {
      return this.calcObj.leasingClientType !== null
    },

    /* vuelidate error handlers */
    // itemCostErrors() {
    //   const errors = []
    //   if (!this.$v.calcObj.leasingAmount.$error) return errors
    //   !this.$v.calcObj.leasingAmount.minCost && errors.push(`Вартiсть має бути бильше нiж 150 000грн`)
		// 	return errors
    // },
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
    closeSelect() {
      this.$refs.graphType.blur()
    },
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
      // this.highlightErrors()
      // !this.$v.$invalid
      // && this.$v.$dirty
      // && this.currentTab === 0
      //   ? this.currentTab = 1
      //   : this.currentTab === 1
      //     && !this.$v.$invalid
      //     && this.$v.$dirty
      //       ? this.sendRequest()
      //       : this.notify('', 'Заповнiть даннi', 'error')
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
          })
    },
    test() {
      
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
    },
    user() {
      if(this.user) {
        this.calcObj.agentId = this.$store.state.user.agent.id
        console.log(this.$store.state.user)
      } else {
        return
      }
    }
  },
  mounted() {
    console.log(this.$router.currentRoute.params)
    if(this.$router.currentRoute.params.edit === true) {
    axios
      .get(`/calculation/${this.$router.currentRoute.params.id}`)
      .then(response => {
        let data = response.data.request_data
        console.log(response.data.request_data)
        let calcObj = {
          urkAssistService: data['UNSPR-month'],
          gainEvenGraphicMonths: data['gain-even-graphic-months'],
          gainEvenGraphicPercent: data['gain-even-graphic-percent'],
          UnsrMonths: data['UNSPR-month'],
          agentId: data['assist-service'],
          leasedAssertMark: data['leased-assert-mark'],
          leasedAssertModel: data['leased-assert-model'],
          gpsTrackerModel: data['gps-tracker-model'],
          // isNew: data,
          leasingObjectType: data['leasing-object-type'],
          // leasingQuantity: data,
          // leasingObjectYear: data,
          leasedAssertEngine: data['leased-assert-engine'],
          leasingClientType: data['client-type'],
          currency: data['currency'],
          leasingCurrency: data['leasing-currency'],
          // leasingCurrencyCourse: data,
          leasingAmount: data['leasing-amount'],
          // graphType: data,
          advance: data['advance'], // Уточни данные приходят меньше чем вводил пользователь
          leasingTerm: data['leasing-term'],
          vehicleOwnerTax: data['vehicle-owner-tax'],
          paymentPf: data['payment-PF'],
          insuranceProgram: data['insurance-program'],
          insuranceFranchise: data['insurance-franchise'],
        }
        Object.assign(this.calcObj, calcObj)
        console.log(this.calcObj)
      })
      .catch(error => {
        console.log(error.response)
      })
    }
    this.calcObj._token = this.getCsrf()
    this.calcObj.agentId = this.$store.state.user.agent.id
  }
}
</script>
