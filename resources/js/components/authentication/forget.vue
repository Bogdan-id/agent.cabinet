<template>
	<div class="app__form-block">
    <div :class="`app__login-form ${$vuetify.breakpoint.xs ? 'pl-2 pr-2' : ''}`" style="position: relative;">
      <v-progress-linear
        :active="loading"
        :indeterminate="loading"
        absolute
        bottom
        color="red">
      </v-progress-linear>
			<div class="app__form-logo-wrapper">
				<div class="app__header-logo-card"></div>
			</div>
      <!-- fake autofill form -->
      <div style="position: absolute; opacity: 0;">
        <label for="_email">email</label><input name="_email" type="email">
        <label for="_password">password</label><input name="_password" type="password">
      </div>
			<p class="app__login-form-title">Відновлення паролю</p>
      <!--  -->
			<div class="app__form">
				<div 
          v-if="this.userId === null"
          class="app__input-text-wrapper" >
					<input 
						class="app__input-text"
						id="number"
						type="tel" 
						placeholder="Вкажiть номер телефону"
						v-model="number"
						@paste="pasteEvent = true"
						@input="applyMask(); $v.number.$touch()"/>
          <span 
						:class="numberErrors.length > 0 
							? 'app__input-error input-error--active' 
							: 'app__input-error'"> 
              {{ numberErrors[0] }}
          </span>
				</div>
        <div v-if="userId && !showCaptcha" class="recovery-code-section">
          <div class="app__input-text-wrapper">
            <input 
              class="app__input-text"
              type="text" 
              placeholder="Код з SMS"
              v-model="verificationCode"
              @blur="$v.verificationCode.$touch()"/>
            <div v-show="secondTimer" class="wrap">
              <span style="
                font-size: 0.73rem; 
                color: #808080; 
                padding-top: 15px; 
                display: inline-flex; 
                max-width: 60%; 
                align-self: flex-end; 
                -webkit-align-self: flex-end;
                -ms-flex-item-align: end;  padding-bottom: 2px;">
                Повторно вiдправити код через
              </span>
              <div style="display: inline-block;">
                <div class="bounceball"></div>
                <div class="bounceball-text" id="bounceball-text"></div>
              </div>
            </div>
            <div 
              v-if="repeatSendSms && !secondTimer" 
              style="padding-top: 23px; padding-left: 2px">
              <v-btn 
                small dark color="grey darken-3"
                @click="sendSms()"
                style="font-size: 0.8rem; text-transform: none; height: 20px;">
                Надiслати код
              </v-btn>
            </div>
            <span 
              :class="verificationCodeErrors.length > 0 
                ? 'app__input-error input-error--active' 
                : 'app__input-error'"> 
                {{ verificationCodeErrors[0] }}
            </span>
          </div>
          <div style="height: 40px;"></div>
          <div class="app__input-text-wrapper">
            <input 
              class="app__input-text"
              type="password" 
              placeholder="Новий пароль"
              v-model="password"
              @blur="$v.password.$touch()"/>
            <span 
              :class="passwordErrors.length > 0 
                ? 'app__input-error input-error--active' 
                : 'app__input-error'"> 
                {{ passwordErrors[0] }}
            </span>
          </div>
          <div class="app__input-text-wrapper">
            <input 
              class="app__input-text"
              type="password" 
              placeholder="Пiдтвердiть пароль"
              v-model="repeatedPassword"
              @blur="$v.repeatedPassword.$touch()"/>
            <span 
              :class="repeatedPasswordErrors.length > 0 
                ? 'app__input-error input-error--active' 
                : 'app__input-error'"> 
                {{ repeatedPasswordErrors[0] }}
            </span>
          </div>
        </div>
        <!-- 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI -->
        <vue-recaptcha
          v-show="!$v.number.$error && number && !secondTimer && showCaptcha"
          @verify="verify($event)"
          @expired="expired($event)"
          ref="recaptcha"
          sitekey="6LcYM7sZAAAAAD1lQSj3jHiJRaez3aRXXEjNua7L" 
          :loadRecaptchaScript="true">
        </vue-recaptcha>
				<div v-if="!showCaptcha" class="app__button-wrapper">
					<button 
            @click="submit()"
            id="sign-in-btn" 
            class="app__btn-primary">
						<span v-if="!request">{{ userId ? 'Змiнити пароль' : 'Надiслати код'}}</span>
						<div v-if="request" class="lds-dual-ring"></div>
					</button>
					<div class="app__sign-navigate">
						<a class="app__sign-navigate-link --link" href="/login">Авторизацiя</a>
						<a class="app__sign-navigate-link --link" href="/register">Реєстрація</a>
					</div>
				</div>
        <div v-show="showCaptcha" >
          <span>
            <a style="display: inline-block;" class="app__sign-navigate-link --link" href="/login">Авторизацiя</a>
          </span>
          <span>&nbsp;|&nbsp;</span>
          <span>
            <a style="display: inline-block;" class="app__sign-navigate-link --link" href="/register">Реєстрація</a>
          </span>
        </div>
			</div>
		</div>
	</div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, sameAs, minLength } from 'vuelidate/lib/validators'
