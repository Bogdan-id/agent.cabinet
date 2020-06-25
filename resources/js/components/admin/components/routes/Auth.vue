<template>
  <div class="admin-auth-form">
    <v-card width="400" class="admin-panel__login-card mx-auto" elevation="10">
		<v-card-title class="headline">Авторизацiя</v-card-title>
      <v-card-text class="pb-0">
        <v-form>
          <v-text-field
            @blur="$v.userObj.username.$touch()"
            @input="$v.userObj.username.$touch()"
            :error-messages="usernameErr"
            v-model="userObj.username"
            label="Iм`я користувача"
            prepend-icon="mdi-account-circle"/>
          <v-text-field
            @blur="$v.userObj.password.$touch()"
            @input="$v.userObj.password.$touch()"
            :error-messages="passwordErr"
            v-model="userObj.password"
            :type="showPassword ? 'text' : 'password'"
            label="Пароль"
            prepend-icon="mdi-lock"
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="showPassword = !showPassword"/>
        </v-form>
        <v-checkbox
          v-model="userObj.remember"
          :false-value="2"
          :true-value="1"
          label="Запам`ятати мене">
        </v-checkbox>
      </v-card-text>
      <v-divider class="mt-0 mb-0"></v-divider>
      <v-card-actions style="justify-content: center;">
        <span>
          <v-btn 
            @click="signIn()" 
            class="error white--text" 
            :loading="loading">
            Увiйти
          </v-btn>
        </span>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import axios from 'axios'

export default {
  mixins: [validationMixin],
	data: () => ({
    showPassword: false,
    loading: false,
    commonErr: ['Обов`язкове поле'],

    userObj: {
      username: null,
      password: null,
      remember: 2,
      _token: null,
    },
  }),
  validations: {
    userObj: {
      username: { required },
      password: { required },
    },
  },
  computed: {
    usernameErr() {
      if (!this.$v.userObj.username.$error) return
      return this.commonErr
    },
    passwordErr() {
      if (!this.$v.userObj.password.$error) return
      return this.commonErr
    },
  },
  methods: {
    signIn() {
      console.log('sign-in')
      console.log(this.$v.$dirty)
      console.log(!this.$v.$invalid)
      this.$v.$dirty
      && !this.$v.$invalid
        ? this.sendRequest()
        : this.highlightErrors()
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    sendRequest() {
      this.loading = true
      axios.
        post('/admin/auth/login', this.userObj)
        .then(response => {
          console.log(response)
          // this.$notify({
          //   group: 'success',
          //   title: 'Успiшно',
          //   text: '',
          // })
          this.$router.go()
          this.loading = false
        })
        .catch(error => {
          console.log(error.response)
          this.loading = false
          this.$router.go()
          // this.$notify({
          //   group: 'error',
          //   title: 'Помилка',
          //   text: `${error.response.status} \n ${error.response.data.message}`,
          // })

        })
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
  },
  created() {
    let params = this.$router.currentRoute.params
    if(params.reload && params.reload === true) {
      this.$router.go()
    } else return
  },
  mounted() {
    this.userObj._token = this.getCsrf()
  }
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