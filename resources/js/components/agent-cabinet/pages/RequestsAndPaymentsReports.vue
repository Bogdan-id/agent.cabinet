<template>
<div>
  <div 
    v-show="isObjEmpty"
    style="position: absolute; margin: 60px auto; width: 100%; text-align: center; color: grey; font-size: 1rem;">
    За даний рiк звiтнiсть за заявками на лiзинг та виплат АВ вiдсутня 
  </div>
  <div class="pl-2 pr-2 canvas-wrapper" v-show="!isObjEmpty">
    <div id="legend"></div>
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

    objShouldContain: ['ac_sum', 'paid_sum', 'not_paid_sum'],

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
          type: 'bar',
          hidden: false,
        },
        {
          yAxisID: 'B',
          label: 'Виплаченi АВ',
          data: [],
          backgroundColor: '#4caf4fbb',
          borderColor: '#4caf4f',
          borderWidth: 2,
          type: 'bar',
          hidden: false,
        },
        {
          yAxisID: 'A',
          label: 'Сума фiнансування',
          lineTension: 0, 
          data: [],
          fill: false,
          backgroundColor: '#e53935', 
          borderColor: '#e53935',
          borderWidth: 2,
          type: 'line',
          hidden: false,
        },
      ]
    },

    options: {
      responsive: true,
      aspectRatio: 1.9,
      hover: {
        animationDuration: 0
      },

      legend: {
        display: false
      },

      legendCallback: (chart) => {
        let text = []

        text.push('<ul class="0-legend"><div style="display: inline-block;">')

        let ds = chart.data.datasets

        let legendSquare = (ds, i) => {
          return `
            <span style="
              display: inline-block; 
              width: 2.5rem; 
              height: 0.88rem; 
              background: ${ds[i].backgroundColor}; 
              border: ${ds[i].borderWidth}px solid ${ds[i].borderColor};">
            </span>`
        }

        let lineChart = (ds, i) => {
          return `
            <svg style="vertical-align: sub;" width="2.5rem" height="1rem" viewBox="0 0 64 25" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <g id="#ffffffff">
            </g>
            <g id="#ed1c24ff">
            <path fill="${ds[i].backgroundColor}" opacity="1.00" d=" M 22.63 9.95 C 23.98 4.73 29.56 1.26 34.81 2.05 C 38.50 3.37 42.96 5.54 43.34 9.97 C 48.85 10.11 54.36 9.86 59.87 10.05 C 62.79 9.91 62.80 15.09 59.87 14.95 C 54.67 15.15 49.46 14.88 44.26 15.04 C 41.99 19.56 37.34 23.38 32.08 23.04 C 27.74 22.36 23.76 19.45 22.63 15.05 C 16.47 14.86 10.30 15.16 4.14 14.95 C 1.20 15.10 1.20 9.91 4.13 10.05 C 10.30 9.84 16.47 10.14 22.63 9.95 Z" />
            </g>
            </svg>`
        }

        for (let i = 0; i < ds.length; i++) {
          text.push('<li style="font-size: 0.75rem; color: #70706e; display: inline-block;">')

          text.push(`
            ${ds[i].label === 'Сума фiнансування' ? lineChart(ds, i) : legendSquare(ds, i)}
            <span class="legend-label" style="vertical-align: text-bottom;">
              &nbsp; ${ds[i].label}
            </span>`)

          text.push('</li>')
        }

        text.push('</div></ul>')

        return text.join("")
      },
      
      animation: {
        onComplete: function() {
          let chartInstance = this.chart
          let ctx = chartInstance.ctx

          ctx.textAlign = "center"
          ctx.font = "10px Roboto, sans-serif"
          ctx.fillStyle = "#fff"

          let sumHandler = (dataSetIndex, dataSetMetaIndex, allSum) => {
            this.data.datasets[dataSetIndex].data
              .forEach(function(data, index) {
                let total = allSum 
                  ? data + this.data.datasets[dataSetMetaIndex].data[index]
                  :this.data.datasets[dataSetMetaIndex].data[index]
                let meta = chartInstance.controller.getDatasetMeta(dataSetMetaIndex)
                let posX = meta.data[index]._model.x
                let posY = meta.data[index]._model.y

                ctx.fillStyle = "black"
                ctx.fillText(total, posX + 4, posY -7)
              }, this)
          }

          let item = this.data.datasets

          if(!item[0].hidden && !item[1].hidden){
            sumHandler(0, 1, true)

          } else if(!item[0].hidden && item[1].hidden) {
            sumHandler(1, 0, false)

          } else if (item[0].hidden && !item[1].hidden) {
            sumHandler(0, 1, false)
          }
        }
      },

      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [
          {
            id: 'A',
            position: 'left',
            type: 'linear',
            ticks: {
              beginAtZero: true,
              max: null,
              stepSize: 200000,
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
              stepSize: 3000,
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
          // label: function(tooltipItem, data) {
          //   let corporation = data.datasets[tooltipItem.datasetIndex].label;
          //   let valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

          //   // Loop through all datasets to get the actual total of the index
          //   let total = 0;
          //   console.log({DATASETS: [data.datasets, tooltipItem.index]})
          //   for (let i = 0; i < data.datasets.length; i++)
          //       total += data.datasets[i].data[tooltipItem.index]

          //   // If it is not the last dataset, you display it as you usually do
          //   if (tooltipItem.datasetIndex != data.datasets.length - 1) {
          //       return corporation + " : $" + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
          //   } else { // .. else, you display the dataset and the total, using an array
          //       return [corporation + " : $" + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'), "Total : $" + total];
          //   }
          // }
          label: function (tooltipItem) {
            return parseInt(tooltipItem.value)
              .toLocaleString('ru') + ' грн'
          }
        },
      }
    }
  }),

  methods: {
    toggleLegend() {
      let myLegendContainer = document.getElementById("legend")
      let legendItems = myLegendContainer.getElementsByTagName('li')

      myLegendContainer.innerHTML = this.chartInst.generateLegend()

      let legendClickCallback = (event) => {
        event = event || window.event

        let target = event.target || event.srcElement

        while (target.nodeName !== 'LI') {
          target = target.parentElement
        }

        let parent = target.parentElement
        let index = Array.prototype.slice.call(parent.children).indexOf(target)

        if (this.data.datasets[index].hidden === null || this.data.datasets[index].hidden === false) {
          this.data.datasets[index].hidden = true
          target.classList.add('hidden')

        } else {
          target.classList.remove('hidden')
          this.data.datasets[index].hidden = null
        }

        this.chartInst.update()
      }

      for (let i = 0; i < legendItems.length; i += 1) {
        legendItems[i].addEventListener("click", legendClickCallback, false)
      }

      
    },
    switchGraphById(v) {
      switch (v) {
        case 'paid_sum': return 'Виплаченi АВ';
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
        this.toggleLegend()

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
    },

    setMaxMinB(v, res) {
      this.options.scales.yAxes[v].ticks.max = res.max_sum * 3.2
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

[class$="-legend"] {
  margin-bottom: 0.5rem;
  list-style: none;
  padding-left: 0;
  display: flex;
  justify-content: center;
}

[class$="-legend"] li {
  display: block;
  padding: 0 5px;
  cursor: pointer;
}

[class$="-legend"] li.hidden .legend-label {
  text-decoration: line-through;
}

[class$="-legend"] li span {
  /* border-radius: 5px; */
  display: inline-block;
  /* height: 10px;
  margin-right: 10px;
  width: 10px; */
}
</style>