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
  theme: {
    primary: '#1976D2',
    secondary: '#424242',
    accent: '#82B1FF',
    error: '#FF5252',
    info: '#2196F3',
    success: '#4CAF50',
    warning: '#FFC107',
  }
})
