<template>
	<div class="app__form-block">
		<div :class="`app__login-form ${$vuetify.breakpoint.xs ? 'pl-2 pr-2' : ''}`">
			<div class="app__form-logo-wrapper">
				<div class="app__header-logo-card"></div>
			</div>
			<p class="app__login-form-title">Авторизація</p>
			<p class="app__login-form-subtitle">Вхід до особистого кабінету</p>
			<div class="app__form">
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text"
						id="number"
						type="tel" 
						placeholder="+38 (0__) ___ __ __"
						v-model="number"
						@paste="pasteEvent = true"
						@input=" applyMask()"
						/>
          <span 
						:class="numberErrors.length > 0 
							? 'app__input-error input-error--active' 
							: 'app__input-error'"> 
              {{ numberErrors[0] }}
          </span>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="password" 
						placeholder="Пароль"
						v-model="password"
						@blur="$v.password.$touch()"
						/>
						<span 
              :class="passwordErrors.length > 0 
                ? 'app__input-error input-error--active' 
                : 'app__input-error'"> 
                {{ passwordErrors[0] }}
            </span>
				</div>
				<div class="app__register-checkbox-wrapper">
					<input
						class="app__register-checkbox"
						id="register-checkbox"
						type="checkbox"
						v-model="remember"
						/>
					<span class="app__custom-checkbox"></span>
					<label for="register-checkbox" class="app__regiser-checkbox-label">
						Запам'ятати мене
					</label>
				</div>
				<div class="app__button-wrapper">
					<button @click="submit()" id="sign-in-btn" class="app__btn-primary">
						<span v-if="!request">Увiйти</span>
						<div v-if="request" class="lds-dual-ring"></div>
					</button>
					<div class="app__sign-navigate">
						<router-link class="app__sign-navigate-link --link" :to="{path: '/login/forget'}">Забули пароль?</router-link>
						<a class="app__sign-navigate-link --link" href="/register">Реєстрація</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

import axios from 'axios'

export default {
	props: ['reload'],
	mixins: [validationMixin],
	data: () => ({
		password: null,
		number: null,
		remember: false,
		pasteEvent: false,
		request: false,
	}),
	validations: {
		password: {
			required,
    },
    number: {
      required
    }
	},
	methods: {
		getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
		submit() {
      this.highlightErrors()
			if(!this.$v.$invalid && this.$v.$dirty ){
				this.signIn(this.getRegObject())
			} else {
				this.$notify({
						message: 'Форма заповнена не невірно',
						type: 'info',
						horizontalAlign: 'center'
					})
			}
		},
		getRegObject() {
			return {
				'phone': this.number,
				'password': this.password,
				'_token': this.getCsrf(),
			}
		},
		checkUser() {
			axios.get(`/getCurrentUser`)
				.then(response => {
					// user has registered account and the application is approved
					if(response.data.is_active === 1) {
						this.checkUserAgent()
					// user has registered an account. Application at the verification stage
					} else if(response.data.user.is_active === 0) {
						this.$router.push('/verification')
					}
				})
				.catch(error => this.$catchStatus(error))
		},
		checkUserAgent() {
      // user is tied to an agent. Pass to dashboard
      axios.get(`/getUserAgent`)
				.then(response => {
					// user has registered account and the application is approved
					if(response.data.agent !== null) {
            this.$chLocal('/')
          // Application approved. Fill the form (complete register)
          } else {
            this.$router.push('/finish-register')
          }
				})
				.catch(error => {
          this.$router.push('/finish-register')
					this.$catchStatus(error)
				})
		},
		signIn(userObj) {
			this.request = true
			axios.post(`/login`, userObj)
			.then(() => {
        this.request = false
        this.$chLocal('/')
			})
			.catch(e => {
				this.request = false
				if(e.response.status == 422) {
					this.consoleErrors(e)
					const error = 'Невірний логін або пароль'
					this.simpleNotify('Помилка', error, 'error')
				} else if (e.response.status == 429) {
					this.consoleErrors(e)
					const error = 'Перевищено ліміт запитів. Спробуйте ще раз через 1-2 хвилини'
					this.simpleNotify('Помилка', error, 'error')
				} else {
					this.$catchStatus(e)
				}
			})
		},
		simpleNotify(title, text, group) {
			this.$notify({
				group: group || 'standard',
				title: title,
				text: text,
			})
		},
		consoleErrors(e) {
			console.log(e.response.data)
			console.log(e.response.status)
			console.log(e.response.headers)
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
    }
	},
	computed: {
		passwordErrors() {
			const errors = []
			if (!this.$v.password.$error) return errors
			!this.$v.password.required && errors.push('Обов\'язкове поле')
			return errors
    },
    numberErrors() {
      const errors = []
			if (!this.$v.number.$error) return errors
			!this.$v.number.required && errors.push('Обов\'язкове поле')
			return errors
    }
	},
	created() {
		this.checkUser()
  },
  mounted() {
    this.signInByEnter()
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