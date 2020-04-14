<template>
    <div class="row">
      <div class="col-12">
        <v-row justify="center">
          <v-dialog v-model="newCalculationModal" max-width="600px">
            <template v-slot:activator="{ on }">
              <div class="calculator__btn-wrapper">
                <v-btn 
                    v-on="on"
                    color="success" 
                    small
                    >Новий розрахунок
                </v-btn>
              </div>
            </template>
            <v-card class="card-cust pt-5">
              <v-card-title class="headline">
                {{ 'Новый розрахунок' }}
              </v-card-title>
              <v-card-text>
                <div class="calculator__new-calculation pt-4">
                  <!-- (При виборі пункту обладнання, марка і модель показувати “Інше”) -->
                  <v-select
                      v-model="itemType"
                      :items="itemTypes" 
                      color="green darken-3" 
                      label="Тип" 
                      dense>
                  </v-select>
                  <v-select
                      v-model="itemCondition"
                      :items="itemConditions" 
                      color="green darken-3"   
                      label="Стан" 
                      dense>
                  </v-select>
                  <v-select 
                      v-model="brand"
                      :items="itemBrand" 
                      color="green darken-3"    
                      label="Марка"
                      dense>
                  </v-select>
                  <v-select
                      v-model="itemYear" 
                      :items="itemYears"
                      color="green darken-3"  
                      label="Рік" 
                      dense>
                  </v-select>
                  <v-text-field
                      v-model="engineCapacity"
                      placeholder="Об'єм двигуна" 
                      color="green darken-3" 
                      dense>
                  </v-text-field>
                  <v-divider></v-divider>
                  <v-select
                      v-model="clientType"
                      :items="clientTypes" 
                      color="green darken-3" 
                      label="Суб'єкт"
                      dense>
                  </v-select>
                  <v-select
                      v-model="agreementCurrency"
                      :items="agreementCurrencys" 
                      color="green darken-3"
                      label="Валюта договору лізингу"
                      dense>
                  </v-select>
                  <v-text-field
                      v-model="itemCost" 
                      color="green darken-3"
                      placeholder="Вартість"
                      dense>
                  </v-text-field>
                  <v-select
                      v-model="chartType" 
                      color="green darken-3"
                      :items="chartTypes"
                      placeholder="Тип графіка"
                      dense>
                  </v-select>
                  <v-slider
                      v-model="advancePayment"
                      label="Авансовий платіж"
                      min="20"
                      max="70"
                      thumb-label>
                  </v-slider>
                  <v-slider
                      v-model="leasingTime"
                      label="Термін лізингу"
                      min="12"
                      max="72"
                      thumb-label>
                  </v-slider>
                  <v-select
                      v-model="vehicleTax" 
                      color="green darken-3"
                      :items="vehicleTaxItems"
                      placeholder="Податок з власників ТЗ"
                      dense>
                  </v-select>
                </div>
              </v-card-text>
              <v-card-actions class="d-flex justify-center new-calculation-btn">
                <span><v-btn class="mb-3" color="error">Розрахувати</v-btn></span>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>
        <card :title="table.title" :subTitle="table.subTitle">
          <div slot="raw-content" class="table-responsive">
            <paper-table 
              :data="table.data" 
              :columns="table.columns"
              :title="table.title" 
              :sub-title="table.subTitle" 
              type="hover">
            </paper-table>
          </div>
        </card>
      </div>
    </div>
</template>
<script>
  // title: "",
  // subTitle: "(Розрахунки за місяць)",
  // №	Дата	Тип	Клієнт	Марка	Модель	Cума	Детально
import { PaperTable } from "../components"
const tableColumns = ["№", "Дата", "Тип", "Клієнт", "Марка", "Модель", "Сума", "Детально"]
const tableData = [
  {
    '№': 223,
    'дата': "26.06.19",
    'тип': "Легкові та комерційні авто",
    'клієнт': "Иванов В.М.",
    'марка': "Renault",
    'модель': "Dokker",
    'сума': "$11.789",
    'детально': "edit"
  },
  {
    '№': 227,
    'дата': "26.06.19",
    'тип': "СПЕЦІАЛЬНІ ТЗ",
    'клієнт': "Зубенко В.М.",
    'марка': "Ваз",
    'модель': "21.011",
    'сума': "$23.789",
    'детально': "edit"
  },
  {
    '№': 290,
    'дата': "26.06.19",
    'тип': "Вантажні авто",
    'клієнт': "Иванов В.М.",
    'марка': "Djeep",
    'модель': "hunter",
    'сума': "$27.789",
    'детально': "edit"
  },
  {
    '№': 301,
    'дата': "26.01.20",
    'тип': "Обладнання",
    'клієнт': "Иванов В.М.",
    'марка': "Renault",
    'модель': "Dokker",
    'сума': "$23.789",
    'детально': "edit"
  },
  {
    '№': 324,
    'дата': "26.02.20",
    'тип': "Сільгосптехніка",
    'клієнт': "Бабенко Л.Н",
    'марка': "Opel",
    'модель': "Astrra",
    'сума': "$13.789",
    'детально': "edit"
  }
];
export default {
  components: {
    PaperTable,
  },
  data:() => ({
    table: {
      title: "Історія розрахунків",
      subTitle: "Розрахунки за місяць",
      columns: [...tableColumns],
      data: [...tableData]
    },
    itemConditions: [ "Новий", "б/у" ],
    itemTypes: [
      "Легкові та комерційні авто",
      "СПЕЦІАЛЬНІ ТЗ",
      "Вантажні авто",
      "Обладнання",
      "Причепи та Напівпричепи",
      "Сільгосптехніка"
    ],
    itemBrand: [ "Audi", "Renault", "ВАЗ" ],
    clientTypes: [ "Юридична особа", "Фізична особа" ],
    agreementCurrencys: [ "UAH", "USD", "EUR" ],
    itemYears: [ "2019", "2020" ],
    chartTypes: [
      // якщо вибраний індивідуальний графік на сервер прихована передаються дані в калькулято
      "Класичний",
      "Ануїтет",
      "Індивідуальний"
    ],
    pensionFundVal: ["так", "нi"],
    vehicleTaxItems: ["так", "нi"],

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

    /* modals */
    newCalculationModal: false,

    newCalculation: false
  }),
  methods: {
    test() {
      console.log(this.itemEngineCapacity)
    },
    dataUpdate(e) {
      console.log(e)
    }
  },
}
</script>
<style lang="scss">
  .new-calculation-btn {
    .v-btn__content {
      text-transform: capitalize!important;
    }
  }
  .calculator__new-calculation {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
  }
  .calculator__new-calculation .v-input {
    display: inline-block;
    width: 28%;
    margin: 0 12px;
  }

  .cust-form-group .form-control {
    border-bottom: 1px solid black;
    border-radius: 0;
  }
  .form-group--sm .form-control {
    height: 26px;
    max-width: 70px;
    padding: 7px 8px;
    margin: 0 4px;
  }

  .calculator__btn-wrapper {
    display: block;
    margin: 12px 10px;
  }
  .card-cust .card-body {
    transition: all 0.2s;
  }

  @media(max-width: 757px) {
    .calculator__new-calculation .v-input {
      width: 40%;
    }
  }
  @media(max-width: 637px) {
    .calculator__new-calculation .v-input {
      width: 90%;
    }
  }
</style>
