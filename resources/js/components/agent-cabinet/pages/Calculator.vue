<template>
  <div>
    <breads-crumb></breads-crumb>
    <keep-alive include="Графiки"> 
      <router-view :key="$route.path"></router-view>
    </keep-alive>
    <calculator-data  v-if="$route.name === 'Калькулятор лізингу'"></calculator-data>
  </div>
</template>

<script>
import BreadsCrumb from '../components/breadScrumb.vue'
import CalculatorData from './Calculator/CalculatorData'

export default {
  components: {
    BreadsCrumb,
    CalculatorData
  },
}
</script>


<style lang="scss">
  .calculations-table {
    .text-start {
      font-size: 1rem!important;
    }
    .v-data-footer__select,
    .v-data-footer__pagination {
      font-size: 14px!important;
    }
    .v-data-table__empty-wrapper {
      td {
        font-size: 1.2rem!important;
      }
    }
    .v-select__selection {
      font-size: 0.9rem!important;
    }
  }
  .new-calculation-btn {
    .v-btn__content {
      text-transform: capitalize;
    }
  }
  .calculator-radio {
    .v-input--radio-group__input {
      .v-input--selection-controls__ripple {
        height: 0!important;
      }
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
