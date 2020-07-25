<template>
  <v-card class="edit-profile" elevation="7">
    <v-card-title class="d-block grey darken-3 white--text">
      <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-account'"></v-icon>
      Профiль
    </v-card-title>
    <v-card-text class="pt-7">
    <div class="profile-block">
      <form @submit.prevent>
        <v-card class="mb-6 custom-border">
          <v-card-title>
            ПIБ
          </v-card-title>
          <v-card-text>
            <v-row>
              <div class="col-md-4 pt-0 pb-0">
                <v-text-field
                  v-model="user.last_name"
                  :error-messages="last_nameErr"
                  @blur="$v.user.last_name.$touch()"
                  label="Прізвище"
                  :placeholder="user.last_name"
                  dense>
                </v-text-field>
              </div>
              <div class="col-md-4 pt-0 pb-0">
                <v-text-field
                  v-model="user.first_name"
                  :error-messages="first_nameErr"
                  @blur="$v.user.first_name.$touch()"
                  label="Ім'я"
                  dense>
                </v-text-field>
              </div>
              <div class="col-md-4 pt-0 pb-0">
                <!-- @input="$v.user.patronymic.$touch()"
                  @blur="$v.user.patronymic.$touch()"
                  :error-messages="patronymicErr" -->
                <v-text-field
                  v-model="user.patronymic"
                  label="По батькові"
                  dense>
                </v-text-field>
              </div>
            </v-row>
          </v-card-text>
        </v-card>
        <!--  -->
        <v-card class="mb-6 custom-border">
          <v-card-title>
            Контактна iнформацiя
          </v-card-title>
          <v-card-text>
            <v-row>
              <div class="col-md-5 pt-0 pb-0">
                <v-text-field
                  v-model="user.phone"
                  :error-messages="phoneErr"
                  @input="applyMask()"
                  @blur="$v.user.phone.$touch()"
                  id="number"
                  label="Телефон"
                  outlined dense>
                </v-text-field>
              </div>
              <div class="col-md-6 pt-0 pb-0">
                <v-text-field
                  :error-messages="emailErr"
                  @blur="$v.user.email.$touch()"
                  v-model="user.email"
                  label="Email"
                  outlined dense>
                </v-text-field>
              </div>
            </v-row>
          </v-card-text>
        </v-card>
        <v-card class="mb-6 custom-border">
          <v-card-title>
            Посадовi данi
          </v-card-title>
          <v-card-text>
            <v-row>
              <div class="col-md-5 pt-0 pb-0">
                <!-- :error-messages="company_typeErr"
                  @input="$v.user.company_type.$touch()"
                  @blur="$v.user.company_type.$touch()" -->
                <v-select
                  v-model="user.company_type"
                  append-icon=""
                  label="Місце роботи"
                  :items="companyTypes"
                  outlined dense>
                </v-select>
              </div>
              <div class="col-md-3 pt-0 pb-0">
                <v-text-field
                  v-if="user.company_type !== 'freelance'"
                  v-model="user.company_name"
                  :error-messages="company_nameErr"
                  @blur="$v.user.company_name.$touch()"
                  label="Назва компанії"
                  outlined dense>
                </v-text-field>
              </div>
              <div class="col-md-4 pt-0 pb-0">
                <v-text-field
                  v-if="user.company_type !== 'freelance'"
                  v-model="user.position"
                  :error-messages="positionErr"
                  @blur="$v.user.position.$touch()"
                  label="Посада"
                  :placeholder="user.position"
                  outlined dense>
                </v-text-field>
              </div>
            </v-row>
          </v-card-text>
        </v-card>
        <!--  -->
        <v-card class="mb-6 custom-border">
          <v-card-title>
            Особовi данi
          </v-card-title>
          <v-card-text>
            <v-row>
              <div class="col-md-4 pt-0 pb-0">
                <v-dialog
                  v-model="modal"
                  ref="modal"
                  width="290px">
                <template v-slot:activator="{ on }">
                  <!-- :error-messages="birthErr"
                    @input="$v.user.birth.$touch()"
                    @blur="$v.user.birth.$touch()" -->
                  <v-text-field
                    v-model="user.birth"
                    v-on="on"
                    label="Дата народження"
                    prepend-icon="mdi-calendar-range"
                    dense readonly>
                  </v-text-field>
                </template>
                <v-date-picker 
                  @change="save"
                  v-model="choosedDate"  
                  ref="picker"
                  color="red lighten-1"
                  :min="hundredYears" 
                  :max="eighteenYearsAgo">
                </v-date-picker>
              </v-dialog>
              </div>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-checkbox 
                  v-model="showPassportEditField" label="Паспортнi данi" color="#d24a43">
                </v-checkbox>
              </v-col>
            </v-row>
            <div class="row" v-if="showPassportEditField">
              <div class="col-md-4 pt-0 pb-0">
                <v-select
                  append-icon=""
                  label="Тип паспорту"
                  @change="clearPassportData()"
                  v-model="user.passport_type_id"
                  :items="pasportItems"
                  item-text="text"
                  item-value="value"
                  outlined dense>
                </v-select>
              </div>
              <div class="col-md-4 pt-0 pb-0">
                <v-text-field v-if="!bioPassport && user.passport_type_id"
                  :error-messages="passport_serieErr"
                  @input="trimExceededLength('passport-serie', 2, (arg) => {return arg.replace(/[^\а-яА-Я]/g, '').toUpperCase()})"
                  @blur="$v.user.passport_serie.$touch()"
                  v-model="user.passport_serie"
                  id="passport-serie"
                  label="Серiя паспорту"
                  outlined dense>
                </v-text-field>
                <v-text-field v-if="bioPassport  && user.passport_type_id"
                  label="Номер УНЗР"
                  :error-messages="passport_serieErr"
                  @input="trimExceededLength('profile-unzr', 13, (arg) => {return arg.replace(/[^\d]/g, '').toUpperCase()})"
                  id="profile-unzr"
                  @blur="$v.user.passport_serie.$touch()"
                  v-model="user.passport_serie"
                  outlined dense>
                </v-text-field>
              </div>
              <div class="col-md-4 pt-0 pb-0">
                <v-text-field v-if="!bioPassport && user.passport_type_id"
                  v-model="user.passport_number"
                  :error-messages="passport_numberErr"
                  @input="trimExceededLength('profile-passport-number', 6, (arg) => {return arg.replace(/[^\d]/g, '').toUpperCase()})"
                  @blur="$v.user.passport_number.$touch()"
                  id="profile-passport-number"
                  label="Номер паспорту"
                  outlined dense>
                </v-text-field>
                <v-text-field v-if="bioPassport && user.passport_type_id"
                  v-model="user.passport_number"
                  :error-messages="passport_numberErr"
                  @input="trimExceededLength('profile-passport-bio-number', 9, (arg) => {return arg.replace(/[^\d]/g, '').toUpperCase()})"
                  @blur="$v.user.passport_number.$touch()"
                  id="profile-passport-bio-number"
                  label="Номер документа"
                  outlined dense>
                </v-text-field>
              </div>
            </div>
            <!--  -->
            <div class="row">
              <div class="col-md-4 pt-0 pb-0">
                <v-text-field
                  :error-messages="innErr"
                  @blur="$v.user.inn.$touch()"
                  v-model="user.inn"
                  v-mask="'##########'"
                  label="Ідентифікаційний код"
                  outlined dense>
                </v-text-field>
              </div>
              <div class="col-md-4 pt-0 pb-0">
                <v-text-field
                  :error-messages="card_numberErr"
                  @blur="$v.user.card_number.$touch()"
                  v-model="user.card_number"
                  v-mask="'#### #### #### ####'"
                  label="Реквiзити картки"
                  outlined dense>
                </v-text-field>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8 pt-0 pb-0">
                <v-text-field
                  :error-messages="ibanErr"
                  @blur="$v.user.iban.$touch()"
                  v-model="user.iban"
                  @input=" trimExceededLength('profile-iban', 29)"
                  id="profile-iban"
                  label="IBAN"
                  outlined dense>
                </v-text-field>
              </div>
            </div>
          </v-card-text>
        </v-card>
        <!--  -->
        <v-card class="mb-6 custom-border">
          <v-card-title>
            Лiзинг
          </v-card-title>
          <v-card-text>
            <div class="row">
              <div class="col-md-3 pt-0 pb-0">
                <v-text-field
                  v-model="ab_size"
                  label="Розмiр АВ"
                  placeholder="Розмiр АВ"
                  append-icon="mdi-percent-outline"
                  dense readonly>
                </v-text-field>
              </div>
              <div class="col-md-4 pt-0 pb-0">
                <!-- :error-messages="purpose_of_paymentErr"
                  @input="$v.user.purpose_of_payment.$touch()"
                  @blur="$v.user.purpose_of_payment.$touch()" -->
                <v-text-field
                  v-model="user.purpose_of_payment"
                  label="Призначення платежу"
                  dense>
                </v-text-field>
              </div>
              <div class="col-md-4 pt-0 pb-0">
                <v-text-field
                  v-if="manager !== null"
                  v-model="manager.name"
                  label="Куратор"
                  placeholder="Куратор"
                  dense readonly>
                </v-text-field>
              </div>
            </div>
          </v-card-text>
        </v-card>
        <div class="text-center">
          <v-btn
            color="red darken-1"
            style="color: white;"
            :loading="loading"
            :disabled="profileHasNoChanges"
            @click="updateProfile()">
            Оновити профіль
          </v-btn>
        </div>
        <div class="clearfix"></div>
      </form>
    </div>
    </v-card-text>
  </v-card>
