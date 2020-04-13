<template>
  <div class="app">
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="7" md="5" lg="4" xl="3">
            <v-card class="elevation-9 form-card" flat>
              <div class="complete-reg-form__title headline indigo white--text">
                <div class="complete-reg-form__title-logo"></div>
                <span class="d-block headline">Заповніть особові дані</span>
              </div>
              <div class="pt-12" v-show="!nextPage">
                <div class="input__text">
                  <v-text-field 
                      :error-messages="lastNameErrors" 
                      @blur="$v.lastName.$touch()" 
                      @input="$v.lastName.$touch(); trimReplaceNum('crf-last-name')"
                      v-model="lastName"
                      :maxlength="lastNameMaxLength"
                      id="crf-last-name"
                      label="Прізвище" 
                      outlined dense clearable>
                  </v-text-field>
                  <v-text-field 
                      v-model="knownUserData.name" 
                      readonly outlined dense>
                  </v-text-field>
                  <v-text-field 
                      :error-messages="patronymicErrors" 
                      @blur="$v.patronymic.$touch()" 
                      @input="$v.patronymic.$touch(); trimReplaceNum('crf-patronymic')"
                      v-model="patronymic"
                      :maxlength="patronymicMaxLength"
                      id="crf-patronymic"
                      label="По батькові"
                      outlined dense clearable>
                  </v-text-field>
                  <v-text-field 
                      v-model="knownUserData.phone"
                      prepend-inner-icon="mdi-cellphone-android" 
                      outlined dense readonly>
                  </v-text-field>
                  <v-text-field
                      v-model="knownUserData.email"
                      prepend-inner-icon="mdi-at" 
                      outlined dense readonly>
                  </v-text-field>
                </div>
                <div class="d-flex justify-space-around">
                  <span class="title font-italic font-weight-light complete-reg-form-sub-title">Ви є -&nbsp;</span>
                  <v-radio-group 
                      @input="$v.companyType.$touch()" 
                      @change="$v.companyType.$touch(); scrollToId('input-67')" 
                      :error-messages="companyTypeErrors" 
                      v-model="companyType"
                      class="complete-reg-form__radio" 
                      dense>
                    <v-radio label="Салон" color="light-blue darken-4" value="salon"></v-radio>
                    <v-radio label="Дилер" color="light-blue darken-4" value="dealer"></v-radio>
                  </v-radio-group>
                </div>
                <div :class="expandCompany">
                  <div class="input__text">
                    <v-text-field 
                        :error-messages="companyNameErrors" 
                        @blur="$v.companyName.$touch();" 
                        @input="$v.companyName.$touch()" 
                        v-model="companyName" 
                        :maxlength="companyNameMaxLength"
                        label="Назва компанії" 
                        outlined dense clearable>
                    </v-text-field>
                    <v-text-field 
                        :error-messages="positionErrors" 
                        @blur="$v.position.$touch()" 
                        @input="$v.position.$touch(); scrollToId('btn-next')" 
                        v-model="position"
                        :maxlength="positionMaxLength"
                        label="Посада" 
                        outlined dense clearable>
                    </v-text-field>
                  </div>
                </div>
                <div :class="showBtnNextPage">
                  <span>
                    <v-btn @click="nextPage = !nextPage" 
                        class="d-block"
                        id="btn-next" 
                        color="primary"
                        :disabled="!firstPageValid">
                        Далi&nbsp;
                        <v-icon v-text="'mdi-arrow-right-bold-circle'"></v-icon>
                   </v-btn>
                  </span>
                </div>
              </div>
              <div v-show="nextPage">
              <div class="d-flex justify-space-between pt-7">
                <span class="subtitle-1 font-weight-bold complete-reg-form-sub-title">
                    Оберіть тип паспорту:
                </span>
                <v-radio-group 
                    :error-messages="passportTypeErrors" 
                    @input="$v.passportType.$touch()" 
                    v-model="passportType"
                    class="complete-reg-form__radio"
                    dense>
                  <v-radio label="ID - картка" color="primary" value="2"></v-radio>
                  <v-radio  label="Книжковий" color="primary" value="1"></v-radio>
                </v-radio-group>
              </div>
                <div :class="expandPassport">
                  <div class="d-flex password-wrapper">
                    <v-text-field
                        v-show="passportType === '1'" 
                        :error-messages="passportSeriesErrors" 
                        @input="lettersToUpperCase(); $v.passportSeries.$touch()" 
                        @blur="$v.passportSeries.$touch()"
                        v-model="passportSeries" 
                        class="passport-serries" 
                        id="passwordS" 
                        :maxlength="passportSeriesMaxLength"  
                        hint="Серія паспорта"
                        label="Серія" 
                        dense outlined counter persistent-hint>
                    </v-text-field>
                    <v-text-field
                        v-show="passportType === '1'" 
                        :error-messages="passportNumberErrors" 
                        @blur="$v.passportNumber.$touch()" 
                        @input="$v.passportNumber.$touch()"
                        v-mask="passportNumberMask"  
                        v-model="passportNumber" 
                        class="passport-number"
                        label="Номер"
                        :maxlength="passportNumberMaxLength"
                        hint="Номер паспорта"
                        outlined counter dense persistent-hint>
                    </v-text-field>
                    <v-text-field 
                        v-show="passportType === '2'"
                        :error-messages="unzrErrors" 
                        @blur="$v.unzr.$touch()"
                        @input="$v.unzr.$touch()"
                        v-model="unzr"
                        v-mask="unzrMask"
                        class="passport-number"
                        label="УНЗР"
                        hint="Унікальний номер запису у реєстрі"
                        :maxlength="unzrMaxLength" 
                        outlined dense counter clearable>
                    </v-text-field>
                    <v-text-field
                        v-show="passportType === '2'"
                        :error-messages="bioPassportNumberErrors" 
                        @blur="$v.bioPassportNumber.$touch()" 
                        @input="$v.bioPassportNumber.$touch()" 
                        v-model="bioPassportNumber" 
                        v-mask="bioPassportNumberMask" 
                        class="passport-serries"
                        label="Номер документа"
                        :maxlength="bioPassportNumberMaxLength"
                        hint="Номер документа"
                        outlined counter dense persistent-hint>
                    </v-text-field>
                  </div>
                  <div class="input__text">
                    <v-text-field 
                        :error-messages="innErrors" 
                        @blur="$v.inn.$touch()" 
                        @input="$v.inn.$touch()"
                        v-mask="innMask"
                        v-model="inn" 
                        prepend-inner-icon="mdi-account-outline"
                        label="Ідентифікаційний код" 
                        :maxlength="innMaxLength" 
                        outlined dense counter clearable>
                    </v-text-field>
                    <v-dialog
                      v-model="modal"
                      ref="modal"
                      width="290px">
                      <template v-slot:activator="{ on }">
                        <v-text-field
                            v-model="dateOfBirth"
                            v-on="on"
                            prepend-inner-icon="mdi-calendar-range"
                            label="Дата народження"
                            dense readonly outlined>
                        </v-text-field>
                      </template>
                      <v-date-picker 
                          @change="save"
                          v-model="choosedDate"  
                          ref="picker"
                          :min="hundredYears" 
                          :max="eighteenYearsAgo">
                      </v-date-picker>
                    </v-dialog>
                    <v-text-field 
                        :error-messages="cardNumberErrors" 
                        @blur="$v.cardNumber.$touch()" 
                        @input="$v.cardNumber.$touch()"
                        v-model="cardNumber" 
                        v-mask="cardNumberMask"
                        prepend-inner-icon="mdi-credit-card-outline" 
                        label="Реквізити карти для виплат" 
                        outlined dense>
                    </v-text-field>
                  </div>
                </div>
                <div class="d-flex justify-space-around pb-6 pt-3">
                  <span>
                    <v-btn @click="nextPage = !nextPage" class="d-block" color="primary">
                      <v-icon v-text="'mdi-arrow-left-bold-circle'">
                      </v-icon>
                      &nbsp;Назад
                    </v-btn>
                  </span>
                  <span>
                    <v-btn @click="submit()" 
                        :disabled="!dataValid"
                        :loading="request"
                        class="d-block" 
                        color="primary">
                      <v-icon v-text="'mdi-check-bold'">
                      </v-icon>
                      &nbsp;Зберегти
                    </v-btn>
                  </span>
                </div>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </div>
