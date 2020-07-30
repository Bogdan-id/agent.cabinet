<template>
<v-card>
  <v-dialog
    max-width="390"
    v-model="agentComissionDialog">
    <v-card>
      <v-card>
        <v-card-title style="background: #424242; position: relative" class="white--text">
          <v-btn @click="agentComissionDialog = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon color="white" v-text="'mdi-close'"></v-icon></v-btn>
          Пiдтвердження оплати
        </v-card-title>
        <v-card-text style="margin-top: 25px;">
          <v-text-field
            v-model="cardNumber"
            label="Картка"
            v-mask="'#### #### #### ####'" 
            readonly
            dense>
          </v-text-field>
          <v-text-field
            v-model="iban" 
            label="IBAN"
            readonly 
            dense>
          </v-text-field>
          <v-text-field
            v-model="amount" 
            label="Сума"
            readonly 
            dense>
          </v-text-field>
        </v-card-text>
        <v-card-text class="d-flex justify-space-between pt-0">
          <v-btn :loading="loading" dark color="#d24a43" @click="sendToPay()">Сплатити</v-btn>
          <v-btn dark color="#424242" @click="clearForm()">Вiдмiнити</v-btn>
        </v-card-text>
      </v-card>
    </v-card>
  </v-dialog>
  <v-card-title>
    Роздiл - Заявки на винагороду
  </v-card-title>
  <v-card-text v-if="$store.state.adminLoader">
    <v-progress-circular
      color="red"
      indeterminate>
    </v-progress-circular>
  </v-card-text>
  <v-card-title>
    <v-select
      v-model="filteredStatus"
      @change="filterCommisitonsByPaid($event)"
      label="Сортувати"
      :items="[
        {text: 'Оплаченi', value: 'paid'},
        {text: 'Не оплаченi', value: 'not_paid'},
        {text: 'Всi', value: 'all'},
      ]"
      item-text="text"
      item-value="value"
      dense outlined>
    </v-select>
    <v-spacer></v-spacer>
    <v-text-field
      color="black"
      v-model="search"
      append-icon="mdi-magnify"
      label="Пошук по датi або cумi"
      dense
      outlined>
    </v-text-field>
  </v-card-title>
  <v-card-text>
    <v-data-table
      :search="search"
      v-if="agentComissions !== null && agentComissions.length > 0"
      :headers="tableHeader"
      :items="filteredAgentComissions">
      <template v-slot:item.initials="{ item }">
        <span>{{ item.agent.last_name + ' ' + item.agent.first_name[0] + '. ' + item.agent.patronymic[0] + '. '}}</span>
      </template>
      <template v-slot:item.amountSum="{ item }">
        <span>{{ (parseInt(item.leasing_request.leasing_amount.replace(/[^\d]/g, ''))  / 100) * parseInt(item.agent.ab_size) }}</span>
      </template>
      <template v-slot:item.created_at="{ item }">
        <span>{{ item.created_at.substr(0, 10) }}</span>
      </template>
      <template v-slot:item.status="{ item }">
        <v-chip :color="item.status === 'paid' ? 'success' : 'error'" small>{{ switchStatus(item.status) }}</v-chip>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-btn class="paid-btn" :disabled="item.status === 'paid'" @click="openAgentCommisitonDialog(item)" style="text-transform: lowercase" small :dark="item.status !== 'paid'" color="#d24a43">Сплатити</v-btn>
      </template>
    </v-data-table>
  </v-card-text>
</v-card>
</template>

<script>
import axios from 'axios'

export default {
  data: () => ({
    cardNumber: null,
    iban: null,
    amount: null,
    id: null,
    status: null,
    search: '',

    tableHeader: [
      { text: 'Iм`я', value: 'initials', align: 'start', sortable: false},
      { text: 'Сума, грн', value: 'amountSum', align: 'center', sortable: false},
      { text: 'Дата', value: 'created_at', align: 'center' },
      { text: 'Статус', value: 'status', align: 'center', sortable: false },
      { text: 'Сплатити', value: 'actions', align: 'center', sortable: false },
    ],

    filteredStatus: null,
    filteredAgentComissions: null,

    agentComissions: null,
    agentComissionDialog: false,
    loading: false
  }),
  methods: {
    filterCommisitonsByPaid(event) {
      this.filteredAgentComissions = this.agentComissions
        .filter(value => {
          if(event !== 'all'){
            return value.status === event
          } else {
            return this.agentComissions
          }
        })
    },
    sendToPay() {
      if(this.status != 'paid') {
        this.loading = true
        this.sendRequest()
      }
    },
    sendRequest() {
      axios
        .get(`/admin/agent-commission/paid/${this.id}`)
        .then(() => {
          this.$notify({
            group: 'success',
            title: 'Заявку оплачено!',
            text: '',
          })
          this.loading = false
          this.agentComissionDialog = false
          this.getAgentComission()
        })
        .catch(error => {
          console.log(error.response)
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
          this.loading = false
          this.getAgentComission()
        })
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    clearForm() {
      this.agentComissionDialog = false
      this.cardNumber = null
      this.iban = null
      this.amount = null
      this.id = null
      this.status = null
    },
    openAgentCommisitonDialog(itemObject) {
      this.agentComissionDialog = true
      this.cardNumber = itemObject.agent.card_number
      this.iban = itemObject.agent.iban
      this.amount = (parseInt(itemObject.leasing_request.leasing_amount.replace(/[^\d]/g, ''))  / 100) * parseInt(itemObject.agent.ab_size)
      this.id = itemObject.id
      this.status = itemObject.status
    },
    getAgentComission() {
      axios
        .get('/admin/agent-commission/all')
        .then(response => {
          this.agentComissions = response.data
          this.filteredAgentComissions = response.data
        })
        .catch(error => {
          console.log(error.response)
        })
    },
    switchStatus(status) {
      if(status === 'paid') return 'Оплачено'
      if(status === 'not_paid') return 'Не оплачено'
    }
  },
  created() {
    this.getAgentComission()
  }
}
</script>

<style lang="scss">
.paid-btn {
  &.v-btn--disabled {
    background: #cbcbcb!important;
  }
}
</style>