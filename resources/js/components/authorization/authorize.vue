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
						@input="applyMask()"
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
				<div class="app__register-checkbox-wrapper">
					<input
						class="app__register-checkbox"
						id="register-checkbox"
						type="checkbox"
						v-model="rememberMe"
						/>
					<span class="app__custom-checkbox"></span>
					<label for="register-checkbox" class="app__regiser-checkbox-label">
						Запам'ятати мене
					</label>
				</div>
				<div class="app__button-wrapper">
					<button @click="submit()" class="app__sign-in-btn">Зареєструватися</button>
					<div class="app__sign-navigate">
						<a class="app__sign-navigate-link --link" href="#">Забули пароль?</a>
						<a class="app__sign-navigate-link --link" href="#">Регистрация</a>
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

export default {
	mixins: [validationMixin],
	data: () => ({
		password: null,
		number: null,
		rememberMe: false,
		pasteEvent: false,
		errors: {}
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
		submit() {
			!this.$v.$invalid 
			&& this.$v.$dirty 
				? console.log(this.getRegObject())
				: false
		},
		getRegObject() {
			return {
				'number': this.number,
				'password': this.password,
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
		}
	},
}
</script>