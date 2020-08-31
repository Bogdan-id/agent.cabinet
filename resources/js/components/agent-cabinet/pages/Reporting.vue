<template>
  <v-card class="chart-card" style="min-height: 200px; position: relative" elevation="8">
    <v-card-title>
      <v-select
        :items="[
          {text: 'Звіт заявок на лізинг', value: 'leasingRequestReport'},
          {text: 'Звiт заявок та виплат', value: 'RequestsAndPaymentsReports'},
        ]"
        @change="getReport()"
        item-text="text"
        item-value="value"
        style="max-width: 280px;"
        v-model="currentReport"
        color="#e65048"
        item-color="#e65048"
        :hide-details="true"
        dense>
      </v-select>
      <v-spacer></v-spacer>
      <v-select
        @change="callGetLReq()"
        style="max-width: 170px;"
        v-model="currentYear"
        :items="years"
        label="Рiк"
        color="#e65048"
        item-color="#e65048"
        :hide-details="true"
        dense>
      </v-select>
    </v-card-title>
    <component 
      v-if="currentYear"
      ref="chart"
      :currentYear="currentYear"
      :cnvsW="cnvsW"
      :cnvsH="cnvsH"
      :months="months"
      :is="currentReport">
    </component>
    <section class="chart-navigation">
      
    </section>
  </v-card>
</template>

<script>
import leasingRequestReport from './LeasingRequestReport'
import RequestsAndPaymentsReports from './RequestsAndPaymentsReports'

import axios from 'axios'

export default {
  data: () => ({
    cnvsW: 280,
    cnvsH: 280,

    months: [
      {id: '01', name: 'Січень'},
      {id: '02', name: 'Лютий'},
      {id: '03', name: 'Березень'},
      {id: '04', name: 'Квітень'},
      {id: '05', name: 'Травень'},
      {id: '06', name: 'Червень'},
      {id: '07', name: 'Липень'},
      {id: '08', name: 'Серпень'},
      {id: '09', name: 'Вересень'},
      {id: '10', name: 'Жовтень'},
      {id: '11', name: 'Листопад'},
      {id: '12', name: 'Грудень'},
    ],

    currentReport: 'leasingRequestReport',
    currentYear: null,
    years: []
  }),

  components: {
    RequestsAndPaymentsReports,
    leasingRequestReport
  },

  methods: {
    getAvailableYears(url) {
      axios
        .get(url)
        .then(res => {
          this.getCurrentYear(res)
        })
        .catch(err => {
          console.log(err.response)
        })
    },

    getReport() {
      let url = null

      if (this.currentReport === 'leasingRequestReport') {
        url = `/reports/years/leasing-requests/${this.$store.state.user.agent.id}`
        this.getAvailableYears(url)

      } else if(this.currentReport === 'RequestsAndPaymentsReports') {
        url = `/reports/years/agent-commissions/${this.$store.state.user.agent.id}`
        this.getAvailableYears(url)
      }
    },

    getCurrentYear(res) {
      this.years = res.data.sort((a, b) => a - b)

      console.log({years: this.years})

      this.currentYear = this.years[0]
      console.log({currentYear: this.currentYear})

      this.callGetLReq()
    },

    callGetLReq() {
      this.$nextTick(() => {
        this.$refs.chart.getLeasingRequests()
      })
    }
  },

  mounted() {
    this.getReport()
  }
}

</script>
