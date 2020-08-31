<template>
<v-row>
  <v-col class="ml-6 mr-4" style="position: relative;">
    <div 
      v-show="isObjEmpty"
      style="position: absolute; margin: 60px auto; width: 100%; text-align: center; color: grey; font-size: 1rem;">
      За даний рiк звiтнiсть за заявками на лiзинг вiдсутня 
    </div>
    <canvas id="myChart"></canvas>
  </v-col>
</v-row>
</template>

<script>
import Chart from 'chart.js'
import axios from 'axios'

export default {
  props: [
    'currentYear', 
    'months',
    'cnvsW', 
    'cnvsH',
  ],

  data: () => ({
    isObjEmpty: false,
    chartData: {},
    
    objShouldContain: ['0', '5', 'inWork'],

    chartInst: null,

    // Chart.js
    type: 'bar',

    data: {
      labels: [],
      datasets: [
        {
          label: 'В роботi',
          data: [],
          backgroundColor: '#ff9900bb',
          borderColor: '#ff9900',
          borderWidth: 2,
          type: 'bar'
        },
        {
          label: 'Відвантажено',
          data: [],
          backgroundColor: '#4caf4fbb',
          borderColor: '#4caf4f',
          borderWidth: 2,
          type: 'bar'
        },
        {
          label: 'Відмовлено',
          data: [],
          backgroundColor: '#f44336b9',
          borderColor: '#f44336',
          borderWidth: 2,
          type: 'bar'
        },
      ]
    },

    options: {
      aspectRatio: 1.8,
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:false
          },

          // scaleLabel: {
          //   display: true,
          //   labelString: 'Заявки на лiзинг'
          // },

          afterTickToLabelConversion : function(v){
            for(var tick in v.ticks){
              v.ticks[tick] = parseInt(v.ticks[tick])
                .toLocaleString('ru') // + ' грн'
            }
          }
      }]
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
        case '0': return 'Відмовлено';
        case '5': return 'Відвантажено';
        case 'inWork': return 'В роботi';
      }
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

    initChart() {
      if (this.checkEmptyObj()) return this.isObjEmpty = true

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

    buildGraph(response) {
      this.chartData = {}

      this.chartData = Object.assign(this.chartData, response.data)

      this.getProperties()

      this.initChart()
    },

    checkEmptyObj() {
      return Object.keys(this.chartData).length === 0 && this.chartData.constructor === Object
    },

    getLeasingRequests() {
      const agentId = this.$store.state.user.agent.id

      axios
        .get(`/reports/leasing-requests/${agentId}/${this.currentYear}`)
        .then(response => {
          console.log(response)

          this.buildGraph(response)
        })
        .catch(err => {
          console.log(err.response)
          this.$catchStatus(err.response.status)
        })
    },
  },

  computed: {
    agentId() {
      return this.$store.state.user.agent.id
    }
  },

  watch: {
    setIndentToLeasingReport() {
      this.masterSchedule()
    },

    isObjEmpty(val) {
      if(val) {
        document.getElementById('myChart').style = "opacity: 0;"
      } else {
        document.getElementById('myChart').style = "opacity: 1"
      }
    }
  },
}
</script>

<style lang="scss">

.chart-navigation {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 10px 0;
  padding: 0 15px;
  .label-hint {
    display: inline-flex;
    flex-wrap: nowrap;
    margin: 0 8px;
    .hint-square {
      display: inline-block;
      align-self: center;
      width: 10px;
      height: 10px;
      margin: 0 4px;
    }
  }
}

</style>