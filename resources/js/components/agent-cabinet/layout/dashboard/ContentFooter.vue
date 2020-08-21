<template>
  <footer class="footer flex-column">
    <div class="footer__nav pa-3">
      <div class="logo-wrapper">
        <bestLLogo :width="'140px'" :height="'40px'" :customStyle="'margin-bottom: 10px;'"/>
      </div>
      <div class="contact-wrapper">
        <div class="footer__contacts">
          <ul class="footer__contacts-wrapper">
            <li class="contact-title">
              <v-icon class="icon-position" small v-text="'mdi-cellphone-iphone'"></v-icon>
              +38 (044) 359-03-99
            </li>
            <li class="contact-title">
              <v-icon class="icon-position" small v-text="'mdi-map-marker'"></v-icon>
              Адреса: буд. 17, вулиця Лінійна, Київ, 03038
            </li>
            <li class="contact-title">
              <v-icon class="icon-position" small v-text="'mdi-email'"></v-icon>
              <a style="font-size: 0.78rem; font-family: Montserrat,sans-serif,Arial; color: #2f2f2f" href="mailto:office@bestleasing.com.ua">office@bestleasing.com.ua</a>
            </li>
          </ul>
        </div>
        <div class="footer__feedback">
          <div class="d-flex flex-column pa-1 feedback__text">
            <span style="font-size: 0.78rem; font-family: Montserrat,sans-serif,Arial;">Перейти на сайт</span>
            <a class="footer__best-leasing-link" target="_blank" href="https://bestleasing.com.ua/">bestleasing.com.ua</a>
          </div>
          <v-menu 
            top 
            :offset-y="true" 
            :close-on-click="false"
            :close-on-content-click="false">
            <template v-slot:activator="{ on }">
              <a 
                v-on="on"
                @click="formState = !formState"
                ref="techSupport"
                class="footer__custom-btn" 
                style="background: #e65048; font-family: montserrat;">
                Тех. підтримка
              </a>
            </template>
            <v-card :min-width="300" class="support-card">
              <v-card-title class="tech-support-title">
                <v-btn 
                  @click="$refs.techSupport.click()" 
                  style="position: absolute; right: 2px; top: 2px;" icon>
                  <v-icon small color="white" v-text="'mdi-close'"></v-icon>
                </v-btn>
                <v-icon color="white" v-text="'mdi-face-agent'"></v-icon>
                &nbsp;Зв'язатися з нами
              </v-card-title>
              <v-card-text class="pt-3 pb-1 tech-support">
                <div style="font-size: 0.78rem;">Тема</div>
                <v-text-field
                  v-model="theme"
                  @blur="$v.theme.$touch()"
                  :error-messages="themeErr"
                  color="#c0433c"
                  placeholder="Тема вашого звернення"
                  outlined
                  dense>
                </v-text-field>
                <div style="font-size: 0.78rem;">Повідомлення</div>
                <v-textarea
                  v-model="message"
                  @blur="$v.message.$touch()"
                  :error-messages="messageErr"
                  color="#c0433c"
                  placeholder="Ваше повiдомлення"
                  :rows="5"
                  outlined
                  dense>
                </v-textarea>
                <v-card-text class="pt-0 d-flex justify-center">
                  <v-btn 
                    @click="submit()"
                    color="#e65048"
                    :loading="sndMsgLodng"
                    dark small>Вiдправити
                  </v-btn>
                </v-card-text>
              </v-card-text>
            </v-card>
          </v-menu>
        </div>
      </div>
    </div>
    <div 
      class="container-fluid 
        d-flex 
        flex-wrap 
        justify-content-between 
        pt-2 pb-2">
      <div>
        <router-link class="--dark" :to="{path:'/dashboard'}">© 2020 ТОВ «Бест Лiзинг»</router-link>
      </div>
    </div>
  </footer>
</template>
<script>
import bestLLogo from '../../assets/svg-icons/best-leasing-logo.vue'

import axios from 'axios'

import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  components: {
    bestLLogo,
  },

  data: () => ({
    theme: null,
    message: null,

    formState: false,
    sndMsgLodng: false,

    commonErr: ['Обов`язкове поле'],
  }),

  validations() {
    return this.validationRules
  },
    
  computed: {
    xSmall() {
      return this.$vuetify.breakpoint.xs
    },

    curRoute() {
      return this.$route.path
    },

    validData() {
      return !this.$v.$invalid
    },

    validationRules() {
      let validateObj = {}

      this.formState ? 
        validateObj = {
            theme: { required },
            message: { required }
        } :
        false

      return validateObj 
    },

    messageErr() {
      if (!this.$v.message) return
      if (!this.$v.message.$error) return
      return this.commonErr
    },

    themeErr() {
      if (!this.$v.theme) return
      if (!this.$v.theme.$error) return
      return this.commonErr
    }
  },

  methods: {
    clearMsgObj() {
      this.theme = null
      this.message = null
    },
    submit() {
      !this.$v.$invalid ?
        this.sendAgntMsg() :
        this.highlightErrors()
    },

    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },

    msgObj() {
      return {
        agentId: this.$store.state.user.agent.id,
        subject: this.theme,
        message: this.message,
        _token: this.getCsrf()
      }
    },

    sendAgntMsg() {
      this.sndMsgLodng = true

      axios
        .post('/feedback/send', this.msgObj() )
        .then( res => {
          console.log(res)

          this.$notify({
            group: 'success',
            title: '',
            text: `Ваше звернення вiдправлено. Чекайте ми зв\`яжемося з вами найближчим часом`,
          })

          this.sndMsgLodng = false

          setTimeout(() => {
            this.$refs.techSupport.click()
            this.clearMsgObj()
          }, 800)
        })
        .catch( err => {
          console.log(err.response)

          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${err.response.status} \n ${err.response.data.message}`,
          })

          this.$catchStatus(err.response)
        })
    },

    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },

    mailTo() {
      window.location.href = "mailto:address@dmail.com";
    },
  },

  mounted() {
    console.log({formValidations: this.$v})
  },

  watch: {
    formState() {
      this.$v.$reset()
    },

    curRoute() {
      if (this.formState) {
        this.$refs.techSupport.click()
      }
    }
  }
}
</script>
<style lang="scss">
.feedback__text {
  .footer__best-leasing-link {
    color: #2f2f2f; 
    font-family: Montserrat,sans-serif,Arial;
    transition: color 0.3s;
    &:hover {
      color: #e65048;
    }
  }
}
.support-card {
  .tech-support-title {
    background: #e65048; 
    color: white; 
    padding: 5px 15px; 
    font-size: 1.05rem;
  }
  .tech-support {
    .v-text-field__details {
      margin-bottom: 0!important;
    }
    .v-input__slot {
      margin-bottom: 0!important;
    }
  }
}

</style>