</template>

<script>
import axios from 'axios'
import { validationMixin } from 'vuelidate'
import { required, minLength } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  data: () => ({
    /* known user data */
    knownUserData: {
      id: null,
      name: null,
      email: null,
      phone: null,
    },

    /* boolean */
    modal: false,
    nextPage: false,
    request: false,

    /* masks */
    unzrMask: '########-#####',
    passportNumberMask: '######',
    bioPassportNumberMask: '#########',
    innMask: '##########',
    cardNumberMask: '####  ####  ####  ####',

    /* Personal data */
    lastName: null,
    patronymic: null,
    companyType: null,
    companyName: null,
    position: null,
    dateOfBirth: null,

    /* passport data */
    passportType: null,
    passportSeries: null,
    passportNumber: null,
    bioPassportNumber: null,
    unzr: null,
    inn: null,
    cardNumber: null,

    /* temporary data */
    choosedDate: null,

    /* max-length values */
    passportSeriesMaxLength: 2,
    lastNameMaxLength: 46,
    patronymicMaxLength: 46,
    companyNameMaxLength: 120,
    positionMaxLength: 120,
    innMaxLength: 10,
    bioPassportNumberMaxLength: 9,
    passportNumberMaxLength: 6,
    unzrMaxLength: 14,
  }),
  validations: {
    lastName: {
      required,
    },
    patronymic: {
      required
    },
    companyName: {
      required
    },
    position: {
      required
    },
    companyType: {
      checked: value => value != null
    },
    passportType: {
      checked: value => value != null
    },
    passportSeries: {
      required,
      minLength: minLength(2)
    },
    passportNumber: {
      required,
      minLength: minLength(6)
    },
    unzr: {
      required,
      minLength: minLength(14)
    },
    bioPassportNumber: {
      required,
      minLength: minLength(9)
    },
    inn: {
      required,
      minLength: minLength(10)
    },
    cardNumber: {
      required,
      minLength: minLength(16),
      lunValid: function luhn(array) {
        return function (number) {
          let pureNum = number.replace(/[^\d]/g, '')
          let len = pureNum ? pureNum.length : 0, bit = 1, sum = 0;
          while (len--) {sum += !(bit ^= 1) 
            ? parseInt(pureNum[len], 10) 
            : array[pureNum[len]]}
          return sum % 10 === 0 && sum > 0;
        }
      }([0, 2, 4, 6, 8, 1, 3, 5, 7, 9])
    }
  },
  methods: {
    getCsrf() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    submit() {
      if(this.dataValid) {
        // console.log(this.finalObj())
        this.request = true
        axios.post(`/agent/create`, this.finalObj())
          .then((response) => {
            console.log(response)
            const message = 'Зараз вас буде перенаправлено до вашого остобистого кабiнету'
            this.simpleNotify('Успiшно', message, 'success')
            setTimeout(() => {
              this.$router.push('/home')
            }, 5000)
            this.request = false
          })
          .catch(error => {
            console.log(error.response)
            this.simpleNotify('Помилка', error.response.statusText, 'warning')
            this.request = false
          })
      }
    },
    finalObj() {
      return {
        'user_id': this.knownUserData.id,
        'first_name': this.knownUserData.name,
        // this.knownUserData.email
        // this.knownUserData.phone
        'last_name': this.lastName,
        'patronymic': this.patronymic,
        'company_type': this.companyType,
        'company_name': this.companyName,
        'position': this.position,
        'birth': this.dateOfBirth,
        'passport_type_id': this.passportType,
        'passport_serie': this.getPassportCode,
        'passport_number': this.getPassportNumber,
        'inn': this.inn,
        'card_number': this.cardNumber.replace(/[^\d]/g, ''),
        '_token': this.getCsrf()
      }
    },
    lettersToUpperCase() {
      const event = new Event('input', {bubbles: true})
      const el = document.getElementById('passwordS')
      let value = el.value.toUpperCase().replace(/[^А-Я]/g, '')
      if(el.value !== value) {
        el.value = value
        el.dispatchEvent(event)
      }
    },
    save (date) {
      this.$refs.modal.save(date)
    },
    trimReplaceNum(id) {
      const event = new Event('input', {bubbles: true})
      const el = document.getElementById(id)
      let value = el.value.trim().replace(/[^А-Яа-я]/g, '')
      if(el.value !== value) {
        el.value = value
        el.dispatchEvent(event)
      }
    },
    getCurrentUser() {
      axios.get('/getCurrentUser').then(({data}) => {
        Object.assign(this.knownUserData, data)
      })
      .catch(e => {
        if(e.response.status == 401) {
          this.$router.push({ name: 'authorization', params: { reload: true }})
        }
        // console.log(e.response)
      })
    },
    simpleNotify(title, text, group) {
			this.$notify({
				group: group || 'standard',
				title: title,
				text: text,
			})
		},
    scrollToId(id) {
      const el = document.getElementById(id)
      setTimeout(() => {
        el.scrollIntoView({behavior: "smooth"})
      }, 250)
    }
  },
  watch: {
    modal(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR')) 
    },
		choosedDate(val) {
			const [year, month, day] = val.split('-')
			this.dateOfBirth = `${day}.${month}.${year}`
    }
  },
  computed: {
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
    expandCompany() {
      return this.companyType !== null 
        ? 'complete-reg-form__expand --expand-active' 
        : 'complete-reg-form__expand'
    },
    expandPassport() {
      return this.passportType !== null 
        ? 'complete-reg-form__expand --expand-active mt-2' 
        : 'complete-reg-form__expand'
    },
    getPassportCode() {
      return this.passportType === 1 ? this.passportSeries : this.unzr.replace(/[^\d]/g, '')
    },
    getPassportNumber() {
      return this.passportType === 1 ? this.passportNumber : this.bioPassportNumber
    },
    lastNameErrors() {
			const errors = []
			if (!this.$v.lastName.$error) return errors
			!this.$v.lastName.required && errors.push('Поле "Прізвище" - обов\'язкове для заповнення (Кириличні літери)')
			return errors
    },
    patronymicErrors() {
			const errors = []
			if (!this.$v.patronymic.$error) return errors
			!this.$v.patronymic.required && errors.push('Поле "По батькові" - обов\'язкове для заповнення (Кириличні літери)')
			return errors
    },
    companyNameErrors() {
			const errors = []
			if (!this.$v.companyName.$error) return errors
			!this.$v.companyName.required && errors.push('Поле "Назва компанії" - обов\'язкове для заповнення')
			return errors
    },
    positionErrors() {
			const errors = []
			if (!this.$v.position.$error) return errors
			!this.$v.position.required && errors.push('Поле "Посада" - обов\'язкове для заповнення')
			return errors
    },
    passportSeriesErrors() {
			const errors = []
      if (!this.$v.passportSeries.$error) return errors
      !this.$v.passportSeries.minLength && errors.push(`Поле "Серія паспорта" - має складатися з двох букв`)
			!this.$v.passportSeries.required && errors.push('Поле "Серія паспорта" - обов\'язкове для заповнення (Двi кириличні лiтери)')
			return errors
    },
    passportNumberErrors() {
			const errors = []
      if (!this.$v.passportNumber.$error) return errors
      !this.$v.passportNumber.minLength && errors.push(`Поле "Номер паспорта" - має складатися з 6 цифр`)
			!this.$v.passportNumber.required && errors.push('Поле "Номер паспорта" - обов\'язкове для заповнення (Цифри)')
			return errors
    },
    unzrErrors() {
			const errors = []
      if (!this.$v.unzr.$error) return errors
      !this.$v.unzr.minLength && errors.push(`Поле "унікальний номер запису у реєстрі" - має складатися з 13 цифр`)
			!this.$v.unzr.required && errors.push('Поле "унікальний номер запису у реєстрі" - обов\'язкове для заповнення (Цифри)')
			return errors
    },
    innErrors() {
			const errors = []
      if (!this.$v.inn.$error) return errors
      !this.$v.inn.minLength && errors.push(`Поле "Iдентифікаційний код" - має складатися з 10 цифр`)
			!this.$v.inn.required && errors.push('Поле "Iдентифікаційний код" - обов\'язкове для заповнення (Цифри)')
			return errors
    },
    cardNumberErrors() {
			const errors = []
      if (!this.$v.cardNumber.$error) return errors
      !this.$v.cardNumber.minLength && errors.push(`Поле "Карта для виплат" - має складатися з 12 цифр`)
      !this.$v.cardNumber.required && errors.push('Поле "Карта для виплат" - обов\'язкове для заповнення (Цифри)')
      !this.$v.cardNumber.lunValid && errors.push('Не вiрна картка')
			return errors
    },
    bioPassportNumberErrors() {
      const errors = []
      if (!this.$v.bioPassportNumber.$error) return errors
      !this.$v.bioPassportNumber.minLength && errors.push(`Поле "Номер документа" - має складатися з 9 цифр`)
      !this.$v.bioPassportNumber.required && errors.push('Поле "Номер документа" - обов\'язкове для заповнення (Цифри)')
			return errors
    },
    firstPageValid() {
      let check = false
      !this.$v.lastName.$invalid
      && !this.$v.patronymic.$invalid
      && !this.$v.companyName.$invalid
      && !this.$v.position.$invalid
        ? check = true
        : false
      return check
    },
    companyTypeErrors() {
      const errors = []
      if (!this.$v.companyType.$error) return errors
      !this.$v.companyType.checked && errors.push(`Оберiть мiсце роботи`)
			return errors
    },
    passportTypeErrors() {
      const errors = []
      if (!this.$v.passportType.$error) return errors
      !this.$v.passportType.checked && errors.push(`Оберiть тип паспорту`)
			return errors
    },
    passport() {
      let check = false
      !this.$v.lastName.$invalid
      && !this.$v.patronymic.$invalid
      && !this.$v.companyName.$invalid
      && !this.$v.companyType.$invalid
      && !this.$v.position.$invalid
      && !this.$v.passportType.$invalid
      && !this.$v.passportSeries.$invalid
      && !this.dateOfBirth != null
      && !this.$v.passportNumber.$invalid
      && !this.$v.inn.$invalid
      && !this.$v.cardNumber.$invalid
        ? check = true
        : false
      return check
    },
    bioPassport() {
      let check = false
      !this.$v.lastName.$invalid
      && !this.$v.patronymic.$invalid
      && !this.$v.companyName.$invalid
      && !this.$v.companyType.$invalid
      && !this.$v.position.$invalid
      && !this.$v.passportType.$invalid
      && !this.$v.unzr.$invalid
      && !this.dateOfBirth != null
      && !this.$v.inn.$invalid
      && !this.$v.cardNumber.$invalid
        ? check = true
        : false
      return check
    },
    dataValid() {
      let check = false
      this.passport || this.bioPassport
        ? check = true
        : false
      return check
    },
    showBtnNextPage() {
      return this.firstPageValid 
        ? 'complete-reg-form__btn btn-expanded--active'
        : 'complete-reg-form__btn'
    },
  },
  created() {
    /* Authorize the user (if not authorized) before filling out the form */
    this.getCurrentUser()
  },
}
</script>
<style scoped>
  .app {
    background: #fafafa;
  }
