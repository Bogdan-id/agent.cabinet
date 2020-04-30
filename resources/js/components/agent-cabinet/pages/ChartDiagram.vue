<template>
  <v-card>
    <v-tabs
      v-model="currentTab"
      background-color="red lighten-1"
      dark grow>
      <v-tabs-slider></v-tabs-slider>
      <v-tab v-if="hasAnnuity" href="#annuity">Ануїтет</v-tab>
      <v-tab v-if="hasIrregular" href="#irregular">Iндивiдуальний</v-tab>
      <v-tab v-if="hasEven" href="#even">Класичний</v-tab>
      </v-tabs>
      <v-tabs-items v-model="currentTab">
        <v-tab-item :key="1" value="annuity">
          <v-card>
            <chart-buttons 
              v-if="graphData !== null"
              :graph="currentTab" 
              :data="graphData"/>
            <v-data-table
              :search="search"
              color="black"
              :headers="tableHeader"
              :items="annuity"
              :items-per-page="180"
              class="elevation-1"
              :hide-default-footer="true">
            </v-data-table>
          </v-card>
        </v-tab-item>
        <v-tab-item :key="2" value="even">
          <v-card>
            <chart-buttons 
              v-if="graphData !== null"
              :graph="currentTab" 
              :data="graphData"/>
            <v-data-table
              :search="search"
              color="black"
              :headers="tableHeader"
              :items="even"
              :items-per-page="180"
              class="elevation-1"
              :hide-default-footer="true">
            </v-data-table>
          </v-card>
        </v-tab-item>
        <v-tab-item :key="3" value="irregular">
          <v-card>
            <chart-buttons 
              v-if="graphData !== null"
              :graph="currentTab" 
              :data="graphData"/>
            <v-data-table
              :search="search"
              color="black"
              :headers="tableHeader"
              :items="irregular"
              :items-per-page="180"
              class="elevation-1"
              :hide-default-footer="true">
            </v-data-table>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
  </v-card>
</template>

<script>
import ChartButtons from './ChartDiagramBtn'
export default {
  components: {
    ChartButtons
  },
  data: () => ({
    // data to props 
    graphData: null,
    // data
    currentTab: null,
    search: '',
    tableHeader: [
      { text: '№', value: 'n', align: 'end'},
      { text: 'Оплата за авто', value: 'payment-principal', align: 'end'},
      { text: 'Винагорода лiзингодавця', value: 'interest', align: 'end' },
      { text: 'Сума платежу', value: 'payment', align: 'end' },
    ],
    even: [],
    irregular: [],
    annuity: [],
  }),
  methods: {
    addObjects(data) {
      /* eslint-disable */
      if(data.result_data.hasOwnProperty('annuity')) this.annuity = data.result_data.annuity.graph
      if(data.result_data.hasOwnProperty('irregular')) this.irregular = data.result_data.irregular.graph
      if(data.result_data.hasOwnProperty('even')) this.even = data.result_data.even.graph
      /* eslint-enable */
      this.setCurrentTab(data)
    },
    setCurrentTab(data) {
      let arr = Object.keys(data)
      let index = Object.keys(data).indexOf('requestId')
      arr.splice(index, 1)
      this.currentTab = arr[0]
    }
  },
  computed: {
    hasAnnuity() {
      return this.annuity.length > 0
    },
    hasIrregular() {
      return this.irregular.length > 0
    },
    hasEven() {
      return this.even.length > 0
    }
  },
  mounted() {
    this.graphData = this.$router.currentRoute.params.data
    Object.keys(this.graphData).forEach(object => {
      if(this.graphData[object].graph) {
        this.graphData[object].graph.splice(0, 1)
      }
    })
    this.addObjects(this.graphData)
    document.body.scrollTop = 0
  },
  watch:{
    graphData(val) {
      console.log(val)
    }
  }
}
</script>
