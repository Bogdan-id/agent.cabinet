<template>
	<div class="app__form-block">
		<div :class="`app__login-form ${$vuetify.breakpoint.xs ? 'pl-2 pr-2' : ''}`">
			<div class="app__form-logo-wrapper">
				<div class="app__header-logo-card"></div>
			</div>
      <!-- fake autofill form -->
      <div style="position: absolute; opacity: 0;">
        <label for="_email">email</label><input name="_email" type="email">
        <label for="_password">password</label><input name="_password" type="password">
      </div>
			<p class="app__login-form-title">Реєстрація</p>
			<p class="app__login-form-subtitle">Зареєструватися за номером телефону</p>
			<div class="app__form">
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="text" 
						placeholder="Ім'я"
						v-model="firstName"
						@blur="$v.firstName.$touch()"
						/>
						<span 
							:class="firstNameError
								? 'app__input-error input-error--active' 
								: 'app__input-error'"> 
							{{ firstNameErrors[0] }}
						</span>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text"
						id="number"
            autocomplete="anyrandomstring"
						placeholder="+38 (0__) ___ __ __"
						v-model="number"
						@paste="pasteEvent = true"
						@input="applyMask()"
            @blur="$v.number.$touch()"
						/>
            <span 
							:class="numberError
								? 'app__input-error input-error--active' 
								: 'app__input-error'"> 
							{{ numberErrors[0] }}
						</span>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						autocomplete="anyrandomstring"
						placeholder="Email"
						v-model="email"
						@blur="$v.email.$touch()"
						/>
						<span 
							:class="emailError
								? 'app__input-error input-error--active' 
								: 'app__input-error'"> 
							{{ emailErrors[0] }}
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
							:class="passwordError
								? 'app__input-error input-error--active' 
								: 'app__input-error'"> 
							{{ passwordErrors[0] }}
						</span>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="password" 
						placeholder="Повторіть пароль"
						v-model="repeatPassword"
						@blur="$v.repeatPassword.$touch()"
						/>
						<span 
							:class="repeatPasswordErrors
								? 'app__input-error input-error--active' 
								: 'app__input-error'"> 
							{{ repeatPasswordErrors[0] }}
						</span>
				</div>
				<div class="app__register-checkbox-wrapper">
					<input
						class="app__register-checkbox"
						id="register-checkbox"
						type="checkbox"
						v-model="consent"
						/>
					<span class="app__custom-checkbox"></span>
					<label for="register-checkbox" class="app__regiser-checkbox-label">
						Даю свою згоду на обробку моїх персональних даних у відповідності з 
						<a class="--link" href="#">політикою конфіденційності</a>
					</label>
				</div>
				<div class="app__button-wrapper">
					<button @click="submit()" class="app__btn-primary">
						<span v-if="!request">Зареєструватися</span>
						<div v-if="request" class="lds-dual-ring"></div>
					</button>
				</div>
				<p class="app__login-footer">
					Якщо у вас вже є доступ в особистий кабінет виконайте вхід на 
					<a class="--link" href="/login">сторінці авторизації</a>
				</p>
			</div>
		</div>
	</div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { 
	required, 
	email, 
	sameAs,
	minLength,
	maxLength
	} from 'vuelidate/lib/validators'
import { minPassLength, maxPassLength} from '../../variables'

import axios from 'axios'

