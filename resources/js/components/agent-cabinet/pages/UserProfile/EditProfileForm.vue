<template>
  <v-card class="edit-profile-card">
    <v-card-title>
      Профiль
    </v-card-title>
    <v-card-text>
    <div>
      <form @submit.prevent>
        <!--  -->
        <div class="row">
          <div class="col-md-4">
            <fg-input type="text"
                label="Прізвище"
                :placeholder="agent.last_name">
            </fg-input>
          </div>
          <div class="col-md-4">
            <fg-input type="text"
                label="Ім'я"
                :placeholder="agent.first_name">
            </fg-input>
          </div>
          <div class="col-md-4">
            <fg-input type="text"
                label="По батькові"
                :placeholder="agent.patronymic">
            </fg-input>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-6">
            <fg-input type="text"
                label="Телефон"
                :placeholder="user.phone">
            </fg-input>
          </div>
          <div class="col-md-6">
            <fg-input type="email"
                label="Email"
                :placeholder="user.email">
            </fg-input>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <fg-input type="text"
                label="Місце роботи"
                :placeholder="agent.company_type">
            </fg-input>
          </div>
          <div class="col-md-3">
            <fg-input type="text"
                label="Статус"
                :placeholder="agent.status || ''">
            </fg-input>
          </div>
          <div class="col-md-3">
            <fg-input type="text"
                label="Посада"
                :placeholder="agent.position">
            </fg-input>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-3">
            <fg-input type="text"
                label="Тип паспорту"
                :placeholder="passportType">
            </fg-input>
          </div>
          <div class="col-md-3">
            <fg-input v-show="!bioPassport" type="text"
                label="Серiя паспорту"
                placeholder="Серія паспорту">
            </fg-input>
            <fg-input v-show="bioPassport" type="number"
                label="Номер УНЗР"
                placeholder="номер УНЗР">
            </fg-input>
          </div>
          <div class="col-md-3">
            <fg-input v-show="!bioPassport" type="number"
                label="Номер паспорту"
                placeholder="номер паспорту">
            </fg-input>
            <fg-input v-show="bioPassport" type="number"
                label="Номер документа"
                placeholder="номер документа">
            </fg-input>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-12">
            <fg-input type="number"
                label="Ідентифікаційний код"
                :placeholder="agent.inn">
            </fg-input>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-6">
            <fg-input type="text"
                label="Дата народження"
                :placeholder="agent.birth">
            </fg-input>
          </div>
          <div class="col-md-6">
            <fg-input type="text"
                label="Реквiзити картки"
                :placeholder="agent.card_number">
            </fg-input>
          </div>
        </div>
        <!--  -->
        <div class="row">
          <div class="col-md-6">
            <fg-input type="text"
                label="Розмiр АВ"
                placeholder="">
            </fg-input>
          </div>
          <div class="col-md-6">
            <fg-input type="text"
                label="Куратор"
                placeholder="">
            </fg-input>
          </div>
        </div>
        <div class="text-center">
          <p-button type="info"
              round
              @click.native.prevent="updateProfile">
              Оновити профіль
          </p-button>
        </div>
        <div class="clearfix"></div>
      </form>
    </div>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  props: ['user', 'agent'],
  methods: {
    updateProfile() {
      /* */
    },
  },
  computed: {
    passportType() {
      return this.agent.passport_type_id === 1
        ? "Книжкового зразка" 
        : "Бiо-паспорт"
    },
    bioPassport() {
      return this.agent.passport_type_id === 2
    }
  }
}
</script>
<style lang="scss">
  .edit-profile-card {
    ::-webkit-input-placeholder 
    { 
      color: black!important;
    }
    ::-moz-placeholder {
      color: black!important;
    }
    :-ms-input-placeholder {
      color: black!important;
    }
    :-moz-placeholder {
      color: black!important;
    }
  }
  .edit-profile-card {
    .form-control {
      border-bottom: 1px solid #7b7b7b;
      border-radius: 0;
    }
  }
  .edit-profile-card {
    .btn {
      text-transform: capitalize;
    }
  }
</style>
