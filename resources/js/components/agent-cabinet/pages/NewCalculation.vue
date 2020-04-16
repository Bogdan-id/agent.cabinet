<template>
  <v-card>
    <v-card-title class="headline">
      Новий розрахунок
    </v-card-title>
    <v-card-text>
      <v-row>
        <v-col cols="12" xs="12" sm="12" md="6" lg="6" xl="4">
          <v-card>
            <v-card-title>Загальна iнформацiя</v-card-title>
            <v-card-text>
              <v-select 
                  v-model="itemType"
                  :items="selects.itemConditions" 
                  label="Тип авто"
                  color="grey darken-3"
                  dense outlined>
              </v-select>
              <v-select
                  @change="getMarksByType()"
                  v-model="LeasedAssetType"
                  :items="selects.itemTypes"
                  item-text="text"
                  item-value="text"
                  return-object
                  label="Тип предмета лізингу"
                  color="grey darken-3"
                  :disabled="!typeOfCar"
                  dense outlined>
              </v-select>
              <v-autocomplete
                  @change="getModelByMark()"
                  v-model="modelItem"
                  :items="brandItems"
                  item-text="name"
                  item-value="value"
                  return-object
                  label="Марка"
                  :disabled="!leasedOfAssetType || noBrandItems"
                  dense outlined>
              </v-autocomplete>
              <v-autocomplete
                  v-model="carModel"
                  :items="modelItems"
                  item-text="name"
                  item-value="value"
                  return-object
                  label="Модель"
                  :disabled="!modelOfItem || noModelItems"
                  dense outlined>
              </v-autocomplete>
              <v-select
                  v-if="itemType === true"
                  v-model="itemYear" 
                  :items="selects.itemYears"
                  label="Рік"
                  :disabled="!typeOfModel"
                  dense outlined>
              </v-select>
              <v-select
                  v-if="itemType === false"
                  v-model="itemYear" 
                  :items="selects.oldItemYers"
                  label="Рік"
                  :disabled="!typeOfModel"
                  dense outlined>
              </v-select>
              <v-text-field
                  v-model="engineCapacity"
                  placeholder="Об'єм двигуна" 
                  :disabled="!yearOfModel"
                  dense outlined>
              </v-text-field>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" xs="12"  sm="12" md="6" lg="6" xl="4">
          <v-card>
            <v-card-title>Суб'єкт</v-card-title>
              <v-card-text>
                <v-select
                    v-model="clientType"
                    :items="selects.clientTypes" 
                    label="Суб'єкт"
                    dense outlined>
                </v-select>
                <v-select
                    v-model="agreementCurrency"
                    :items="selects.currencys" 
                    label="Валюта договору лізингу"
                    dense outlined>
                </v-select>
                <v-text-field
                    v-model="carQuantity" 
                    placeholder="Кiлькiсть"
                    dense outlined>
                </v-text-field>
                <v-text-field
                    @change="$v.itemCost.$touch()"
                    @input="$v.itemCost.$touch()"
                    :error-messages="itemCostErrors"
                    v-model="itemCost" 
                    placeholder="Вартість"
                    dense outlined>
                </v-text-field>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" xs="12"  sm="12" md="8" lg="8" xl="8">
          <v-card>
            <v-card-title>
              Тип графiку
            </v-card-title>
            <v-card-text>
              <v-select
                v-model="chartType"
                :items="selects.chartTypes"
                placeholder="Тип графіка"
                dense outlined multiple>
              </v-select>
              <v-row v-show="hasAnnuity">
                <v-col cols="12" xs="12" sm="12" md="4" lg="4" xl="4">
                  <v-text-field
                    v-model="gainEvenGraphicMonths" 
                    placeholder="Кiлькiсть мiсяцiв"
                    dense outlined>
                  </v-text-field>
                </v-col>
                <v-col cols="12" xs="12"  sm="12" md="4" lg="4" xl="4">
                  <v-text-field
                    v-model="gainEvenGraphicPercent" 
                    placeholder="Вiдстоток посилення"
                    dense outlined>
                  </v-text-field>
                </v-col>
                <v-col cols="12" xs="12"  sm="12" md="4" lg="4" xl="4">
                  <v-text-field
                    v-model="UnsrMonths" 
                    placeholder="УНСПР, місяцiв"
                    dense outlined>
                  </v-text-field>
                </v-col>
              </v-row>
              <div :class="`d-flex flex-${smAndDown ? 'column' : 'row'}`">
                <div class="body-1 mr-2">Авансовий платiж (%):</div>
                  <v-slider
                    v-model="advancePayment"
                    min="20"
                    max="70"
                    thumb-label>
                    <template v-slot:prepend>
                      <span>20%</span>
                    </template>
                    <template v-slot:append>
                      <span>70%</span>
                    </template>
                  </v-slider>
                </div>
              <div :class="`d-flex flex-${smAndDown ? 'column' : 'row'}`">
                <div class="body-1 mr-2">Термiн лiзингу (мic):</div>
                <v-slider
                  v-model="leasingTime"
                  min="12"
                  max="72"
                  thumb-label>
                  <template v-slot:prepend>
                    <span>12</span>
                  </template>
                  <template v-slot:append>
                    <span>72</span>
                  </template>
                </v-slider>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" xs="12"  sm="12" md="6" lg="6" xl="5">
          <v-card>
            <v-card-title>
              Податок
            </v-card-title>
            <v-card-text>
              <v-select
                  v-model="vehicleTax" 
                  :items="selects.yesOrNo"
                  label="Податок з власників ТЗ"
                  dense outlined>
              </v-select>
              <v-select
                  v-model="pensionFundTax" 
                  :items="selects.yesOrNo"
                  label="Податок в ПФ"
                  dense outlined>
              </v-select>
              <v-select
                  v-model="gpsTracker" 
                  :items="selects.gpsTrackers"
                  label="GPS-трекер"
                  dense outlined>
              </v-select>
              <v-select
                  v-model="insuranceProgram" 
                  :items="selects.insurancePrograms"
                  item-text="text"
                  item-value="text"
                  return-object=""
                  label="Програма страхування"
                  dense outlined>
              </v-select>
              <v-select
                  v-model="franchise" 
                  :items="selects.franchises"
                  label="Франшиза"
                  dense outlined>
              </v-select>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-card-text>
    <v-card-actions class="d-flex justify-center new-calculation-btn">
      <span><v-btn @click="submit()" class="mb-3" dark color="red darken-1">Розрахувати</v-btn></span>
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
    /* select items & values */
    selects: selectsItemAndVAlue,
    
    /* temporary arrays */
    brandItems: [],
    modelItems: [],

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

    /* min-max values */

  }),
  validations: {
    itemCost: {
      minCost: val => parseInt(val.replace(/[^\d]/g, '')) >= 150000,
      numeric
    }
  },
  methods: {
    getMarksByType() {
      axios.get(`/mark?category=${this.LeasedAssetType.itemValue}`)
        .then(response => {
          this.brandItems = response.data
        })
        .catch(error => {
          let message = error.response.statusText
          this.notify('Помилка', message, 'error')
        })
    },
    getModelByMark() {
      let categorieId = this.LeasedAssetType.itemValue
      axios.get(`/models?category=${categorieId}&mark=${this.modelItem.value}`)
        .then(response => {
          this.modelItems = response.data
        })
        .catch(error => {
          let message = error.response.statusText
          this.notify('Помилка', message, 'error')
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
          .then(response => {
            console.log(response)
          })
          .catch(error => {
            console.log(error.response)
            const message = error.response.statusText
            this.notify('Помилка', message, 'error')
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
    }
  },
  created() {
    this.userId = this.$store.state.user.agent_id
  }
}
</script>