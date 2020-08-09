<template>
<div class="col-12 pt-0 pl-0 pr-0">
  <v-dialog
    v-model="dialogToSwitchBetweenTypesOfSave"
    max-width="530">
    <v-card>
      <v-card-title style="background: #424242; position: relative" class="white--text">
        <v-btn @click="dialogToSwitchBetweenTypesOfSave = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon color="white" v-text="'mdi-close'"></v-icon></v-btn>
        Оберiть тип збереження
      </v-card-title>
      <v-card-text 
        class="calculator-data__custom-btn-wrapper" 
        :style="`display: flex; ${$vuetify.breakpoint.xs ? 'flex-direction: column; align-items: center;' : 'justify-content: space-around;'} margin-top: 25px;`">
        <span style="display: inline-block; padding: 10px 0;"><v-btn @click="openDialogToSave('email')" dark color="#e94949">Вiдправити на email</v-btn></span>
        <span style="display: inline-block; padding: 10px 0;"><v-btn @click="openDialogToSave('pdf')" dark color="#e94949">Зберегти у форматi .pdf</v-btn></span>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-dialog 
    v-model="dialogToSend"
    max-width="490">
    <v-card class="graphs-to-delete">
      <v-card-title style="background: #424242; position: relative" class="white--text">
        <v-btn @click="dialogToSend = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon color="white" v-text="'mdi-close'"></v-icon></v-btn>
        {{ formatToSave === 'email' ? 'Вiдправити на email' : 'Зберегти'}}
      </v-card-title>
      <v-card-text>
        <div style="margin-top: 23px;">
          <span style="line-height: 2rem; font-size: 0.93rem; color: black;">Оберiть тип графiку</span>
        </div>
        <v-checkbox
          v-if="currentGraphToDownload && currentGraphToDownload.result_data && currentGraphToDownload.result_data.hasOwnProperty('even')"
          value="even"
          v-model="graphName"
          color="red darken-4">
          <template v-slot:label>
            <span :style="graphName == 'even' ? 'color: black;' : ''" class="graph-label-to-download">Класичний</span>
          </template>
        </v-checkbox>
        <v-checkbox
          v-if="currentGraphToDownload && currentGraphToDownload.result_data && currentGraphToDownload.result_data.hasOwnProperty('annuity')"
          v-model="graphName"
          value="annuity"
          color="red darken-4">
          <template v-slot:label>
            <span :style="graphName == 'annuity'? 'color: black;' : ''" class="graph-label-to-download">Ануїтет</span>
          </template>
        </v-checkbox>
        <v-checkbox
          v-if="currentGraphToDownload && currentGraphToDownload.result_data && currentGraphToDownload.result_data.hasOwnProperty('irregular')"
          value="irregular"
          v-model="graphName"
          color="red darken-4">
          <template v-slot:label>
            <span :style="graphName == 'irregular' ? 'color: black;' : ''" class="graph-label-to-download">Iндивiдуальний</span>
          </template>
        </v-checkbox>
        <v-text-field 
          v-if="formatToSave === 'email'"
          class="email-to-send"
          @input="$v.emailToSend.$touch()"
          @blur="$v.emailToSend.$touch()"
          :error-messages="emailToSendErr"
          v-model="emailToSend"
          label="email"
          outlined dense>
        </v-text-field>
        <v-divider class="mt-0"></v-divider>
        <v-btn 
          style="border-radius: 0; text-transform: capitalize; border-color: rgb(224, 77, 69); font-size: 1rem; color: white;"
          class="send-graph-btn"
          @click="sendGraph()" 
          color="#e04d45"
          :loading="loading"
          :disabled="graphName.length === 0"
          >
          {{ formatToSave === 'email' ? 'Вiдправити' : 'Зберегти' }}
        </v-btn>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-dialog
    v-model="leasingApplicationForm"
    max-width="550">
    <v-card>
      <v-card-title style="background: #424242; position: relative" class="white--text">
        Заявка на лiзинг
        <v-btn @click="leasingApplicationForm = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon v-text="'mdi-close'" color="white"></v-icon></v-btn>
      </v-card-title>
      <div class="complete-reg-form__title title">
        <div class="complete-reg-form__title-logo"></div>
      </div>
      <v-card-text class="pb-0">

        <v-row>
          <v-col cols="12" class="pt-0 pb-0 calculator-data-graph">
            <div style="font-size: 0.9rem; font-weight: bold; letter-spacing: 0.03rem">Оберiть тип графiку платежiв</div>
            <v-radio-group 
              v-model="selectedGraph" 
              :error-messages="selectedGraphErr"
              :column="$vuetify.breakpoint.xs" :dense="$vuetify.breakpoint.xs">
              <v-radio class="pl-2" v-if="graphType && graphType.result_data && graphType.result_data && graphType.result_data.hasOwnProperty('even')" label="Класичний" value="even"></v-radio>
              <v-radio class="pl-2" v-if="graphType && graphType.result_data && graphType.result_data && graphType.result_data.hasOwnProperty('annuity')"  label="Ануїтет" value="annuity"></v-radio>
              <v-radio class="pl-2" v-if="graphType && graphType.result_data && graphType.result_data && graphType.result_data.hasOwnProperty('irregular')"  label="Індивідуальний" value="irregular"></v-radio>
            </v-radio-group>
          </v-col>
          <v-row>
            <v-col 
              cols="12" md="6" 
              v-if="clientTypeId === 2 && leasingApplicationForm"
              class="pt-0 pb-0">
              <v-text-field
                @input="parseToInt('edrpou');
                  $v.legalInfo.edrpou.$touch(); getEdropu()"
                @blur="$v.legalInfo.edrpou.$touch();"
                v-model="legalInfo.edrpou"
                :error-messages="edrpouErr"
                v-mask="'########'"
                id="edrpou"
                max="8"
                label="ЄДРПОУ"
                dense outlined>
                <template v-slot:append>
                  <v-btn 
                    :loading="edrpouLoading"
                    icon>
                  </v-btn>
                </template>
              </v-text-field>
            </v-col>
            <v-col 
              cols="12" md="6" 
              v-if="clientTypeId === 2 && leasingApplicationForm"
              class="pt-0 pb-0">
              <v-text-field
                v-model="legalInfo.companyName"
                :error-messages="companyNameErr"
                label="Назва компанії"
                dense outlined readonly>
                <template v-slot:append>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-icon 
                        v-on="on"
                        style="cursor: pointer;" 
                        color="#d24a43" 
                        v-text="'mdi-information-outline'">
                      </v-icon>
                    </template>
                    <span>Поле заповнюється автоматично за вказаним ЄДРПОУ</span>
                  </v-tooltip>
                </template>
              </v-text-field>
            </v-col>
            <v-col 
              cols="12" md="6" 
              v-if="clientTypeId === 1 && leasingApplicationForm"
              class="pt-0 pb-0">
              <v-text-field
                @input="parseToInt('inn');
                  $v.legalInfo.inn.$touch()"
                @blur="$v.legalInfo.inn.$touch()"
                v-model="legalInfo.inn"
                :error-messages="innErr"
                id="inn"
                max="10"
                v-mask="'##########'"
                label="Iдентифiкацiйний код"
                dense outlined>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="lastName"
                @blur="$v.lastName.$touch()"
                @input="$v.lastName.$touch()" 
                :error-messages="lastNameErr"
                label="Прiзвище"
                dense outlined>
              </v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="firstName"
                @blur="$v.firstName.$touch()"
                @input="$v.firstName.$touch()" 
                :error-messages="firstNameErr"
                label="Им`я"
                dense outlined>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="patronymic"
                @blur="$v.patronymic.$touch()"
                @input="$v.patronymic.$touch()" 
                :error-messages="patronymicErr"
                label="Побатьковi"
                dense outlined>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="phone"
                @blur="$v.phone.$touch()"
                @input="$v.phone.$touch();
                  applyMask()" 
                id="number"
                :error-messages="phoneErr"
                label="Телефон"
                dense outlined>
              </v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pt-0 pb-0">
              <v-text-field
                v-model="email"
                @blur="$v.email.$touch()"
                @input="$v.email.$touch()" 
                :error-messages="emailErr"
                label="email"
                dense outlined>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="pt-0 pb-0">
              <input id="insurance" class="toggle" type="checkbox">
              <label for="insurance" class="lbl-toggle" style="background: white; color: #5f6368; padding-top: 0rem; margin-bottom: 0;">Документи</label>
                <div class="collapsible-content">
                  <div class="content-inner">
                    <div class="document-list" v-if="clientTypeId === 2">
                      <div 
                        v-for="(item, key) in legalDocs"
                        :key="key">
                        <span :style="documentUrls[item.prop] ? 'color: black;' : ''">{{ 
                          documentUrls[item.prop] && documentUrls[item.prop].text 
                            ? documentUrls[item.prop].text + ' ' + `(${(documentUrls[item.prop].size / 1000).toFixed(2)} - kb)` 
                            : item.text 
                          }}
                        </span>&nbsp;
                        <v-btn
                          v-show="!documentUrls[item.prop]"
                          @click="$refs[item.prop][0].click()" 
                          icon small>
                          <v-icon small v-text="'mdi-plus-thick'"></v-icon>
                        </v-btn>
                        <div 
                          v-if="documentUrls[item.prop]"
                          style="display: inline-block; min-width: 15px; position: relative;">
                          <v-tooltip top>
                            <template v-slot:activator="{ on }">
                              <v-icon 
                                style="position: absolute; left: -10px; top: -15px;" 
                                v-text="'mdi-information-variant'" 
                                color="#d24a43"
                                v-on="on"
                                small>
                              </v-icon>
                            </template>
                            <span>{{ item.text }}</span>
                          </v-tooltip>
                        </div>
                        <v-btn
                          v-show="documentUrls[item.prop]"
                          @click="deleteDoc(item.prop)" 
                          color="#d24a43"
                          class="document-delete-icon"
                          icon small>
                          <v-icon small v-text="'mdi-close'"></v-icon>
                        </v-btn>
                      </div>
                      <!-- <v-btn @click="test()">test</v-btn> -->
                    </div>
                    <div class="document-list" v-if="clientTypeId === 1">
                      <div 
                        v-for="(item, key) in personDocs"
                        :key="key">
                        <span :style="documentUrls[item.prop] ? 'color: black;' : ''">{{ 
                          documentUrls[item.prop] && documentUrls[item.prop].text 
                            ? documentUrls[item.prop].text + ' ' + `(${(documentUrls[item.prop].size / 1000).toFixed(2)} - kb)` 
                            : item.text 
                          }}
                        </span>&nbsp;
                        <v-btn
                          v-show="!documentUrls[item.prop]"
                          @click="$refs[item.prop][0].click()" 
                          icon small>
                          <v-icon small v-text="'mdi-plus-thick'"></v-icon>
                        </v-btn>
                        <div 
                          v-if="documentUrls[item.prop]"
                          style="display: inline-block; min-width: 15px; position: relative;">
                          <v-tooltip top>
                            <template v-slot:activator="{ on }">
                              <v-icon 
                                style="position: absolute; left: -10px; top: -15px;" 
                                v-text="'mdi-information-variant'" 
                                color="#d24a43"
                                v-on="on"
                                small>
                              </v-icon>
                            </template>
                            <span>{{ item.text }}</span>
                          </v-tooltip>
                        </div>
                        <v-btn
                          v-show="documentUrls[item.prop]"
                          @click="deleteDoc(item.prop)" 
                          color="#d24a43"
                          class="document-delete-icon"
                          icon small>
                          <v-icon small v-text="'mdi-close'"></v-icon>
                        </v-btn>
                      </div>
                    </div>
                    <div v-if="clientTypeId === 2">
                      <input
                        v-for="(item, key) in legalDocs"
                        :key="key"
                        style="visibility: hidden; position: absolute; left: 0; top: 0;"
                        type="file"
                        accept="image/x-png,image/gif,image/jpeg,image/jpg,image/svg+xml,application/msword,application/vnd.ms-excel,application/pdf"
                        :ref="item.prop"
                        :class="item.prop"
                        @change="listenFileInput(item.prop)">
                    </div>
                    <div v-if="clientTypeId === 1">
                      <input
                        v-for="(item, key) in personDocs"
                        :key="key"
                        style="visibility: hidden; position: absolute; left: 0; top: 0;"
                        type="file"
                        accept="image/x-png,image/gif,image/jpeg,image/jpg,image/svg+xml,application/msword,application/vnd.ms-excel,application/pdf"
                        :ref="item.prop"
                        :class="item.prop"
                        @change="listenFileInput(item.prop)">
                    </div>
                  </div>
                </div>
            </v-col>
          </v-row>
        </v-row>
        <v-row>
          <v-col class="d-flex justify-center pb-6">
            <span>
              <v-btn @click="submit()" 
                  :loading="loading"
                  class="d-block white--text" 
                  color="grey darken-3">
                Вiдправити заявку
              </v-btn>
            </span>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-dialog
    v-model="deleteCalculationDialog"
    max-width="420">
    <v-card>
      <v-card-title style="color: white; background: #424242; position: relative">
        Видалення
        <v-btn @click="deleteCalculationDialog = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon v-text="'mdi-close'" color="white"></v-icon></v-btn>
      </v-card-title>
      <v-card-text>
        <div style="line-height: 1.5rem; font-size: 1.15rem; padding: 35px 10px 25px 10px; color: black">
          Розрахунок буде видалено назавжди. <b style="color: black;">Продовжити?</b>
        </div>
        <div class="d-flex justify-space-between">
          <span><v-btn @click="deleteCalculation()" dark color="#e94949" :loading="btnLoading">Так</v-btn></span>
          <span><v-btn @click="deleteCalculationDialog = false" dark color="#333333">Нi</v-btn></span>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
  <v-card class="pb-4" min-height="300" elevation="12">
    <v-card-text class="d-block grey darken-3 white--text" style="font-size: 1.25rem">
      <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-calculator-variant'"></v-icon>
      Калькулятор лiзингу
    </v-card-text>
    <v-card-title
      :class="`calculator-custom-title pb-0 pt-7 ${$vuetify.breakpoint.xs ? 'd-flex justify-center' : ''}`"
      :style="`transition: all 0.5s; opacity: ${!loading ? '1' : '0'}`">
        <v-btn
          to="/calculator/new"
          color="#e65048" 
          dark>
          Новий розрахунок
        </v-btn>
        <v-spacer v-if="!$vuetify.breakpoint.xs"></v-spacer>
        <v-text-field
          v-if="!$vuetify.breakpoint.xs"
          v-show="tableDataPresent"
          color="black"
          v-model="search"
          append-icon="mdi-magnify"
          label="Пошук"
          single-line
          hide-details>
        </v-text-field>
    </v-card-title>
    <v-progress-linear
      :height="3"
      :active="loading"
      :indeterminate="loading"
      absolute
      top
      color="red lighten-1"
    ></v-progress-linear>
    <v-card-text
      v-if="!loading && !tableDataPresent"
      absolute
      style="font-size: 1.25rem; padding-top: 25px;"
      class="text-center">
      (Iсторiя розрахункiв порожня)
    </v-card-text>
    <v-card-text 
      v-show="tableDataPresent" 
      class="calculations-table">
      <v-card-text 
        :style="`${$vuetify.breakpoint.xs ? 'font-size: 1.1rem; padding-bottom: 0;' : 'font-size: 1.25rem'}`" 
        :class="`black--text d-block text-center ${$vuetify.breakpoint.xs ? '' :'mb-8 mt-3'} `">
        Iсторiя розрахункiв
      </v-card-text>
      <v-data-table
        :search="search"
        color="black"
        :headers="tableHeader"
        :items="tabledata"
        :items-per-page="10"
        :custom-sort="customSort"
        :class="`elevation-1 ${$vuetify.breakpoint.xs ? 'leasing-calculator-data small' : 'leasing-calculator-data'}`">
        <template v-slot:item.sendGraph>
          <span style="white-space: nowrap">{{ $store.state.user.agent.ab_size }}</span>
        </template>
        <template v-slot:item.request_data="{ item }">
          <span style="white-space: nowrap">
            {{ 
              parseInt(item.request_data.leasingAmount.replace(/\s/g, '' ))
                .toLocaleString()
                .replace(/,/g, ' ')
            }}
          </span>
        </template>
        <template v-slot:item.updated_at="{ item }">
          <span style="white-space: nowrap">
            {{ item.updated_at.substring(0, 10) }}
          </span>
        </template>
        <!-- <template v-slot:item.leasingObjectType.label="{ item }">
          <span>{{ item.request_data.leasingObjectType.label}}</span>
        </template> -->
        <template v-slot:item.actions="{ item }">
          <div style="display: flex; justify-content: center">
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click="toEdit(item.id)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="grey darken-2"
                      >
                      mdi-pencil-outline
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Редагувати</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn
                    @click="toDetail(item.id)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="grey darken-2"
                      >
                      mdi-file-find-outline
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Переглянути</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click="openForm(item)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="grey darken-2">
                      mdi-file-document-edit-outline
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Подати заявку на лiзинг</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click="openDeleteCalculationDialog(item.id)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="grey darken-2"
                      >
                      mdi-delete-forever-outline
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Видалити</span>
            </v-tooltip>
            <v-tooltip bottom>
              <!-- <template #activator="{ on }">
                <span>
                  <v-btn 
                    @click="openDialogToSave(item, 'email')"
                    v-on="on"
                    icon>
                    <v-icon
                      color="grey darken-2"
                      >
                      mdi-email-send-outline
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Вiдправити на пошту</span>  -->
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on }">
                <span>
                  <!-- @click="openDialogToSave(item, 'pdf')" -->
                  <v-btn 
                    @click="openDialogToSwitchBetweenTypesOfSave(item)"
                    v-on="on"
                    icon>
                    <v-icon
                      color="grey darken-2">
                      mdi-download
                    </v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Зберегти розрахунок</span>
            </v-tooltip>
          </div>
        </template>
      </v-data-table>
      <!-- <v-btn @click="sendGraph()">sendGraph</v-btn> -->
    </v-card-text>
  </v-card>
