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
						@paste="$v.email.$touch()"
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
				<div class="app__register-checkbox-wrapper">
					<input
						class="app__register-checkbox"
						id="register-checkbox"
						type="checkbox"
						v-model="consent"
						/>
					<span class="app__custom-checkbox"></span>
					<label for="register-checkbox" class="app__regiser-checkbox-label">
						Запам'ятати мене
					</label>
				</div>
				<div class="app__button-wrapper">
					<button @click="submit()" class="app__sign-in-btn">Увiйти</button>
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
		consent: false,
		pasteEvent: false,
		errors: {}
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
			&& this.consent
				? this.userRegister(this.getRegObject())
				: false
			// const tokenLogIn = 'fL9h15mSfNFxye321P68ZRCpWioDJfV9EXhc6cjR'
			// const tokenLogOut =  'T9wegtoS1EgWYAPRCPtLV8IavuUL6rbaTIPPxo82'
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
}
</script>