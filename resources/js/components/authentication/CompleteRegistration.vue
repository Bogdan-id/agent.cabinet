<template>
  <div class="app">
    <v-content>
      <v-container
        class="fill-height"
        fluid>
        <v-row
          align="center"
          justify="center">
          <v-col cols="12" sm="7" md="5" lg="4" xl="3">
            <v-card class="elevation-9 form-card" flat>
              <v-card-title
                class="headline font-weight-regular indigo  white--text mb-8"
                >Заповніть особові дані
              </v-card-title>
              <div v-show="!nextPage">
                <div class="input__text">
                  <v-text-field 
                      :error-messages="lastNameErrors" 
                      @blur="$v.lastName.$touch()" 
                      @input="$v.lastName.$touch()" 
                      label="Прізвище" 
                      v-model="lastName" 
                      outlined dense clearable>
                  </v-text-field>
                  <v-text-field 
                      :error-messages="firstNameErrors" 
                      @blur="$v.firstName.$touch()" 
                      @input="$v.firstName.$touch()" 
                      label="Ім'я" 
                      v-model="firstName" 
                      outlined dense clearable>
                  </v-text-field>
                  <v-text-field 
                      :error-messages="patronymicErrors" 
                      @blur="$v.patronymic.$touch()" 
                      @input="$v.patronymic.$touch()" 
                      label="По батькові" 
                      v-model="patronymic" 
                      outlined dense clearable>
                  </v-text-field>
                  <v-text-field 
                      prepend-inner-icon="mdi-cellphone-android" 
                      :placeholder="number" 
                      outlined dense readonly>
                  </v-text-field>
                  <v-text-field 
                      prepend-inner-icon="mdi-at" 
                      :placeholder="email" 
                      outlined dense readonly>
                  </v-text-field>
                </div>
                <div class="d-flex justify-space-between">
                  <span class="subtitle-1 font-weight-bold complete-reg-form-sub-title">Місце роботи:</span>
                  <v-radio-group dense class="complete-reg-form__radio" v-model="companyType">
                    <v-radio label="Салон" color="primary" value="salon"></v-radio>
                    <v-radio label="Дилер" color="primary" value="dealer"></v-radio>
                  </v-radio-group>
                </div>
                <div :class="expandCompany">
                  <div class="input__text">
                    <v-text-field 
                        :error-messages="companyNameErrors" 
                        @blur="$v.companyName.$touch()" 
                        @input="$v.companyName.$touch()" 
                        label="Назва компанії" 
                        v-model="companyName" 
                        outlined dense clearable>
                    </v-text-field>
                    <v-text-field 
                        :error-messages="positionErrors" 
                        @blur="$v.position.$touch()" 
                        @input="$v.position.$touch()" 
                        label="Посада" 
                        v-model="position" 
                        outlined dense clearable>
                    </v-text-field>
                  </div>
                </div>
                <div class="d-flex justify-center pb-6 pt-3">
                  <span>
                    <v-btn @click="nextPage = !nextPage" 
                      class="d-block" 
                      color="primary"
                      :disabled="!firstPageValid">
                      Далi&nbsp;
                      <v-icon v-text="'mdi-arrow-right-bold-circle'"></v-icon>
                    </v-btn>
                  </span>
                </div>
              </div>
              <div v-show="nextPage">
              <div class="d-flex justify-space-between">
                <span class="subtitle-1 font-weight-bold complete-reg-form-sub-title">Тип паспорту:</span>
                <v-radio-group dense class="complete-reg-form__radio" v-model="passportType">
                  <v-radio label="ID - картка" color="primary" value="2"></v-radio>
                  <v-radio  label="Книжковий" color="primary" value="1"></v-radio>
                </v-radio-group>
              </div>
                <div :class="expandPassport">
                  <div class="d-flex password-wrapper">
                    <v-text-field 
                        persistent-hint
                        class="password-serries" 
                        :error-messages="passportSeriesErrors" 
                        @input="lettersToUpperCase(); $v.passportSeries.$touch()" 
                        @blur="$v.passportSeries.$touch()"
                        id="passwordS" 
                        v-show="passportType === '1'" 
                        maxlength="2" counter 
                        hint="Серія паспорта"
                        label="Серія" 
                        v-model="passportSeries" 
                        outlined 
                        dense>
                    </v-text-field>
                    <v-text-field
                        persistent-hint
                        class="password-number"
                        :error-messages="passportNumberErrors" 
                        @blur="$v.passportNumber.$touch()" 
                        @input="$v.passportNumber.$touch()"
                        v-show="passportType === '1'" 
                        v-mask="passportNumberMask"  
                        label="Номер"
                        maxlength="6"
                        hint="Номер паспорта"
                        counter 
                        v-model="passportNumber" 
                        outlined dense>
                    </v-text-field>
                  </div>
                  <div class="input__text">
                    <v-text-field 
                        v-show="passportType === '2'" 
                        v-mask="unzrMask"
                        :error-messages="unzrErrors" 
                        @blur="$v.unzr.$touch()" 
                        @input="$v.unzr.$touch()"
                        prepend-inner-icon="mdi-passport-biometric" 
                        label="УНЗР" 
                        maxlength="14"  
                        v-model="unzr" 
                        outlined dense counter clearable>
                    </v-text-field>
                    <v-text-field 
                        v-mask="innMask" 
                        prepend-inner-icon="mdi-account-outline"
                        :error-messages="innErrors" 
                        @blur="$v.inn.$touch()" 
                        @input="$v.inn.$touch()"
                        label="Ідентифікаційний код" 
                        v-model="inn" 
                        maxlength="10" 
                        outlined dense counter clearable>
                    </v-text-field>
                    <v-dialog
                      v-model="modal"
                      ref="modal"
                      width="290px">
                      <template v-slot:activator="{ on }">
                        <v-text-field
                            dense
                            outlined
                            v-model="dateOfBirth"
                            label="Дата народження"
                            readonly
                            v-on="on"
                            prepend-inner-icon="mdi-calendar-range"
                        ></v-text-field>
                      </template>
                      <v-date-picker 
                          @change="save" 
                          ref="picker" 
                          v-model="choosedDate" 
                          min="1930-04-20" 
                          max="2002-04-10">
                      </v-date-picker>
                    </v-dialog>
                    <v-text-field 
                        prepend-inner-icon="mdi-credit-card-outline" 
                        v-mask="cardNumberMask" 
                        :error-messages="cardNumberErrors" 
                        @blur="$v.cardNumber.$touch()" 
                        @input="$v.cardNumber.$touch()"
                        label="Реквізити карти для виплат" 
                        v-model="cardNumber" 
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
                        class="d-block" 
                        color="primary"
                        >
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
import { validationMixin } from 'vuelidate'
import { required, minLength } from 'vuelidate/lib/validators'
  