</div>
</template>

<script>
import selectItems from './selectItems.js'
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'
import axios from 'axios'
import { saveAs } from 'file-saver'

export default {
  mixins: [validationMixin],
  data:() => ({
    /* v-dialog data  */
    rules: [
      value => {
        if(value.length === 0) return true
        if(value.every(val => val.size < 5000000)) return true
        return 'Розмiр документу не повинен перевищувати 5 MB!'
      }
    ],
    dialogToSend: false,
    graphName: [],
    currentGraphToDownload: null,
    formatToSave: null,
    emailToSend: null,
    graphObj: null,
    dialogToSwitchBetweenTypesOfSave: false,

    select: selectItems,
    leasingApplicationForm: false,
    calculationToDelete: null,
    commonErr: ['Обов`язкове поле'],
    pasteEvent: false,
    loading: false,

    legalDocs: [
      {text: 'Копія свідоцтва про державну реєстрацію та / або виписка з ЄДР', prop: 'state_registration_certificate'},
      {text: 'Статут', prop: 'regulations'},
      {text: 'Баланс Ф1 та Ф2', prop: 'balance'},
      {text: 'Протокол засновників про обрання керівника (підписувача)', prop: 'protocol'},
      {text: 'Наказ про призначення керівника (підписувача)', prop: 'order'},
      {text: 'Паспорт / ID-карта керівника (підписувача)', prop: 'passport'},
      {text: 'Довідка про присвоєння ІПН керівника (підписувача)', prop: 'taxNumber'},
    ],
    personDocs: [
      {text: 'Паспорт громадянина України / ID-карта', prop: 'passport'},
      {text: 'Довідка про присвоєння ІПН', prop: 'taxNumber'},
      {text: 'Довідка про заробітну плату', prop: 'salary_certificate'},
      {text: 'Паспорт дружини (чоловіка) позичальника', prop: 'relatives_passport'},
    ],
    // creditPayment: null,
    selectedGraph: null,

    docs: [],
    resultDataGraphs: null,

    listItem: null,

    agentId: null,
    calculationId: null,
    clientTypeId: null,
    lastName: null,
    firstName: null,
    patronymic: null,
    // region: null,
    phone: null,
    email: null,
    leasingObject: null,
    advance: null,
    leasingTerm: null,
    leasingAmount: null,
    graphType: null,
    legalInfo: {
      // creditPayment: null,
      inn: null,
      // monthlyIncome: null,
      // acquisitionTargetId: null,
      edrpou: null,
      companyName: null,
      // currencyBalance: null,
      // equity: null,
      // balances: null, // - waiting... (пока не отправляй)
    },
    documentUrls: {},
    deleteCalculationDialog: false,
    edrpouLoading: false,
    _token: null,

    tableHeader: [
      { text: 'Код розрахунку', value: 'request_id', align: 'start'},
      { text: 'Дата', value: 'updated_at', align: 'center' },
      { text: 'Тип ПЛ', value: 'request_data.leasingObjectType.label', align: 'center' },
      { text: 'Марка', value: 'request_data.leasedAssertMark.name', align: 'center'},
      { text: 'Модель', value: 'request_data.leasedAssertModel.name', align: 'center' },
      { text: 'Цiна, грн', value: 'request_data', align: 'center' },
      // { text: 'АВ, %', value: 'sendGraph', align: 'center' },
      { text: 'Дiї', value: 'actions', align: 'center', sortable: false },
    ],
    tabledata: [],
    search: '',
    attachedFiles: null,
    btnLoading: false,
    showDocumentAttachment: false
  }),
  validations() {
    return this.validationRules
  },
  computed: {
    validData() {
      return !this.$v.$invalid
    },
    validationRules() {
      let validateObj = {}
      this.formatToSave === 'email'
        ? validateObj = {emailToSend: { email, required }}
        : false
      this.clientTypeId === 1 && this.leasingApplicationForm
        ? validateObj = Object.assign({},
          this.commonRules,
          this.individualPerson
        )
        : this.clientTypeId === 2 && this.leasingApplicationForm
          ? validateObj = Object.assign({},
            this.commonRules,
            this.legalPerson
          )
          : false
      return validateObj 
    },
    commonRules() {
      return {
        selectedGraph: { required },
        lastName: { required },
        firstName: { required },
        patronymic: { required },
        phone: { 
          minLength: value => {
            if(value == null) return false
            return value.replace(/[^\d]/g, '').length === 12
          },
          required
        },
        email: { email, required },
        // region: { required },
        // creditPayment: { required },
      }
    },
    individualPerson() {
      return {
        legalInfo: {
          inn: { 
            minLength: value => {
              if(value == null) return false
              return value.length === 10
            }
           }, 
          // monthlyIncome: { required },
          // acquisitionTargetId: { required },
        }
      }
    },
    legalPerson() {
      return {
        legalInfo: {
          edrpou: { 
            required,
            minLength: value => {
              if(value == null) return false
              return value.length === 8
            }
          },
          companyName: { required },
          // currencyBalance: { required },
          // equity: { required },
        }
      }
    },
    isLegalPerson() {
      return this.clientTypeId === 2
    },
    /* error handlers */
    lastNameErr() {
      if (!this.$v.lastName) return
      if (!this.$v.lastName.$error) return
      return this.commonErr
    },
    selectedGraphErr() {
      if (!this.$v.selectedGraph) return
      if (!this.$v.selectedGraph.$error) return
      return this.commonErr
    },
    firstNameErr() {
      if (!this.$v.firstName) return
      if (!this.$v.firstName.$error) return
      return this.commonErr
    },
    patronymicErr() {
      if (!this.$v.patronymic) return
      if (!this.$v.patronymic.$error) return
      return this.commonErr
    },
    emailToSendErr() {
      let errors = []
      if (!this.$v.emailToSend || !this.$v.emailToSend.$error) return
      !this.$v.emailToSend.required && errors.push('Вкажiть ваш email')
      !this.$v.emailToSend.email && errors.push('Невiрний email')
      return errors
    },
    // regionErr() {
    //   if (!this.$v.region) return
    //   if (!this.$v.region.$error) return
    //   return this.commonErr
    // },
    phoneErr() {
      if (!this.$v.phone) return
      if (!this.$v.phone.$error) return
      return ['Невiрний номер']
    },
    emailErr() {
      if (!this.$v.email) return
      if (!this.$v.email.$error) return
      return ['Невiрний email']
    },
    // creditPaymentErr() {
    //   if (!this.$v.creditPayment) return
    //   if (!this.$v.creditPayment.$error) return
    //   return this.commonErr
    // },
    innErr() {
      if (!this.$v.legalInfo.inn) return
      if (!this.$v.legalInfo.inn.$error) return
      return ['Повинно бути 10 цифр']
    },
    // monthlyIncomeErr() {
    //   if (!this.$v.legalInfo.monthlyIncome) return
    //   if (!this.$v.legalInfo.monthlyIncome.$error) return
    //   return this.commonErr
    // },
    // acquisitionTargetIdErr() {
    //   if (!this.$v.legalInfo.acquisitionTargetId) return
    //   if (!this.$v.legalInfo.acquisitionTargetId.$error) return
    //   return this.commonErr
    // },
    edrpouErr() {
      if (!this.$v.legalInfo.edrpou) return
      if (!this.$v.legalInfo.edrpou.$error) return
      return ['Повинно бути 8 цифр']
    },
    companyNameErr() {
      if (!this.$v.legalInfo.companyName) return
      if (!this.$v.legalInfo.companyName.$error) return
      return this.commonErr
    },
    // currencyBalanceErr() {
    //   if (!this.$v.legalInfo.currencyBalance) return
    //   if (!this.$v.legalInfo.currencyBalance.$error) return
    //   return this.commonErr
    // },
    // equityErr() {
    //   if (!this.$v.legalInfo.equity) return
    //   if (!this.$v.legalInfo.equity.$error) return
    //   return this.commonErr
    // },
    legalEmail() {
      return ''
    },
    legalPhone() {
      return ''
    },

    user() {
      return this.$store.state.user.agent
    },
    userData() {
      return Object.keys(this.$store.state.user.agent).length > 0
    },
    tableDataPresent() {
      return this.tabledata.length > 0
    },
  },
  methods: {
    getEdropu() {
      if(!this.$v.legalInfo.edrpou.$invalid && !this.edrpouLoading){
        this.edrpouLoading = true
        axios
          .get(`https://open-data-332145.herokuapp.com/get-company-name/${this.legalInfo.edrpou}`)
          .then(response => {
            console.log(response)
            this.edrpouLoading = false
            this.legalInfo.companyName = response.data.companyName
          })
          .catch(error => {
            console.log(error.response)
            this.edrpouLoading = false
            this.$notify({
              group: 'error',
              title: `Помилка - ${error.response.status}`,
              text: `${error.response.data.message || error.response.data.errors[0].msg}`,
            })
          })
          // .get(`/leasing-reqeust/company/${this.legalInfo.edrpou}`)
          // .then(response => {
          //   this.edrpouLoading = false
          //   if(response.status === 200 && response.data.companyShortName) {
          //     this.legalInfo.companyName = response.data.companyShortName
          //   } else if (response.data.status !== 200) {
          //     this.getEdrpouErrorHandler(response.data.status)
          //   }
          // })
          // .catch(error => {
          //   this.$catchStatus(error.response.status)
          //   console.log(error.response)
          //   this.getEdrpouErrorHandler(error.response.status)
          //   this.edrpouLoading = false
          // })
      } else return
    },
    getEdrpouErrorHandler(status) {
      if(status === 403 || status === 503) {
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Зверніться в технічну підтримку`,
        })
      } else if (status === 404) {
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Компанію не знайдено`,
        })
      } else {
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Зверніться в технічну підтримку`,
        })
      }
    },
    deleteDoc(property) {
      this.$delete(this.documentUrls, property)
      let el = document.querySelector(`.${property}`)
      el.value = null
    },
    async uploadDoc(document, selector) {
      let formData = new FormData()
      formData.append('doc', document)
      axios
        .post('/leasing-reqeust/document/upload', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
          this.documentUrls = Object.assign({}, this.documentUrls, {[selector]: {url: response.data.url, text: document.name, size: document.size}})
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
          this.$notify({
            message: 'Помилка',
            type: 'error',
          })
        })
    },
    async listenFileInput(selector) {
      let file = document.querySelector(`.${selector}`).files[0]
      if(file.size <= 5242880) {
        this.uploadDoc(file, selector)
      } else {
        this.$notify({
            group: 'error',
            title: 'Помилка розiр файлу не повинен перевищувати 5 mb',
            text: ``,
          })
        document.querySelector(`.${selector}`).value = null
        return
      }
    },
    openDialogToSwitchBetweenTypesOfSave(item) {
      this.graphObj = item
      this.dialogToSwitchBetweenTypesOfSave = true
    },
    openDialogToSave(format){
      this.formatToSave = format
      this.dialogToSend = true
      this.currentGraphToDownload = this.graphObj
      console.log(this.currentGraphToDownload)
    },
    openDeleteCalculationDialog(id) {
      this.deleteCalculationDialog = true
      this.calculationToDelete = id
    },
    deleteCalculation() {
      this.btnLoading = true
      axios
        .delete(`/calculation/delete/${this.calculationToDelete}`)
        .then(() => {
          this.$notify({
            group: 'success',
            title: 'Розрахунок видалено',
            text: '',
          })
          this.btnLoading = false
          this.getUserCalculations()
          setTimeout(() => {
            this.deleteCalculationDialog = false
          }, 800)
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          this.$notify({
            group: 'error',
            title: `Помилка - ${error.response.status}`,
            text: `${error.response.data.message}`,
          })
          console.log(error.response)
          this.btnLoading = false
        })
    },
    customSort(items) {
      items
        .sort((a, b) => {
          return new Date(b.updated_at) - new Date(a.updated_at)
      })
      return items
    },
    object() {
      return {
        agent_id: this.agentId,
        calculation_id: this.calculationId,
        client_type_id: this.clientTypeId,
        last_name: this.lastName,
        first_name: this.firstName,
        patronymic: this.patronymic,
        // region: this.region,
        phone: this.phone,
        email: this.email,
        leasing_object: this.leasingObject,
        advance: this.advance,
        leasing_term: this.leasingTerm,
        leasing_amount: this.leasingAmount,
        graph_type: this.selectedGraph,
        documents: this.documentUrls,
        legal_info: {
          // creditPayment: this.creditPayment,

          inn: this.legalInfo.inn,
          // monthlyIncome: this.legalInfo.monthlyIncome,
          // acquisitionTargetId: this.legalInfo.acquisitionTargetId,
          
          edrpou: this.legalInfo.edrpou,
          company_name: this.legalInfo.companyName,
          // currencyBalance: this.legalInfo.currencyBalance,
          // equity: this.legalInfo.equity,
          // balances: this.legalInfo.balances, // - waiting... (пока не отправляй)
        },
        _token: this._token
      }
    },
    clearObject() {
      this.docs = []
      // this.creditPayment = null,
      this.selectedGraph = null,
      this.resultDataGraphs = null,
      this.documentUrls = {},
      this.lastName = null,
      this.firstName = null,
      this.patronymic = null,
      // this.region = null,
      this.phone = null,
      this.email = null,
      this.legalInfo = {
        // creditPayment: null,
        inn: null,
        // monthlyIncome: null,
        // acquisitionTargetId: null,
        edrpou: null,
        companyName: null,
        // currencyBalance: null,
        // equity: null,
      }
    },
    applyMask() {
			const el = document.getElementById('number')
			const event = new Event('input', {bubbles: true})
			const mask = '+38 (0##) ### - ## - ##'
			const sign = '#'

			const numLengthRe = /[^#\d+]/g
			const notDigit = /[^\d]/g

			const firstIndex = mask.indexOf(sign)
			const countryCode = mask.slice(0, firstIndex)
			const numLength = mask.slice(firstIndex).replace(numLengthRe, '').length
			const number = this.phone.replace(countryCode, '').replace(numLengthRe, '')
			const cCpresent = this.phone.indexOf(countryCode)

			let splitMask = mask.split('')
			let indexes = []

			splitMask.forEach((val, i) => {
				val === sign ? indexes.push(i) : false
			})
			let fillMask = (val) => {
        val.split('').forEach((val, i) => {
            if(this.phone[6] == 0) {
              this.phone = this.phone.substr(0, 6) + this.phone.substr(6) 
            } else {
              indexes[i] ? splitMask[indexes[i]] = val : false
            }
          })
			}
			if(number.length >= numLength && cCpresent !== -1 && this.pasteEvent) {
				fillMask(
					this.phone
						.replace(countryCode, '')
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if(number.length >= numLength && cCpresent === -1) {
				fillMask(
					this.phone
						.replace(numLengthRe, '')
						.slice(number.length - numLength)
				)
			} else if (this.phone.length > 1){
				fillMask(
					this.phone
						.slice(firstIndex)
						.replace(notDigit, '')
				)
			} else if (this.phone.length <= 1) {
				fillMask(
					this.phone
						.replace(notDigit, '')
				)
			}
			const joinMask = splitMask.join('').replace(/[^\d]+$/, '')
			if(el.value !== joinMask) {
				el.value = joinMask
				el.setSelectionRange(splitMask.indexOf(sign), splitMask.indexOf(sign))
				el.dispatchEvent(event)
			}
			this.pasteEvent = false
		},
    parseToInt(id) {
      let input = new Event('input', {bubbles: true})
      let el = document.getElementById(id)
      let value = el.value
      let intVal = el.value.replace(/[^\d]/g, '')
      if(value !== intVal) {
        el.value = intVal
        el.dispatchEvent(input)
      }
    },
    openForm(item) {
      if(item.is_send_request === 1) {
        this.$notify({
            group: 'warning',
            title: '',
            text: 'Заявку на лізинг з цього розрахунку вже подано',
          })
        return
      }
      this.graphType = this.getGraphById(item.id)[0]
      this.resultDataGraphs = Object.keys(this.graphType.result_data)
        .filter(val => {
          if(val !== 'requestId') {
            return this.graphType.result_data[val]
          }
        })
      if(this.resultDataGraphs && this.resultDataGraphs.length === 1) {
          this.selectedGraph = this.resultDataGraphs[0]
      }
      this.getDefaultProperties()
      this.leasingApplicationForm = true
    },
    submit() {
      !this.$v.$invalid
        ? this.sendRequest()
        : this.highlightErrors()
    },
    sendRequest() {
      this.loading = true
      let object = this.object()
      axios.
        post('/leasing-reqeust/create', this.requestObj(object))
        .then(() => {
          this.$notify({
            group: 'success',
            title: 'Заявку успiшно вiдправленно',
            text: '',
          })
          this.loading = false
          this.getUserCalculations()
          setTimeout(() => {
            this.clearObject()
            this.$v.$reset()
            this.leasingApplicationForm = false
          }, 5000);
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
          this.loading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
        })
    },
    requestObj(object) {
      let finalObj = null
      if(Object.keys(this.documentUrls).length === 0 && this.documentUrls.constructor === Object) {
        delete object.documents
      }
      this.isLegalPerson === false
        ? finalObj = this.deleteLegalFields(object)
        : finalObj = this.deleteIndividualFields(object)
      return finalObj
    },
    deleteIndividualFields(object) {
      if(object.phone === null || object.phone === 'undefined') delete object.phone
      if(object.email === null || object.email === 'undefined') delete object.email
      delete object.legal_info.inn
      // delete object.legalInfo.monthlyIncome
      // delete object.legalInfo.acquisitionTargetId
      return object
    },
    deleteLegalFields(object) {
      delete object.legal_info.edrpou,
      delete object.legal_info.companyName
      // delete object.legalInfo.currencyBalance
      // delete object.legalInfo.equity
      return object
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
    toEdit(id) {
      this.$router.push({name: 'Редагувати', params: {id: id, edit: true}})
    },

    toDetail(id) {
      let graphs = this.getGraphById(id)[0]
      this.$router.push({name: 'Графiки ', params: {data: graphs}})
    },
    getGraphById(id) {
      return this.$store.state.graphs
        .filter(val => val.id === id)
    },
    getDefaultProperties() {
      this.agentId = this.$store.state.user.agent.id
      this.calculationId = this.graphType.id
      this.leasingObject = `${this.graphType.request_data.leasedAssertMark.name} ${this.graphType.request_data.leasedAssertModel.name}`
      this.clientTypeId = this.graphType.request_data.leasingClientType
      this.advance = this.graphType.request_data.advance
      this.leasingTerm = this.graphType.request_data.leasingTerm
      this.leasingAmount = this.graphType.request_data.leasingAmount
      this._token = this.getCsrf()
    },

    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    getUserCalculations() {
      this.loading = true
      this.tabledata = []
      if(this.userData){
        const agentId = this.$store.state.user.agent.id
        axios
          .get(`calculations/agent/${agentId}`)
          .then(response => {
            this.loading = false
            if(response.data.length > 0)  {
              this.tabledata = response.data
              console.log(this.tabledata)
              this.$store.commit('addGraph', response.data)
            } else {
              this.tabledata = []
            }
          })
          .catch(error => {
            this.$catchStatus(error.response.status)
            this.loading = false
            this.$notify({
              group: 'error',
              title: 'Помилка',
              text: `${error.response.status} \n ${error.response.data.message}`,
            })
          })
        }
    },
    sendGraph() {
      let graphs = this.currentGraphToDownload.result_data
      let graph = graphs[Object.keys(graphs)[0] !== 'requestId' ? Object.keys(graphs)[0] : Object.keys(graphs)[1]]
      let calcData = this.currentGraphToDownload.request_data
      let rootCalcData = this.currentGraphToDownload

      let dataToSave = {
        request_id: this.currentGraphToDownload.request_id,
        agentId: this.$store.state.user.agent.id,
        mark: calcData.leasedAssertMark.name,
        model: calcData.leasedAssertModel.name,
        leasingObjType: calcData.leasingObjectType.label,
        price: parseInt(calcData.leasingAmount.replace(/\s/g, '' )),
        term: calcData.leasingTerm,
        advance: calcData.advance,
        prepaid: graph['offer-advance'],
        offerBrutto: graph['offer-price-brutto'],
        oneTimeComission: (graph['offer-administrative-payment-per'] * 100).toFixed(2),
        currency: calcData.currency,
        leasingRest: graph['offer-rest'],
        requestId: rootCalcData.request_id,
        date: this.currentGraphToDownload.updated_at.substring(0, 10),
        _token: this.getCsrf()
      }

      if(this.formatToSave === 'email') dataToSave.email = this.emailToSend
      
      this.graphName
        .forEach(val => {
          dataToSave[val] = graphs[val]
          dataToSave[val].agg = {}
          dataToSave[val].agg['avg'] = graphs[val]['offer-month-payment']
          dataToSave[val].agg['payment-principal'] = graphs[val]['total-payment-principal']
          dataToSave[val].agg['interest'] = graphs[val]['total-interest']
          dataToSave[val].agg['payment'] = graphs[val]['total-payment']
        })
      !this.$v.$invalid
        ? this.sendData(dataToSave)
        : this.highlightErrors()
    },
    sendData(dataToSave) {
      this.loading = true
      axios
        .post('/calculation/getPdf', dataToSave, this.formatToSave === 'pdf' ? { responseType: 'blob' } : false)
        .then(response => {
          if(this.formatToSave === 'pdf') {
            let index = response.headers['content-disposition'].indexOf('"') + 1
            saveAs(response.data, response.headers['content-disposition'].substr(index).split('"')[0])
          }
          if(this.formatToSave === 'email') {
            this.$notify({
              group: 'success',
              title: 'Графiк успiшно вiдправлено',
              text: '',
            })
          }
          this.loading = false
          setTimeout(() => {
            this.dialogToSend = false
          }, 1200)
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
          this.loading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`
          })
        })
    },
    sortData(a, b) {
      return new Date(b.updated_at) - new Date(a.updated_at)
    },
  },
  watch: {
    dialogToSend(value) {
      if(value === false) {
        this.formatToSave = null
        this.currentGraphToDownload = null
      }
    },
    deleteCalculationDialog(value) {
      if(value === false) {
        this.calculationToDelete = null
      }
    },
    leasingApplicationForm(val) {
      this.$v.$reset()
      if(val === false) {
        this.clearObject()
      }
    },
    user() {
      if(this.userData) this.getUserCalculations()
      return
    },
    showDocumentAttachment(val) {
      if(val === false) {
        this.attachedFiles = null
      }
    }
  },
  mounted() {
    this.$store.state.user.agent 
      ? this.getUserCalculations()
      : false
  },
}
</script>

