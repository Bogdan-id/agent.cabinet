<template>
    <div class="row">
      <div class="col-12">
        <div class="calculator__btn-wrapper">
          <p-button 
            @click.native="newCalculation = !newCalculation"
            type="success" 
            size="sm">Новий розрахунок
          </p-button>
        </div>
        <card class="card-cust">
          <div v-if="newCalculation" class="calculator__new-calculation">
            <!-- (При виборі пункту обладнання, марка і модель показувати “Інше”) -->
            <v-select id="" dense :items="itemType" v-model="calculation.itemType" label="Тип">
            </v-select>
            <v-select dense :items="itemCondition" v-model="calculation.itemCondition" label="Стан">
            </v-select>
            <v-select dense :items="itemBrand" v-model="calculation.itemBrand" label="Марка">
            </v-select>
            <v-select dense :items="itemYear" v-model="calculation.itemYear" label="Рік">
            </v-select>
            <v-text-field dense v-model="calculation.itemEngineCapacity" placeholder="Об'єм двигуна">
            </v-text-field>
            <v-select dense :items="clientType" v-model="calculation.clientType" label="Суб'єкт">
            </v-select>
            <v-select dense :items="agreementCurrency" v-model="calculation.agreementCurrency" label="Валюта договору лізингу">
            </v-select>
            <v-text-field dense v-model="calculation.itemCost" placeholder="Вартість">
            </v-text-field>
          </div>
        </card>
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
import { Button as PButton } from "../components"
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
    PButton,
  },
  data:() => ({
    table: {
      title: "Історія розрахунків",
      subTitle: "Розрахунки за місяць",
      columns: [...tableColumns],
      data: [...tableData]
    },
    itemCondition: [ "Новий", "б/у" ],
    itemType: [
      "Легкові та комерційні авто",
      "СПЕЦІАЛЬНІ ТЗ",
      "Вантажні авто",
      "Обладнання",
      "Причепи та Напівпричепи",
      "Сільгосптехніка"
    ],
    itemBrand: [ "Audi", "Renault", "ВАЗ" ],
    clientType: [ "Юридична особа", "Фізична особа" ],
    agreementCurrency: [ "UAH", "USD", "EUR" ],
    itemYear: [ "2019", "2020" ],
    calculation: {
      itemType: null,
      itemCondition: null,
      itemBrand: null,
      itemYear: null,
      itemEngineCapacity: null,
      clientType: null,
      agreementCurrency: null,
      itemCost: null
    },

    newCalculation: false
  }),
  methods: {
    test() {
      console.log(this.calculation.itemEngineCapacity)
    },
    dataUpdate(e) {
      console.log(e)
    }
  },
}
</script>
<style lang="scss">
  .calculator__new-calculation {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
  }
  .calculator__new-calculation .v-input {
    display: inline-block;
    width: 28%;
    margin: 0 11px;
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
