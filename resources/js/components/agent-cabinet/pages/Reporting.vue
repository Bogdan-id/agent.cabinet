<template>
  <v-card class="chart-card" style="min-height: 350px; position: relative" elevation="8">
    <v-card-title class="pb-1">
      <v-select
        :items="[
          {text: 'Звіт заявок на лізинг', value: 'leasing-request-report'},
          {text: 'Звiт заявок та виплат', value: 'requests-and-payments-report'},
        ]"
        @change="getReport($event)"
        item-text="text"
        item-value="value"
        style="max-width: 280px;"
        v-model="currentReport"
        color="#e65048"
        item-color="#e65048"
        dense>
      </v-select>
      <v-spacer></v-spacer>
      <!-- <v-btn @click="test()">test</v-btn> -->
      <v-select
        @change="callGetLReq()"
        style="max-width: 170px;"
        v-model="currentYear"
        :items="years"
        label="Рiк"
        color="#e65048"
        item-color="#e65048"
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
import RequestsAndPaymentsReport from './RequestsAndPaymentsReport'

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

    currentReport: 'leasing-request-report',
    currentYear: null,
    years: []
  }),

  components: {
    RequestsAndPaymentsReport,
    leasingRequestReport
  },

  methods: {
    // test() {
    //   this.$router.push({name: 'reporting', params: { year: 2020, report: 'requests-and-payments-report'}})
    // },
    getAvailableYears(url, e) {
      axios
        .get(url)
        .then(res => {
          // console.log({res: res})
          // this.years.splice(0)
          // this.years.push(res.data)
          // console.log(this.years)
          // this.getCurrentYear(res, e)
          this.getCurrentYear(res)
        })
        .catch(err => {
          console.log(err.response)
          this.$catchStatus(err.response.status)
        })
    },

    getReport(e) {
      console.log('getReport')
      let url = null
      // if(!e) {
      //   this.currentReport = this.$route.params.report
      // }

      if (this.currentReport === 'leasing-request-report') {
        url = `/json/reports/years/leasing-requests/${this.$store.state.user.agent.id}`
        this.getAvailableYears(url)

      } else if(this.currentReport === 'requests-and-payments-report') {
        url = `/json/reports/years/agent-commissions/${this.$store.state.user.agent.id}`
        this.getAvailableYears(url) 
      }
    },

    getCurrentYear(res, e) {
      this.years = res.data.sort((a, b) => a - b)

      console.log({years: this.years})

      // if(!this.$route.params.year) {
        this.currentYear = this.years[0]
      // } else {
      //   this.currentYear = +this.$route.params.year
      //   console.log({'this.currentYear': this.currentYear})
      // }

      // let {report, year} = this.$route.params
      // if(e) {
      //   this.$router.push({name: 'reporting', params: {report: this.currentReport, year: this.currentYear} })
      // }
      
      this.callGetLReq()
    },

    callGetLReq() {
      this.$nextTick(() => {
        this.$refs.chart.getLeasingRequests()
      })
    }
  },

  computed: {
    user() {
      return this.$store?.state?.user?.agent || false
    }
  },

  watch: {
    user(val) {
      if(val && !this.years.length) {
        this.getReport()
      }
    },

    currentReport(val) {
      console.log({currentReport: this.currentReport})
    }
  },

  mounted() {
    console.log({Params: this.$route.params})
    this.user ? this.getReport() : false
  }
}

</script>
