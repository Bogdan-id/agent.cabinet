<template>
	<div class="app__form-block">
		<div class="app__login-form">
			<div class="app__form-logo-wrapper">
				<div class="app__header-logo-card"></div>
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
						@input="$v.firstName.$touch()"
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
						type="text" 
						placeholder="+38 (0__) ___ __ __"
						v-model="number"
						@paste="pasteEvent = true"
						@input=" applyMask()"
						/>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="text" 
						placeholder="Email"
						v-model="email"
						@blur="$v.email.$touch()"
						@input="$v.email.$touch()"
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
						@input="$v.password.$touch()"
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
						@input="$v.repeatPassword.$touch()"
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
				this.simpleNotify(error, 'warning')
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
			console.log(userObj)
			axios.post(`/register`, userObj)
			.then(response => { 
			console.log(response)
			this.request = false
			this.$router.push('/verification')
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
						? this.simpleNotify(emailError, 'warning')
						: this.simpleNotify(errorObj.message, 'warning')
				} else if (errorObj.hasOwnProperty('phone')) {
					errorObj.phone[0] == checkNumber
						? this.simpleNotify(numberError ,'warning')
						: this.simpleNotify(errorObj.message, 'warning')
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
			this.simpleNotify(error, 'warning')
		},
		otherErrors(e) {
			this.consoleError(e)
			const error = `Код помилки: ${e.response.status} \n ${e.response.data.message}`
			this.simpleNotify(error, 'warning')
		},
		simpleNotify(message, type) {
			this.$notify({
				message: message,
				type: type,
				horizontalAlign: 'center'
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
						indexes[i] ? splitMask[indexes[i]] = val : false
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
		phoneErrors() {
			return this.errors.phone.length > 0
		},
		emailErrors() {
			const errors = []
			if (!this.$v.email.$error) return errors
			!this.$v.email.email && errors.push('Невірний імейл')
			!this.$v.email.required && errors.push('Поле імейл обов\'язков для заповнення')
			return errors
		},
		firstNameErrors() {
			const errors = []
			if (!this.$v.firstName.$error) return errors
			!this.$v.firstName.required && errors.push('Поле "Ім\'я" - обов\'язкове для заповнення')
			return errors
		},
		passwordErrors() {
			const errors = []
			if (!this.$v.password.$error) return errors
			!this.$v.password.required && errors.push('Поле "Пароль" - обов\'язкове для заповнення')
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
}
</script>