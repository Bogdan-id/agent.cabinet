<template>
  <v-card class="edit-profile">
    <v-card-title class="d-block grey darken-3 white--text">
      <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-account'"></v-icon>
      Профiль
      <v-divider></v-divider>
    </v-card-title>
    <v-card-text>
    <div>
      <form @submit.prevent>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-text-field
                v-model="user.last_name"
                label="Прізвище"
                :placeholder="user.last_name"
                dense>
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.first_name"
                label="Ім'я"
                :placeholder="user.first_name"
                dense>
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.patronymic"
                label="По батькові"
                :placeholder="user.patronymic"
                dense>
            </v-text-field>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-text-field
                v-model="user.phone"
                label="Телефон"
                :placeholder="user.phone"
                outlined dense>
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.email"
                label="Email"
                :placeholder="user.email"
                outlined dense>
            </v-text-field>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-dialog
                v-model="modal"
                ref="modal"
                width="290px">
            <template v-slot:activator="{ on }">
              <v-text-field
                  v-model="user.birth"
                  v-on="on"
                  label="Дата народження"
                  :placeholder="user.birth"
                  prepend-inner-icon="mdi-calendar-range"
                  dense readonly
                  outlined>
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
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-select
                v-model="user.company_type"
                append-icon=""
                label="Місце роботи"
                :placeholder="companyType"
                :items="companyTypes"
                outlined dense>
            </v-select>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.satus"
                label="Статус"
                :placeholder="user.status || 'Статус'"
                outlined dense disabled>
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.position"
                label="Посада"
                prepend-inner-icon="mdi-briefcase"
                :placeholder="user.position"
                outlined dense>
            </v-text-field>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-text-field
                :value="showPassportType"
                append-icon=""
                :label="showPassportType"
                :items="pasportItems"
                outlined dense readonly>
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field v-if="!bioPassport"
                v-model="user.serie"
                label="Серiя паспорту"
                placeholder="Серія паспорту"
                outlined dense readonly>
            </v-text-field>
            <v-text-field v-if="bioPassport"
                label="Номер УНЗР"
                placeholder="номер УНЗР"
                v-model="user.unzr_number"
                outlined dense readonly>
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field v-if="!bioPassport"
                v-model="user.passport_number"
                label="Номер паспорту"
                placeholder="номер паспорту"
                outlined dense readonly>
            </v-text-field>
            <v-text-field v-if="bioPassport"
                v-model="user.id_card_number"
                label="Номер документа"
                placeholder="номер документа"
                outlined dense readonly>
            </v-text-field>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-text-field
                v-model="user.inn"
                label="Ідентифікаційний код"
                :placeholder="user.inn"
                outlined dense>
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.card_number"
                label="Реквiзити картки"
                :placeholder="user.card_number"
                outlined dense>
            </v-text-field>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-text-field
                v-model="user.ab_size"
                label="Розмiр АВ"
                placeholder="Розмiр АВ"
                outlined dense disabled>
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.manager_id"
                label="Куратор"
                placeholder="Куратор"
                outlined dense disabled>
            </v-text-field>
          </div>
        </div>
        <div class="text-center">
          <v-btn
              dark
              color="red darken-1"
              @click.native.prevent="updateProfile">
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
export default {
  data: () => ({
    user: {},
    /* temp data */
    choosedDate: null,

    /* item v-model */
    passportType: null,

    /* item types */
    pasportItems: [
      { text: 'ID-картка', value: '2' },
      { text: 'Книжкового зразка', value: '1' }
    ],
    companyTypes: [
      { text: 'Салон', value: 'salon' },
      { text: 'Дилер', value: 'dealer' }
    ],

    /* modals */
    modal: false,
  }),
  methods: {
    updateProfile() {
      /* */
    },
    save (date) {
      this.$refs.modal.save(date)
    },
  },
  computed: {
    showPassportType() {
      return parseInt(this.user.passport_type_id) === 1
        ? "Книжкового зразка" 
        : "ID-картка"
    },
    userEditPassword() {
      return this.passportType !== null
    },
    bioPassport() {
      return parseInt(this.user.passport_type_id) === 2
    },
    companyType() {
      return this.user.company_type === 'salon'
        ? 'Салон'
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
    modal(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR')) 
    },
		choosedDate(val) {
			const [year, month, day] = val.split('-')
			this.user.birth = `${day}.${month}.${year}`
    },
    showPassportType(val) {
      this.user.passport_type_id = val
    }
  },
  created() {
    Object.assign(this.user, 
      this.$store.state.user.agent,
      this.$store.state.user.user,
      this.$store.state.user.agent.document
    ) 
    console.log(this.user)
  },
  mounted() {
    this.user.passport_type_id = this.showPassportType
  }
}
</script>
<style lang="scss">
  // .edit-profile {
  //   ::-webkit-input-placeholder 
  //   { 
  //     color: black!important;
  //   }
  //   ::-moz-placeholder {
  //     color: black!important;
  //   }
  //   :-ms-input-placeholder {
  //     color: black!important;
  //   }
  //   :-moz-placeholder {
  //     color: black!important;
  //   }
  // }
  .edit-profile {
    .v-text-field input {
      padding-left: 10px!important;
    }
    // .btn {
    //   text-transform: capitalize;
    // }
    .form-control {
      border-bottom: 1px solid #a8a6a6;
      border-radius: 0;
    }
    .v-text-field__slot .v-label--active, .v-label {
      font-size: 20px!important;
    }
  }
</style>
