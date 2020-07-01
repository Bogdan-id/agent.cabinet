<template>
  <v-card elevation="9">
    <section class="chart-diagram" id="chart-diagram">
      <div class="tabs">
        <div class="tabs-input active">
          <label 
            @click.stop="changeActive($event)" 
            for="tab-2" 
            class="label">
            КЛАСИЧНИЙ
          </label>
          <input
            @click.stop="changeActive($event)"
            id="tab-2" 
            name="tab" 
            type="radio" 
            value="2"
            checked  
            v-model="currentTab">
          <div class="content">
            <v-card  class="view-charts">
              <v-row>
                <v-col cols="12" md="8" sm="9" lg="5" class="pt-0 pb-0">
                  <v-data-table
                    class="leasing-object-table"
                    v-if="graphData"
                    color="black"
                    :headers="leasingObjectDataHeader"
                    :items="[graphData]"
                    :items-per-page="180"
                    :hide-default-footer="true"
                    :must-sort="false"
                    dense
                    :mobile-breakpoint="6500">
                    <template v-slot:item.offer_price_brutto="{ item }">
                      <span v-if="item.result_data.even">
                        {{
                          item.result_data.even['offer-price-brutto']
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                    <template v-slot:item.offer_advance="{ item }">
                      <span v-if="item.result_data.even">
                        {{ 
                          item.result_data.even['offer-advance']
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                    <template v-slot:item.offer_month_payment="{ item }">
                      <span v-if="item.result_data.even">
                        {{ item.result_data.even['offer-month-payment']
                          .toLocaleString()
                          .replace(/,/g, ' ')  
                        }}
                      </span>
                    </template>
                    <template v-slot:item.one_time_commission="{ item }">
                      <span v-if="item.result_data.even">
                        {{ (item.result_data.even['offer-leasing-appreciation'] * 100).toFixed(2) }}
                      </span>
                    </template>
                    <template v-slot:item.offer_rest="{ item }">
                      <span v-if="item.result_data.even">
                        {{ 
                          item.result_data.even['offer-rest']
                            .toLocaleString()
                            .replace(/,/g, ' ') 
                        }}
                      </span>
                    </template>
                    <template v-slot:item.actions="{ item }">
                      {{ `${item.request_data.leasedAssertMark.name}   ${item.request_data.leasedAssertModel.name}` }}
                    </template>
                    <template v-slot:item.request_data.leasingAmount="{ item }">
                      <span>
                        {{ 
                          parseInt(item.request_data.leasingAmount.replace(/ /g, '' ))
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                  </v-data-table>
                </v-col>
              </v-row>
            <div class="payout-schedule">
              Графiк виплат
            </div>
            <v-data-table
              v-if="graphData"
              :search="search"
              color="black"
              :headers="tableHeader"
              :items="even"
              :items-per-page="180"
              :hide-default-footer="true"
              dense>
              <template v-slot:body.append>
                <tr style="color: black; border-left: 8px solid black;">
                  <td style="text-align: center;">Всього</td>
                  <td style="text-align: center;">{{ graphData.result_data.even['total-payment-principal'] }}</td>
                  <td style="text-align: center;">{{ graphData.result_data.even['total-interest'] }}</td>
                  <td style="text-align: center;">{{ graphData.result_data.even['total-payment'] }}</td>
                </tr>
                <span></span>
              </template>
              <template v-slot:footer>
                <chart-buttons 
                  v-if="graphData !== null"
                  :graph="currentTab" 
                  :data="graphData"/>
              </template>
              <template v-slot:item.interest="{ item }">
                <span>
                  {{  
                    item.interest
                      .toLocaleString()
                      .replace(/,/g, ' ')
                  }}
                </span>
              </template>
              <template v-slot:item.payment="{ item }">
                <span>
                  {{  
                    item.payment
                      .toLocaleString()
                      .replace(/,/g, ' ')
                  }}
                </span>
              </template>
              <template v-slot:item.payment_principal="{ item }">
                <span>
                  {{
                    item['payment-principal']
                      .toLocaleString()
                      .replace(/,/g, ' ')
                  }}
                </span>
              </template>
            </v-data-table>
          </v-card>
          </div>
        </div>
        <div class="tabs-input">
          <label
            @click.stop="changeActive($event)" 
            for="tab-1" 
            class="label">
            АНУЇТЕТ
          </label>
          <input 
            @click.stop="changeActive($event)"
            id="tab-1" 
            name="tab" 
            type="radio" 
            value="1" 
            v-model="currentTab"/>
          <div class="content">
            <v-card class="view-charts">
              <v-row>
                <v-col cols="12" md="8" sm="9" lg="5" class="pt-0 pb-0">
                  <v-data-table
                    class="leasing-object-table"
                    v-if="graphData"
                    color="black"
                    :headers="leasingObjectDataHeader"
                    :items="[graphData]"
                    :items-per-page="180"
                    :hide-default-footer="true"
                    :must-sort="false"
                    dense
                    :mobile-breakpoint="6500">
                    <template v-slot:item.offer_price_brutto="{ item }">
                      <span v-if="item.result_data.annuity">
                        {{
                          item.result_data.annuity['offer-price-brutto']
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                    <template v-slot:item.offer_advance="{ item }">
                      <span v-if="item.result_data.annuity">
                        {{ 
                          item.result_data.annuity['offer-advance']
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                    <template v-slot:item.offer_month_payment="{ item }">
                      <span v-if="item.result_data.annuity">
                        {{ item.result_data.annuity['offer-month-payment']
                          .toLocaleString()
                          .replace(/,/g, ' ')  
                        }}
                      </span>
                    </template>
                    <template v-slot:item.one_time_commission="{ item }">
                      <span v-if="item.result_data.annuity">
                        {{ (item.result_data.annuity['offer-leasing-appreciation'] * 100).toFixed(2) }}
                      </span>
                    </template>
                    <template v-slot:item.offer_rest="{ item }">
                      <span v-if="item.result_data.annuity">
                        {{ 
                          item.result_data.annuity['offer-rest']
                            .toLocaleString()
                            .replace(/,/g, ' ') 
                        }}
                      </span>
                    </template>
                    <template v-slot:item.actions="{ item }">
                      {{ `${item.request_data.leasedAssertMark.name}   ${item.request_data.leasedAssertModel.name}` }}
                    </template>
                    <template v-slot:item.request_data.leasingAmount="{ item }">
                      <span>
                        {{ 
                          parseInt(item.request_data.leasingAmount.replace(/ /g, '' ))
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                  </v-data-table>
                </v-col>
              </v-row>
              <div class="payout-schedule">
                Графiк виплат
              </div>
              <v-data-table
                v-if="graphData"
                :search="search"
                color="black"
                :headers="tableHeader"
                :items="annuity"
                :items-per-page="180"
                :hide-default-footer="true"
                dense>
                <template v-slot:body.append>
                  <tr style="color: black; border-left: 8px solid black;">
                    <td style="text-align: center;">Всього</td>
                    <td style="text-align: center;">{{ graphData.result_data.annuity['total-payment-principal'] }}</td>
                    <td style="text-align: center;">{{ graphData.result_data.annuity['total-interest'] }}</td>
                    <td style="text-align: center;">{{ graphData.result_data.annuity['total-payment'] }}</td>
                  </tr>
                  <span></span>
                </template>
                <template v-slot:footer>
                  <chart-buttons 
                    v-if="graphData"
                    :graph="currentTab" 
                    :data="graphData"/>
                </template>
                <template v-slot:item.interest="{ item }">
                  <span>
                    {{  
                      item.interest
                        .toLocaleString()
                        .replace(/,/g, ' ')
                    }}
                  </span>
                </template>
                <template v-slot:item.payment="{ item }">
                  <span>
                    {{  
                      item.payment
                        .toLocaleString()
                        .replace(/,/g, ' ')
                    }}
                  </span>
                </template>
                <template v-slot:item.payment_principal="{ item }">
                  <span>
                    {{
                      item['payment-principal']
                        .toLocaleString()
                        .replace(/,/g, ' ')
                    }}
                  </span>
                </template>
              </v-data-table>
            </v-card>
          </div>
        </div>
        <div class="tabs-input">
          <label 
            @click.stop="changeActive($event)" 
            for="tab-3" 
            class="label">
            IНДИВIДУАЛЬНИЙ
          </label>
          <input
            @click.stop="changeActive($event)"
            id="tab-3" 
            name="tab" 
            type="radio" 
            value="3" 
            v-model="currentTab">
          <div class="content">
            <v-card  class="view-charts">
              <v-row>
                <v-col cols="12" md="8" sm="9" lg="5" class="pt-0 pb-0">
                  <v-data-table
                    class="leasing-object-table"
                    v-if="graphData"
                    color="black"
                    :headers="leasingObjectDataHeader"
                    :items="[graphData]"
                    :items-per-page="180"
                    :hide-default-footer="true"
                    :must-sort="false"
                    dense
                    :mobile-breakpoint="6500">
                    <template v-slot:item.offer_price_brutto="{ item }">
                      <span v-if="item.result_data.irregular">
                        {{
                          item.result_data.irregular['offer-price-brutto']
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                    <template v-slot:item.offer_advance="{ item }">
                      <span v-if="item.result_data.irregular">
                        {{ 
                          item.result_data.irregular['offer-advance']
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                    <template v-slot:item.offer_month_payment="{ item }">
                      <span v-if="item.result_data.irregular">
                        {{ 
                          item.result_data.irregular['offer-month-payment']
                            .toLocaleString()
                            .replace(/,/g, ' ')  
                        }}
                      </span>
                    </template>
                    <template v-slot:item.one_time_commission="{ item }">
                      <span v-if="item.result_data.irregular">
                        {{ (item.result_data.irregular['offer-leasing-appreciation'] * 100).toFixed(2) }}
                      </span>
                    </template>
                    <template v-slot:item.offer_rest="{ item }">
                      <span v-if="item.result_data.irregular">
                        {{ 
                          item.result_data.irregular['offer-rest']
                            .toLocaleString()
                            .replace(/,/g, ' ') 
                        }}
                      </span>
                    </template>
                    <template v-slot:item.actions="{ item }">
                      {{ `${item.request_data.leasedAssertMark.name}   ${item.request_data.leasedAssertModel.name}` }}
                    </template>
                    <template v-slot:item.request_data.leasingAmount="{ item }">
                      <span>
                        {{ 
                          parseInt(item.request_data.leasingAmount.replace(/ /g, '' ))
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                  </v-data-table>
                </v-col>
              </v-row>
              <div class="payout-schedule">
                Графiк виплат
              </div>
              <v-data-table
                v-if="graphData"
                :search="search"
                color="black"
                :headers="tableHeader"
                :items="irregular"
                :items-per-page="180"
                :hide-default-footer="true"
                dense>
                <template v-slot:body.append>
                  <tr style="color: black; border-left: 8px solid black;">
                    <td style="text-align: center;">Всього</td>
                    <td style="text-align: center;">{{ graphData.result_data.irregular['total-payment-principal'] }}</td>
                    <td style="text-align: center;">{{ graphData.result_data.irregular['total-interest'] }}</td>
                    <td style="text-align: center;">{{ graphData.result_data.irregular['total-payment'] }}</td>
                  </tr>
                  <span></span>
                </template>
                <template v-slot:item.payment_principal="{ item }">
                  <span>
                    {{
                      item['payment-principal']
                        .toLocaleString()
                        .replace(/,/g, ' ')
                    }}
                  </span>
                </template>
                <template v-slot:footer>
                  <chart-buttons 
                    v-if="graphData !== null"
                    :graph="currentTab" 
                    :data="graphData"/>
                </template>
                <template v-slot:item.interest="{ item }">
                <span>
                  {{  
                    item.interest
                      .toLocaleString()
                      .replace(/,/g, ' ')
                  }}
                </span>
              </template>
              <template v-slot:item.payment="{ item }">
                <span>
                  {{  
                    item.payment
                      .toLocaleString()
                      .replace(/,/g, ' ')
                  }}
                </span>
              </template>
              </v-data-table>
            </v-card>
          </div>
        </div>
      </div>
    </section>
  </v-card>
</template>

<script>
import ChartButtons from './ChartDiagramBtn'
export default {
  components: {
    ChartButtons
  },
  data: () => ({
    graphData: null,
    leasingObjectData: null,
    currentTab: '2',
    search: '',
    leasingObjectDataHeader: [
      { text: 'Автомобiль', value: 'actions', align: 'center', sortable: false},
      { text: 'Вартiсть автомобiля, грн', value: 'request_data.leasingAmount', align: 'end', sortable: false},
      { text: 'Вартість автомобіля з реєстрацією, грн', value: 'offer_price_brutto', align: 'end', sortable: false },
      { text: 'Авансовий платiж, грн', value: 'offer_advance', align: 'center', sortable: false},
      { text: 'Термiн лiзингу, мiс', value: 'request_data.leasingTerm', align: 'end', sortable: false },
      { text: 'Валюта фiнансування', value: 'request_data.leasingCurrency', align: 'end', sortable: false },
      { text: 'Єдиноразова комiсiя, %', value: 'one_time_commission', align: 'end', sortable: false},
      { text: 'Залишкова вартiсть, грн', value: 'offer_rest', align: 'end', sortable: false },
      { text: 'Середньомiсячний платiж, грн', value: 'offer_month_payment', align: 'end', sortable: false },
    ],
    tableHeader: [
      { text: '№', value: 'n', align: 'center', sortable: false},
      { text: 'Оплата за авто, грн', value: 'payment_principal', align: 'center', sortable: false},
      { text: 'Винагорода лiзингодавця, грн', value: 'interest', align: 'center', sortable: false },
      { text: 'Сума платежу, грн', value: 'payment', align: 'center', sortable: false },
    ],
    even: [],
    irregular: [],
    annuity: [],
  }),
  methods: {
    addObjects(data) {
      /* eslint-disable */
      if(data.result_data.hasOwnProperty('annuity')) {console.log(data.result_data.annuity.graph); this.annuity = data.result_data.annuity.graph}
      if(data.result_data.hasOwnProperty('irregular')) this.irregular = data.result_data.irregular.graph
      if(data.result_data.hasOwnProperty('even')) this.even = data.result_data.even.graph
    },
    changeActive(event) {
      let tabs = document.querySelectorAll('#chart-diagram .tabs-input')
      tabs.forEach(element => element.classList.remove('active'))
      event.target.parentNode.classList.add('active')
    },
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
    // this.leasingObjectData = [this.graphData.request_data]
    // this.leasingObjectData = Object.assign([this.leasingObjectData, this.graphData.result_data[0]])
    // console.log(this.leasingObjectData)
    console.log('**************')
    console.log(this.graphData)
    console.log(this.graphData.result_data.even['total-payment'])
    console.log('**************')
    Object.keys(this.graphData.result_data).forEach(object => {
      if(this.graphData.result_data[object].graph) {
        this.graphData.result_data[object].graph[0].n = 'Аванс'
      }
    })

    this.addObjects(this.graphData)
    document.body.scrollTop = 0
  },
}
</script>
<style lang="scss">
  .payout-schedule {
    border-bottom: 1px solid #e0e0e0;
    font-size: 1.1rem;
    font-weight: bold;
    color: #424242;
    padding-left: 1rem;
  }
.v-data-table {
  &.leasing-object-table{
    margin-bottom: 0.7rem;
    .v-data-table__mobile-row {
      min-height: 22px!important;
      border-bottom: 1px solid #eeedeb;
      margin: 0 25px 0 25px;
      padding: 0;
      .v-data-table__mobile-row__cell {
        font-weight: bold;
        color: #424242;
      }
    }
    thead {
      tr {
        th {
          .v-data-table-header-mobile__wrapper:before {
            font-size: 1.1rem;
            color: #424242;
            content: "Об'єкт лізингу"
          }
        }
      }
    }
  }
}
</style>
<style lang="scss" scoped>
.v-data-table.leasing-object-table {
  .v-data-table__mobile-row {
    min-height: 22px!important;
  }
}
.chart-diagram {
  width: 100%;
  min-height: 100vh;
  overflow-x: hidden;
  .tabs {
    display: flex;
    position: relative;
  }
  .tabs .tabs-input input,
  .tabs .tabs-input input:focus {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    display: block;
    width: 100%;
    outline: none;
  }
  .tabs .tabs-input .label {
    cursor: pointer;
    display: block;
    width: 100%;
    height: 95%;
    font-size: 1.2rem;
    text-align: center;
    line-height: 2rem;
    color: white;
    padding: 0.33rem 0 0.33rem 0;
    margin: 0;
    background: #424242;
  }
  .tabs .tabs-input.active .label {
    color: white;
    background: #e94949;
    // border-top: 2px solid #424242;
    // box-shadow: 0px -5px 8px 4px rgba(0,0,0,1);
  }

  .tabs .tabs-input {
    width: 50%;
    display: block;
    height: 100%;
    opacity: 1;
    // transition: opacity 0.3s ease;
  }
  .tabs .tabs-input.active,
  .tabs .tabs-input:hover {
    opacity: 1;
  }
  .tabs-input [type="radio"] + .content {
    display: block;
    opacity: 0;
    padding: 0.5rem 0;
    width: 100%;
    position: absolute;
    left: 100%;
    transition: left 0.3s ease-in-out, opacity 0.3s ease; 
    .view-charts {
      box-shadow: none!important;
    }
  }
  .tabs-input [type="radio"]:checked + .content {
    left: 0;
    opacity: 1;
  }
}
  
</style>