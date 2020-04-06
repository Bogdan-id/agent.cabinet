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
						type="text" 
						placeholder="email"
						v-model="email"
						@blur="$v.email.$touch()"
						@input="$v.email.$touch()"
						/>
					<span 
						:class="emailErrors.length > 0 
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
					<button @click="submit()" class="app__btn-primary">Увiйти</button>
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
import { required, minLength, maxLength, email } from 'vuelidate/lib/validators'
import { minPassLength, maxPassLength} from '../../variables'

import axios from 'axios'

export default {
	mixins: [validationMixin],
	data: () => ({
		password: null,
		email: null,
		remember: false,
		pasteEvent: false
	}),
	validations: {
		password: {
			required,
			minLength: minLength(minPassLength()),
			maxLength: maxLength(maxPassLength())
		},
		email: {
			email,
			required
		}
	},
	methods: {
		minPassLength,
		maxPassLength,
		getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		},
		submit() {
			!this.$v.$invalid 
			&& this.$v.$dirty 
				? this.userRegister(this.getRegObject())
				: false
		},
		getRegObject() {
			return {
				'email': this.email,
				'password': this.password,
				'_token': this.getCsrf(),
			}
		},
		userRegister(userObj) {
			console.log(userObj)
			axios.post(`/login`, userObj)
			.then(response => {
				console.log(response)
			})
			.catch(e => {
				console.log(e)
			})
		},
	},
	computed: {
		emailErrors() {
			const errors = []
			if (!this.$v.email.$error) return errors
			!this.$v.email.email && errors.push('Невірний імейл')
			!this.$v.email.required && errors.push('Поле імейл обов\'язкове для заповнення')
			return errors
		},
		passwordErrors() {
			const errors = []
			if (!this.$v.password.$error) return errors
			!this.$v.password.required && errors.push('Поле пароль обов\'язкове для заповнення')
			!this.$v.password.minLength && errors.push(`Мінімальна кількість знаків ${this.minPassLength()}`)
			!this.$v.password.maxLength && errors.push(`Максимальна кількість знаків ${this.maxPassLength()}`)
			return errors
		}
	},
}
</script>