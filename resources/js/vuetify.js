import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'
import uk from 'vuetify/es5/locale/uk'

Vue.use(Vuetify)

export default new Vuetify({
  lang: {
    locales: { uk },
    current: 'uk',
  },
  icons: {
    iconfont: 'mdi',
  },
  // theme: {
  //   primary: '#4285F4',
  //   secondary: '#424242',
  //   accent: '#82B1FF',
  //   error: '#ff4444',
  //   info: '#33b5e5',
  //   success: '#00C851',
  //   warning: '#ffbb33'
  // }
})
