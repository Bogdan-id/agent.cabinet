<template>
  <v-card>
    <v-card-title class="d-block"> 
      <div>Заявки на лiзинг</div>
      <v-divider></v-divider>
    </v-card-title>
    <v-card-actions>
      <v-tooltip right>
        <template v-slot:activator="{ on }">
          <v-btn class="mx-2" fab dark v-on="on" @click.stop="subjectTypeDialog = true" color="error">
            <v-icon dark>mdi-plus-thick</v-icon>
          </v-btn>
        </template>
        <span>Створити заявку</span>
      </v-tooltip>
      <v-dialog
        v-model="subjectTypeDialog"
        max-width="490">
        <v-card class="d-flex flex-column">
          <v-card-title class="d-inline-flex text-center">
            Створити заявку для:
          </v-card-title>
          <v-card-text :class="`d-flex justify-space-around ${smallScreen ? 'flex-column' : ''}`">
            <span>
              <v-btn class="ma-2" dark @click.stop="applicationByType(2)">
                Фізичної особи
              </v-btn>
              </span>
            <span>
              <v-btn class="ma-2" dark @click.stop="applicationByType(1)">
                Юридичної особи
              </v-btn>
            </span>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog
        v-model="newLeasingAplication"
        :width="dialogSize">
        <v-card class="pb-1">
          <v-card-title class="headline">Заявка на фiзичну особу</v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="12" md="3">
                <v-select
                  v-model="rigion"
                  :items="regions"
                  label="Область"
                  dense outlined>
                </v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="3">
                <v-text-field
                    v-model="lastName"
                    label="Прiзвище"
                    dense outlined>
                </v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                    v-model="firstName"
                    label="Им`я"
                    dense outlined>
                </v-text-field>
              </v-col>
              <v-col md="3">
                <v-text-field
                    v-model="patronymic"
                    label="По батьковi"
                    dense outlined>
                </v-text-field>
              </v-col>
            </v-row>
            <!-- bellow conditionally -->
            <v-row>
              <v-col md="4">
                <v-text-field
                    v-if="subjectPerson"
                    v-model="number"
                    label="Телефон"
                    dense outlined>
                </v-text-field>
                <v-text-field
                    v-if="subjectLegalEntity"
                    v-model="companyName"
                    label="Назва компанiї"
                    dense outlined>
                </v-text-field>
              </v-col>
              <v-col md="4">
                <v-text-field
                    v-if="subjectPerson"
                    v-model="email"
                    label="Email"
                    dense outlined>
                </v-text-field>
                <v-text-field
                    v-if="subjectLegalEntity"
                    v-model="usreou"
                    label="ЄДРПОУ"
                    dense outlined>
                </v-text-field>
              </v-col>
            </v-row>
            <v-row v-if="subjectPerson">
              <v-col md="5">
                <v-text-field
                    v-model="inn"
                    label="Iдентифiкацiйний код"
                    dense outlined>
                </v-text-field>
              </v-col>
            </v-row>
            
            <v-row>
              <v-col md="7">
                <v-select
                    v-model="inn"
                    label="Об'єкт лiзингу"
                    dense outlined>
                </v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col md="3">
                <v-text-field
                    v-model="prepaid"
                    label="Аванс"
                    dense outlined readonly>
                </v-text-field>
              </v-col>
              <v-col md="4">
                <v-text-field
                    v-model="leasingTerm"
                    label="Термiн лiзингу"
                    dense outlined readonly>
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col md="3">
                <v-text-field
                    v-model="cost"
                    label="Вартiсть з ПДВ"
                    dense outlined readonly>
                </v-text-field>
              </v-col>
              <v-col md="4">
                <v-text-field
                    v-if="subjectPerson"
                    v-model="monthlyIncome"
                    label="Середньомiсячний дохiд"
                    dense outlined readonly>
                </v-text-field>
                <v-text-field
                    v-if="subjectLegalEntity"
                    v-model="monthlyIncome"
                    label="Власный капитал"
                    dense outlined readonly>
                </v-text-field>
              </v-col>
              <v-col v-if="subjectLegalEntity" md="4">
                <v-text-field
                    v-model="monthlyIncome"
                    label="Валюта балансу"
                    dense outlined readonly>
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col md="5">
                <v-text-field
                    v-model="loanPayment"
                    label="Платіж по кредитам та позикам (мiс/грн)"
                    dense outlined>
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col md="10">
                <v-select
                    v-if="subjectPerson"
                    v-model="carBuyingPurpose"
                    label="Мета придбання авто"
                    dense outlined>
                </v-select>
                <v-file-input
                    v-if="subjectLegalEntity"
                    v-model="companyBalances"
                    label="Баланси"
                    dense outlined chips multiple show-size>
                </v-file-input>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions class="justify-center">
            <span><v-btn color="grey darken-1 white--text">створити</v-btn></span>
            <v-btn @click="test()">test</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-card-actions>
    <v-card-text>
      <div slot="raw-content" class="table-responsive">
        <paper-table 
          :data="table.data" 
          :columns="table.columns"
          :title="table.title" 
          :sub-title="table.subTitle" 
          type="hover">
        </paper-table>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>

import { PaperTable } from "../components"
import { appTableColumns, appTableData } from './calculator-temp-data.js'
import selectItems from './selectItems.js'

export default {
  data: () => ({
    /* data for v-selects and tables */
    table: {
      title: "Історія розрахунків",
      subTitle: "Розрахунки за місяць",
      columns: [...appTableColumns],
      data: [...appTableData]
    },
    regions: selectItems.regions,

    /* leasing-application data */

    subjectType: null,

    rigion: null,
    lastName: null,
    firstName: null,
    patronymic: null,
    prepaid: null,
    leasingTerm: null,
    cost: null,
    loanPayment: null,
    // below for legal entity
    companyName: null,
    usreou: null, // ЄДРПОУ
    equity: null,
    balanceCurrency: null,
    companyBalances: null,
    // below for person
    number: null,
    email: null,
    monthlyIncome: null,
    inn: null,
    carBuyingPurpose: null,

    /* booleans */
    subjectTypeDialog: false,
    newLeasingAplication: false,
  }),
  methods: {
    test() {
      console.log(this.$vuetify.breakpoint.smAndDown)
    },
    applicationByType(type) {
      this.newLeasingAplication = true; 
      this.subjectTypeDialog = false; 
      this.subjectType = type
    }
  },
  computed: {
    dialogSize() {
      let screenSize = 'md'
      if(this.$vuetify.breakpoint.name === 'xs') screenSize = '360'
      if(this.$vuetify.breakpoint.name === 'sm') screenSize = '550'
      if(this.$vuetify.breakpoint.name === 'md') screenSize = '800'
      if(this.$vuetify.breakpoint.name === 'lg') screenSize = '1000'
      if(this.$vuetify.breakpoint.name === 'xl') screenSize = '1300'
      return screenSize
    },
    smallScreen() {
      return this.$vuetify.breakpoint.smAndDown
    },
    subjectPerson() {
      return this.subjectType === 2
    },
    subjectLegalEntity() {
      return this.subjectType === 1
    }
  },
  components: {
    PaperTable
  }
}
</script>