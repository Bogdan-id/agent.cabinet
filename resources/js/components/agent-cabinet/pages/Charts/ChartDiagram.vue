<template>
  <v-card elevation="9">
    <section class="chart-diagram" id="chart-diagram">
      <div>
        <v-select
          v-if="graphData && graphData.result_data"
          v-show="$vuetify.breakpoint.xs"
          v-model="currentTab"
          :items="[
            {text: 'Класичний', value: '2'},
            {text: 'Ануїтет', value: '1'},
            {text: 'Iндивiдуальний', value: '3'}
          ]"
          :class="`calculator-graph-select ${graphData.result_data.hasOwnProperty(switchGraphByNumber(currentTab)) ? 'active' : ''}`"
          item-text="text"
          item-value="value"
          @change="changeActive()"
          :background-color="graphData.result_data.hasOwnProperty(switchGraphByNumber(currentTab)) ? '#d24a43' : '#808080'"
          dark 
          outlined
          item-color="grey darken-4"
          hide-details dense>
        </v-select>
      </div>
      <div class="tabs" v-if="graphData && graphData.result_data">
        <div class="tabs-input active">
          <label 
            v-show="!$vuetify.breakpoint.xs"
            @click.stop="changeActive($event)" 
            for="tab-2"
            :style="`${!graphData.result_data.hasOwnProperty('even') ? 'background: grey' : ''}`"
            class="label">
            КЛАСИЧНИЙ
          </label>
          <input
            v-show="!$vuetify.breakpoint.xs"
            @click.stop="changeActive($event)"
            id="tab-2" 
            name="tab" 
            type="radio" 
            value="2"
            checked  
            v-model="currentTab">
          <div class="content">
            <div style="position: relative;">
              <div v-if="!graphData.result_data.hasOwnProperty('even')" class="empty-chart">
              </div>
              <div class="router-link-to-no-calc" :style="$vuetify.breakpoint.xs ? 'width: 90%;' : '60%;'">
                <p style="font-size: 1rem"><span style="position: relative; width: 40px; display: inline-block; left: 0; top: -25px;"><v-icon style="padding: 0 10px 5px 0; position: absolute; left: 0" size="34" color="#d24a43" >mdi-information-outline</v-icon></span>
                  Даний графiк не було додано при калькуляцii, але ви можете додати його до розрахунку за 
                  <router-link style="color: #d24a43; text-decoration: underline" :to="{name: 'Редагувати', params: {id: graphData.id, edit: true}}"> посиланням</router-link> в роздiлi - графiк платежiв
                </p>
              </div>
            </div>
            <v-card  class="view-charts" v-if="graphData.result_data.hasOwnProperty('even')">
              <v-row>
                <v-col cols="12" md="8" sm="9" lg="6" class="pt-0 pb-0 pr-0">
                  <v-data-table
                    :class="$vuetify.breakpoint.smAndDown ? 'leasing-object-table small' : 'leasing-object-table'"
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
                        {{ (item.result_data.even['offer-administrative-payment-per'] * 100).toFixed(2) }}
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
                          parseInt(item.request_data.leasingAmount.replace(/\s/g, '' ))
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                  </v-data-table>
                </v-col>
                <v-col 
                  cols="12" md="4" sm="3" lg="6" 
                  v-if="graphData.result_data.hasOwnProperty('even')"
                  :style="`${$vuetify.breakpoint.xs ? 'display: flex; justify-content: center;' : ''}`">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn 
                        :class="`${$vuetify.breakpoint.xs ? '' : 'mt-5'}`" 
                        v-on="on" :to="{name: 'Редагувати', params: {id: graphData.id, edit: true}}" 
                        width="45" height="45" fab dark color="#d24a43">
                        <v-icon color="white" size="30">mdi-file-find-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>Переглянути графiк в режимi редагування</span>
                  </v-tooltip>
                </v-col>
              </v-row>
            <div class="payout-schedule">
              Графiк виплат
            </div>
            <v-data-table
              v-if="graphData && graphData.result_data && graphData.result_data.even"
              :class="$vuetify.breakpoint.xs ? 'payment-schedule-table small' : 'payment-schedule-table'"
              color="black"
              :headers="tableHeader"
              :items="even"
              :items-per-page="180"
              :hide-default-footer="true"
              dense>
              <template v-slot:item.n="{ item }">
                <span>{{ item.n === 0 ? 'Аванс' : item.n }}</span>
              </template>
              <template v-slot:body.append>
                <div v-show="$vuetify.breakpoint.xs" style="border-left: 4px solid #d24a43;">
                  <div class="total-payment-schedule-header">Всього</div>
                  <tr
                    class="total-payment-schedule">
                    <td class="total-payment-schedule-text">Оплата за авто, грн</td>
                    <td class="total-payment-schedule-value">
                      {{ 
                        parseInt(graphData.result_data.even['total-payment-principal'].toFixed())
                          .toLocaleString()
                          .replace(/,/g, ' ')
                      }}
                    </td>
                  </tr>
                  <tr
                    class="total-payment-schedule">
                    <td class="total-payment-schedule-text">Винагорода лiзингодавця, грн</td>
                    <td class="total-payment-schedule-value">
                      {{ 
                        parseInt(graphData.result_data.even['total-interest'].toFixed()) 
                          .toLocaleString()
                          .replace(/,/g, ' ')
                      }}
                    </td>
                  </tr>
                  <tr
                    class="total-payment-schedule">
                    <td class="total-payment-schedule-text">Сума платежу, грн</td>
                    <td class="total-payment-schedule-value">
                      {{ 
                        parseInt(graphData.result_data.even['total-payment'].toFixed())
                          .toLocaleString()
                          .replace(/,/g, ' ') 
                      }}
                    </td>
                  </tr>
                </div>
                <tr 
                  v-show="!$vuetify.breakpoint.xs"
                  style="color: black; border-left: 8px solid black;" >
                  <td style="text-align: center;">Всього</td>
                  <td></td>
                  <td style="text-align: center;">
                    {{ 
                      parseInt(graphData.result_data.even['total-payment-principal'].toFixed())
                        .toLocaleString()
                        .replace(/,/g, ' ')
                    }}
                  </td>
                  <td style="text-align: center;">
                    {{ 
                      parseInt(graphData.result_data.even['total-interest'].toFixed()) 
                        .toLocaleString()
                        .replace(/,/g, ' ')
                    }}
                  </td>
                  <td style="text-align: center;">
                    {{ 
                      parseInt(graphData.result_data.even['total-payment'].toFixed())
                        .toLocaleString()
                        .replace(/,/g, ' ') 
                    }}
                  </td>
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
                <span v-if="item.interest !== null">
                  {{  
                    parseInt(item.interest.toFixed())
                      .toLocaleString()
                      .replace(/,/g, ' ')
                  }}
                </span>
              </template>
              <template v-slot:item.payment="{ item }">
                <span v-if="item.payment !== null">
                  {{  
                    parseInt(item.payment.toFixed())
                      .toLocaleString()
                      .replace(/,/g, ' ')
                  }}
                </span>
              </template>
              <template v-slot:item.payment_principal="{ item }">
                <span v-if="item['payment-principal'] !== null">
                  {{
                    parseInt(item['payment-principal'].toFixed())
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
            v-show="!$vuetify.breakpoint.xs"
            @click.stop="changeActive($event)" 
            for="tab-1" 
            :style="`${!graphData.result_data.hasOwnProperty('annuity') ? 'background: grey' : ''}`"
            class="label">
            АНУЇТЕТ
          </label>
          <input 
            v-show="!$vuetify.breakpoint.xs"
            @click.stop="changeActive($event)"
            id="tab-1" 
            name="tab" 
            type="radio" 
            value="1" 
            v-model="currentTab"/>
          <div class="content">
            <div style="position: relative;">
              <div v-if="!graphData.result_data.hasOwnProperty('annuity')" class="empty-chart">
              </div>
              <div class="router-link-to-no-calc" :style="$vuetify.breakpoint.xs ? 'width: 90%;' : '60%;'">
                <p style="font-size: 1rem">
                  <span style="position: relative; width: 40px; display: inline-block; left: 0; top: -25px;">
                    <v-icon style="padding: 0 10px 5px 0; position: absolute; left: 0" size="34" color="#d24a43" >mdi-information-outline</v-icon>
                  </span>
                  Даний графiк не було додано при калькуляцii, але ви можете додати його до розрахунку за 
                  <router-link style="color: #d24a43; text-decoration: underline" :to="{name: 'Редагувати', params: {id: graphData.id, edit: true}}"> посиланням</router-link> в роздiлi - графiк платежiв
                </p>
              </div>
            </div>
            <v-card class="view-charts"  v-if="graphData.result_data.hasOwnProperty('annuity')">
              <v-row>
                <v-col cols="12" md="8" sm="9" lg="6" class="pt-0 pb-0">
                  <v-data-table
                    :class="$vuetify.breakpoint.smAndDown ? 'leasing-object-table small' : 'leasing-object-table'"
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
                        {{ 
                          item.result_data.annuity['offer-month-payment']
                            .toLocaleString()
                            .replace(/,/g, ' ')  
                        }}
                      </span>
                    </template>
                    <template v-slot:item.one_time_commission="{ item }">
                      <span v-if="item.result_data.annuity">
                        {{ (item.result_data.annuity['offer-administrative-payment-per'] * 100).toFixed(2) }}
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
                      <span v-if="item.request_data && item.request_data.leasingAmount">
                        {{ 
                          parseInt(item.request_data.leasingAmount.replace(/\s/g, '' ))
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                  </v-data-table>
                </v-col>
                <v-col 
                  cols="12" md="4" sm="3" lg="6" 
                  v-if="graphData.result_data.hasOwnProperty('annuity')"
                  :style="`${$vuetify.breakpoint.xs ? 'display: flex; justify-content: center;' : ''}`">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn 
                        :class="`${$vuetify.breakpoint.xs ? '' : 'mt-5'}`" 
                        v-on="on" :to="{name: 'Редагувати', params: {id: graphData.id, edit: true}}" 
                        width="45" height="45" fab dark color="#d24a43">
                        <v-icon color="white" size="30">mdi-file-find-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>Переглянути графiк в режимi редагування</span>
                  </v-tooltip>
                </v-col>
              </v-row>
              <div class="payout-schedule">
                Графiк виплат
              </div>
              <v-data-table
                v-if="graphData && graphData.result_data && graphData.result_data.annuity"
                :class="$vuetify.breakpoint.xs ? 'payment-schedule-table small' : 'payment-schedule-table'"
                color="black"
                :headers="tableHeader"
                :items="annuity"
                :items-per-page="180"
                :hide-default-footer="true"
                dense>
                <template v-slot:item.n="{ item }">
                  <span>{{ item.n === 0 ? 'Аванс' : item.n }}</span>
                </template>
                <template v-slot:body.append>
                  <div v-show="$vuetify.breakpoint.xs" style="border-left: 4px solid #d24a43;">
                    <div class="total-payment-schedule-header">Всього</div>
                    <tr
                      class="total-payment-schedule">
                      <td class="total-payment-schedule-text">Оплата за авто, грн</td>
                      <td class="total-payment-schedule-value">
                        {{ 
                          parseInt(graphData.result_data.annuity['total-payment-principal'].toFixed())
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </td>
                    </tr>
                    <tr
                      class="total-payment-schedule">
                      <td class="total-payment-schedule-text">Винагорода лiзингодавця, грн</td>
                      <td class="total-payment-schedule-value">
                        {{ 
                          parseInt(graphData.result_data.annuity['total-interest'].toFixed()) 
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </td>
                    </tr>
                    <tr
                      class="total-payment-schedule">
                      <td class="total-payment-schedule-text">Сума платежу, грн</td>
                      <td class="total-payment-schedule-value">
                        {{ 
                          parseInt(graphData.result_data.annuity['total-payment'].toFixed())
                            .toLocaleString()
                            .replace(/,/g, ' ') 
                        }}
                      </td>
                    </tr>
                  </div>
                  <tr 
                    v-show="!$vuetify.breakpoint.xs" 
                    style="color: black; border-left: 8px solid black;">
                    <td style="text-align: center;">Всього</td>
                    <td></td>
                    <td style="text-align: center;">
                      {{ 
                        parseInt(graphData.result_data.annuity['total-payment-principal'].toFixed())
                          .toLocaleString()
                          .replace(/,/g, ' ')
                      }}
                    </td>
                    <td style="text-align: center;">
                      {{ 
                        parseInt(graphData.result_data.annuity['total-interest'].toFixed()) 
                          .toLocaleString()
                          .replace(/,/g, ' ')
                      }}
                    </td>
                    <td style="text-align: center;">
                      {{ 
                        parseInt(graphData.result_data.annuity['total-payment'].toFixed())
                          .toLocaleString()
                          .replace(/,/g, ' ') 
                      }}
                    </td>
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
                  <span v-if="item.interest !== null">
                    {{  
                      parseInt(item.interest.toFixed())
                        .toLocaleString()
                        .replace(/,/g, ' ')
                    }}
                  </span>
                </template>
                <template v-slot:item.payment="{ item }">
                  <span v-if="item.payment !== null">
                    {{  
                      parseInt(item.payment.toFixed())
                        .toLocaleString()
                        .replace(/,/g, ' ')
                    }}
                  </span>
                </template>
                <template v-slot:item.payment_principal="{ item }">
                  <span v-if="item['payment-principal'] !== null">
                    {{
                      parseInt(item['payment-principal'].toFixed())
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
            v-show="!$vuetify.breakpoint.xs"
            @click.stop="changeActive($event)" 
            for="tab-3" 
            :style="`${!graphData.result_data.hasOwnProperty('irregular') ? 'background: grey' : ''}`"
            class="label">
            IНДИВIДУАЛЬНИЙ
          </label>
          <input
            v-show="!$vuetify.breakpoint.xs"
            @click.stop="changeActive($event)"
            id="tab-3" 
            name="tab" 
            type="radio" 
            value="3" 
            v-model="currentTab">
          <div class="content">
            <div style="position: relative;">
              <div v-if="!graphData.result_data.hasOwnProperty('irregular')" class="empty-chart">
              </div>
              <div class="router-link-to-no-calc" :style="$vuetify.breakpoint.xs ? 'width: 90%;' : '60%;'">
                <p style="font-size: 1rem"><span style="position: relative; width: 40px; display: inline-block; left: 0; top: -25px;"><v-icon style="padding: 0 10px 5px 0; position: absolute; left: 0" size="34" color="#d24a43" >mdi-information-outline</v-icon></span>Даний графiк не було додано при калькуляцii, але ви можете додати його до розрахунку за 
                  <router-link style="color: #d24a43; text-decoration: underline" :to="{name: 'Редагувати', params: {id: graphData.id, edit: true}}"> посиланням</router-link> в роздiлi - графiк платежiв
                </p>
              </div>
            </div>
            <v-card  class="view-charts" v-if="graphData.result_data.hasOwnProperty('irregular')">
              <v-row>
                <v-col cols="12" md="8" sm="9" lg="6" class="pt-0 pb-0">
                  <v-data-table
                    :class="$vuetify.breakpoint.smAndDown ? 'leasing-object-table small' : 'leasing-object-table'"
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
                        {{ (item.result_data.irregular['offer-administrative-payment-per'] * 100).toFixed(2) }}
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
                      <span v-if="item.request_data && item.request_data.leasingAmount">
                        {{ 
                          parseInt(item.request_data.leasingAmount.replace(/\s/g, '' ))
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </span>
                    </template>
                  </v-data-table>
                </v-col>
                <v-col 
                  cols="12" md="4" sm="3" lg="6" 
                  v-if="graphData.result_data.hasOwnProperty('irregular')"
                  :style="`${$vuetify.breakpoint.xs ? 'display: flex; justify-content: center;' : ''}`">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn 
                        :class="`${$vuetify.breakpoint.xs ? '' : 'mt-5'}`" 
                        v-on="on" :to="{name: 'Редагувати', params: {id: graphData.id, edit: true}}" 
                        width="45" height="45" fab dark color="#d24a43">
                        <v-icon color="white" size="30">mdi-file-find-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>Переглянути графiк в режимi редагування</span>
                  </v-tooltip>
                </v-col>
              </v-row>
              <div class="payout-schedule">
                Графiк виплат
              </div>
              <v-data-table
                v-if="graphData && graphData.result_data && graphData.result_data.irregular"
                :class="$vuetify.breakpoint.xs ? 'payment-schedule-table small' : 'payment-schedule-table'"
                color="black"
                :headers="tableHeader"
                :items="irregular"
                :items-per-page="180"
                :hide-default-footer="true"
                dense>
                <template v-slot:item.n="{ item }">
                  <span>{{ item.n === 0 ? 'Аванс' : item.n }}</span>
                </template>
                <template v-slot:body.append>
                  <div v-show="$vuetify.breakpoint.xs" style="border-left: 4px solid #d24a43;">
                    <div class="total-payment-schedule-header">Всього</div>
                    <tr
                      class="total-payment-schedule">
                      <td class="total-payment-schedule-text">Оплата за авто, грн</td>
                      <td class="total-payment-schedule-value">
                        {{ 
                          parseInt(graphData.result_data.irregular['total-payment-principal'].toFixed())
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </td>
                    </tr>
                    <tr
                      class="total-payment-schedule">
                      <td class="total-payment-schedule-text">Винагорода лiзингодавця, грн</td>
                      <td class="total-payment-schedule-value">
                        {{ 
                          parseInt(graphData.result_data.irregular['total-interest'].toFixed()) 
                            .toLocaleString()
                            .replace(/,/g, ' ')
                        }}
                      </td>
                    </tr>
                    <tr
                      class="total-payment-schedule">
                      <td class="total-payment-schedule-text">Сума платежу, грн</td>
                      <td class="total-payment-schedule-value">
                        {{ 
                          parseInt(graphData.result_data.irregular['total-payment'].toFixed())
                            .toLocaleString()
                            .replace(/,/g, ' ') 
                        }}
                      </td>
                    </tr>
                  </div>
                  <tr 
                    v-show="!$vuetify.breakpoint.xs" 
                    style="color: black; border-left: 8px solid black;" >
                    <td style="text-align: center;">Всього</td>
                    <td></td>
                    <td style="text-align: center;">
                      {{ 
                        parseInt(graphData.result_data.irregular['total-payment-principal'].toFixed())
                          .toLocaleString()
                          .replace(/,/g, ' ')
                      }}
                    </td>
                    <td style="text-align: center;">
                      {{ 
                        parseInt(graphData.result_data.irregular['total-interest'].toFixed()) 
                          .toLocaleString()
                          .replace(/,/g, ' ')
                      }}
                    </td>
                    <td style="text-align: center;">
                      {{ 
                        parseInt(graphData.result_data.irregular['total-payment'].toFixed())
                          .toLocaleString()
                          .replace(/,/g, ' ') 
                      }}
                    </td>
                  </tr>
                  <span></span>
                </template>
                <template v-slot:item.payment_principal="{ item }">
                  <span v-if="item['payment-principal'] !== null">
                    {{
                      parseInt(item['payment-principal'].toFixed())
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
                <span v-if="item.interest !== null">
                  {{  
                    parseInt(item.interest.toFixed())
                      .toLocaleString()
                      .replace(/,/g, ' ')
                  }}
                </span>
              </template>
              <template v-slot:item.payment="{ item }">
                <span v-if="item.payment !== null">
                  {{  
                    parseInt(item.payment.toFixed())
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
  name: 'Графiки',
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
      { text: 'Термін оплати', value: 'date', align: 'center', sortable: false},
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
      if(data.result_data.hasOwnProperty('annuity')) { this.annuity = data.result_data.annuity.graph}
      if(data.result_data.hasOwnProperty('irregular')) this.irregular = data.result_data.irregular.graph
      if(data.result_data.hasOwnProperty('even')) this.even = data.result_data.even.graph
    },
    changeActive() {
      let tabs = document.querySelectorAll('#chart-diagram .tabs-input')
      setTimeout(() => {
        tabs.forEach(element => element.classList.remove('active'))
        let getValue = document.querySelectorAll('#chart-diagram .tabs-input input')
        getValue.forEach(val => {
          if(val.value == this.currentTab) {
            val.parentNode.classList.add('active')
          }
        })
      }, 150)
    },
    switchGraphName(graph) {
      if(graph === 'annuity' || graph === 'Ануїтет') {return '1'}
      if(graph === 'even' || graph ===  'Класичний') {return '2'}
      if(graph === 'irregular' || graph === 'Індивідуальний') {return '3'}
    },
    switchGraphByNumber(number) {
      if(number === '1') {return 'annuity'}
      if(number === '2') {return 'even'}
      if(number === '3') {return 'irregular'}
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
    this.graphData = this.$route.params.data
    let firstGraphFromArray = Object.keys(this.graphData.result_data).filter(val => {
      if(val !== 'requestId') {
        return this.graphData.result_data[val]
      }
    })
    this.addObjects(this.graphData)
    this.currentTab = this.switchGraphName(firstGraphFromArray[0])
    this.changeActive()
    if(this.$route.params.preview === true) {
      this.currentTab = this.switchGraphName(this.$route.params.graph)
      this.changeActive()
    }
    document.body.scrollTop = 0
  },
  watch: {
    currentTab(val) {
      console.log(val)
    }
  }
}
</script>

<style lang="scss">
.total-payment-schedule-header {
  font-size: 1rem; 
  padding: 13px 0 0 10px; 
  color: #d24a43; 
  font-weight: bold;
}
.tabs-input{
  .v-data-table__mobile-row__cell {
    min-width: 80px;
  }
}
.total-payment-schedule {
  width: 100%; 
  display: flex; 
  justify-content: space-between; 
  font-size: 1rem;
  .total-payment-schedule-text {
    font-weight:bold; 
    border-bottom: none!important; 
    display: flex; 
    align-items: center; 
    min-height: 48px;
  }
  .total-payment-schedule-value {
    border-bottom: none!important; 
    display: flex; 
    align-items: center; 
    min-height: 48px;
    min-width: 90px;
    justify-content: flex-end;
    font-weight: bold;
  }
}
.payment-schedule-table {
  &.small {
    tbody {
      display: flex;
      flex-direction: column;
    }
    tr {
      margin: 10px 0;
    }
    td {
      min-height: 23px;
    }
    thead {
      display: none;
    }
  }
}
.calculator-graph-select {
  fieldset {
    border: 3px solid #808080!important;
  }
  &.active {
    fieldset {
      border: 3px solid #d24a43!important;
    }
  }
}
.payout-schedule {
  border-bottom: 1px solid #e0e0e0;
  font-size: 1.1rem;
  font-weight: bold;
  color: #424242;
  padding-left: 0.5rem;
}

.v-data-table {
  &.leasing-object-table{
    &.small {
      .v-data-table__mobile-row {
        height: auto;
      }
    }
    margin-bottom: 0.7rem;
    tr {
      &:hover {
        background: none!important;
      }
    }
    .v-data-table__mobile-table-row {
      display: flex;
      flex-direction: column;
    }
    .v-data-table__mobile-row {
      min-height: 10px!important;
      height: 20px;
      border-bottom: 1px solid #eeedeb;
      margin: 0 13px 0 13px;
      padding: 0;
      display: flex;
      justify-content: space-between;
      .v-data-table__mobile-row__cell {
        font-weight: bold;
        color: #424242;
        display: inline-block;
        float: right;
        padding-top: 0;
        padding-bottom: 0;
        margin-top: 0;
        margin-bottom: 0;
      }
      .v-data-table__mobile-row__header {
        display: inline-block;
        float: left;
        padding-top: 0;
        padding-bottom: 0;
        margin-top: 0;
        margin-bottom: 0;
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
.router-link-to-no-calc {
  border-radius: 10px; 
  padding: 25px 25px 10px 25px; 
  border: 1px solid darkgrey; 
  backdrop-filter: blur(4px); 
  background: rgba(252, 252, 252, 0.7); 
  position: absolute; 
  left: 50%; 
  top: 22%; 
  transform: translate(-50%, 0%); 
  font-size: 1.5rem; 
  text-align: center;
}
.v-data-table.leasing-object-table {

  .v-data-table__mobile-row {
    min-height: 22px!important;
    min-height: 10px!important;
    height: 20px;
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
    font-size: 1.2rem;
    text-align: center;
    line-height: 2rem;
    color: white;
    padding: 0.33rem 10px;
    margin: 0;
    background: #424242;
  }
  .tabs .tabs-input.active .label {
    color: white;
    background: #d24a43;
    // border-top: 2px solid #424242;
    // box-shadow: 0px -5px 8px 4px rgba(0,0,0,1);
  }

  .tabs .tabs-input {
    width: 50%;
    display: block;
    opacity: 1;
    // transition: opacity 0.3s ease;
  }
  .tabs .tabs-input
  .tabs .tabs-input.active,
  .tabs .tabs-input:hover {
    opacity: 1;
  }
  .tabs-input [type="radio"] {
    opacity: 0;
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
.empty-chart {
  width: 100%; 
  height: 100vh; 
  background-image: url('../../assets/img/empty-graph-backgound.png'); 
  background-size: contain; 
  filter: blur(0.15rem);
}
  
</style>