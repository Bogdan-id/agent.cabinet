<template>
	<div class="app__form-block">
		<div class="app__login-form" style="position: relative;">
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
						@input="applyMask()"/>
          <span 
						:class="numberErrors.length > 0 
							? 'app__input-error input-error--active' 
							: 'app__input-error'"> 
              {{ numberErrors[0] }}
          </span>
				</div>
        <div v-if="userId !== null" class="recovery-code-section">
          <div class="app__input-text-wrapper">
            <input 
              class="app__input-text"
              type="text" 
              placeholder="Код з SMS"
              v-model="verificationCode"
              @input="$v.verificationCode.$touch()"
              @blur="$v.verificationCode.$touch()"/>
            <div v-if="secondTimer" class="wrap">
              <span style="font-size: 0.8rem; color: #808080;">Повторно вiдправити код через&nbsp;</span>
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
          <div style="height: 32px;"></div>
          <div class="app__input-text-wrapper">
            <input 
              class="app__input-text"
              type="password" 
              placeholder="Новий пароль"
              v-model="password"
              @input="$v.password.$touch()"
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
              @input="$v.repeatedPassword.$touch()"
              @blur="$v.repeatedPassword.$touch()"/>
            <span 
              :class="repeatedPasswordErrors.length > 0 
                ? 'app__input-error input-error--active' 
                : 'app__input-error'"> 
                {{ repeatedPasswordErrors[0] }}
            </span>
          </div>
        </div>
				<div class="app__button-wrapper">
					<button @click="submit()" id="sign-in-btn" class="app__btn-primary">
						<span v-if="!request">{{ userId ? 'Змiнити пароль' : 'Надiслати код'}}</span>
						<div v-if="request" class="lds-dual-ring"></div>
					</button>
          <!-- <v-btn @click="test()">timer</v-btn> -->
					<div class="app__sign-navigate">
						<a class="app__sign-navigate-link --link" href="/login">Авторизацiя</a>
						<a class="app__sign-navigate-link --link" href="/register">Реєстрація</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, sameAs, minLength } from 'vuelidate/lib/validators'

import axios from 'axios'

export default {
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
  }),
  validations() {
    return this.validationRules
  },
	methods: {
    sendSms() {
      this.loading = true
      axios
        .post('/password/reset/sendSms', {phone: this.number, _token: this.getCsrf()})
        .then(response => {
          console.log(response)
          this.loading = false
          this.userId = response.data.userId
          this.timer(120)
          console.log('response')
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
        .post('/password/reset/resetPassword', object)
        .then(response => {
          console.log(response)
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
      console.log(this.$v)
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
    // test() {
    //   this.timer(10)
    // }
	},
	computed: {
		passwordErrors() {
			const errors = []
			if (!this.$v.password.$error) return errors
      !this.$v.password.required && errors.push('Поле пароль обов\'язкове для заповнення')
      !this.$v.password.minLength && errors.push('Поле пароль має мiстити мінімум 8 знаків')
			return errors
    },
    numberErrors() {
      const errors = []
			if (!this.$v.number.$error) return errors
      !this.$v.number.required && errors.push('Поле номер телефону обов\'язкове для заповнення')
      !this.$v.number.minLength && errors.push('Невiрный номер')
			return errors
    },
    verificationCodeErrors() {
      const errors = []
			if (!this.$v.verificationCode.$error) return errors
			!this.$v.verificationCode.required && errors.push('Поле код верифікації обов\'язкове для заповнення')
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
    console.log(this.$v)
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
@import  url(https://fonts.googleapis.com/css?family=Montserrat);

body {
  position: relative; 
  width: 100%;
  height: 100vh;
  font-family: Montserrat;
}
// .wrap {
//   position: absolute;
//   top: 20px;
//   right: 10px;
// }
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