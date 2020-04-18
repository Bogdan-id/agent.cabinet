<template>
  <footer class="footer flex-column">
    <div :class="`footer__nav pa-3 ${xSmall ? 'flex-column align-center' : 'flex-row'}`">
      <bestLLogo :width="'140px'" :height="'40px'" :customStyle="'margin-bottom: 10px;'"/>
      <div class="footer__contacts">
        <ul class="footer__contacts-wrapper">
          <li class="contact-title">
            <v-icon class="icon-position" small v-text="'mdi-map-marker'"></v-icon>
            01054, м.Киив, вулиця ЯРОСЛАВИВ ВАЛ, будинок 13/2
          </li>
          <li class="contact-title">
            <v-icon class="icon-position" small v-text="'mdi-email'"></v-icon>
            <a href="#">office@bestleasing.com.ua</a>
          </li>
          <li class="contact-title">
            <v-icon class="icon-position" small v-text="'mdi-cellphone-iphone'"></v-icon>
            +38 (044) 359 03 99
          </li>
        </ul>
      </div>
      <div class="footer__feedback">
        <a class="footer__custom-btn">Зворотнiй зв`язок</a>
      </div>
    </div>
    <div class="container-fluid d-flex flex-wrap justify-content-between pt-2 pb-2">
      <div>
        <router-link class="--dark" :to="{path:'/home'}">Dashboard</router-link>
      </div>
      <div>
        Designed by <a href="#" target="_blank">&nbsp; Best leasing.</a>
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
