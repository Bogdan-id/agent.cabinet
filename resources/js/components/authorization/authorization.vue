<template>
	<div class="app__form-block">
		<div class="app__login-form">
			<div class="app__form-logo-wrapper">
				<div class="app__header-logo-card"></div>
			</div>
			<p class="app__login-form-title">Авторизация</p>
			<p class="app__login-form-subtitle">Вход в личный кабинет</p>
			<div class="app__form">
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="text" 
						placeholder="Имя"
						v-model="name"
						/>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text"
						id="phone"
						@input="applyMask()"
						type="text" 
						placeholder="+38 (0__) ___ __ __"
						v-model="phone"
						/>
						<span v-if="phoneErrors" class="app__input-errors"> {{ errors.phone }} </span>
				</div>
				<div class="app__input-text-wrapper">
					<input 
						class="app__input-text" 
						type="text" 
						placeholder="Email" 
						v-model="email"
						/>
				</div>
				<label class="app__input-checkbox-wrapper">
					{{ 'Запомнить меня' }}
					<input 
						type="checkbox"
						v-model="rememberMe"
						/>
					<span class="app__custom-checkbox"></span>
				</label>
				<div class="app__button-wrapper">
					<button class="app__sign-in-btn">Войти</button>
					<div class="app__sign-navigate">
						<a class="app__sign-navigate-link" href="#">Забыли пароль?</a>
						<a class="app__sign-navigate-link" href="#">Регистрация</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data: () => ({
		name: null,
		email: null,
		phone: null,
		rememberMe: false,
		errors: {
			phone: [
				'Some error'
			]
		}
	}),
	methods: {
		applyMask() {
			const el = document.getElementById('phone')
			const event = new Event('input', {bubbles: true})

			// '#' - is place where the real sign (number character) will be substituted
			// You can add any other characters between first and last '#' below (exclude numbers)
			// Also you can edit quantity of '#'
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
	}
}
</script>