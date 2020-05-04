<template>
  <div class="admin-auth-form">
    <v-card width="400" class="admin-panel__login-card mx-auto">
		<v-card-title>Авторизацiя</v-card-title>
      <v-card-text class="pb-0">
        <v-form>
          <v-text-field
            v-model="userName"
            label="Iм`я користувача" 
            prepend-icon="mdi-account-circle"/>
          <v-text-field
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
            label="Пароль"
            prepend-icon="mdi-lock"
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="showPassword = !showPassword"/>
        </v-form>
        <v-checkbox
          label="Запам`ятати мене">
        </v-checkbox>
      </v-card-text>
      <v-divider class="mt-0 mb-0"></v-divider>
      <v-card-actions style="justify-content: center;">
        <span><v-btn class="error white--text">Увiйти</v-btn></span>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
export default {
	data: () => ({
    showPassword: false,
    userName: null,
    password: null,
    remember: false
	})
}
</script>

<style lang="scss">
.admin-auth-form {
  min-height: 100vh;
  background: #5f6368;
  display: flex; 
  justify-content: center; 
  align-items: flex-start;
  .admin-panel__login-card {
    margin-top: 80px;
  }
  .v-label {
    margin: 0!important;
  }
  .v-input--checkbox {
    margin-top: 5px!important;
  }
}
</style>