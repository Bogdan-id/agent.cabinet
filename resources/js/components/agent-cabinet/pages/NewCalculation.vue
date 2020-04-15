<template>
  <v-card class="card-cust">
    <v-card-title class="headline">
      Новий розрахунок
    </v-card-title>
    <v-card-text>
      <v-row>
        <v-col cols="6">
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
                  return-object
                  :items="selects.itemTypes"
                  item-text="text"
                  item-value="text"
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
                  label="Марка"
                  :disabled="!leasedOfAssetType || noBrandItems"
                  dense outlined>
              </v-autocomplete>
              <v-autocomplete
                  @change="test()"
                  v-model="carModel"
                  :items="modelItems"
                  item-text="name"
                  item-value="value"
                  label="Модель"
                  :disabled="!modelOfItem || noModelItems"
                  dense outlined>
              </v-autocomplete>
              <v-select
                  v-if="itemType === 1"
                  v-model="itemYear" 
                  :items="selects.itemYears"
                  label="Рік"
                  :disabled="!typeOfModel"
                  dense outlined>
              </v-select>
              <v-select
                  v-if="itemType === 2"
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
        <v-col cols="6">
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
                    @change="$v.itemCost.$touch()"
                    @input="$v.itemCost.$touch()"
                    :error-messages="itemCostErrors"
                    v-model="itemCost" 
                    placeholder="Вартість"
                    dense outlined>
                </v-text-field>
                <v-select
                    v-model="chartType"
                    :items="selects.chartTypes"
                    placeholder="Тип графіка"
                    dense outlined multiple>
                </v-select>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="11">
          <v-card>
            <v-card-text>
              <div style="display: flex;">
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
              <div style="display: flex;">
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
        <v-col cols="6">
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
      <span><v-btn @click="submit()" class="mb-3" dark color="grey darken-1">Розрахувати</v-btn></span>
      <v-btn @click="test()">test</v-btn>
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
    data: null,
    gpsTrackerQuantity: 1, // gps-tracker-quantity = 1 (GPS маячок)
    urkAssistService: 1, // assist-service = 1 (UKRASSIST-Service)
    discount: {
      'rate-manipulate': 0.1,
      'agent-commission': 1,
      'commission-manipulate': -0.5, // 0.5% від вартості
      'commission-lk': 0.5 //сума (0.5% від вартості) 
    },
    /* new calculation data */

    modelItem: null,
    carModel: null,
    itemType: null,
    LeasedAssetType: null,
    itemCondition: null,
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
    test() {
      this.$v.itemCost.$touch()
      console.log(Object.keys(this.brandItems).length === 0)
    },
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
      axios.get(`/models?category=${categorieId}&mark=${this.modelItem}`)
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
    submit() {
      console.log(this.modelItem)
      console.log(this.carModel)
      console.log(this.itemType)
      console.log(this.LeasedAssetType)
      console.log(this.itemCondition)
      console.log(this.itemYear)
      console.log(this.engineCapacity)
      console.log(this.clientType)
      console.log(this.agreementCurrency)
      console.log(this.itemCost)
      console.log(this.chartType)
      console.log(this.advancePayment)
      console.log(this.leasingTime)
      console.log(this.pensionFund)
      console.log(this.vehicleTax)
      console.log(this.pensionFundTax)
      console.log(this.gpsTracker)
      console.log(this.insuranceProgram)
      console.log(this.franchise)
    }
  },
  computed: {
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
    }
  },
  mounted() {
    console.log(this.$v)
  }
}
</script>