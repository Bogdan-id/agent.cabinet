<template>
<div>
  <router-view></router-view>
  <calculation v-if="isExact"></calculation>
</div>
</template>

<script>
import Calculation from './Calculation.vue'

export default {
  components: {
    Calculation
  },
  computed: {
    isExact() {
      return this.$route.name === 'Новий розрахунок'
        || this.$route.name === 'Редагувати'
    }
  },
  mounted() {
    console.log(this.$route.name)
  }
}
</script>

<style lang="scss">
  .calculation-row-wrapper {
    .custom-input {
      margin: 13px 0!important;
    }
  }
  .term-and-advance {
    margin: 30px 0;
  }
  .calculator-insurance {
    .insurance-title {
      border-left: 2px solid red;
      padding-top: 0;
      font-size: 18px;
    }
  }
  .calculation__tab-btn {
    background: grey;
    color: white;
    padding: 0 18px;
    line-height: 40px;
  }
  .tab-btn--active {
    background: black;
    color: white;
  }
  .content__switch {
    display: flex;
    align-items: center;
    border-radius: 4px;
    padding: 0 8px;
    border-left: 5px solid black;
    box-shadow: 0 2px 4px -1px rgba(0,0,0,.2),
      0 4px 5px 0 rgba(0,0,0,.14),
      0 1px 10px 0 rgba(0,0,0,.12)
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
    white-space: nowrap;
    font-size: 17px;
    padding: 1px 1px;
    // color: black!important;
  }
  .slider-wrapper {
    position: relative;
    border: 2px solid #818080;
    border-radius: 8px;
    padding: 12px 8px 0 6px;
    .custom-chip {
      font-size: 17px;
      position: absolute;
      top: -22px;
    }
    .v-input__slider {
      .v-input__slot {
        margin-bottom: 0!important;
        margin-top: 2px!important;
        .v-slider__thumb-label {
          z-index: 3!important;
        }
      }
      margin-top: 2px!important;
      padding: 0 8px!important;
    }
    .v-slider__track-container,
    .v-slider__track-background {
      height: 2px;
    }
  }
  .slider-wrapper .v-input__append-outer {
    // margin: 0 4px;
    padding: 5px;
    background: #ef5350;
    color: white!important;
    border-radius: 4px;
  }
  .slider-wrapper .v-input__prepend-outer {
    // margin: 0 4px;
    padding: 5px;
    background: #ef5350;
    color: white!important;
    border-radius: 4px;
  }
  .calculator-radio {
    .v-radio {
      transition: border 0.3s ease;
      border: 1px solid #808080;
      padding: 7px 10px;
      border-radius: 4px;
      margin: 10px 0;
      &.v-item--active {
        border: 1px solid black;
      }
    }
    label {
      color: black!important;
    }
  }
  @media(max-width: 716px) {
    .content__switch {
      padding-top: 5px;
      flex-direction: column;
      justify-content: flex-start;
      align-items: start;
    }
    .content__switch-wrapper,
    .content__switch-title-wrapper {
      width: 100%;
    }
  }
  /* hide input number field arrows */
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  input[type=number] {
    -moz-appearance: textfield;
  }
  // .v-input--selection-controls__input label {
  //   color: black!important;
  // }
</style>