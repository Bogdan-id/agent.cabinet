<template>
  <v-card>
    <v-tabs 
      v-model="currentTab"
      class="d-inline-flex"
      slider-color="grey darken-3"
      background-color="red lighten-1"
      slider-size="7"
      dark>
      <v-tab key="page-one">Cторiнка 1</v-tab>
      <v-tab key="page-two">Cторiнка 2</v-tab>
    </v-tabs>
    
    <v-tabs-items v-model="currentTab">
      <v-tab-item key="page-one">
        <v-card-text>
          <div>
            <v-row>
              <v-col cols="12" md="7">
                <v-radio-group class="calculator-radio" v-model="clientType" row>
                  <v-radio label="Фiзична особа" value="2" color="black"></v-radio>
                  <v-radio label="Юридична особа" value="1" color="black"></v-radio>
                </v-radio-group>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md=3>
                <v-select 
                    v-model="itemType"
                    :items="selects.itemConditions" 
                    label="Тип авто"
                    color="red darken-4"
                    dense outlined :disabled="!isClientType">
                </v-select>
              </v-col>
              <v-col cols="12" md="3">
                <v-select
                    v-model="agreementCurrency"
                    :items="selects.currencys" 
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
                    v-model="LeasedAssetType"
                    :items="selects.itemTypes"
                    item-text="text"
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
                    v-model="modelItem"
                    :items="brandItems"
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
                    v-model="carModel"
                    :items="modelItems"
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
              <v-col cols="12" md="3" v-if="itemType === false">
                <v-select
                    v-model="itemYear" 
                    :items="selects.oldItemYers"
                    label="Рік"
                    :disabled="!typeOfModel"
                    color="red darken-4"
                    dense outlined>
                </v-select>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                    v-model="engineCapacity"
                    placeholder="Об'єм двигуна" 
                    :disabled="!yearOfModel"
                    color="red darken-4"
                    dense outlined>
                </v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                    @change="$v.itemCost.$touch()"
                    @input="$v.itemCost.$touch()"
                    :error-messages="itemCostErrors"
                    v-model="itemCost" 
                    placeholder="Вартість"
                    color="red darken-4"
                    dense outlined>
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="3">
                <v-select
                    :items="selects.currencys" 
                    placeholder="Валюта"
                    color="red darken-4"
                    dense>
                </v-select>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                    v-model="currencyCourse" 
                    placeholder="Курс"
                    color="red darken-4"
                    dense readonly>
                </v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                    v-model="carQuantity" 
                    placeholder="Кiлькiсть"
                    min="1"
                    type="number"
                    color="red darken-4"
                    dense>
                </v-text-field>
              </v-col>
            </v-row>
          </div>
        </v-card-text>
      </v-tab-item>

      <!-- Уточни валюта договора и просто валюта -->
      <v-tab-item key="page-two">
        <v-card-text>
          <div>
            <v-row>
              <v-col cols="12" md="9" class="pb-0">
                <v-select
                  v-model="chartType"
                  :items="selects.chartTypes"
                  placeholder="Тип графіка"
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
                    v-model="gainEvenGraphicMonths" 
                    placeholder="Кiлькiсть мiсяцiв"
                    color="red darken-4"
                    dense outlined>
                  </v-text-field>
                </v-col>
                <v-col cols="9" md="4">
                  <v-text-field
                    v-model="gainEvenGraphicPercent" 
                    placeholder="Вiдстоток посилення"
                    color="red darken-4"
                    dense outlined>
                  </v-text-field>
                </v-col>
                <v-col cols="9" md="4">
                  <v-text-field
                    v-model="UnsrMonths" 
                    placeholder="УНСПР, місяцiв"
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
                    v-model="advancePayment"
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
                    v-model="leasingTime"
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
                        v-model="vehicleTax"
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
                        v-model="pensionFundTax"
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
                    v-model="gpsTracker" 
                    :items="selects.gpsTrackers"
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
                    v-model="franchise" 
                    :items="selects.franchises"
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
      <span><v-btn @click="submit()" class="mb-3" dark color="grey darken-3">Розрахувати</v-btn></span>
    <!-- <v-btn @click="test()">test</v-btn> -->
    </v-card-actions>
  </v-card>
</template>