<style lang="scss">
  .leasing-calculator-data {
    &.small {
      td {
        min-height: 23px;
      }
      td:last-child {
        margin-bottom: 20px!important;
      }
      .v-data-footer__select {
        font-size: 0.67rem!important;
      }
    }
  }
  .email-to-send {
    .v-text-field__details {
      margin-bottom: 0!important;
    }
  }
  .calculator-data-leasing-obj-type {
    font-weight: bold;
    font-size: 0.91rem!important;
  }
  .graphs-to-delete {
    .v-input--selection-controls {
      margin: 0!important;
    }
    .v-input__slot {
      margin: 0!important;
    }
  }
  .document-list div {
    font-size: 14px;
    color: rgba(0,0,0,.6);
    transition: color 0.3s ease;
    .document-delete-icon {
      opacity: 0;
      transition: opacity 0.2s ease-in;
    }
  }
  .document-list div:hover {
    color: #4d4c4c;
    .document-delete-icon {
      opacity: 1;
    }
  }
  .send-graph-btn {
    transition: box-shadow 0.5s;
    &:hover {
      -webkit-box-shadow: 1px 3px 17px -6px rgba(0,0,0,0.75);
      -moz-box-shadow: 1px 3px 17px -6px rgba(0,0,0,0.75);
      box-shadow: 1px 3px 17px -6px rgba(0,0,0,0.75);
    }
  }
  .graph-label-to-download {
    font-size: 1.05rem;
    letter-spacing: 0.09rem;
    margin-bottom: 4px;
  }
  .calculator-custom-title {
    .v-text-field {
      padding: 0!important;
    }
    .v-btn {
      border-radius: 0!important;
    }
  }
  .calculator-data-graph {
    .v-input--selection-controls {
      margin-top: 8px!important;
    }
  }
  .calculator-data__custom-btn-wrapper {
    .v-btn__content {
      text-transform: none!important;
      font-size: 1rem;
    }
  }
</style>