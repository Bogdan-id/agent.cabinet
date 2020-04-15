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
                  :items="itemConditions" 
                  label="Тип авто"
                  color="grey darken-3"
                  dense outlined>
              </v-select>
              <v-select 
                  v-model="brand"
                  :items="itemBrand" 
                  label="Марка"
                  dense outlined>
              </v-select>
              <v-select
                  v-if="itemType === 1"
                  v-model="itemYear" 
                  :items="itemYears"
                  label="Рік" 
                  dense outlined>
              </v-select>
              <v-select
                  v-if="itemType === 2"
                  v-model="itemYear" 
                  :items="oldItemYers"
                  label="Рік" 
                  dense outlined>
              </v-select>
              <v-text-field
                  v-model="engineCapacity"
                  placeholder="Об'єм двигуна" 
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
                    :items="clientTypes" 
                    label="Суб'єкт"
                    dense outlined>
                </v-select>
                <v-select
                    v-model="agreementCurrency"
                    :items="currencys" 
                    label="Валюта договору лізингу"
                    dense outlined>
                </v-select>
                <v-text-field
                    v-model="itemCost" 
                    placeholder="Вартість"
                    dense outlined>
                </v-text-field>
                <v-select
                    v-model="chartType" 
                    :items="chartTypes"
                    placeholder="Тип графіка"
                    dense outlined>
                </v-select>
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
                  :items="yesOrNo"
                  label="Податок з власників ТЗ"
                  dense outlined>
              </v-select>
              <v-select
                  v-model="pensionFundTax" 
                  :items="yesOrNo"
                  label="Податок в ПФ"
                  dense outlined>
              </v-select>
              <v-select
                  v-model="gpsTracker" 
                  :items="gpsTrackers"
                  label="GPS-трекер"
                  dense outlined>
              </v-select>
              <v-select
                  v-model="insuranceProgram" 
                  :items="insurancePrograms"
                  label="Програма страхування"
                  dense outlined>
              </v-select>
              <v-select
                  v-model="franchise" 
                  :items="franchises"
                  label="Франшиза"
                  dense outlined>
              </v-select>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="6">
          <!-- Physical person -->
          <!-- v-if -->
          <v-card v-if="clientType === 1">
            <v-card-title>
              Фiзична особа
            </v-card-title>
            <v-card-text>
              <v-text-field
                  label="Прізвище"
                  dense outlined>
              </v-text-field>
              <v-text-field
                  label="Ім'я"
                  dense outlined>
              </v-text-field>
              <v-text-field
                  label="По батьковi"
                  dense outlined>
              </v-text-field>
              <v-text-field
                  label="Телефон"
                  dense outlined>
              </v-text-field>
              <v-text-field
                  label="Email"
                  dense outlined>
              </v-text-field>
              <v-text-field
                  label="ІПН"
                  dense outlined>
              </v-text-field>
              <v-select
                  label="Область"
                  :items="urkRegions"
                  dense outlined>
              </v-select>
              <!-- monthly income -->
              <v-text-field
                  label="Середньомісячний дохід, грн*"
                  dense outlined>
              </v-text-field>
              <v-text-field
                  label="Платіж по кредитам (міс. / грн)"
                  dense outlined>
              </v-text-field>
              <v-select
                  label="Мета придбання авто"
                  dense outlined>
              </v-select>
            </v-card-text>
          </v-card>
          <!-- Legal person -->
          <!-- v-if -->
          <v-card v-if="clientType === 2">
            <v-card-title>
              Юридична особа
            </v-card-title>
            <v-card-text>
                <v-text-field
                  label="Прізвище"
                  dense outlined>
                </v-text-field>
                <v-text-field
                    label="Ім'я"
                    dense outlined>
                </v-text-field>
                <v-text-field
                    label="По батьковi"
                    dense outlined>
                </v-text-field>
                <v-text-field
                    label="ЄДРПОУ"
                    dense outlined>
                </v-text-field>
                <v-text-field
                    label="Назва компанії"
                    dense outlined>
                </v-text-field>
                <v-select
                    label="Область"
                    :items="urkRegions"
                    dense outlined>
                </v-select>
                <v-text-field
                    label="Власний капітал (Розділ І Пасиву Балансу), за 2019 р"
                    dense outlined>
                </v-text-field>
                <v-text-field
                    label="Валюта Балансу"
                    :items="currencys"
                    dense outlined>
                </v-text-field>
                <v-text-field
                    label="Щомісячний платіж по кредитам та ін. кредитним зобов'язанням, тис. грн"
                    :items="currencys"
                    dense outlined>
                </v-text-field>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-card-text>
    <v-card-actions class="d-flex justify-center new-calculation-btn">
      <span><v-btn class="mb-3" dark color="grey darken-1">Розрахувати</v-btn></span>
    </v-card-actions>
  </v-card>
</template>

<script>
  // title: "",
  // subTitle: "(Розрахунки за місяць)",
  // №	Дата	Тип	Клієнт	Марка	Модель	Cума	Детально
import { regions } from "./calculator-temp-data.js"
export default {
  data:() => ({
    /* items */
    itemConditions: [
      {text: 'Новий', value: 1},
      {text: 'Б/У', value: 2}
    ],
    itemTypes: [
      "Легкові та комерційні авто",
      "СПЕЦІАЛЬНІ ТЗ",
      "Вантажні авто",
      "Обладнання",
      "Причепи та Напівпричепи",
      "Сільгосптехніка"
    ],
    itemBrand: [ "Audi", "Renault", "ВАЗ" ],
    clientTypes: [ 
      {text: "Юридична особа", value: 2}, 
      {text: "Фізична особа", value: 1}
    ],
    currencys: [ "UAH", "USD", "EUR" ],
    itemYears: [ "2019", "2020" ],
    oldItemYers: [ '2013', '2014', '2015', '2016', '2017', '2018', '2019' ],
    chartTypes: [
      // якщо вибраний індивідуальний графік на сервер прихована передаються дані в калькулято
      "Класичний",
      "Ануїтет",
      "Індивідуальний"
    ],
    yesOrNo: [
      {text: "Так", value: 1},
      {text: "Нi", value: 2}
    ],
    gpsTrackers: [
      'Antenor з блокувань', 
      'ні', 
      'Benish Logistic з Блокування',
      'Premium Benish Guard',
      'Benish GuardPlatinum',
      'Benish Universal з Блокування'
    ],
    insurancePrograms: [
      'Стандарт',
      'Обережно',
      'Таксі',
      'Тотал / крадіжка'
    ],
    franchises: [
      {text: '0%', value: '0%'},
      {text: '0.3%', value: '0.3%'},
      {text: '0.5%', value: '0.5%'},
      {text: '1%', value: '1%'},
      {text: '2%', value: '2%'},
    ],
    urkRegions: regions,

    /* new calculation data */
    itemType: null,
    itemCondition: null,
    brand: null,
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

    //physical person

    // legarl person

    /* modals */
    newCalculationModal: false,

    newCalculation: false
  }),
}
</script>