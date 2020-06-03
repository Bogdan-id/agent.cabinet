<template>
<div  style="border-radius: 8px 8px 0 0; position: relative; border-radius: 8px 8px 0 0; overflow: hidden; margin-right: 15px;">
  <div style="position: absolute; top: 0; left: 0; right: 0; height: 560px; background-color: #d24a43; padding: 10px 0"></div>
  <div class="text-color-for-red-absolute" style="z-index:1; position: relative; color: white;l">
    <div style="padding: 1rem 2rem">
      <span style="font-size: 1.5rem">Пiдбери найвигiднiшi умови фiнансування</span>
    </div>
    <!-- main container -->
    <div class="calculator-red-block" style="margin: 0 19px; border:1px solid #e5e5e5; border-radius: 8px; padding: 15px 25px;">
      <v-row class="leasing-types">
        <v-col cols="12" sm="4" md="2">
          <input @change="getMarksByType()" v-model="calcObj.leasingObjectType" type="radio" id="car" name="leasing-type" :value="1" checked>
          <label for="car" style="display: inline-block;">
            <car style="height: 50px;"></car>
            <span style="display: block; border-bottom: 4px solid white; padding-bottom: 0.35rem;">Легковi авто</span>
          </label>
        </v-col>
        <v-col cols="12" sm="4" md="2">
          <input @change="getMarksByType()" v-model="calcObj.leasingObjectType" type="radio" id="cargo" name="leasing-type" :value="6">
          <label for="cargo" style="display: inline-block;">
            <cargo style="height: 50px;"></cargo>
            <span style="display: block; border-bottom: 4px solid white; padding-bottom: 0.35rem;">Вантажiвки</span>
          </label>
        </v-col>
        <v-col cols="12" sm="4" md="2">
          <input @change="getMarksByType()" v-model="calcObj.leasingObjectType" type="radio" id="special" name="leasing-type" :value="4">
          <label for="special" style="display: inline-block;">
            <special style="height: 50px;"></special>
            <span style="display: block; border-bottom: 4px solid white; padding-bottom: 0.35rem;">Спецтехнiка</span>
          </label>
        </v-col>
        <v-col cols="12" sm="4" md="2">
          <input @change="getMarksByType()" v-model="calcObj.leasingObjectType" type="radio" id="agricultural" name="leasing-type" :value="6">
          <label for="agricultural" style="display: inline-block;">
            <agricultural style="height: 50px;"></agricultural>
            <span style="display: block; border-bottom: 4px solid white; padding-bottom: 0.35rem;">С/Г Технiка</span>
          </label>
        </v-col>
        <v-col cols="12" sm="4" md="2">
          <input @change="getMarksByType()" v-model="calcObj.leasingObjectType" type="radio" id="equipment" name="leasing-type" :value="6">
          <label for="equipment" style="display: inline-block;">
            <equipment style="height: 50px;"></equipment>
            <span style="display: block; border-bottom: 4px solid white; padding-bottom: 0.35rem;">Обладнання</span>
          </label>
        </v-col>
        <v-col cols="12" sm="4" md="2">
          <input @change="getMarksByType()" v-model="calcObj.leasingObjectType" type="radio" id="trailer" name="leasing-type" :value="6">
          <label for="trailer" style="display: inline-block;">
            <div style="height: 50px;"></div>
            <span style="display: block; border-bottom: 4px solid white; padding-bottom: 0.35rem;">Причепи та напiвпричепи</span>
          </label>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6" class="pt-0 pb-0">
          <v-radio-group
            dark
            color="white"
            v-model="calcObj.leasingClientType"
            :error-messages="leasingClientTypeErr"
            row>
            <v-radio :value="1" color="white">
              <template #label>
                <span>Фiзична особа</span>
              </template>
            </v-radio>
            <v-radio :value="2" color="white">
              <template #label>
                <span>Юридична особа</span>
              </template>
            </v-radio>
          </v-radio-group>
        </v-col>
      </v-row>
      <!--  -->
      <v-row>
        <v-col cols="12" md="6" class="pt-0 pb-0">
          <v-row>
            <v-col cols="12" md="4">
              <div style="min-height: 40px; display: inline-flex; align-items: center;">
                <span style="display: block; font-size: 0.93rem; color: #e5e5e5">Тип авто</span>
              </div>
            </v-col>
            <v-col cols="12" md="7">
              <v-select
                background-color="white"
                append-icon="mdi-chevron-down"
                v-model="calcObj.isNew"
                :items="selects.itemConditions"
                itemColor="red darken-4"
                :error-messages="isNewErr"
                label="Тип авто"
                color="red darken-4"
                dense outlined :disabled="!isClientType">
              </v-select>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="6" class="pt-0 pb-0">
          <v-row style="display: flex; justify-content: flex-end;">
            <v-col cols="12" md="4">
              <div style="min-height: 40px; display: inline-flex; align-items: center;">
                <span style="display: block; font-size: 0.93rem; color: #e5e5e5">Предмет лізингу</span>
              </div>
            </v-col>
            <v-col cols="12" md="7">
              <v-select
                background-color="white"
                append-icon="mdi-chevron-down"
                @change="getMarksByType()"
                v-model="calcObj.leasingObjectType"
                :items="selects.itemTypes"
                :error-messages="leasingObjectTypeErr"
                item-text="label"
                item-value="text"
                return-object
                label="Тип предмета лізингу"
                itemColor="red darken-4"
                ref="refLoading"
                :disabled="!typeOfCar"
                color="red darken-4"
                dense outlined>
              </v-select>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <!--  -->
      <v-row>
        <v-col cols="12" md="6" class="pt-0 pb-0">
          <v-row style="display: flex;">
            <v-col cols="12" md="4">
              <div style="min-height: 40px; display: inline-flex; align-items: center;">
                <span style="display: block; font-size: 0.93rem; color: #e5e5e5">Марка</span>
              </div>
            </v-col>
            <v-col cols="12" md="7">
              <v-autocomplete
                background-color="white"
                append-icon="mdi-chevron-down"
                @change="getModelByMark()"
                v-model="calcObj.leasedAssertMark"
                :items="brandItems"
                itemColor="red darken-4"
                :error-messages="leasedAssertMarkErr"
                item-text="name"
                item-value="value"
                return-object
                label="Марка"
                loaderHeight="1"
                :loading="!leasedOfAssetType && $store.state.loader
                  || noBrandItems && $store.state.loader"
                :disabled="!leasedOfAssetType || noBrandItems"
                color="red darken-4"
                dense outlined>
              </v-autocomplete>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="6" class="pt-0 pb-0">
          <v-row style="display: flex; justify-content: flex-end;">
            <v-col cols="12" md="4">
              <div style="min-height: 40px; display: inline-flex; align-items: center;">
                <span style="display: block; font-size: 0.93rem; color: #e5e5e5">Модель</span>
              </div>
            </v-col>
            <v-col cols="12" md="7">
              <v-autocomplete
                background-color="white"
                append-icon="mdi-chevron-down"
                v-model="calcObj.leasedAssertModel"
                :items="modelItems"
                itemColor="red darken-4"
                :error-messages="leasedAssertModelErr"
                item-text="name"
                item-value="value"
                return-object
                label="Модель"
                loaderHeight="1"
                :loading="$store.state.loader && modelOfItem"
                :disabled="!modelOfItem || modelItems.length === 0"
                color="red darken-4"
                dense outlined>
              </v-autocomplete>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <!--  -->
      <v-row>
        <v-col cols="12" md="6" class="pt-0 pb-0">
          <v-row style="display: flex;">
            <v-col cols="12" md="4">
              <div style="min-height: 40px; display: inline-flex; align-items: center;">
                <span style="display: block; font-size: 0.93rem; color: #e5e5e5">Об'єм двигуна</span>
              </div>
            </v-col>
            <v-col cols="12" md="7">
              <v-text-field
                background-color="white"
                v-model="calcObj.leasedAssertEngine"
                @input="parseToInt('leasedAssertEngine')"
                id="leasedAssertEngine"
                :error-messages="leasedAssertEngineErr"
                label="Об'єм двигуна"
                :disabled="!yearOfModel"
                color="red darken-4"
                dense outlined>
                <template v-slot:append>
                  <span style="color: grey!important; display: block; margin-top: 5px;">см3</span>
                </template>
              </v-text-field>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="6" class="pt-0 pb-0">
          <v-row style="display: flex; justify-content: flex-end;">
            <v-col cols="12" md="4">
              <div style="min-height: 40px; display: inline-flex; align-items: center;">
                <span style="display: block; font-size: 0.93rem; color: #e5e5e5">Рік</span>
              </div>
            </v-col>
            <v-col cols="12" md="7">
              <v-select
                background-color="white"
                append-icon="mdi-chevron-down"
                v-model="calcObj.leasingObjectYear"
                :items=" calcObj.isNew ? selects.itemYears : selects.oldItemYears"
                itemColor="red darken-4"
                :error-messages="leasingObjectYearErr"
                label="Рік"
                :disabled="!typeOfModel"
                color="red darken-4"
                dense outlined>
              </v-select>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <!--  -->
      <v-row>
        <v-col cols="12" md="6" class="pt-0 pb-0">
          <v-row style="display: flex;">
            <v-col cols="12" md="4">
              <div style="min-height: 40px; display: inline-flex; align-items: center;">
                <span style="display: block; font-size: 0.93rem; color: #e5e5e5">Валюта</span>
              </div>
            </v-col>
            <v-col cols="12" md="7">
              <v-select
                background-color="white"
                append-icon="mdi-chevron-down"
                v-model="calcObj.currency"
                :items="selects.currencys"
                itemColor="red darken-4"
                :error-messages="currencyErr"
                label="Оберiть валюту"
                color="red darken-4"
                dense outlined :disabled="!isClientType">
              </v-select>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="6" class="pt-0 pb-0">
          <v-row style="display: flex; justify-content: flex-end">
            <v-col cols="12" md="4">
              <div style="min-height: 40px; display: inline-flex; align-items: center;">
                <span style="display: block; font-size: 0.93rem; color: #e5e5e5">Вартiсть</span>
              </div>
            </v-col>
            <v-col cols="12" md="7">
              <v-text-field
                background-color="white"
                @input="$v.calcObj.leasingAmount.$touch();
                  parseToInt('leasingAmount')"
                :error-messages="itemCostErrors"
                v-model="calcObj.leasingAmount"
                id="leasingAmount"
                label="Вартість"
                color="red darken-4"
                :disabled="calcObj.leasedAssertEngine === null"
                dense outlined>
                <template v-slot:append>
                  <span style="color: grey!important; display: block; margin-top: 5px;">грн</span>
                </template>
              </v-text-field>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="3">
          <v-select
            :items="selects.currencys"
            append-icon="mdi-chevron-down"
            itemColor="red darken-4"
            :error-messages="leasingCurrencyErr"
            label="Валюта"
            v-model="calcObj.leasingCurrency"
            :disabled="calcObj.leasingAmount === null"
            color="red darken-4"
            dense>
          </v-select>
        </v-col>
        <v-col cols="12" md="3">
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
      <v-row>
        <v-col cols="12" md="9">
          <v-select
            v-model="calcObj.graphType"
            append-icon="mdi-chevron-down"
            :items="selects.chartTypes"
            itemColor="red darken-4"
            :error-messages="graphTypeErr"
            ref="graphType"
            placeholder="Оберiть тип графiку"
            color="red darken-4"
            multiple deletable-chips chips>
            <template #append-item>
              <v-btn
                @click="closeSelect()"
                color="grey darken-4"
                v-show="!hasGraph"
                class="ml-3 mt-3 mb-1 d-block"
                fab dark x-small>
                <v-icon small v-text="'mdi-check-bold'"></v-icon>
              </v-btn>
            </template>
          </v-select>
        </v-col>
      </v-row>
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
      <v-row>
        <v-col cols="12" md="10">
        <div :class="`slider-wrapper d-flex flex-${smAndDown ? 'column' : 'row'}`">
          <!-- create v-chip absolute on border-top -->
          <v-chip class="custom-chip ma-1 font-weight-thin" color="white" text-color="black">Авансовий платiж</v-chip>
            <v-slider
              v-model="calcObj.advance"
              :error-messages="advanceErr"
              min="20"
              max="70"
              color="red"
              track-color="grey lighten-2"
              persistent-hint
              thumb-label>
              <template #prepend>
                <span class="slider-prepend">20<v-icon small class="ml-1" color="white" v-text="'mdi-percent-outline'"></v-icon></span>
              </template>
              <template #append>
                <span class="slider-prepend">70<v-icon small class="ml-1" color="white" v-text="'mdi-percent-outline'"></v-icon></span>
              </template>
            </v-slider>
          </div>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="10">
          <div :class="`slider-wrapper d-flex flex-${smAndDown ? 'column' : 'row'}`">
            <v-chip class="custom-chip ma-1 font-weight-thin" color="white" text-color="black">Термiн лiзингу</v-chip>
            <v-slider
              v-model="calcObj.leasingTerm"
              :error-messages="leasingTermErr"
              min="12"
              max="72"
              color="red"
              track-color="grey lighten-2"
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
            flat
            v-model="calcObj.gpsTrackerModel"
            append-icon="mdi-chevron-down"
            :items="selects.gpsTrackers"
            itemColor="red darken-4"
            :error-messages="gpsTrackerModelErr"
            class="mt-1 mb-1"
            label="GPS-трекер"
            color="red darken-4"
            dense>
          </v-select>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="4">
          <v-select
              v-model="insuranceProgram"
              append-icon="mdi-chevron-down"
              :items="selects.insurancePrograms"
              :error-messages="insuranceProgramErr"
              item-text="text"
              item-value="text"
              return-object
              label="Програма страхування"
              itemColor="red darken-4"
              color="red darken-4"
              dense outlined>
          </v-select>
        </v-col>
        <v-col cols="12" md="4">
          <v-select
              v-model="calcObj.insuranceFranchise"
              append-icon="mdi-chevron-down"
              :items="selects.franchises"
              itemColor="red darken-4"
              :error-messages="insuranceFranchiseErr"
              label="Франшиза"
              color="red darken-4"
              dense outlined>
          </v-select>
        </v-col>
      </v-row>
      <v-card-actions class="d-flex justify-center ">
        <span>
          <v-btn @click="submit()" class="mb-3" dark color="grey darken-3">
          {{'Розрахувати'}}
          </v-btn>
        </span>
      <!-- <v-btn @click="test()">test</v-btn> -->
    </v-card-actions>
  </div>
  </div>
