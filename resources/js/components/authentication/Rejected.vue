<template>
  <div class="app">
    <div class="label__block" style="background: repeating-linear-gradient(60deg, #808080, #707070 15%, #444444 20%)">
      <div class="rhombus-1 --grey"></div>
      <div class="rhombus-2 --grey"></div>
      <div class="rhombus-3 --grey"></div>
        <div class="label__block-wrapper">
        <div class="label__logo-wrapper">
          <div class="label__logo"></div>
        </div>
        <div class="label__hr"></div>
        <div class="label__header-wrapper">
          <h1 class="label__header">
            <alert-svg class="label__header-svg"></alert-svg>
            Відмовлено у використанні
          </h1>
        </div>
        <p class="subtitle-1">
          Вибачте, але наразі ми не можемо зареєструвати Вас у персональному кабінеті агента
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