export default {
	mixins: [validationMixin],
	data: () => ({
		// inputs
		firstName: null,
		email: null,
		number: null,
		password: null,
		repeatPassword: null,
		consent: false,

		// apply mask checker
		pasteEvent: false,

		// errors
		errors: {},

		// boolean
		request: false
	}),

	validations: {
		firstName: {
			required,
    },
		email: {
			required,
			email
    },
    number: {
      minLength: minLength(23)
    },
		password: {
			required,
			minLength: minLength(minPassLength()),
			maxLength: maxLength(maxPassLength()),
		},
		repeatPassword: {
			sameAsPassword: sameAs('password')
		}
	},
	methods: {
		getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		},
		minPassLength,
		maxPassLength,
		submit() {
			if(!this.$v.$invalid && this.$v.$dirty && this.consent){
				this.userRegister(this.getRegObject())
			} else {
				let error = 'Форма заповнена не невірно'
				this.simpleNotify('Помилка', error, 'error')
			}
		},
		getRegObject() {
			return {
				'name': this.firstName,
				'phone': this.number,
				'email': this.email,
				'password': this.password,
				'password_confirmation': this.repeatPassword,
				'_token': this.getCsrf(),
			}
		},
		userRegister(userObj) {
			this.request = true
			axios.post(`/register`, userObj)
			.then(() => { 
				this.request = false
				this.$router.go()
			})
			.catch(e => {
				this.request = false
					if(e.response.status == 422) {
						this.error422(e)
					} else if (e.response.status == 429) {
						this.error429(e)
					} else {
						this.otherErrors(e)
					}
				})
		},
		error422(e) {
			this.consoleError(e)
			const checkEmail = 'The email has already been taken.'
			const checkNumber = 'The phone has already been taken.'
			const emailError = 'Зазначена вами електронна пошта вже зареєстрована у нашому сервісі'
			const numberError = 'Зазначений вами номер телефону вже зареєстрований у нашому сервісі'
			let errorObj = e.response.data.errors
			if (Object.keys(errorObj).length > 0) {
				if(errorObj.hasOwnProperty('email')) {
					errorObj.email[0] == checkEmail
						? this.simpleNotify('Помилка', emailError, 'error')
						: this.simpleNotify('Помилка', errorObj.message, 'error')
				} else if (errorObj.hasOwnProperty('phone')) {
					errorObj.phone[0] == checkNumber
						? this.simpleNotify('Помилка', numberError, 'error')
						: this.simpleNotify('Помилка', errorObj.message, 'error')
				}	
			}
		},
		consoleError(e) {
			console.log(e.response.data)
			console.log(e.response.status)
			console.log(e.response.headers)
		},
		error429(e) {
			this.consoleError(e)
			const error = 'Перевищено ліміт запитів. Спробуйте ще раз через 1-2 хвилини'
			this.simpleNotify('Помилка', error, 'error')
		},
		otherErrors(e) {
			this.consoleError(e)
			const error = `Код помилки: ${e.response.status} \n ${e.response.data.message}`
			this.simpleNotify('Помилка', error, 'error')
		},
		simpleNotify(title, text, group) {
			this.$notify({
				group: group || 'standard',
				title: title,
				text: text,
			})
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
	},
	computed: {
		numberError() {
			return this.numberErrors.length > 0
    },
    numberErrors() {
      const errors = []
			if (!this.$v.number.$error) return errors
			!this.$v.number.minLenght && errors.push('Невірний номер')
			return errors
    },
		emailErrors() {
			const errors = []
			if (!this.$v.email.$error) return errors
			!this.$v.email.email && errors.push('Невірний імейл')
			!this.$v.email.required && errors.push('Обов\'язкове поле')
			return errors
		},
		firstNameErrors() {
			const errors = []
			if (!this.$v.firstName.$error) return errors
			!this.$v.firstName.required && errors.push('Обов\'язкове поле')
			return errors
		},
		passwordErrors() {
			const errors = []
			if (!this.$v.password.$error) return errors
			!this.$v.password.required && errors.push('Обов\'язкове поле')
			!this.$v.password.minLength && errors.push(`Мінімальна кількість знаків ${this.minPassLength()}`)
			!this.$v.password.maxLength && errors.push(`Максимальна кількість знаків ${this.maxPassLength()}`)
			return errors
		},
		repeatPasswordErrors() {
			const errors = []
			if (!this.$v.repeatPassword.$error) return errors
			!this.$v.repeatPassword.sameAsPassword && errors.push('Паролі не співпадають')
			return errors
		},
		emailError() {
			return this.emailErrors.length > 0 
		},
		firstNameError() {
			return this.firstNameErrors.length > 0 
		},
		passwordError() {
			return this.passwordErrors.length > 0 
		},
		repeatPasswordError() {
			return this.repeatPasswordErrors.length > 0 
		}
  },
  mounted() {
    window.addEventListener('keypress', (event) => {
      if (event.key === 'Enter') {
        this.submit()
      }
    })
  },
  beforeDestroy() {
    window.removeEventListener('keypress', (event) => {
      if (event.key === 'Enter') {
        this.submit()
      }
    })
  }
}
</script>