<script>
import axios from 'axios'
import selectsItemAndVAlue from './selectItems'
import { validationMixin } from 'vuelidate'
import { numeric } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  data:() => ({

    active_tab: 1,
    /* select items & values */
    selects: selectsItemAndVAlue,
    
    /* temporary arrays */
    brandItems: [],
    modelItems: [],

    currentTab: null,

    /* hidden data to server */
    data: null, // КУДА ПЕРЕДАВАТЬ
    gpsTrackerQuantity: 1, // gps-tracker-quantity = 1 (GPS маячок)
    urkAssistService: 1, // assist-service = 1 (UKRASSIST-Service)
    
    /* Уточни как правильно передавать если Ануитет */
    gainEvenGraphicMonths: null,
    gainEvenGraphicPercent: null,
    UnsrMonths: null,
    // discount: {
    //   'rate-manipulate': 0.1,
    //   'agent-commission': 1,
    //   'commission-manipulate': -0.5, // 0.5% від вартості
    //   'commission-lk': 0.5 //сума (0.5% від вартості) 
    // },
    /* new calculation data */
    userId: null,

    modelItem: null,
    carModel: null,
    itemType: null,
    LeasedAssetType: null,
    itemCondition: null,
    carQuantity: null,
    itemYear: null,
    engineCapacity: null,
    clientType: null,
    agreementCurrency: null,
    currencyCourse: null,
    itemCost: null,
    chartType: null,
    advancePayment: null,
    leasingTime: null,
    pensionFund: null,
    vehicleTax: null,
    pensionFundTax: null,
    gpsTracker: null,
    insuranceProgram: null,
    franchise: null,

    /* booleans */
    

  }),
  validations: {
    itemCost: {
      minCost: val => parseInt(val.replace(/[^\d]/g, '')) >= 150000,
      numeric
    }
  },
  methods: {
    getMarksByType() {
      this.$store.commit('toggleSpinner', true)
      axios.get(`/mark?category=${this.LeasedAssetType.itemValue}`)
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
    getModelByMark() {
      this.$store.commit('toggleSpinner', true)
      let categorieId = this.LeasedAssetType.itemValue
      axios.get(`/models?category=${categorieId}&mark=${this.modelItem.value}`)
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
    test() {
      // console.log(this.calculationObj())
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		},
    submit() {
      axios
        .post('/calculate', this.calculationObj())
          this.$store.commit('toggleSpinner', true)
          .then(response => {
            console.log(response)
          })
          .catch(error => {
            console.log(error.response)
            const message = error.response.statusText
            this.notify('Помилка', message, 'error')
            this.$store.commit('toggleSpinner', false)
          })
    },

    calculationObj() {
      return {
        'agentId': this.userId,
        'leasingObjectType': {
          'label': this.LeasedAssetType.text,
          'value': this.LeasedAssetType.itemValue
        },
        'leasedAssertMark': {
          'name': this.modelItem.name,
          'value': this.modelItem.value
        },
        'leasedAssertModel': {
          'name': this.carModel.name,
          'value': this.carModel.value
        },
        'leasingObjectYear': parseInt(this.itemYear),
        'leasedAssertEngine': this.engineCapacity,
        'leasingClientType': this.clientType,
        'currency': this.agreementCurrency,
        'isNew': this.itemType,
        'leasingAmount': this.itemCost,
        'leasingQuantity': this.carQuantity,
        'leasingCurrencyCourse': '25.3', // курс валют откуда брать?
        'graphType': this.chartType,
        'advance': this.advancePayment,
        'leasingTerm': this.leasingTime,
        'paymentPf': this.pensionFundTax,
        'vehicleOwnerTax': this.vehicleTax,
        'gpsTrackerModel': this.gpsTracker,
        'insuranceProgram': this.insuranceProgram.value,
        'insuranceFranchise': this.franchise,
        'insuranceVehicleType': 'waiting ..',
        /* Уточни данные на ануитет и вцелом "прихованi данi" к каким ключам привязывать */
        // data - также уточни
        'gainEvenGraphicMonths': this.gainEvenGraphicMonths,
        'gainEvenGraphicPercent': this.gainEvenGraphicPercent,
        'UnsrMonths': this.UnsrMonths,
        '_token': this.getCsrf()
      }
    }
  },
  computed: {
    smAndDown() {
      return this.$vuetify.breakpoint.smAndDown
    },
    typeOfCar() {
      return this.itemType !== null
    },
    leasedOfAssetType() {
      return this.LeasedAssetType !== null
    },
    modelOfItem() {
      return this.modelItem !== null
    },
    typeOfModel() {
      return this.carModel !== null
    },
    yearOfModel() {
      return this.itemYear !== null
    },
    noBrandItems() {
      return Object.keys(this.brandItems).length === 0
    },
    noModelItems() {
      return Object.keys(this.modelItems).length === 0
    },
    /* vuelidate error handlers */
    itemCostErrors() {
      const errors = []
      if (!this.$v.itemCost.$error) return errors
      !this.$v.itemCost.minCost && errors.push(`Вартiсть має бути бильше нiж 150 000грн`)
      !this.$v.itemCost.numeric && errors.push(`Числове поле`)
			return errors
    },
    hasAnnuity() {
      if(this.chartType === null) return false
      return this.chartType.indexOf('annuity') > -1
    },
    isClientType() {
      return this.clientType !== null
    }
  },
  created() {
    this.userId = this.$store.state.user.agent_id
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
  // .v-input--selection-controls__input label {
  //   color: black!important;
  // }
</style>