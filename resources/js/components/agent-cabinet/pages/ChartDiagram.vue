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
            <chart-buttons />
            <v-data-table
              :search="search"
              color="black"
              :headers="tableHeader"
              :items="annuity"
              :items-per-page="180"
              class="elevation-1">
            </v-data-table>
          </v-card>
        </v-tab-item>
        <v-tab-item :key="2" value="even">
          <v-card>
            <chart-buttons />
            <v-data-table
              :search="search"
              color="black"
              :headers="tableHeader"
              :items="even"
              :items-per-page="180"
              class="elevation-1">
            </v-data-table>
          </v-card>
        </v-tab-item>
        <v-tab-item :key="3" value="irregular">
          <v-card>
            <chart-buttons />
            <v-data-table
              :search="search"
              color="black"
              :headers="tableHeader"
              :items="irregular"
              :items-per-page="180"
              class="elevation-1">
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
    currentTab: "even",
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
      if(data.hasOwnProperty('annuity')) this.annuity = data.annuity.graph
      if(data.hasOwnProperty('irregular')) this.irregular = data.irregular.graph
      if(data.hasOwnProperty('even')) this.even = data.even.graph
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
    let data = this.$router.currentRoute.params.data
    Object.keys(data).forEach(object => {
      if(data[object].graph) {
        data[object].graph.splice(0, 1)
      }
    })
    this.addObjects(data)
    document.body.scrollTop = 0
  }
}
</script>