</template>
<script>
import axios from 'axios'

import { validationMixin } from 'vuelidate'
import { required, minLength, email } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  data: () => ({
    user: {
      last_name: null,
      first_name: null,
      patronymic: null,
      phone: null,
      email: null,
      company_type: null,
      company_name: null,
      position: null,
      passport_type_id: null,
      passport_serie: null,
      passport_number: null,
      inn: null,
      birth: null,
      card_number: null,
      purpose_of_payment: null,
      iban: null,
      leasingCurrencyCourse: null,
      customStepOptionFirst: null,
      customStepOptionMiddle: null,
      customUniversalOption: null,
      leasingAmountDkp: null,
      advance: null,
    },
    userBackUp: {},

    loading: false,
    manager: null,
    ab_size: null,
    leasingTerm: null,
    leasingAmountDkp: null,

    /* temp data */
    choosedDate: null,

    showPassportEditField: false,
    pasteEvent: false,
    

    /* item v-model */
    passportType: null,

    /* item types */
    pasportItems: [
      { text: 'ID-картка', value: 2 },
      { text: 'Книжкового зразка', value: 1 }
    ],
    companyTypes: [
      { text: 'Незалежний агент', value: 'freelance' },
      { text: 'Дилер', value: 'dealer' }
    ],

    /* modals */
    modal: false,
  }),
  validations() {
    return {
      user: this.validateObj,
    }
  },
  methods: {
    assignObject() {
      let user = {
        last_name: this.$store.state.user.agent.last_name,
        first_name: this.$store.state.user.agent.first_name, // user
        patronymic: this.$store.state.user.agent.patronymic,
        phone: this.$store.state.user.user.phone, // user
        email: this.$store.state.user.user.email, // user
        company_type: this.$store.state.user.agent.company_type,
        company_name: this.$store.state.user.agent.company_name,
        position: this.$store.state.user.agent.position,
        passport_type_id: this.$store.state.user.agent.passport_type_id,
        ...this.$store.state.user.agent.document && this.$store.state.user.agent.document.unzr_number && {passport_serie: this.$store.state.user.agent.document.unzr_number},
        ...this.$store.state.user.agent.document && this.$store.state.user.agent.document.serie && {passport_serie: this.$store.state.user.agent.document.serie},
        ...this.$store.state.user.agent.document && this.$store.state.user.agent.document.id_card_number && {passport_number: this.$store.state.user.agent.document.id_card_number},
        ...this.$store.state.user.agent.document && this.$store.state.user.agent.document.passport_number && {passport_number: this.$store.state.user.agent.document.passport_number},
        inn: this.$store.state.user.agent.inn,
        birth: this.$store.state.user.agent.birth,
        card_number: this.$store.state.user.agent.card_number,
        purpose_of_payment: this.$store.state.user.agent.purpose_of_payment,
        iban: this.$store.state.user.agent.iban,
        _token: this.getCsrf()
      }

      Object.assign(this.user, user)
      for (let value in this.user) {
        if(!this.user[value]) {
          this.user[value] = ''
        }
      }
      Object.assign(this.userBackUp, this.user)

      this.manager = this.$store.state.user.agent.manager
      this.ab_size = this.$store.state.user.agent.ab_size
      this.leasingTerm = this.$store.state.user.agent.leasingTerm
      this.leasingAmountDkp = this.$store.state.user.agent.leasingAmountDkp
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    clearPassportData() {
      this.user.passport_serie = null
      this.user.passport_number = null
    },
    updateProfile() {
      !this.$v.$invalid
        ? this.sendRequest()
        : this.highlightErrors()
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
    sendRequest() {
      let object = {}
      for (let val in this.user) {
        if(this.user[val]) {
          object[val] = this.user[val]
        }
      }
      console.log(object)
      this.loading = true
      axios 
        .post(`/agent/update/${this.$store.state.user.agent.id}`, object)
        .then(response => {
          console.log(response)
          this.$notify({
            group: 'success',
            title: 'Профiль успiшно оновлено',
            text: '',
          })
          this.loading = false
          this.$store.dispatch('getCurrentUser')
            .then(() => {
              setTimeout(() => {
                this.assignObject()
              }, 1000)
            })
        })
        .catch(error => {
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
          this.assignObject()
          this.loading = false
          console.log(error.response)
        })
    },
    save (date) {
      this.$refs.modal.save(date)
    },
    trimExceededLength(elId, maxLength, callback) {
      let el = document.getElementById(elId)
      let event = new Event('input', {bubbles: true})
      if(el.value && typeof callback === "function") {
        if(el.value !== callback(el.value)) {
          el.value = callback(el.value)
          el.dispatchEvent(event)
        }
      }
      if(el.value && el.value.length > maxLength) {
        el.value = el.value.substr(0, maxLength)
        el.dispatchEvent(event)
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
			const number = this.user.phone.replace(countryCode, '').replace(numLengthRe, '')
			const cCpresent = this.user.phone.indexOf(countryCode)

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
					this.user.phone
						.replace(countryCode, '')
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if(number.length >= numLength && cCpresent === -1) {
				fillMask(
					this.user.phone
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if (this.user.phone.length > 1){
				fillMask(
					this.user.phone
						.slice(firstIndex)
						.replace(notDigit, '')
				)
			} else if (this.user.phone.length <= 1) {
				fillMask(
					this.user.phone
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
    signInByEnter() {
      window.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
          this.submit()
        }
      })
    }
  },
  computed: {
    profileHasNoChanges() {
      return JSON.stringify(this.userBackUp) === JSON.stringify(this.user)
    },
    last_nameErr() {
      const errors = []
			if (!this.$v.user.last_name.$error) return errors
			!this.$v.user.last_name.required && errors.push('Поле "Прiзвище" - обов\'язкове для заповнення')
			return errors
    },
    first_nameErr() {
      const errors = []
			if (!this.$v.user.first_name.$error) return errors
			!this.$v.user.first_name.required && errors.push('Поле "Iм`я" - обов\'язкове для заповнення')
			return errors
    },
    patronymicErr() {
      const errors = []
			if (!this.$v.user.patronymic.$error) return errors
			!this.$v.user.patronymic.required && errors.push('Поле "По батьковi" - обов\'язкове для заповнення')
			return errors
    },
    phoneErr() {
      const errors = []
			if (!this.$v.user.phone.$error) return errors
      !this.$v.user.phone.required && errors.push('Поле "Телефон" - обов\'язкове для заповнення')
      !this.$v.user.phone.minLength && errors.push('Нe вiрний номер')
			return errors
    },
    emailErr() {
      const errors = []
			if (!this.$v.user.email.$error) return errors
      !this.$v.user.email.required && errors.push('Поле "Email" - обов\'язкове для заповнення')
      !this.$v.user.email.email && errors.push('Невiрний email')
			return errors
    },
    company_typeErr() {
      const errors = []
			if (!this.$v.user.company_type.$error) return errors
			!this.$v.user.company_type.required && errors.push('Поле "Тип компанії" - обов\'язкове для заповнення')
			return errors
    },
    company_nameErr() {
      const errors = []
			if (!this.$v.user.company_name.$error) return errors
			!this.$v.user.company_name.required && errors.push('Поле "Назва компанiї" - обов\'язкове для заповнення')
			return errors
    },
    positionErr() {
      const errors = []
			if (!this.$v.user.position.$error) return errors
			!this.$v.user.position.required && errors.push('Поле "Посада" - обов\'язкове для заповнення')
			return errors
    },
    birthErr() {
      const errors = []
			if (!this.$v.user.birth.$error) return errors
			!this.$v.user.birth.required && errors.push('Поле "Дата народження" - обов\'язкове для заповнення')
			return errors
    },
    passport_serieErr() {
      const errors = []
			if (!this.$v.user.passport_serie.$error) return errors
			!this.$v.user.passport_serie.required && errors.push('Обов\'язкове для заповнення поле')
      !this.$v.user.passport_serie.minLength && errors.push('Невiрна кiлькiсть знакiв')
      return errors
    },
    passport_numberErr() {
      const errors = []
			if (!this.$v.user.passport_number.$error) return errors
			!this.$v.user.passport_number.required && errors.push('Поле "Номер паспорту" - обов\'язкове для заповнення')
      !this.$v.user.passport_number.minLength && errors.push('Невiрна кiлькiсть знакiв')
      return errors
    },
    innErr() {
      const errors = []
			if (!this.$v.user.inn.$error) return errors
      !this.$v.user.inn.minLength && errors.push('Невiрний iдентифiкацiйний код')
      return errors
    },
    card_numberErr() {
      const errors = []
			if (!this.$v.user.card_number.$error) return errors
      !this.$v.user.card_number.lunValid && errors.push('Невiрний номер банкiвської картки')
      return errors
    },
    ibanErr() {
      const errors = []
			if (!this.$v.user.iban.$error) return errors
      !this.$v.user.iban.isValidIban && errors.push('Невiрний IBAN')
      return errors
    },
    purpose_of_paymentErr() {
      const errors = []
			if (!this.$v.user.purpose_of_payment.$error) return errors
			!this.$v.user.purpose_of_payment.required && errors.push('Поле "Призначення платежу" - обов\'язкове для заповнення')
			return errors
    },
    validateObj() {
      return {
        last_name: { required },
        first_name: { required },
        // patronymic: { required },
        phone: { 
          required,
          minLength: minLength(23),
          },
        email: { 
          required,
          email },
        // company_type: { required },
        company_name: (() => { 
          if(this.user.company_type === 'dealer') {
            return { required }
          } else return true
        })(),
        position: (() => { 
          if(this.user.company_type === 'dealer') {
            return { required }
          } else return true
        })(),
        // passport_type_id: { required },
        passport_serie:
          (() => { 
          if(this.user.passport_type_id === 1 && this.showPassportEditField) {
            // паспорт старого образца
            return {
              required,
              minLength: minLength(2),
              // maxLength: maxLength(2)
            }
          } else if(this.user.passport_type_id === 2 && this.showPassportEditField) {
            // id картка
            return {
              required,
              minLength: minLength(13),
            }
          } else return true
        })(),
        passport_number:
          (() => { 
          if(this.user.passport_type_id === 1 && this.showPassportEditField) {
            // паспорт старого образца
            return {
              required,
              minLength: minLength(6),
              // maxLength: maxLength(14)
            }
          } else if(this.user.passport_type_id === 2 && this.showPassportEditField) {
            // id карточка
            return {
              required,
              minLength: minLength(9),
            }
          } else return true
        })(),
        inn: { 
          minLength: minLength(10)
        },
        // birth: { required },
        card_number: { 
          lunValid: function luhn(array) {
            return number => {
              if(!number) return true
              let pureNum = number.replace(/[^\d]/g, '')
              let len = pureNum ? pureNum.length : 0, bit = 1, sum = 0;
              while (len--) {sum += !(bit ^= 1) 
                ? parseInt(pureNum[len], 10) 
                : array[pureNum[len]]}
              return sum % 10 === 0 && sum > 0;
            } 
          }([0, 2, 4, 6, 8, 1, 3, 5, 7, 9]) },
        // purpose_of_payment: { required },
        iban: { 
          isValidIban: function isValidIBANNumber(input) {
            if(!input) return true
            let countriesCode = {
                AD: 24, AE: 23, AT: 20, AZ: 28, BA: 20, BE: 16, BG: 22, BH: 22, BR: 29,
                CH: 21, CR: 21, CY: 28, CZ: 24, DE: 22, DK: 18, DO: 28, EE: 20, ES: 24,
                FI: 18, FO: 18, FR: 27, GB: 22, GI: 23, GL: 18, GR: 27, GT: 28, HR: 21,
                HU: 28, IE: 22, IL: 23, IS: 26, IT: 27, JO: 30, KW: 30, KZ: 20, LB: 28,
                LI: 21, LT: 20, LU: 20, LV: 21, MC: 27, MD: 24, ME: 22, MK: 19, MR: 27,
                MT: 31, MU: 30, NL: 18, NO: 15, PK: 24, PL: 28, PS: 29, PT: 25, QA: 29,
                RO: 24, RS: 22, SA: 24, SE: 24, SI: 19, SK: 24, SM: 27, TN: 24, TR: 26,
                UA: 29
            }
            let iban = String(input).toUpperCase().replace(/[^A-Z0-9]/g, ''), // keep only alphanumeric characters
                code = iban.match(/^([A-Z]{2})(\d{2})([A-Z\d]+)$/) // match and capture (1) the country code, (2) the check digits, and (3) the rest digits
            // check syntax and length
            if (!code || iban.length !== countriesCode[code[1]]) {
                return false
            }
            // rearrange country code and check digits, and convert chars to ints
            let digits = (code[3] + code[1] + code[2]).replace(/[A-Z]/g, (letter) => {
                return letter.charCodeAt(0) - 55
            })
            return (digits) => {
              let checksum = digits.slice(0, 2), fragment
              for (let offset = 2; offset < digits.length; offset += 7) {
                  fragment = String(checksum) + digits.substring(offset, offset + 7)
                  checksum = parseInt(fragment, 10) % 97
              }
              return checksum === 55
            }
          }
        },
        // leasingCurrencyCourse: (() => { 
        //   if(this.hasForeignCurrency) {
        //     return null
        //   } else return true
        // })(),
        // customStepOptionFirst: (() => {
        //   if (this.hasIrregular && this.calcObj.customGraphicType === 3){
        //     return null
        //   } else return true
        // })(),
        // customStepOptionMiddle: (() => {
        //   if (this.hasIrregular && this.calcObj.customGraphicType === 3){
        //     return null
        //   } else return true
        // })(),
        // customUniversalOption: (() => { 
        //   if(this.hasIrregular && this.calcObj.customGraphicType === 5) {
        //     return null
        //   } else return true
        // })(),
        // leasingAmountDkp: (() => { 
        //   if(this.discountPrice) {
        //     return null
        //   } else return true
        // })(),
      }
    },
    userEditPassword() {
      return this.passportType !== null
    },
    bioPassport() {
      return this.user.passport_type_id === 2
    },
    companyType() {
      return this.user.company_type === 'freelance'
        ? 'Незалежний агент'
        : 'Дилер'
    },
    eighteenYearsAgo() {
      let currentDate = new Date()
      let month = currentDate.getUTCMonth()
      let day = currentDate.getUTCDate()
      let year = currentDate.getUTCFullYear()
      return new Date(year - 18, month, day).toISOString().substr(0, 10)
    },
    hundredYears() {
      let currentDate = new Date()
      let month = currentDate.getUTCMonth()
      let day = currentDate.getUTCDate()
      let year = currentDate.getUTCFullYear()
      return new Date(year - 100, month, day).toISOString().substr(0, 10)
    },
  },
  watch: {
    showPassportEditField() {
      if(this.$v.user.passport_serie.$invalid) this.user.passport_serie = null
      if(this.$v.user.passport_number.$invalid) this.user.passport_number = null
      
    },
    modal(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
    },
		choosedDate(val) {
			const [year, month, day] = val.split('-')
			this.user.birth = `${day}.${month}.${year}`
    },
  },
  mounted() {
    this.assignObject()
    delete this.user.agent
  },
}
</script>
<style lang="scss">
  .edit-profile {
    .v-text-field input {
      padding-left: 10px!important;
    }
    .form-control {
      border-bottom: 1px solid #a8a6a6;
      border-radius: 0;
    }
    .v-text-field__slot .v-label--active, .v-label {
      font-size: 20px!important;
    }
    .v-card {
      &.custom-border {
        border-left: 5px solid #e65048;
      }
    }
  }
  .profile-block {
    .v-card__title {
      color: #727170!important;
      font-size: 1.15rem!important;
    }
  }
</style>
