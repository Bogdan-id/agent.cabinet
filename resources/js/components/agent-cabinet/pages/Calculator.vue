<template>
    <div class="row">
      <div class="col-12">
        <div class="calculator__btn-wrapper">
            <p-button 
              @click.native="newCalculation = !newCalculation"
              type="success" 
              size="sm">Новий розрахунок
            </p-button>
            <p-button 
              @click.native="test()"
              type="success" 
              size="sm">test()
            </p-button>
          </div>
        <card class="card-cust">
          <div v-if="newCalculation" class="calculator__new-calculation">
            <drop-down :title="itemType" @change="dataUpdate($event)" tag="div">
              <a class="dropdown-item">Легкові та комерційні авто</a>
              <a class="dropdown-item">СПЕЦІАЛЬНІ ТЗ</a>
              <a class="dropdown-item">Вантажні авто</a>
              <!-- (При виборі пункту обладнання, марка і модель показувати “Інше”) -->
              <a class="dropdown-item">Обладнання</a>
              <a class="dropdown-item">Причепи та Напівпричепи</a>
              <a class="dropdown-item">Сільгосптехніка</a>
            </drop-down>
            <drop-down :title="itemCondition" v-model="calculation.itemCondition" tag="div">
              <a class="dropdown-item">Новий</a>
              <a class="dropdown-item">б/у</a>
            </drop-down>
            <drop-down :title="itemBrand" v-model="calculation.itemBrand" tag="div">
              <a class="dropdown-item">Audi</a>
              <a class="dropdown-item">Renault</a>
              <a class="dropdown-item">ВАЗ</a>
            </drop-down>
            <drop-down :title="itemYear" v-model="calculation.itemYear" tag="div">
              <a class="dropdown-item">2020</a>
              <a class="dropdown-item">2019</a>
            </drop-down>
            <div class="calculator__input">
              <span class="calculator__input-title">Об'єм</span>
              <fg-input 
                placeholder="Об'єм" 
                v-model="calculation.itemEngineCapacity"
                type="number"
                class="cust-form-group form-group--sm">
              </fg-input>
            </div>
            <drop-down :title="clientType" v-model="calculation.clientType" tag="div">
              <a class="dropdown-item">Юридична особа</a>
              <a class="dropdown-item">Фізична особа</a>
            </drop-down>
            <drop-down :title="agreementCurrency" v-model="calculation.agreementCurrency" tag="div">
              <a class="dropdown-item">UAH</a>
              <a class="dropdown-item">USD</a>
              <a class="dropdown-item">EUR</a>
            </drop-down>
            <div class="calculator__input">
              <span class="calculator__input-title">Вартість</span>
              <fg-input 
                placeholder="Вартість" 
                v-model="calculation.itemCost" 
                type="number" 
                class="cust-form-group form-group--sm">
              </fg-input>
            </div>
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
import { 
  Button as PButton, 
  DropDown,
  FormGroupInput as FgInput,
  } from "../components"
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
    DropDown,
    FgInput
  },
  data:() => ({
    table: {
      title: "Історія розрахунків",
      subTitle: "Розрахунки за місяць",
      columns: [...tableColumns],
      data: [...tableData]
    },

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
  computed: {
    itemType() {
      return this.calculation.itemType === null ? 'Тип' : this.calculation.itemType
    },
    itemCondition() {
      return this.calculation.itemCondition === null ? 'Стан' :this.calculation.itemCondition
    },
    itemBrand() {
      return this.calculation.itemBrand === null ? 'Марка' : this.calculation.itemBrand
    },
    itemYear() {
      return this.calculation.itemYear === null ? 'Рік' : this.calculation.itemYear
    },
    clientType() {
      return this.calculation.clientType === null ? 'Тип клієнта' : this.calculation.clientType
    },
    agreementCurrency() {
      return this.calculation.agreementCurrency == null ? 'Валюта договору лізингу' : this.calculation.agreementCurrency
    },
  }
}
</script>
<style lang="scss">
  .calculator__new-calculation {
    /* */
  }
  .calculator__input {
    display: flex; 
    align-items: center;
  }
  .calculator__input-title {
    /*  */
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
  .calculator__input .cust-form-group {
    margin: 0;
  }
  .calculator__btn-wrapper {
    display: block;
    margin: 12px 0;
  }
  .card-cust 
    .dropdown,
    .calculator__input {
      display: inline-flex;
      padding: 10px 8px;
    }
  .card-cust .card-body {
    transition: all 0.2s;
  }
</style>
