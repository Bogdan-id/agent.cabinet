<template>
	<div class="app__form-block">
		<div class="app__login-form">
			<div class="app__form-logo-wrapper">
				<div class="app__header-logo-card"></div>
			</div>
			<p class="app__login-form-title">Зареєструватися по номеру телефону</p>
			<p class="app__login-form-subtitle">Реєстрація</p>
			<div class="app__form">
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="text" 
						placeholder="Ім'я"
						v-model="name"
						@input="$v.name.$touch()"
						@blur="$v.name.$touch()"
						/>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text"
						id="phone"
						type="text" 
						placeholder="+38 (0__) ___ __ __"
						v-model="phone"
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
				<div class="app__register-checkbox-wrapper">
					<input
						class="app__register-checkbox"
						id="register-checkbox"
						type="checkbox"
						v-model="rememberMe"
						/>
					<span class="app__custom-checkbox"></span>
					<label for="register-checkbox" class="app__regiser-checkbox-label">
						Даю свою згоду на обробку моїх персональних даних у відповідності з <a class="--link" href="#">політикою конфіденційності</a>
					</label>
				</div>
				<div class="app__button-wrapper">
					<button class="app__sign-in-btn">Зареєструватися</button>
					<!-- <div class="app__sign-navigate">
						<a class="app__sign-navigate-link" href="#">Забули пароль?</a>
						<a class="app__sign-navigate-link" href="#">Регистрация</a>
					</div> -->
				</div>
				<p class="app__login-footer">
					Якщо у вас вже є доступ в особистий кабінет виконайте вхід на <a class="--link" href="#">сторінці авторизації</a>
				</p>
				<!-- <button @click="test()">TEST</button> -->
			</div>
		</div>
	</div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, email, alpha } from 'vuelidate/lib/validators'

export default {
	mixins: [validationMixin],
	data: () => ({
		name: null,
		email: null,
		phone: null,
		rememberMe: false,
		pasteEvent: false,
		errors: {}
	}),
	validations: {
		email: {
			required,
			email
		},
		name: {
			required,
			alpha
		},
		phone: {
		}
	},
	methods: {
		applyMask() {
			const el = document.getElementById('phone')
			const event = new Event('input', {bubbles: true})
			const mask = '+38 (0##) ### - ## - ##'
			const sign = '#'

			const numLengthRe = /[^#\d+]/g
			const notDigit = /[^\d]/g

			const firstIndex = mask.indexOf(sign)
			const countryCode = mask.slice(0, firstIndex)
			const numLength = mask.slice(firstIndex).replace(numLengthRe, '').length
			const number = this.phone.replace(countryCode, '').replace(numLengthRe, '')
			const cCpresent = this.phone.indexOf(countryCode)

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
					this.phone
						.replace(countryCode, '')
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if(number.length >= numLength && cCpresent === -1) {
				fillMask(
					this.phone
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if (this.phone.length > 1){
				fillMask(
					this.phone
						.slice(firstIndex)
						.replace(notDigit, '')
				)
			} else if (this.phone.length <= 1) {
				fillMask(
					this.phone
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
		}
	},
}
</script>