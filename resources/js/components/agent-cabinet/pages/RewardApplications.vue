<template>
 <div class="row">
  <v-dialog 
    v-model="commissionRequestsDialog"
    max-width="390">
    <v-card>
      <v-card-title class="grey darken-3 white--text">
        <v-icon large class="mr-4" color="white" v-text="'mdi-information'"></v-icon>
          Заявка на винагороду
      </v-card-title>
      <v-card-text class="mt-8 pb-0">
        <v-select
          :error-messages="leasingRequestIdErr"
          v-model="object.leasingRequestId"
          :items="[7]"
          label="Оберiть ID заявки"
          dense outlined>
        </v-select>
        <v-text-field
          v-model="object.purposeOfPayment"
          label="Цiль платежу"
          dense outlined>
        </v-text-field>
      </v-card-text>
      <div class="d-flex justify-center pb-4">
        <span>
          <v-btn class="error" 
            @click="submit()"
            :loading="loading"
            dark>Пiдтвердити
          </v-btn>
        </span>
      </div>
    </v-card>
  </v-dialog>
  <div class="col-md-12 pt-0">
    <v-card style="min-height: 350px;">
      <v-card-title class="d-block grey darken-3 white--text">
        <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-clipboard-check'"></v-icon>
          Заявки на вынагороду
        <v-progress-linear
          :height="3"
          :active="loading"
          :indeterminate="loading"
          absolute
          top
          color="red lighten-1">
        </v-progress-linear>
      </v-card-title>
      <v-card-text class="mt-9">
        <!-- <v-btn 
          dark class="red lighten-1"
          @click.stop="commissionRequestsDialog = !commissionRequestsDialog">
          Подати нову заявку&nbsp;
          <v-icon v-text="'mdi-plus'"></v-icon>
        </v-btn> -->
        <v-card-title
          v-if="agentCommisions.length > 0 && !$store.state.adminLoader"
          class="d-flex justify-center headline black--text mt-3 mb-6">
          Iсторiя заявок на винагороду
        </v-card-title>
        <v-card-text 
          v-if="agentCommisions.length === 0 && !$store.state.adminLoader"
          class="d-flex justify-center"
          style="font-size: 1.3rem; padding-top: 85px;">
          (Iсторiя заявок на винагороду порожня)
        </v-card-text>
      </v-card-text>
      <v-data-table
        v-if="agentCommisions.length > 0 && !$store.state.adminLoader"
        color="black"
        :headers="tableHeader"
        :custom-sort="customSort"
        :items="agentCommisions"
        :hide-default-footer="true"
        class="elevation-1 mr-3 ml-3 mt-4 reward-aplication-table">
        <template v-slot:item.leasing_request.created_at="{ item }">
          <div class="text-center">
            {{ item.created_at.substr(0, 10) }}
          </div>
        </template>
        <template v-slot:item.status="{ item }">
          <v-chip style=" text-align: center;" small :color="item.status === 'paid'? 'success' : 'error'" dark>{{ switchStatus(item.status) }}</v-chip>
        </template>
      </v-data-table>
    </v-card>
  </div>
 </div>
</template>
<script>
import axios from 'axios'

import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  data: () => ({
    loading: false,
    commissionRequestsDialog: false,
    agentCommisions: [],
    object: {
      leasingRequestId: null,
      purposeOfPayment: null,
      agentId: null,
      _token: null
    },
    tableHeader: [
      { text: 'Код заявки', value: 'id'},
      { text: 'Им`я', value: 'leasing_request.first_name', align: 'start'},
      // { text: 'Марка', value: 'leasing_request.leasing_object', align: 'center'},
      { text: 'Авто', value: 'leasing_request.leasing_object', align: 'center' },
      // { text: 'Призначення платежу', value: 'purpose_of_payment', align: 'center' },
      { text: 'Дата заявки', value: 'leasing_request.created_at', align: 'center', sortable: true },
      { text: 'Статус', value: 'status', align: 'center' },
    ],
  }),
  validations: {
    object: {
      leasingRequestId: { required }
    }
  },
  computed: {
    hasAgent() {
      if(!this.$store.state.user.agent) return false
      else return Object.keys(this.$store.state.user).length > 0
    },
    leasingRequestIdErr() {
      if (!this.$v.object.leasingRequestId.$error) return
      return ['Обов`язкове поле']
    }
  },
  methods: {
    switchStatus(status) {
      if(status === 'not_paid') return 'Не оплачено'
      if(status === 'paid') return 'Оплачено'
    },
    customSort(items) {
      console.log(items)
      items
        .sort((a, b) => {
          console.log(a.created_at, b.created_at)
          return new Date(b.updated_at) - new Date(a.updated_at)
      })
      return items
    },
    getCommisionRequests() {
      if(this.$store.state.user.agent){
        this.$store.commit('toggleAdminSpinner', true)
        let id = this.$store.state.user.agent.id
        this.object.agentId = id
        this.object._token = this.getCsrf()
        axios
          .get(`/agent-commission/agent/${id}`)
          .then(response => {
            this.agentCommisions = response.data
            this.object.agentId = id
            this.object._token = this.getCsrf()
            this.$store.commit('toggleAdminSpinner', false)
            // this.agentCommisions = response.data
            console.log('commision request')
            console.log(response)
            console.log('commision request')
          })
          .catch(error => {
            this.$store.commit('toggleAdminSpinner', false)
            console.log(error.response)
          })
      }
    },
    getCsrf() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    submit() {
      this.$v.$dirty
      && !this.$v.error
        ? this.highLightErrors()
        : this.sendRequest()
    },
    highLightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
    clearOptionalField() {
      if(this.object.purposeOfPayment === null) {
        delete this.object.purposeOfPayment
      }
    },
    sendRequest() {
      this.clearOptionalField()
      console.log(this.object)
      this.loading = true
      axios
        .post('/agent-commission/create', this.object)
        .then(response => {
          console.log(response)
          this.$notify({
            group: 'success',
            title: 'Успiшно',
            text: ``,
          })
          this.loading = false
          setTimeout(() => {
            this.commissionRequestsDialog = false
          }, 800)
        })
        .catch(error => {
          console.log(error.response)
          this.loading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
        })
    }
  },
  watch: {
    hasAgent(val) {
      if(val && this.agentCommisions.length === 0) {
        this.getCommisionRequests()
      }
    }
  },
  mounted() {
    this.getCommisionRequests()
  },
}
</script>

<style lang="scss">
.v-chip {
  min-width: 95px;
  span {
    margin: 0 auto;
  }
}
</style>
