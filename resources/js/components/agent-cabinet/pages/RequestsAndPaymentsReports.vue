<template>
<div>
  <div 
    v-show="isObjEmpty"
    style="position: absolute; margin: 60px auto; width: 100%; text-align: center; color: grey; font-size: 1rem;">
    За даний рiк звiтнiсть за заявками на лiзинг та виплат АВ вiдсутня 
  </div>
  <div class="pl-2 pr-2 canvas-wrapper" v-show="!isObjEmpty">
    <canvas id="myChart"></canvas>
  </div>
</div>
</template>

<script>
import Chart from 'chart.js'
import axios from 'axios'

export default {
  props: [
    'currentYear', 
    'months',
    'cnvsW', 
    'cnvsH'
  ],

  data: () => ({
    isObjEmpty: false,
    chartData: {},

    objShouldContain: ['ac_sum', 'price_brutto_sum', 'not_paid_sum'],

    // Chart.js
    type: 'bar',

    data: {
      labels: [],
      datasets: [
        {
          yAxisID: 'B',
          label: 'Не виплаченi АВ',
          data: [],
          backgroundColor: '#ff9900bb',
          borderColor: '#ff9900',
          borderWidth: 2,
          type: 'bar'
        },
        {
          yAxisID: 'A',
          label: 'Сума фiнансування',
          lineTension: 0, 
          data: [],
          backgroundColor: '#fdfdfd00',
          borderColor: '#2fba80',
          borderWidth: 2,
          type: 'line',
        },
        {
          yAxisID: 'B',
          label: 'Сума АВ',
          data: [],
          backgroundColor: '#4caf4fbb',
          borderColor: '#4caf4f',
          borderWidth: 2,
          type: 'bar'
        },
      ]
    },

    options: {
      responsive: true,
      aspectRatio: 1.9,
      scales: {
        xAxes: [{
          stacked: true,
          // gridLines: {
          //   display: false
          // }
        }],
        yAxes: [
          {
            id: 'A',
            position: 'left',
            type: 'linear',
            ticks: {
              beginAtZero: true,
              max: null,
              stepSize: 100000,
              min: null,
            },
            gridLines: {
                color: "rgba(0, 0, 0, 0)",
            },
            afterTickToLabelConversion : function(v){
              for(var tick in v.ticks){
                v.ticks[tick] = parseInt(v.ticks[tick])
                  .toLocaleString('ru')
              }
            }
          },

          {
            id: 'B',
            position: 'right',
            type: 'linear',
            stacked: true,
            ticks: {
              beginAtZero: true,
              max: null,
              min: null,
              stepSize: 1000,
            },
            gridLines: {
                color: "rgba(0, 0, 0, 0)",
            },
            afterTickToLabelConversion : function(v){
              for(var tick in v.ticks){
                v.ticks[tick] = parseInt(v.ticks[tick])
                  .toLocaleString('ru')
              }
            }
          }
        ]
      },

      tooltips: {
        callbacks: {
          label: function (tooltipItem) {
            return parseInt(tooltipItem.value)
              .toLocaleString('ru') + ' грн'
          }
        }
      }
    }
  }),

  methods: {
    switchGraphById(v) {
      switch (v) {
        case 'ac_sum': return 'Сума АВ';
        case 'price_brutto_sum': return 'Сума фiнансування';
        case 'not_paid_sum': return 'Не виплаченi АВ';
      }
    },

    checkEmptyObj() {
      return Object.keys(this.chartData).length === 0 && this.chartData.constructor === Object || this.chartData.max_sum === null
    },

    initChart() {
      if (this.checkEmptyObj()) {
        this.isObjEmpty = true
        this.chartInst.destroy() 
        return 
      }
      
      this.isObjEmpty = false

      try {
        var ctx = document.getElementById('myChart').getContext('2d')
        ctx.clearRect(0, 0, this.cnvsW, this.cnvsH)

        this.chartInst 
          ? this.chartInst.destroy() 
          : false

        this.chartInst = new Chart(
          ctx, 
          {
            type: this.type, 
            data: this.data, 
            options: this.options
          }
        )

      } catch (err) {
        console.log(err)
      }
    },

    setMaxYAxes(res) {
      console.log(res)
       Object.keys(this.options.scales.yAxes)
        .forEach(v => {
          this.options.scales.yAxes[v].id === 'A'
            ? this.setMaxMinA(v, res)
            : this.options.scales.yAxes[v].id === 'B'
              ? this.setMaxMinB(v, res)
              : false
        })
      console.log({Axes:this.options.scales.yAxes})
    },

    setMaxMinA(v, res) {
      this.options.scales.yAxes[v].ticks.max = res.max_price_brutto_sum * 1.2
      // this.options.scales.yAxes[v].ticks.min = res.min_price_brutto_sum / 1.35
    },

    setMaxMinB(v, res) {
      this.options.scales.yAxes[v].ticks.max = res.max_sum * 3.2
      // this.options.scales.yAxes[v].ticks.min = res.min_sum / 1.35
    },

    getLeasingRequests() {
      axios
        .get(`/reports/agent-commissions/${this.agentId}/${this.currentYear}`)
        .then(response => {
          console.log(response)
          this.setMaxYAxes(response.data)
          this.buildGraph(response)
        })
        .catch(err => {
          console.log(err.response)
          this.$catchStatus(err.response.status)
        })
    },

    buildGraph(response) {
      this.chartData = {}

      this.chartData = Object.assign(this.chartData, response.data)

      this.getProperties()

      this.initChart()
    },

    getProperties() {
      this.clearGraphs()

      this.months
        .forEach(v => {
          if(this.chartData[v.id]) {
            this.addPropertyIfEmpty(v)

            this.pushDataToGraph(v)

            this.data.labels.push(v.name)
          }
        })
    },

    pushDataToGraph(v) {
      Object.keys(this.chartData[v.id])
        .forEach(val => {
          if(this.switchGraphById(val)) {
            Object.keys(this.data.datasets)
              .forEach(value => {
                if(this.data.datasets[value].label === this.switchGraphById(val)) {
                  this.data.datasets[value].data.push(this.chartData[v.id][val])
                }
              })
          }
        })
    },

    clearGraphs() {
      this.data.labels.splice(0)

      this.data.datasets
        .forEach(v => v.data.splice(0))
    },

    addPropertyIfEmpty(v) {
      this.objShouldContain
        .forEach(key => {
          if(!this.chartData[v.id][key]) this.chartData[v.id][key] = 0
        })
    },
  },

  computed: {
    agentId() {
      return this.$store.state.user.agent.id
    }
  },
}
</script>

<style>
.chartjs-render-monitor {
  display: block!important;
}
</style>