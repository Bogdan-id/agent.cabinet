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
})