export default {
  mixins: [validationMixin],
  data: () => ({
    modal: false,

    /* masks */
    unzrMask: '########-#####',
    passportNumberMask: '######',
    innMask: '##########',
    cardNumberMask: '####-####-####-####',
    
    number: '+38 095 741 92 42',
    email: 'user1234@gmail.com',

    lastName: null,
    firstName: null,
    patronymic: null,
    companyType: null,
    companyName: null,
    position: null,

    /* passport data */
    passportType: null,
    passportSeries: null,
    passportNumber: null,
    unzr: null,

    inn: null,
    choosedDate: null,
    dateOfBirth: null,
    cardNumber: null,

    nextPage: false,
  }),
  validations: {
    lastName: {
      required,
    },
    firstName: {
      required
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
    inn: {
      required,
      minLength: minLength(10)
    },
    cardNumber: {
      required,
      minLength: minLength(19)
    }
  },
  methods: {
    submit() {
      console.log(this.$v)
      console.log(this.lastName + '\n')
      console.log(this.firstName + '\n')
      console.log(this.patronymic + '\n')
      console.log(this.salon + '\n')
      console.log(this.dealer + '\n')
      console.log(this.companyName + '\n')
      console.log(this.position + '\n')
      console.log(this.inn + '\n')
      console.log(this.dateOfBirth + '\n')
      console.log(this.cardNumber + '\n')
      console.log(this.passportType + '\n')
      console.log(this.passportSeries + '\n')
      console.log(this.passportNumber + '\n')
      console.log(this.unzr + '\n')
      console.log(this.choosedDate + '\n')
      console.log('****************************')
      console.log(this.$v)
    },
    lettersToUpperCase() {
      const event = new Event('input', {bubbles: true})
      const el = document.getElementById('passwordS')
      let value = this.passportSeries.toUpperCase().replace(/[^А-Я]/g, '')
      if(el.value !== value) {
        el.value = value
        el.dispatchEvent(event)
      }
    },
    save (date) {
      this.$refs.modal.save(date)
    },
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
    expandCompany() {
      return this.companyType !== null ? 'complete-reg-form__expand --expand-active' : 'complete-reg-form__expand'
    },
    expandPassport() {
      return this.passportType !== null ? 'complete-reg-form__expand --expand-active mt-2' : 'complete-reg-form__expand'
    },
    firstNameErrors() {
			const errors = []
			if (!this.$v.firstName.$error) return errors
			!this.$v.firstName.required && errors.push('Поле "Ім\'я" - обов\'язкове для заповнення')
			return errors
    },
    lastNameErrors() {
			const errors = []
			if (!this.$v.lastName.$error) return errors
			!this.$v.lastName.required && errors.push('Поле "Прізвище" - обов\'язкове для заповнення')
			return errors
    },
    patronymicErrors() {
			const errors = []
			if (!this.$v.patronymic.$error) return errors
			!this.$v.patronymic.required && errors.push('Поле "По батькові" - обов\'язкове для заповнення')
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
			!this.$v.passportSeries.required && errors.push('Поле "Серія паспорта" - обов\'язкове для заповнення')
			return errors
    },
    passportNumberErrors() {
			const errors = []
      if (!this.$v.passportNumber.$error) return errors
      !this.$v.passportNumber.minLength && errors.push(`Поле "Номер паспорта" - має складатися з 6 цифр`)
			!this.$v.passportNumber.required && errors.push('Поле "Номер паспорта" - обов\'язкове для заповнення')
			return errors
    },
    unzrErrors() {
			const errors = []
      if (!this.$v.unzr.$error) return errors
      !this.$v.unzr.minLength && errors.push(`Поле "унікальний номер запису у реєстрі" - має складатися з 13 цифр`)
			!this.$v.unzr.required && errors.push('Поле "унікальний номер запису у реєстрі" - обов\'язкове для заповнення')
			return errors
    },
    innErrors() {
			const errors = []
      if (!this.$v.inn.$error) return errors
      !this.$v.inn.minLength && errors.push(`Поле "Iдентифікаційний код" - має складатися з 10 цифр`)
			!this.$v.inn.required && errors.push('Поле "Iдентифікаційний код" - обов\'язкове для заповнення')
			return errors
    },
    cardNumberErrors() {
			const errors = []
      if (!this.$v.cardNumber.$error) return errors
      !this.$v.cardNumber.minLength && errors.push(`Поле "Карта для виплат" - має складатися з 12 цифр`)
			!this.$v.cardNumber.required && errors.push('Поле "Карта для виплат" - обов\'язкове для заповнення')
			return errors
    },
    firstPageValid() {
      let check = false
      !this.$v.lastName.$invalid
      && !this.$v.firstName.$invalid
      && !this.$v.patronymic.$invalid
      && !this.$v.companyName.$invalid
      && !this.$v.position.$invalid
        ? check = true
        : false
      return check
    },
    dataValid() {
      return !this.$v.$invalid
    }
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
  .password-serries {
    max-width: 38%!important;
  }
  .password-number {
    max-width: 59%!important;
  }
</style>