import VueRecaptcha from 'vue-recaptcha';

import axios from 'axios'

export default {
  components: {
    VueRecaptcha
  },
	mixins: [validationMixin],
	data: () => ({
		pasteEvent: false,
    request: false,
    loading: false,

    secondTimer: false,
    repeatSendSms: false,

    number: null,
    repeatedPassword: null,

    userId: null, 
    password: null,
    verificationCode: null,

    captchaVerified: false,
    showCaptcha: true,
  }),
  validations() {
    return this.validationRules
  },
	methods: {
    verify() {
      this.captchaVerified = true
      this.showCaptcha = false
      console.log('Verified')
    },
    expired() {
      this.$refs.recaptcha.reset()
      this.captchaVerified = false
      console.log('Expired')
    },
    reError() {
      this.$notify({
        group: 'error',
        title: 'Помилка',
        text: `Помилка. Спробуйте пiзнiше`,
      })
    },
    sendSms() {
      this.loading = true
      if(!this.captchaVerified) { 
        this.loading = false; 
        this.showCaptcha = true; 
        return 
      }
      axios
        .post('/json/password/reset/sendSms', { phone: this.number, _token: this.getCsrf() })
        .then(response => {
          this.loading = false
          this.userId = response.data.userId
          this.timer(120)
        })
        .catch(error => {
          console.log(error.response)
          this.loading = false
          if(error.response.status == 422) {
            this.$notify({
              group: 'error',
              title: 'Помилка',
              text: `Користувача з таким номером не існує!`,
            })
          }
        })
    },
    resetPassword() {
      this.loading = true
      let object = {
        userId: this.userId,
        password: this.password,
        verificationCode: this.verificationCode,
        _token: this.getCsrf()
      }
      axios
        .post('/json/password/reset/resetPassword', object)
        .then(response => {
          if(response.data.status === true) {
            this.$notify({
              group: 'success',
              title: `${response.data.massage}`,
              text: '',
            })
          } else {
            this.$notify({
              group: 'eror',
              title: `${response.data.massage}`,
              text: 'Зараз вас буде перенаправлено на сторiнку авторизації',
            })
          }
          setTimeout(() => {
            this.$router.go()
          }, 1200)
          this.userId = null
          this.loading = false
        })
        .catch(error => {
          console.log(error.response)
          this.$notify({
            group: 'eror',
            title: `${error.response.data.message}`,
            text: '',
          })
          this.loading = false
        })
    },
		getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
		submit() {
      if(!this.$v.$invalid) {
        this.userId !== null 
          ? this.resetPassword()
          : this.sendSms()
      } else this.highlightErrors()
		},
		applyMask() {
			const el = document.getElementById('number')
			const event = new Event('input', {bubbles: true})
			const mask = '+38 (0##) ### - ## - ##'
			const sign = '#'

			const numLengthRe = /[^#\d+]/g
			const notDigit = /[^\d]/g

			const firstIndex = mask.indexOf(sign)
			const countryCode = mask.slice(0, firstIndex)
			const numLength = mask.slice(firstIndex).replace(numLengthRe, '').length
			const number = this.number.replace(countryCode, '').replace(numLengthRe, '')
			const cCpresent = this.number.indexOf(countryCode)

      let splitMask = mask.split('')
			let indexes = []

			splitMask.forEach((val, i) => {
				val === sign ? indexes.push(i) : false
			})
			let fillMask = (val) => {
        val.split('').forEach((val, i) => {
            if(this.number[6] == 0) {
              this.number = this.number.substr(0, 6) + this.number.substr(6) 
            } else {
              indexes[i] ? splitMask[indexes[i]] = val : false
            }
          })
			}
			if(number.length >= numLength && cCpresent !== -1 && this.pasteEvent) {
				fillMask(
					this.number
						.replace(countryCode, '')
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if(number.length >= numLength && cCpresent === -1) {
				fillMask(
					this.number
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if (this.number.length > 1){
				fillMask(
					this.number
						.slice(firstIndex)
            .replace(notDigit, '')
				)
			} else if (this.number.length <= 1) {
				fillMask(
					this.number
						.replace(notDigit, '')
				)
			}

			const joinMask = splitMask.join('').replace(/[^\d]+$/, '')

			if(el.value !== joinMask) {
				el.value = joinMask
				el.setSelectionRange(splitMask.indexOf(sign), splitMask.indexOf(sign))
				el.dispatchEvent(event)
			}
			this.pasteEvent = false
    },
    signInByEnter() {
      window.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
          this.submit()
        }
      })
    },
    timer(seconds) {
      this.repeatSendSms = false
      this.secondTimer = true
      this.showCaptcha = false
      let timer = setInterval(() => {
        if(seconds <= 0){
          clearInterval(timer)
          document.getElementById("bounceball-text").innerHTML = ""
          this.secondTimer = false
          this.repeatSendSms = true
        } else {
          document.getElementById("bounceball-text").innerHTML = seconds + " с."
        }
        seconds -= 1
      }, 1000)
    },
	},
	computed: {
		passwordErrors() {
			const errors = []
			if (!this.$v.password.$error) return errors
      !this.$v.password.required && errors.push('Обов\'язкове поле')
      !this.$v.password.minLength && errors.push('Має мiстити мінімум 8 знаків')
			return errors
    },
    numberErrors() {
      const errors = []
			if (!this.$v.number.$error) return errors
      !this.$v.number.required && errors.push('Обов\'язкове поле')
      !this.$v.number.minLength && errors.push('Невiрный номер')
			return errors
    },
    verificationCodeErrors() {
      const errors = []
			if (!this.$v.verificationCode.$error) return errors
			!this.$v.verificationCode.required && errors.push('Обов\'язкове поле')
			return errors
    },
    repeatedPasswordErrors() {
      const errors = []
			if (!this.$v.repeatedPassword.$error) return errors
			!this.$v.repeatedPassword.sameAsPassword && errors.push('Паролi не спiвпадають')
			return errors
    },
    validationRules() {
      return {
        number: (() => { 
          if(this.userId === null) {
            return { required, minLength: minLength(23) }
          } else return true
        })(),
        password: (() => { 
          if(this.userId !== null) {
            return { required, minLength: minLength(8)}
          } else return true
        })(),
        repeatedPassword: (() => { 
          if(this.userId !== null) {
            return { sameAsPassword: sameAs('password') }
          } else return true
        })(),
        verificationCode: (() => { 
          if(this.userId !== null) {
            return { required }
          } else return true
        })()
      }
    },
  },
  watch: {
    userId(val) {
      if(val !== null) {
        this.timer(120) 
      }
    } 
  },
  mounted() {
    this.signInByEnter()
    let reCaptchaScript = document.createElement('script')
    reCaptchaScript.src = "https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit"
    reCaptchaScript.defer = true
    reCaptchaScript.async = true
    document.head.appendChild(reCaptchaScript)
  },
  beforeDestroy() {
    window.removeEventListener('keypress', (event) => {
      if (event.key === 'Enter') {
        this.submit()
      }
    })
  },
}
</script>

<style lang="scss">
// @import  url(https://fonts.googleapis.com/css?family=Montserrat);

body {
  position: relative; 
  width: 100%;
  height: 100vh;
  font-family: Montserrat;
}
.wrap {
  display: flex; 
  justify-content: space-around;
}
.bounceball-text {
  color: black;
  display: inline-block;
  margin-left: 5px;
  min-width: 60px;
  font-size: 1.3rem;
}
.bounceball {
  position: relative;
  display: inline-block;
  height: 35px;
  width: 15px;
  &:before {
    position: absolute;
    content: '';
    display: block;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #5f6368;
    transform-origin: 50%;
    animation: bounce 500ms alternate infinite ease;
  }
}
@keyframes bounce {
  0% {
    top: 30px;
    height: 5px;
    border-radius: 60px 60px 20px 20px;
    transform: scaleX(2);
  }
  35% {
    height: 15px;
    border-radius: 50%;
    transform: scaleX(1);
  }
  100% {
    top: 0;
  }
}
</style>