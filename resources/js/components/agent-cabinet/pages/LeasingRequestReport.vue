<template>
<v-row>
  <v-col class="ml-6 mr-4">
    <div :class="`ct-chart ct-octave ${xs ? 'xs' : leasingReportXSmall ? 'x-small' : leasingReportSmall ? 'small' : ''}`"></div>
    <section class="chart-navigation">
      <div style="display: inline-block;">
        <div
          v-for="(item, key) in data.series"
          :key="key"
          class="label-hint">
          <span 
            class="hint-square" 
            :style="`background: ${item.color};`">
          </span>
          <span>{{ item.name }}</span>
        </div>
      </div>
      <div style="display: inline-block; max-width: 170px;">
        <v-select
          @change="getLeasingRequests()"
          v-model="currentYear"
          :items="years"
          label="Рiк"
          color="#e65048"
          item-color="#e65048"
          dense>
        </v-select>
      </div>
    </section>
  </v-col>
</v-row>
</template>

<script>
import Chartist from 'chartist'
// eslint-disable-next-line
import * as ChartistTooltips from 'chartist-plugin-tooltip'

import axios from 'axios'

export default {
  data: () => ({
    years: [],
    currentYear: null,
    chartData: {},
    objShouldContain: ['0', '5', 'inWork'],
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

    data: {
      labels: [],
      series: [
        {
          className: 'ct-series-a',
          name: 'В роботi',
          data: [],
          color: '#ff9800'
        },
        {
          className: 'ct-series-b',
          name: 'Відвантажено',
          data: [],
          color: '#4caf50'
        },
        {
          className: 'ct-series-c',
          name: 'Відмовлено',
          data: [],
          color: '#f44336'
        },
      ],
    },

    options: {
      seriesBarDistance: 15,
      plugins: [
        Chartist.plugins.tooltip()
      ],
      axisY: {
        offset: 60
      }
    },

    responsiveOptions: [
      ['screen and (min-width: 641px) and (max-width: 1024px)', {
        seriesBarDistance: 10,
        axisY: {
          offset: 60,
          labelInterpolationFnc: function (value) {
            return (parseInt(value)).toLocaleString('ru');
          }
        },
        axisX: {
          labelInterpolationFnc: function (value) {
            return value;
          }
        }
      }],
      ['screen and (max-width: 640px)', {
        seriesBarDistance: 10,
        axisY: {
          offset: 60,
          labelInterpolationFnc: function (value) {
            return (parseInt(value)).toLocaleString('ru');
          }
        },
        axisX: {
          labelInterpolationFnc: function (value) {
            return value[0];
          }
        }
      }]
    ]
  }),

  methods: {
    switchGraphById(v) {
      switch (v) {
        case '0': return 'Відмовлено';
        case '5': return 'Відвантажено';
        case 'inWork': return 'В роботi'; 
      }
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
            Object.keys(this.data.series)
              .forEach(value => {
                if(this.data.series[value].name === this.switchGraphById(val)) {
                  this.data.series[value].data.push(this.chartData[v.id][val])
                }
              })
          }
        })
    },

    getProperties() {
      this.months
        .forEach(v => {
          if(this.chartData[v.id]) {
            this.addPropertyIfEmpty(v)

            this.pushDataToGraph(v)

            this.data.labels.push(v.name)
          }
        })
    },

    initChartistGraph() {
      try {
        new Chartist.Bar('.ct-chart', this.data, this.options, this.responsiveOptions)

      } catch (err) {
        console.log(err)
      }
    },

    buildGraph(response) {
      this.chartData = Object.assign(this.chartData, response.data)

      this.getProperties()

      this.masterSchedule()

      this.initChartistGraph()
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

    getCurrentYear(res) {
      this.years = res.data.sort((a, b) => a - b)

      console.log({years: this.years})
      
      this.currentYear = this.years[0]
    },

    masterSchedule() {
      this.responsiveOptions
      .forEach(v => v[1].seriesBarDistance = this.setIndentToLeasingReport)
      this.options.seriesBarDistance = this.setIndentToLeasingReport
    },

    getAvailableYears() {
      axios
        .get(`/reports/years/leasing-requests/${this.$store.state.user.agent.id}`)
        .then(res => {
          this.getCurrentYear(res)
          this.getLeasingRequests()
        })
        .catch(err => {
          console.log(err.response)
        })
    }
  },

  computed: {
    leasingReportSmall() {
      return this.data.labels.length <= 3
    },

    leasingReportXSmall() {
      return this.data.labels.length <= 1
    },

    setIndentToLeasingReport() {
      return this.xs ? 15 : this.leasingReportXSmall ? 55 : this.leasingReportSmall ? 45 : 25
    },

    xs() {
      return this.$vuetify.breakpoint.xs
    }
  },

  watch: {
    setIndentToLeasingReport() {
      this.masterSchedule()
    }
  },

  mounted() {
    this.getAvailableYears()
  }
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

.ct-chart {
  .ct-series-a .ct-bar {
    stroke-width: 25px;
    stroke: #ffa726;
  }
  .ct-series-b .ct-bar {
    stroke-width: 25px;
    stroke: #66bb6a;
  }
  .ct-series-c .ct-bar {
    stroke-width: 25px;
    stroke: #ef5350;
  }
  &.small {
    .ct-label {
      font-size: 0.8rem;
    }
    .ct-series-a .ct-bar {
      stroke-width: 35px;
    }
    .ct-series-b .ct-bar {
      stroke-width: 35px;
    }
    .ct-series-c .ct-bar {
      stroke-width: 35px;
    }
  }
  &.x-small {
    .ct-series-a .ct-bar {
      stroke-width: 45px;
    }
    .ct-series-b .ct-bar {
      stroke-width: 45px;
    }
    .ct-series-c .ct-bar {
      stroke-width: 45px;
    }
  }
  &.xs {
    .ct-series-a .ct-bar {
      stroke-width: 15px;
    }
    .ct-series-b .ct-bar {
      stroke-width: 15px;
    }
    .ct-series-c .ct-bar {
      stroke-width: 15px;
    }
  }
}

.ct-tooltip {
    position: absolute;
    display: inline-block;
    min-width: 5em;
    padding: 8px 10px;
    background: #383838;
    color: #fff;
    text-align: center;
    pointer-events: none;
    z-index: 100;
    transition: opacity .2s linear;

    &:before {
        position: absolute;
        bottom: -14px;
        left: 50%;
        border: solid transparent;
        content: ' ';
        height: 0;
        width: 0;
        pointer-events: none;
        border-color: rgba(251, 249, 228, 0);
        border-top-color: #383838;
        border-width: 7px;
        margin-left: -8px;
    }

    &.hide {
        display: block;
        opacity: 0;
        visibility: hidden;
    }
}

.chart-card {
  .v-select__selection {
    font-size: 1rem;
    font-weight: bold;
  }
}

@import "chartist/dist/scss/chartist.scss";

</style>