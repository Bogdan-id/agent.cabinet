<template>
	<div class="app__form-block">
		<div class="app__login-form">
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
						type="password" 
						placeholder="Пароль"
						v-model="password"
						@blur="$v.password.$touch()"
						@input="$v.password.$touch()"
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
					<button @click="submit()" class="app__btn-primary">
						<span v-if="!request">Увiйти</span>
						<div v-if="request" class="lds-dual-ring"></div>
					</button>
					<div class="app__sign-navigate">
						<a class="app__sign-navigate-link --link" href="#">Забули пароль?</a>
						<a class="app__sign-navigate-link --link" href="/register">Реєстрація</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, minLength, maxLength } from 'vuelidate/lib/validators'
import { minPassLength, maxPassLength} from '../../variables'

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
			minLength: minLength(minPassLength()),
			maxLength: maxLength(maxPassLength())
		},
	},
	methods: {
		minPassLength,
		maxPassLength,
		getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		},
		submit() {
			if(!this.$v.$invalid || this.$v.$dirty ){
				this.userRegister(this.getRegObject())
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
		userRegister(userObj) {
			this.request = true
			console.log(userObj)
			axios.post(`/login`, userObj)
			.then(response => {
				this.request = false
				console.log(response)
				this.$router.push('/home')
			})
			.catch(e => {
				this.request = false
				if(e.response.status == 422) {
					console.log(e.response.data)
					console.log(e.response.status)
					console.log(e.response.headers)
					this.$notify({
						message: 'Невірний логін або пароль',
						type: 'warning',
						horizontalAlign: 'center'
					})
				} else if (e.response.status == 429) {
					console.log(e.response.data)
					console.log(e.response.status)
					console.log(e.response.headers)
					this.$notify({
						message: 'Перевищено ліміт запитів. Спробуйте ще раз через 1-2 хвилини',
						type: 'warning',
						horizontalAlign: 'center'
					})
				} else {
					console.log(e.response.data)
					console.log(e.response.status)
					console.log(e.response.headers)
					this.$notify({
						message: `Код помилки: ${e.response.status} \n ${e.response.data.message}`,
						type: 'warning',
						horizontalAlign: 'center'
					})
				}
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
		passwordErrors() {
			const errors = []
			if (!this.$v.password.$error) return errors
			!this.$v.password.required && errors.push('Поле пароль обов\'язкове для заповнення')
			!this.$v.password.minLength && errors.push(`Мінімальна кількість знаків ${this.minPassLength()}`)
			!this.$v.password.maxLength && errors.push(`Максимальна кількість знаків ${this.maxPassLength()}`)
			return errors
		}
	},
	mounted() {
		this.reload
			? this.$router.go()
			: false
	}
}
</script>