</div>
</template>

<script>
import axios from 'axios'
import selectsItemAndValue from './selectItems'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

import Car from '../../assets/svg-icons/car.vue'
import Agricultural from '../../assets/svg-icons/agricultural.vue'
import Equipment from '../../assets/svg-icons/equipment.vue'
import Special from '../../assets/svg-icons/special.vue'
import Cargo from '../../assets/svg-icons/cargo.vue'

export default {
  mixins: [validationMixin],
  components: {
    Car, Agricultural, Equipment, Special, Cargo
  },
  data:() => ({
    selects: selectsItemAndValue,
    minCarCost: 150000,
    insuranceProgram: null,
    commonErr: ['Обов`язкове поле'],

    brandItems: [],
    modelItems: [],

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
    user() {
      return Object.keys(this.$store.state.user.agent).length > 0
    },
    validationRules() {
      return this.validateObj
    },

    validateObj() {
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
        leasingCurrencyCourse: { required },
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
    hasGraph() {
      if(this.calcObj.graphType === null || this.calcObj.graphType === 'undefined') return false
      return this.calcObj.graphType.length > 0
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
      if (!this.hasForeignCurrency) return
      if (!this.$v.calcObj.leasingCurrencyCourse.$error) return
      return this.commonErr
    },
    leasingQuantityErr() {
      if (!this.$v.calcObj.leasingQuantity.$error) return
      return this.commonErr
    },
    graphTypeErr() {
      if (!this.$v.calcObj.graphType.$error) return
      return this.commonErr
    },
    gainEvenGraphicMonthsErr() {
      if(!this.hasAnnuity) return
      if (!this.$v.calcObj.gainEvenGraphicMonths.$error) return
      return this.commonErr
    },
    gainEvenGraphicPercentErr() {
      if(!this.hasAnnuity) return
      if (!this.$v.calcObj.gainEvenGraphicPercent.$error) return
      return this.commonErr
    },
    UnsrMonthsErr() {
      if(!this.hasAnnuity) return
      if (!this.$v.calcObj.UnsrMonths.$error) return
      return this.commonErr
    },
    advanceErr() {
      if (!this.$v.calcObj.advance.$error) return
      return this.commonErr
    },
    leasingTermErr() {
      if (!this.$v.calcObj.leasingTerm.$error) return
      return this.commonErr
    },
    vehicleOwnerTaxErr() {
      if (!this.$v.calcObj.vehicleOwnerTax.$error) return
      return this.commonErr
    },
    paymentPfErr() {
      if (!this.$v.calcObj.paymentPf.$error ) return
      return this.commonErr
    },
    gpsTrackerModelErr() {
      if (!this.$v.calcObj.gpsTrackerModel.$error ) return
      return this.commonErr
    },
    insuranceProgramErr() {
      if (!this.$v.calcObj.insuranceProgram.$error ) return
      return this.commonErr
    },
    insuranceFranchiseErr() {
      if (!this.$v.calcObj.insuranceFranchise.$error ) return
      return this.commonErr
    }
  },
  methods: {
    test() {
      console.log('event work')
    },
    closeSelect() {
      this.$refs.graphType.blur()
    },
    getMarksByType() {
      this.brandItems = []
      this.$store.commit('toggleSpinner', true)
      axios.get(`/mark?category=${this.calcObj.leasingObjectType.value}`)
        .then(response => {
          console.log('*********')
          console.log(response)
          console.log('*********')
          this.brandItems = response.data
          this.$store.commit('toggleSpinner', false)
        })
        .catch(error => {
          let message = error.response.statusText
          this.notify('Помилка', message, 'error')
          this.$store.commit('toggleSpinner', false)
        })
    },
    getModelByMark() {
      this.modelItems = []
      this.$store.commit('toggleSpinner', true)
      let categorieId = this.calcObj.leasingObjectType.value
      axios.get(`/models?category=${categorieId}&mark=${this.calcObj.leasedAssertMark.value}`)
        .then(response => {
          console.log(response)
          this.modelItems = response.data
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
      // console.log(this.calcObj)
      // this.highlightErrors()
      // !this.$v.$invalid
      // && this.$v.$dirty
      //     && !this.$v.$invalid
      //     && this.$v.$dirty
      //       ? this.sendRequest()
      //       : this.notify('', 'Заповнiть даннi', 'error')

    },
    checkIfHasCurrency() {
      if(this.hasForeignCurrency) return
      this.calcObj.leasingCurrencyCourse = 1
    },
    checkIfHasAnnuity() {
      if(!this.hasAnnuity) {
        delete this.calcObj.gainEvenGraphicMonths
        delete this.calcObj.gainEvenGraphicPercent
        delete this.calcObj.UnsrMonths
      }
    },
    sendRequest() {
      this.checkIfHasAnnuity()
      this.checkIfHasCurrency()
      this.$store.commit('toggleSpinner', true)
      axios
        .post('/calculate', this.calcObj)
          .then(response => {
            console.log(response)
            this.$store.commit('toggleSpinner', false)
            // this.$router.push('/calculator/chart')
            let data = response.data
            this.$router.push({name: 'Графiки', params: {data: data}})
          })
          .catch(error => {
            console.log(error.response)
            const message = error.response.statusText
            this.notify('Помилка', message, 'error')
            this.$store.commit('toggleSpinner', false)
          })
    },
    test() {
      // console.log(this.calcObj)
    },
  },
  watch: {
    insuranceProgram(val) {
      console.log(val)
      // this.calcObj.insuranceProgram = val.value
      this.calcObj.insuranceProgram = val.value
    },
    'calcObj.leasingCurrencyCourse': function (course) {
      if(course == null){
        !this.hasForeignCurrency
          ? this.calcObj.leasingCurrencyCourse = 1
          : false
      } else {
        if(Number.isNaN(parseFloat(course))) return
        this.calcObj.leasingCurrencyCourse = parseFloat(course)
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
    'calcObj.leasingObjectType': function(value) {
      console.log(value)
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
        this.brandItems.push(data.leasedAssertMark)
        this.modelItems.push(data.leasedAssertModel)
        this.insuranceProgram = this.selects.insurancePrograms
          .find(
            obj => obj.value === data.insuranceProgram
          )
        // console.log(data)
        Object.assign(this.calcObj, data)
        console.log(this.calcObj)
        this.getMarksByType()
        this.getModelByMark()
      })
      .catch(error => {
        console.log(error.response)
        this.$notify({
          group: 'error',
          title: 'Виникла помилка',
          text: error.response.data.message,
        })
      })
    }

    this.calcObj._token = this.getCsrf()
    console.log(this.$store.state.user)
    this.calcObj.agentId = this.$store.state.user.agent.id
  }
}
</script>

<style lang="scss">
  .v-card{ 
    &.black-border-left {
      border-left: 3px solid #ff0000!important;
    }
  }
  .calculator-red-block {
    .v-input {
      fieldset {
        border: none!important;
      }
    }
    .v-input--selection-controls__ripple:before {
      background: none!important;
    }
    .v-radio, label {
      color: white;
    }
    .v-select__slot, .v-text-field__slot {
      label {
        &.v-label--active {
          top: -3px!important;
          color: white!important;
        }
      }
    }
    .v-input .v-input__slot {
      border-radius: 8px!important;
    }
    .v-input .v-input__control {
      display: flex!important;
      align-items: center!important;
      .v-text-field__details {
        display: none!important;
      }
    }
    .leasing-types input {
      display: none;
    }
  }
  
</style>