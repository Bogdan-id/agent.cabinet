<template>
  <footer class="footer flex-column">
    <div class="footer__nav pa-3">
      <div class="logo-wrapper">
        <bestLLogo :width="'140px'" :height="'40px'" :customStyle="'margin-bottom: 10px;'"/>
      </div>
      <div class="contact-wrapper">
        <div class="footer__contacts">
          <ul class="footer__contacts-wrapper">
            <li class="contact-title body-1" style="font-family: Montserrat,sans-serif,Arial;">
              <v-icon class="icon-position" small v-text="'mdi-cellphone-iphone'"></v-icon>
              +38 (044) 359-03-99
            </li>
            <li class="contact-title">
              <v-icon class="icon-position" small v-text="'mdi-map-marker'"></v-icon>
              Адреса: буд. 17, вулиця Лінійна, Київ, 03038
            </li>
            <li class="contact-title">
              <v-icon class="icon-position" small v-text="'mdi-email'"></v-icon>
              <a href="#">office@bestleasing.com.ua</a>
            </li>
          </ul>
        </div>
        <div class="footer__feedback">
          <div class="d-flex flex-column pa-1 feedback__text">
            <span>Перейти на сайт</span>
            <a href="https://bestleasing.com.ua/">bestleasing.com.ua</a>
          </div>
          <a class="footer__custom-btn" style="background: #e65048;">Зворотнiй зв`язок</a>
        </div>
      </div>
    </div>
    <div class="container-fluid d-flex flex-wrap justify-content-between pt-2 pb-2">
      <div>
        <router-link class="--dark" :to="{path:'/dashboard'}">© 2020 ТОВ «Бест Лiзинг»</router-link>
      </div>
    </div>
  </footer>
</template>
<script>
import bestLLogo from '../../assets/svg-icons/best-leasing-logo.vue'

export default {
  components: {
    bestLLogo,
  },
  computed: {
    xSmall() {
      return this.$vuetify.breakpoint.xs
    }
  },
}
</script>
