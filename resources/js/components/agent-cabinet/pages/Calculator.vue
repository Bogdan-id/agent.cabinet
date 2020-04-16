<template>
    <div class="row">
      <div class="col-12">
        <v-card class="pb-4">
        <v-card-title class="d-block">
          <div>Калькулятор лiзингу</div>
          <v-divider></v-divider>
        </v-card-title>
          <v-card-actions>
            <v-tooltip right>
              <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    to="/calculator/new"
                    color="error" 
                    fab dark>
                    <v-icon dark>mdi-plus-thick</v-icon>
                </v-btn>
              </template>
              <span>Новий розрахунок</span>
            </v-tooltip>
        </v-card-actions>
        <card :title="table.title" :subTitle="table.subTitle">
          <div slot="raw-content" class="table-responsive">
            <paper-table 
              :data="table.data" 
              :columns="table.columns"
              :title="table.title" 
              :sub-title="table.subTitle" 
              type="hover">
              <template slot-scope="{row}">
                <td>{{row.id}}</td>
                <td>{{row.дата}}</td>
                <td>{{row.тип}}</td>
                <td>{{row.клієнт}}</td>
                <td>{{row.марка}}</td>
                <td>{{row.модель}}</td>
                <td>{{row.сума}}</td>
              <td>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn 
                        color="grey" 
                        v-on="on " 
                        icon 
                        :to="{ 
                            name: 'Детально', 
                            params: {
                              table: table,
                              id: row.id
                            } 
                        }">
                      <v-icon v-text="'mdi-card-bulleted-outline'"></v-icon>
                    </v-btn>
                  </template>
                  <span>Детально</span>
                </v-tooltip>
              </td>
            </template>
            </paper-table>
          </div>
        </card>
        </v-card>
      </div>
    </div>
</template>
<script>
  // title: "",
  // subTitle: "(Розрахунки за місяць)",
  // №	Дата	Тип	Клієнт	Марка	Модель	Cума	Детально
import { PaperTable } from "../components"
import { tableColumns, tableData } from "./calculator-temp-data.js"
export default {
  components: {
    PaperTable,
  },
  data:() => ({
    /* items */
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
