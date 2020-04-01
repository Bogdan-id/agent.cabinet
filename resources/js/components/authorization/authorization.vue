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
						type="text" 
						placeholder="Телефон"
						v-model="phone"
						/>
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
		rememberMe: false
	}),
}
</script>