</style>

<style>
  .form-card {
    text-align: left;
  }
  .complete-reg-form__title {
    padding: 15px 35px;
  }
  .input__text .v-input {
    padding: 0 35px!important;
  }
  .form-card .v-card__title {
    word-break: keep-all;
  }
  .form-card .complete-reg-form__radio {
    margin: 0;
    min-width: 60%;
    display: flex;
    justify-content: center;
  }
  .complete-reg-form-sub-title {
    display: inline-block;
    padding: 0 0 0 35px;
  }
  .complete-reg-form__radio .v-label {
    font-size: 1.2rem;
    margin: 0;
  }
  .complete-reg-form__expand {
    padding-top: 8px;
    transition: height 0.25s ease-in;
    max-height: 0;
    overflow: hidden;
  }
  .--expand-active {
    max-height: 500px;
    transition: max-height 0.25s ease-in;
  }
  .form-card .complete-reg-form__radio {
    display: flex;
    justify-content: flex-end;
  }
  .password-wrapper {
     padding: 0 35px 10px 35px;
     display: flex;
     justify-content: space-between;
  }
  .passport-serries {
    max-width: 38%!important;
  }
  .passport-number {
    max-width: 59%!important;
  }
  .complete-reg-form__btn {
    transition: max-height 0.25s ease-in;
    display: flex;
    position: relative;
    justify-content: center;
    max-height: 0;
    overflow: hidden;
  }
  .btn-expanded--active {
    transition: max-height 0.25s ease-in;
    max-height: 150px;
    padding: 0 0 18px 0;
  }
  #crf-last-name, #crf-patronymic {
    text-transform: capitalize;
  }
  .complete-reg-form__title-logo {
    background: url('../../assets/images/best-leasing-logo.png');
    background-size: 160px 45px;
    width: 160px;
    height: 45px;
    margin: 10px 0;
  }
</style>