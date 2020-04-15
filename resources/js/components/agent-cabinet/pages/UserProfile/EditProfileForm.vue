<template>
  <v-card class="edit-profile">
    <v-card-title class="headline">
      Профiль
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
                >
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.first_name"
                label="Ім'я"
                :placeholder="user.first_name">
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.patronymic"
                label="По батькові"
                :placeholder="user.patronymic">
            </v-text-field>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-text-field
                v-model="user.phone"
                label="Телефон"
                :placeholder="user.phone">
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.email"
                label="Email"
                :placeholder="user.email">
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
                  dense readonly>
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
                :items="companyTypes">
            </v-select>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.satus"
                label="Статус"
                :placeholder="user.status || 'Статус'">
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.position"
                label="Посада"
                :placeholder="user.position">
            </v-text-field>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-select
                v-model="user.passport_type_id"
                append-icon=""
                label="Тип паспорту"
                :placeholder="showPassportType"
                :items="pasportItems">
            </v-select>
          </div>
          <div class="col-md-3">
            <v-text-field v-if="!bioPassport"
                v-model="user.serie"
                label="Серiя паспорту"
                placeholder="Серія паспорту">
            </v-text-field>
            <v-text-field v-if="bioPassport"
                label="Номер УНЗР"
                placeholder="номер УНЗР"
                v-model="user.unzr_number">
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field v-if="!bioPassport"
                v-model="user.passport_number"
                label="Номер паспорту"
                placeholder="номер паспорту">
            </v-text-field>
            <v-text-field v-if="bioPassport"
                v-model="user.id_card_number"
                label="Номер документа"
                placeholder="номер документа">
            </v-text-field>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-text-field
                v-model="user.inn"
                label="Ідентифікаційний код"
                :placeholder="user.inn">
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.card_number"
                label="Реквiзити картки"
                :placeholder="user.card_number">
            </v-text-field>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <v-text-field
                v-model="user.ab_size"
                label="Розмiр АВ"
                placeholder="Розмiр АВ">
            </v-text-field>
          </div>
          <div class="col-md-3">
            <v-text-field
                v-model="user.manager_id"
                label="Куратор"
                placeholder="Куратор">
            </v-text-field>
          </div>
        </div>
        <div class="text-center">
          <v-btn
              dark
              color="grey darken-1"
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
    user: {
      id: null,
      name: null,
      email: null,
      phone: null,
      is_active: 1,
      user_id: 5,
      first_name: null,
      last_name: null,
      patronymic: null,
      company_type: null,
      company_name: null,
      position: null,
      status: null,
      passport_type_id: null,
      inn: null,
      birth: null,
      card_number: null,
      ab_size: null,
      manager_id: null,
      agent_id: null,
      unzr_number: null,
      id_card_number: null
    },
    /* temp data */
    choosedDate: null,

    /* item v-model */
    passportType: null,

    /* item types */
    pasportItems: [
      { text: 'Био-паспорт', value: '2' },
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
        : "Бiо-паспорт"
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
    }
  },
  created() {
    console.log(this.$store.state.user)
    this.user = this.$store.state.user
  },
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
