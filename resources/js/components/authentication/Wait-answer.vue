<template>
  <div class="app">
    <div class="label__block">
      <div class="rhombus-1"></div>
      <div class="rhombus-2"></div>
      <div class="rhombus-3"></div>
        <div class="label__block-wrapper">
        <div class="label__logo-wrapper">
          <div class="label__logo"></div>
        </div>
        <div class="label__hr"></div>
        <div class="label__header-wrapper">
          <h1 class="label__header">
            <alert-svg class="label__header-svg"></alert-svg>
            Ваше звернення в обробці
          </h1>
        </div>
        <h1 class="title">Чекайте</h1>
        <p class="subtitle-1">
          Ми зв'яжемося з вами, найближчим часом
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import AlertSvg from '../../assets/svg/alert.vue'

export default {
  components: {
    AlertSvg
  }
}
</script>
