<template>
	<div class="app__form-block">
		<div class="app__login-form">
			<div class="app__form-logo-wrapper">
				<div class="app__header-logo-card"></div>
			</div>
			<p class="app__login-form-title">Реєстрація</p>
			<div class="app__form">
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="text" 
						placeholder="Прізвище"
						v-model="lastName"
						@input="$v.lastName.$touch()"
						@blur="$v.lastName.$touch()"
						/>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="text" 
						placeholder="Ім'я"
						v-model="firstName"
						@input="$v.firstName.$touch()"
						@blur="$v.firstName.$touch()"
						/>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="text" 
						placeholder="По батькові"
						v-model="patronymic"
						@input="$v.patronymic.$touch()"
						@blur="$v.patronymic.$touch()"
						/>
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
						<!-- <span v-if="phoneErrors" class="app__input-errors"> {{ errors.phone }} </span> -->
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
					<button @click="submit()" class="app__sign-in-btn">Зареєструватися</button>
				</div>
				<p class="app__login-footer">
					Якщо у вас вже є доступ в особистий кабінет виконайте вхід на <a class="--link" href="#">сторінці авторизації</a>
				</p>
			</div>
		</div>
	</div>
</template>

<script>
import axios from 'axios'
import { validationMixin } from 'vuelidate'
import { 
	required, 
	email, 
	alpha, 
	sameAs,
	minLength,
	maxLength
	} from 'vuelidate/lib/validators'
import { minPassLength, maxPassLength} from '../../variables'

export default {
	mixins: [validationMixin],
	data: () => ({
		// inputs
		firstName: null,
		lastName: null,
		patronymic: null,
		email: null,
		number: null,
		password: null,
		repeatPassword: null,
		consent: false,

		// apply mask checker
		pasteEvent: false,

		// errors
		errors: {}
	}),

	validations: {
		firstName: {
			required,
			alpha
		},
		lastName: {
			required,
			alpha
		},
		patronymic: {
			required,
			alpha
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
		minPassLength,
		maxPassLength,
		submit() {
			console.log(JSON.stringify(this.getRegObject()))
			// console.log(this.$v)
			// !this.$v.$invalid 
			// && this.$v.$dirty 
			// && this.consent
			// 	? console.log(this.getRegObject())
			// 	: false
			const token = 'fL9h15mSfNFxye321P68ZRCpWioDJfV9EXhc6cjR'
			const email = 'test@test.com'
			const password = 12341234
			axios.post(`/register`, {
      	body: JSON.stringify(this.getRegObject(password, email))
			})
			.then(response => {
				console.log(response)
			})
			.catch(e => {
				console.log(e)
			})
		},
		getRegObject(password, email) {
			return {
				'first_name': this.firstName,
				'last_name': this.lastName,
				'patronymic': this.patronymic,
				'email': email,
				'phone': this.number,
				'password': password,
				'password_confirmation': password,
			}
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
	},
}
</script>