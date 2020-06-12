<template>
<div class="calculator-block">
  <div class="calculator-red-block">
    <div class="claculator-block-border">
    </div>
    <div style="margin: 0 39px">
      <v-row class="leasing-types">
        <v-col :cols="leasingTypeCol" :class="leasingTypeClass">
          <input 
            @change="getMarksByType($event); 
              addActiveClass($event)" 
            v-model="calcObj.leasingObjectType" 
            type="radio" 
            id="car" 
            name="leasing-type" 
            :value="1" 
            checked>
          <label for="car" class="leasing-type-block">
            <car class="leasing-type-icon"></car>
            <span style="white-space: nowrap">ЛЕГКОВI АВТО</span>
          </label>
        </v-col>
        <v-col :cols="leasingTypeCol" :class="leasingTypeClass">
          <input 
            @change="getMarksByType($event); 
              addActiveClass($event)" 
            v-model="calcObj.leasingObjectType" 
            type="radio" 
            id="cargo" 
            name="leasing-type" 
            :value="6">
          <label for="cargo" class="leasing-type-block">
            <cargo class="leasing-type-icon"></cargo>
            <span>ВАНТАЖIВКИ</span>
          </label>
        </v-col>
        <v-col :cols="leasingTypeCol" :class="leasingTypeClass">
          <input 
            @change="getMarksByType($event); 
              addActiveClass($event)" 
            v-model="calcObj.leasingObjectType" 
            type="radio" 
            id="special" 
            name="leasing-type" 
            :value="4">
          <label for="special" class="leasing-type-block">
            <special class="leasing-type-icon"></special>
            <span>СПЕЦТЕХНIКА</span>
          </label>
        </v-col>
        <v-col :cols="leasingTypeCol" :class="leasingTypeClass">
          <input 
            @change="getMarksByType($event); 
              addActiveClass($event)" 
            v-model="calcObj.leasingObjectType" 
            type="radio" 
            id="agricultural" 
            name="leasing-type" 
            :value="6">
          <label for="agricultural" class="leasing-type-block">
            <agricultural class="leasing-type-icon"></agricultural>
            <span style="white-space: nowrap">С/Г ТЕХНIКА</span>
          </label>
        </v-col>
        <v-col :cols="leasingTypeCol" :class="leasingTypeClass">
          <input 
            @change="getMarksByType($event); 
              addActiveClass($event)" 
            v-model="calcObj.leasingObjectType" 
            type="radio" 
            id="equipment" 
            name="leasing-type" 
            :value="6">
          <label for="equipment" class="leasing-type-block">
            <equipment class="leasing-type-icon"></equipment>
            <span>ОБЛАДНАННЯ</span>
          </label>
        </v-col>
        <v-col :cols="leasingTypeCol" :class="leasingTypeClass">
          <input 
            @change="getMarksByType($event); 
              addActiveClass($event)" 
            v-model="calcObj.leasingObjectType" 
            type="radio" 
            id="trailer" 
            name="leasing-type" 
            :value="6">
          <label for="trailer" class="leasing-type-block">
            <trailer  style="height: 50px; margin-left: 0.40rem;"></trailer>
            <span>ПРИЧЕПИ</span>
          </label>
        </v-col>
      </v-row>
      <div class="pb-4">
        <div>
          <v-row class="justify-space-around">
            <v-col cols="12" md="6" sm="4" class="pt-0 pb-0">
              <v-radio-group
                :class="`leasing-type-radio ${mediumAndDown && !xs ? 'small' : mediumAndDown && xs ? 'small xs' : ''}`"
                dark
                color="white"
                v-model="calcObj.leasingClientType"
                :error-messages="leasingClientTypeErr"
                row :dense="xs" :disabled="calcObj.leasingObjectType === null">
                <div :class="`leasing-type-radio-wrapper ${mediumAndDown ? 'small' : ''}`">
                  <v-radio :value="1" color="white">
                    <template #label>
                      <span class="red-block-radio-label">Фiзична особа</span>
                    </template>
                  </v-radio>
                  <v-radio :value="2" color="white">
                    <template #label>
                      <span class="red-block-radio-label">Юридична особа</span>
                    </template>
                  </v-radio>
                </div>
              </v-radio-group>
            </v-col>
            <v-col cols="12" md="6" sm="4" class="pt-0 pb-0">
              <v-radio-group
                dark
                :class="`auto-type-radio ${mediumAndDown && !xs ? 'small' : mediumAndDown && xs ? 'small xs' : ''}`"
                color="white"
                v-model="calcObj.isNew"
                :error-messages="leasingClientTypeErr"
                row :dense="xs" :disabled="calcObj.leasingObjectType === null">
                <div :class="`auto-type-radio-wrapper ${mediumAndDown ? 'small' : xs ? 'small xs' : ''}`">
                  <v-radio :value="true" color="white">
                    <template #label>
                      <span class="red-block-radio-label">Нове авто</span>
                    </template>
                  </v-radio>
                  <v-radio :value="false" color="white">
                    <template #label>
                      <span class="red-block-radio-label">Б/в авто</span>
                    </template>
                  </v-radio>
                </div>
              </v-radio-group>
            </v-col>
          </v-row>
        </div>
      </div>
      <v-row>
        <v-col cols="12" md="6" sm="6" xs="12" class="pb-0">
          <v-autocomplete
            @change="getModelByMark()"
            v-model="calcObj.leasedAssertMark"
            :items="brandItems"
            :error-messages="leasedAssertMarkErr"
            background-color="white"
            append-icon="mdi-chevron-down"
            itemColor="red darken-4"
            item-text="name"
            item-value="value"
            return-object
            label="Марка"
            loaderHeight="1"
            :loading="!leasedOfAssetType && $store.state.loader
              || noBrandItems && $store.state.loader"
            :disabled="calcObj.isNew === null || calcObj.leasingClientType === null"
            color="red darken-4"
            outlined :dense="xs">
          </v-autocomplete>
        </v-col>
        <v-col cols="12" md="6" sm="6" xs="12"  class="pb-0">
          <v-autocomplete
            v-model="calcObj.leasedAssertModel"
            :error-messages="leasedAssertModelErr"
            :items="modelItems"
            item-text="name"
            item-value="value"
            itemColor="red darken-4"
            background-color="white"
            append-icon="mdi-chevron-down"
            return-object
            label="Модель"
            loaderHeight="1"
            :loading="$store.state.loader && modelOfItem"
            :disabled="calcObj.leasedAssertMark === null"
            color="red darken-4"
            outlined :dense="xs">
          </v-autocomplete>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6" sm="6" xs="12"  class="pb-0">
          <v-select
            v-model="calcObj.leasingObjectYear"
            :items=" calcObj.isNew ? selects.itemYears : selects.oldItemYears"
            background-color="white"
            append-icon="mdi-chevron-down"
            itemColor="red darken-4"
            :error-messages="leasingObjectYearErr"
            label="Рік"
            :disabled="calcObj.leasedAssertModel === null"
            color="red darken-4"
            outlined :dense="xs">
          </v-select>
        </v-col>
        <v-col cols="12" md="6" sm="6" xs="12"  class="pb-0">
          <v-text-field
            @input="parseToInt('leasedAssertEngine')"
            v-model="calcObj.leasedAssertEngine"
            :error-messages="leasedAssertEngineErr"
            background-color="white"
            id="leasedAssertEngine"
            label="Об'єм двигуна"
            :disabled="!yearOfModel"
            color="red darken-4"
            outlined :dense="xs">
            <template v-slot:append>
              <span style="color: grey!important; display: block; margin-top: 5px;">см3</span>
            </template>
          </v-text-field>
        </v-col>
      </v-row>
      <v-row class="justify-center">
        <v-col cols="11" class="pa-0">
          <v-divider dark></v-divider>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="3" sm="6" xs="12"  class="pb-0">
          <v-text-field
            @input="$v.calcObj.leasingAmount.$touch();
              parseToInt('leasingAmount')"
            :error-messages="itemCostErrors"
            v-model="calcObj.leasingAmount"
            background-color="white"
            id="leasingAmount"
            label="Вартість"
            color="red darken-4"
            :disabled="calcObj.leasedAssertEngine === null"
            outlined :dense="xs">
            <template v-slot:append>
              <span style="color: grey!important; display: block; margin-top: 5px;">грн</span>
            </template>
          </v-text-field>
        </v-col>
        <v-col cols="12" md="3" sm="6" xs="12"  class="pb-0">
          <v-select
            v-model="calcObj.currency"
            :items="selects.currencys"
            background-color="white"
            append-icon="mdi-chevron-down"
            itemColor="red darken-4"
            :error-messages="currencyErr"
            label="Валюта"
            color="red darken-4"
            outlined 
            :disabled="calcObj.leasingAmount === null"
            :dense="xs">
          </v-select>
        </v-col>
        <v-col cols="12" md="3" sm="6" xs="12" v-if="hasForeignCurrency"  class="pb-0">
          <v-text-field
            v-model="calcObj.leasingCurrencyCourse"
            :error-messages="leasingCurrencyCourseErr"
            @input="parseToFloat('leasingCurrencyCourse')"
            background-color="white"
            itemColor="red darken-4"
            id="leasingCurrencyCourse"
            label="Курс"
            color="red darken-4"
            outlined :dense="xs"
            :disabled="calcObj.currency === null">
          </v-text-field>
        </v-col>
        <v-col cols="12" md="3" sm="6" xs="12" class="pb-0">
          <v-text-field
            v-model="calcObj.leasingQuantity"
            :error-messages="leasingQuantityErr"
            @input="parseToInt('leasingQuantity')"
            background-color="white"
            id="leasingQuantity"
            label="Кiлькiсть"
            min="1"
            color="red darken-4"
            outlined :dense="xs"
            :disabled="calcObj.currency === null">
          </v-text-field>
        </v-col>
      </v-row>
      <v-row class="pb-4">
        <v-col cols="12" class="pt-0 pb-0">
          <v-checkbox
            v-model="discountPrice"
            :value="true" 
            class="discount-price mt-0 white--text"
            label="Вартiсть зi знижкою" 
            :false-value="false"
            :disabled="calcObj.leasingQuantity === null"
            dark :dense="xs">
          </v-checkbox>
        </v-col>
        <v-col cols="12" md="4" v-show="discountPrice">
          <v-text-field
            v-model="calcObj.discountPrice"
            background-color="white"
            label="вартiсть зi знижкою"
            color="red darken-4"
            outlined :dense="xs">
          </v-text-field>
        </v-col>
      </v-row>
    </div>
  </div>
  <div class="calculator-white-block">
    <v-row style="color: #505050">
      <v-col cols="12" class="mt-9 pb-0 graphs">
        <div class="pb-4">
          <span class="section-title">Графiк платежiв</span>
        </div>
        <v-row class="d-flex justify-space-around pl-5 pr-5">
          <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
            <v-checkbox
              v-model="calcObj.graphType"
              label="Ануїтет"
              color="red darken-3"
              value="annuity"
              :dense="xs"
              :disabled="calcObj.leasingQuantity === null">
            </v-checkbox>
          </v-col>
          <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
            <v-checkbox
              v-model="calcObj.graphType"
              label="Класичний"
              color="red darken-3"
              value="even"
              :dense="xs"
              :disabled="calcObj.leasingQuantity === null">
            </v-checkbox>
          </v-col>
          <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
            <v-checkbox
              v-model="calcObj.graphType"
              label="Iндивiдуальний"
              color="red darken-3"
              value="irregular"
              :dense="xs"
              :disabled="calcObj.leasingQuantity === null">
            </v-checkbox>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-row :style="`display: flex; justify-content: ${mediumAndDown ? 'center;' : 'space-between;'}`">
      <v-col cols="12">
        <v-row :style="`color: #505050; ${mediumAndDown ? 'flex-direction: column-reverse;' : ''}`">
          <v-col :cols="mediumAndDown ? 12 : 7" style="align-self: flex-start;">
            <v-row>
              <v-col cols="12" class="pt-0 pb-0" style="min-height: 250px;">
                <div>
              <span class="section-title">Авансовий платiж</span>
            </div>
            <v-text-field
              v-model="calcObj.advance"
              label=""
              color="red darken-4"
              readonly
              outlined
              :dense="xs">
              <template v-slot:append>
                <percent></percent>
              </template>
            </v-text-field>
            <div>
              <input 
                type="range" 
                id="advance-payment" 
                name="advance-payment" 
                :min="elRange.min" 
                :max="elRange.max"
                v-model="calcObj.advance"
                step="1"
                class="slider" 
                @input="initElRange($event)">
              <div class="advance-range-scale pt-6">
                <div 
                  v-for="v in 14"
                  :key="v"
                  class="advance-range-wrapper">
                  <span 
                    class="advance-range-cell" 
                    :style="`color: ${calcObj.advance == ((v - 1) * 5) ? 'black; font-weight: bold;' : '#969599;' } font-size: ${xs ? '0.5rem' : '0.8rem'}`">
                      {{ (v - 1) * 5 + '%' }}
                  </span>
                  <div v-if="v === 7" style="position: absolute; top: -34px;">
                    <div class="range-black-dot">
                      <div class="range-dashed-line"></div>
                      <div class="arrow-directions-wrapper" :style="`right: ${xs ? '-119px;' : '-147px;'}`">
                        <div class="arrow-directions-content">
                          <div :style="`display: inline-block; margin-right: 1.2rem; text-align: right; font-size: ${xs ? '0.5rem;' : '0.7rem;'}`">
                            <span style="white-space: nowrap; ">З ФIНАНСОВИМИ</span> ДОКУМЕНТАМИ
                          </div>
                          <div>
                            <calculator-left-arrow></calculator-left-arrow>
                          </div>
                        </div>
                        <div style="display: inline-flex; color: #d24a43; align-items: center;"> 
                          <div style="display: inline-block;">
                            <calculator-right-arrow></calculator-right-arrow>
                          </div>
                          <div style="display: inline-block; margin-left: 1.2rem">
                            <b :style="`text-decoration: underline; font-size: ${xs ? '0.5rem;' : '0.7rem;'}`">
                              <span style="white-space: nowrap;">БЕЗ ФIНАНСОВИХ</span> ДОКУМЕНТIВ
                            </b>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div 
                  :style="`position: absolute; right: -9px; color: ${calcObj.advance == '70' ? 'black; font-weight: bold;' : '#969599;'} font-size: ${xs ? '0.5rem' : '0.8rem'}`">
                  {{ `70%` }}
                </div>
              </div>
            </div>
              </v-col>
            </v-row>
             
            <v-row v-if="mediumAndDown || hasIrregular">
              <v-col class="pb-0 pt-0 mt-6 leasing-term-sm" cols="12" md="6" sm="12">
                <div class="pb-4">
                  <span class="section-title">Термiн фiнансування</span>
                </div>
                <v-select
                  v-model="calcObj.leasingTerm"
                  :error-messages="leasingTermErr"
                  :items="['12', '24', '36', '48', '60']"
                  label="Оберiть кiлькiсть"
                  color="red darken-4"
                  itemColor="red darken-4"
                  outlined
                  :dense="xs">
                  <template v-slot:append>
                    <span class="leasing-term-append-label">мiс</span>
                  </template>
                </v-select>
              </v-col>
              <v-col cols="12" md="6" sm="12" :class="`pt-0 pb-0 ${smAndDown ? '' : 'mt-6'}`">
                  <span class="section-title">Валюта фiнансування</span>
                  <v-radio-group
                    :class="`financing-currency d-inline-block ${xs ? 'mt-0' : ''}`"
                    :error-messages="leasingCurrencyErr"
                    color="red darken-4"
                    v-model="calcObj.leasingCurrency"
                    dense>
                    <v-row class="pl-8" :style="`flex-direction: ${xs ? 'column' : ''}`">
                      <div style="display: flex;">
                        <v-radio value="UAH" color="red darken-3" dense class="ml-1">
                          <template #label>
                            <span 
                              class="current-currency-label" 
                              :style="`color: ${calcObj.leasingCurrency === 'UAH' ? 'black' : ''}`">
                              UAH
                            </span>
                          </template>
                        </v-radio>
                      </div>
                      <div style="display: flex;">
                        <v-radio value="USD" color="red darken-3" :class="!xs && !hasIrregular   ? 'ml-5' : 'ml-1'">
                          <template #label>
                            <span 
                              class="current-currency-label"  
                              :style="`color: ${calcObj.leasingCurrency === 'USD' ? 'black' : ''}`">
                              USD
                            </span>
                          </template>
                        </v-radio>
                      </div>
                      <div style="display: flex;">
                        <v-radio value="EURO" color="red darken-3" :class="!xs && !hasIrregular   ? 'ml-5' : 'ml-1'">
                          <template #label>
                            <span 
                              class="current-currency-label"  
                              :style="`color: ${calcObj.leasingCurrency === 'EURO' ? 'black' : ''}`">
                              EURO
                            </span>
                          </template>
                        </v-radio>
                      </div>
                    </v-row>
                  </v-radio-group>
              </v-col>
            </v-row> 
          </v-col>
          <v-col v-if="!hasIrregular && !mediumAndDown" cols="5" :class="`pl-8 ${hasIrregular || mediumAndDown ? 'mt-9' : ''}`">
            <div>
              <span style="font-size:1rem">Термiн фiнансування</span>
            </div>
            <v-select
              v-model="calcObj.leasingTerm"
              :error-messages="leasingTermErr"
              :items="['12', '24', '36', '48', '60']"
              label="Оберiть кiлькiсть"
              color="red darken-4"
              itemColor="red darken-4"
              outlined
              :dense="xs">
              <template v-slot:append>
                <span class="leasing-term-append-label">мiс</span>
              </template>
            </v-select>
            <span style="display: inline-block; font-size: 1rem;">Валюта фiнансування</span>
            <v-radio-group
              :class="`financing-currency d-inline-block ${xs ? 'mt-0' : ''}`"
              :error-messages="leasingCurrencyErr"
              color="red darken-4"
              v-model="calcObj.leasingCurrency"
              dense>
              <v-row class="pl-8" :style="`flex-direction: ${xs ? 'column' : ''}`">
                <div style="display: flex;">
                  <v-radio value="UAH" color="red darken-3" dense>
                    <template #label>
                      <span 
                        class="current-currency-label" 
                        :style="`color: ${calcObj.leasingCurrency === 'UAH' ? 'black' : ''}`">
                        UAH
                      </span>
                    </template>
                  </v-radio>
                </div>
                <div style="display: flex;">
                  <v-radio value="USD" color="red darken-3" :class="!xs ? 'ml-5' : ''">
                    <template #label>
                      <span 
                        class="current-currency-label"  
                        :style="`color 0.15s ease-in; color: ${calcObj.leasingCurrency === 'USD' ? 'black' : ''}`">
                        USD
                      </span>
                    </template>
                  </v-radio>
                </div>
                <div style="display: flex;">
                  <v-radio value="EURO" color="red darken-3" :class="!xs ? 'ml-5' : ''">
                    <template #label>
                      <span 
                        class="current-currency-label" 
                        :style="`color: ${calcObj.leasingCurrency === 'EURO' ? 'black' : ''}`">
                        EURO
                      </span>
                    </template>
                  </v-radio>
                </div>
              </v-row>
            </v-radio-group>
          </v-col>
          <v-col v-if="hasIrregular" :cols="mediumAndDown ? 12 : 5" :class="`mb-6 ${mediumAndDown ? '' : 'mt-2 pl-6'} `">
            <div class="pb-4" :style="`text-align: ${mediumAndDown ? '' : 'center;'};`">
              <span class="section-title">Оберiть тип нестандартного графiку</span>
            </div>
            <v-row class="d-flex justify-space-around">
              <v-col cols="5" xs="12" class="text-center">
                <span
                  @click="changeCustomGraph(1)" 
                  text 
                  :style="`cursor: pointer; color: ${customGraphType === 1 ? '#d24a43' : 'black'}`"
                  ><b>СТУПIНЧАТЕ ПОСИЛЕННЯ</b>
                </span>
              </v-col>
              <v-col cols="6" xs="12" class="text-center">
                <span
                  @click="changeCustomGraph(2)" 
                  text 
                  :style="`cursor: pointer;  color: ${customGraphType === 2 ? '#d24a43' : 'black'}`"
                  ><b>УНIВЕРСАЛЬНЕ ПОСИЛЕННЯ</b>
                </span>
              </v-col>
            </v-row>
            <v-col cols="12" v-if="customGraphType === 1">
              <v-row>
                <v-col cols="12" :style="`text-align: ${mediumAndDown ? '' : 'center;'}`">
                  <span style="font-size: 1rem; color: #787878">Параметри ступеневого графiку</span>
                </v-col>
              </v-row>
              <v-row style="display: flex; justify-content: space-around">
                <v-col cols="6" class="pt-0 pb-0">
                  <v-text-field
                    color="red darken-3"
                    :dense="xs"
                    v-model="stepGain.oneThird" 
                    class="pt-0">
                    <template v-slot:append-outer>
                      <percent style="margin-top: 5px;"></percent>
                    </template>
                    <template v-slot:prepend>
                      <span class="step-schedule-label">1/3</span>
                    </template>
                  </v-text-field>
                </v-col>
              </v-row>
              <v-row style="display: flex; justify-content: space-around">
                <v-col cols="6" class="pt-0 pb-0">
                  <v-text-field
                    color="red darken-3"
                    :dense="xs"
                    v-model="stepGain.twoThirds"
                    class="pt-0"
                    >
                    <template v-slot:append-outer>
                      <percent style="margin-top: 5px;"></percent>
                    </template>
                    <template v-slot:prepend>
                      <span class="step-schedule-label">2/3</span>
                    </template>
                  </v-text-field>
                </v-col>
              </v-row>
              <v-row style="display: flex; justify-content: space-around">
                <v-col cols="6" class="pt-0 pb-0">
                  <v-text-field
                    color="red darken-3"
                    :dense="xs"
                    v-model="stepGain.threeThirds"
                    class="pt-0"
                    >
                    <template v-slot:append-outer>
                      <percent style="margin-top: 5px;"></percent>
                    </template>
                    <template v-slot:prepend>
                      <span class="step-schedule-label">3/3</span>
                    </template>
                  </v-text-field>
                </v-col>
              </v-row>
            </v-col>
            <v-col cols="12" v-if="customGraphType === 2">
              <v-row>
                <v-col cols="12" :style="`text-align: ${mediumAndDown ? '' : 'center;'}`">
                  <span style="font-size: 1rem; color: #787878">Параметри унiверсального посилення</span>
                </v-col>
              </v-row>
              <v-row :style="`${mediumAndDown ? '' : 'justify-content: center'}`">
                <v-col cols="8">
                  <v-text-field
                    color="red darken-3"
                    v-model="universalGain"
                    :dense="xs">
                    <template v-slot:append-outer>
                      <percent style="margin-top: 5px;"></percent>
                    </template>
                  </v-text-field>
                </v-col>
              </v-row>
            </v-col>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" class="pt-0">
        <v-expansion-panels>
          <v-expansion-panel>
            <v-expansion-panel-header>Страхування</v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-row class="d-flex justify-space-between">
                <v-col cols="12" md="5">
                  <v-select
                      v-model="insuranceProgram"
                      append-icon="mdi-chevron-down"
                      :items="selects.insurancePrograms"
                      :error-messages="insuranceProgramErr"
                      item-text="text"
                      item-value="text"
                      return-object
                      label="Програма страхування"
                      itemColor="red darken-4"
                      color="red darken-4"
                      outlined
                      :dense="xs">
                  </v-select>
                </v-col>
                <v-col cols="12" md="6" style="padding-top: 3px;">
                  <div style="margin-bottom: 25px; padding-left: 15px;">
                    <span style="font-size: 0.95rem; color: #757575; ">Франшиза (%)</span>
                  </div>
                  <input 
                    type="range" 
                    id="franchise" 
                    name="franchise" 
                    :min="franchise.min"
                    :max="franchise.max"
                    v-model="calcObj.insuranceFranchise"
                    step="0.5"
                    class="slider" 
                    @input="initElRange($event)">
                  <div style="display: flex; position: relative; margin-right: 14px;" class="pt-6">
                    <div
                      v-for="v in ['0', '0.5', '1', '1.5']"
                      :key="v"
                      style="position: relative; width: 37.8%;'">
                      <span :style="`color: #969599; transition: color 0.2s ease-in; color: ${calcObj.insuranceFranchise === v ? 'black; font-weight: bold;' : '#969599;' }`">{{ v }}</span>
                    </div>
                    <div :style="`position: absolute; transition: color 0.2s ease-in; right: -15px; color: ${calcObj.insuranceFranchise == '2' ? 'black; font-weight: bold;' : '#969599;'} `">
                      {{ `2` }}
                    </div>
                  </div>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-expansion-panels>
          <v-expansion-panel>
            <v-expansion-panel-header>Додатковi умови</v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-row>
                <v-col cols="12" md="5">
                  <v-select
                    v-model="calcObj.promotion"
                    append-icon="mdi-chevron-down"
                    :items="['Акцiя 1', 'Акцiя 2', 'Акцiя 3']"
                    label="Акцiя"
                    itemColor="red darken-4"
                    color="red darken-4"
                    outlined
                    :dense="xs">
                  </v-select>
                </v-col>
                <v-col cols="12" md="7" class="pt-6">
                  <div style="margin-bottom: 25px; padding-left: 15px;">
                    <v-checkbox 
                      v-model="calcObj.holiday"
                      :value="true" 
                      :false-value="false"
                      color="red darken-3" 
                      class="mt-0">
                      <template #prepend>
                        <span style="display: inline-block; margin-top: 2px; font-size: 1.2rem; color: #757575; ">Канiкули</span>
                      </template>
                    </v-checkbox>
                  </div>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="4" class="pb-0">
        <v-select
          flat
          v-model="calcObj.gpsTrackerModel"
          append-icon="mdi-chevron-down"
          :items="selects.gpsTrackers"
          itemColor="red darken-4"
          :error-messages="gpsTrackerModelErr"
          class="mt-1 mb-1"
          label="GPS-трекер"
          color="red darken-4"
          outlined
          :dense="xs">
        </v-select>
      </v-col>
    </v-row>
    <v-card-actions class="d-flex justify-center ">
      <span>
        <v-btn @click="submit()" class="mb-3" dark color="grey darken-3" :dense="xs">
        {{'Розрахувати'}}
        </v-btn>
      </span>
    </v-card-actions>
  </div>
</div>
</template>

<script>
import axios from 'axios'
import selectsItemAndValue from './selectItems'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

import Car from '../../assets/svg-icons/car.vue'
import Agricultural from '../../assets/svg-icons/agricultural.vue'
import Equipment from '../../assets/svg-icons/equipment.vue'
import Special from '../../assets/svg-icons/special.vue'
import Cargo from '../../assets/svg-icons/cargo.vue'
import Trailer from '../../assets/svg-icons/trailer.vue'
import CalculatorRightArrow from '../../assets/svg-icons/calculator-right-arrow'
import CalculatorLeftArrow from '../../assets/svg-icons/calculator-left-arrow'
import Percent from '../../assets/svg-icons/percent'

export default {
  mixins: [validationMixin],
  components: {
    Car, 
    Agricultural, 
    Equipment, 
    Special, 
    Cargo, 
    Trailer,
    CalculatorRightArrow,
    CalculatorLeftArrow,
    Percent
  },
  data:() => ({
    selects: selectsItemAndValue,
    minCarCost: 150000,
    insuranceProgram: null,
    commonErr: ['Обов`язкове поле'],
    windowInnerWidth: null,
    discountPrice: false,
    customGraphType: 1,

    brandItems: [],
    modelItems: [],

    elRange: {
      min: 0,
      max: 70
    },
    franchise: {
      min: 0,
      max: 2
    },
    carCost: {
      current: 20
    },
    input: {
			currentProgress: '#d24a43',
			remainingProgress: '#efefef'
		},
    stepGain: {
      oneThird: null,
      twoThirds: null,
      threeThirds: null
    },
    universalGain: null,

    calcObj: {
      gpsTrackerQuantity: 1,
      urkAssistService: 1,
      gainEvenGraphicMonths: null,
      gainEvenGraphicPercent: null,
      UnsrMonths: null,
      agentId: null,
      leasedAssertMark: null,
      leasedAssertModel: null,
      isNew: null,
      leasingObjectType: null,
      leasingQuantity: null,
      leasingObjectYear: null,
      leasedAssertEngine: null,
      leasingClientType: null,
      currency: null,
      leasingCurrency: null,
      leasingCurrencyCourse: null,
      leasingAmount: null,
      graphType: [],
      advance: 20,
      leasingTerm: null,
      vehicleOwnerTax: "2",
      paymentPf: false,
      insuranceProgram: null,
      gpsTrackerModel: null,
      insuranceFranchise: 0,
      discountPrice: null,
      promotion: null,
      holiday: null,
      _token: null
    }
  }),
  validations() {
    return {
      calcObj: this.validationRules
    }
  },
  computed: {
    user() {
      return Object.keys(this.$store.state.user.agent).length > 0
    },
    validationRules() {
      return this.validateObj
    },

    validateObj() {
      return {
        leasingClientType: { required },
        leasingObjectYear: { required },
        isNew: { required },
        currency: { required },
        leasingObjectType: { required },
        leasedAssertMark: { required },
        leasedAssertModel: { required },
        leasedAssertEngine: { required },
        leasingCurrency: { required },
        leasingQuantity: { required },
        leasingAmount: {
          minCost: val => {
            if(val == null) return false
            return parseInt(val.replace(/[^\d]/g, '')) >= this.minCarCost
          },
        },
        leasingCurrencyCourse: { required },
        vehicleOwnerTax: { required },
        paymentPf: { required },
        leasingTerm: { required },
        graphType: {
          hasIndex: (v) => {
            if(v === null) return false
            return v.length > 0
          }
        },
        gpsTrackerModel: { required },
        advance: { required },
        insuranceProgram: { required },
        insuranceFranchise: { required },
        gainEvenGraphicMonths: { required },
        gainEvenGraphicPercent: { required },
        UnsrMonths: { required },
      }
    },

    /* boolean */

    smAndDown() {
      return this.$vuetify.breakpoint.smAndDown
    },
    typeOfCar() {
      return this.calcObj.isNew !== null
    },
    leasedOfAssetType() {
      return this.calcObj.leasingObjectType !== null
    },
    modelOfItem() {
      return this.calcObj.leasedAssertMark !== null
    },
    typeOfModel() {
      return this.calcObj.leasedAssertModel !== null
    },
    yearOfModel() {
      return this.calcObj.leasingObjectYear !== null
    },
    noBrandItems() {
      return Object.keys(this.brandItems).length === 0
    },
    noModelItems() {
      return Object.keys(this.modelItems).length === 0
    },
    hasAnnuity() {
      if(this.calcObj.graphType === null) return false
      return this.calcObj.graphType.indexOf('annuity') > -1
    },
    hasForeignCurrency() {
      if(this.calcObj.currency === null) return false
      return this.calcObj.currency.indexOf('EUR') > -1
        || this.calcObj.currency.indexOf('USD') > - 1
    },
    isClientType() {
      return this.calcObj.leasingClientType !== null
    },
    hasGraph() {
      if(this.calcObj.graphType === null || this.calcObj.graphType === 'undefined') return false
      return this.calcObj.graphType.length > 0
    },

    /* vuelidate error handlers */
    itemCostErrors() {
      const errors = []
      if (!this.$v.calcObj.leasingAmount.$error) return errors
      !this.$v.calcObj.leasingAmount.minCost && errors.push(`Вартiсть має бути бильше нiж 150 000грн`)
			return errors
    },
    leasingClientTypeErr() {
      if (!this.$v.calcObj.leasingClientType.$error) return
      return this.commonErr
    },
    isNewErr() {
      if (!this.$v.calcObj.isNew.$error) return
      return this.commonErr
    },
    currencyErr() {
      if (!this.$v.calcObj.currency.$error) return
      return this.commonErr
    },
    leasingObjectTypeErr() {
      if (!this.$v.calcObj.leasingObjectType.$error) return
      return this.commonErr
    },
    leasedAssertMarkErr() {
      if (!this.$v.calcObj.leasedAssertMark.$error) return
      return this.commonErr
    },
    leasedAssertModelErr() {
      if (!this.$v.calcObj.leasedAssertModel.$error) return
      return this.commonErr
    },
    leasingObjectYearErr() {
      if (!this.$v.calcObj.leasingObjectYear.$error) return
      return this.commonErr
    },
    leasedAssertEngineErr() {
      if (!this.$v.calcObj.leasedAssertEngine.$error) return
      return this.commonErr
    },
    leasingCurrencyErr() {
      if (!this.$v.calcObj.leasingCurrency.$error) return
      return this.commonErr
    },
    leasingCurrencyCourseErr() {
      if (!this.hasForeignCurrency) return
      if (!this.$v.calcObj.leasingCurrencyCourse.$error) return
      return this.commonErr
    },
    leasingQuantityErr() {
      if (!this.$v.calcObj.leasingQuantity.$error) return
      return this.commonErr
    },
    graphTypeErr() {
      if (!this.$v.calcObj.graphType.$error) return
      return this.commonErr
    },
    gainEvenGraphicMonthsErr() {
      if(!this.hasAnnuity) return
      if (!this.$v.calcObj.gainEvenGraphicMonths.$error) return
      return this.commonErr
    },
    gainEvenGraphicPercentErr() {
      if(!this.hasAnnuity) return
      if (!this.$v.calcObj.gainEvenGraphicPercent.$error) return
      return this.commonErr
    },
    UnsrMonthsErr() {
      if(!this.hasAnnuity) return
      if (!this.$v.calcObj.UnsrMonths.$error) return
      return this.commonErr
    },
    advanceErr() {
      if (!this.$v.calcObj.advance.$error) return
      return this.commonErr
    },
    leasingTermErr() {
      if (!this.$v.calcObj.leasingTerm.$error) return
      return this.commonErr
    },
    vehicleOwnerTaxErr() {
      if (!this.$v.calcObj.vehicleOwnerTax.$error) return
      return this.commonErr
    },
    paymentPfErr() {
      if (!this.$v.calcObj.paymentPf.$error ) return
      return this.commonErr
    },
    gpsTrackerModelErr() {
      if (!this.$v.calcObj.gpsTrackerModel.$error ) return
      return this.commonErr
    },
    insuranceProgramErr() {
      if (!this.$v.calcObj.insuranceProgram.$error ) return
      return this.commonErr
    },
    insuranceFranchiseErr() {
      if (!this.$v.calcObj.insuranceFranchise.$error ) return
      return this.commonErr
    },
    mediumAndDown() {
      return this.windowInnerWidth <= 1145
    },
    xs() {
      return this.$vuetify.breakpoint.name === 'xs'
    },
    sm() {
      return this.$vuetify.breakpoint.name === 'sm'
    },
    hasIrregular() {
      return this.calcObj.graphType.indexOf('irregular') !== -1
    },
    leasingTypeCol() {
      return this.xs ? '6' : this.mediumAndDown && !this.xs ? '4' : '2'
    },
    leasingTypeClass() {
      return `d-flex justify-center ${this.xs ? 'pt-0 pb-0' : ''}`
    }
  },
  methods: {
    test() {
      console.log('event work')
    },
    changeCustomGraph(id) {
      this.customGraphType = id
    },
    closeSelect() {
      this.$refs.graphType.blur()
    },
    addActiveClass(event) {
      let el = document.querySelectorAll('.leasing-type-block')
      el.forEach(val => {
        console.log(val)
        val.classList.remove('active')
      })
      if(event) event.target.nextSibling.nextSibling.classList.add('active')
    },
    getMarksByType() {
      console.log(this.calcObj.leasingObjectType)
      this.brandItems = []
      this.$store.commit('toggleSpinner', true)
      axios.get(`/mark?category=${this.calcObj.leasingObjectType}`)
        .then(response => {
          console.log('*********')
          console.log(response)
          console.log('*********')
          this.brandItems = response.data
          this.$store.commit('toggleSpinner', false)
        })
        .catch(error => {
          let message = error.response.statusText
          this.notify('Помилка', message, 'error')
          this.$store.commit('toggleSpinner', false)
        })
    },
    getModelByMark() {
      console.log(this.$v)
      this.modelItems = []
      this.$store.commit('toggleSpinner', true)
      let categorieId = this.calcObj.leasingObjectType
      axios.get(`/models?category=${categorieId}&mark=${this.calcObj.leasedAssertMark.value}`)
        .then(response => {
          console.log(response)
          this.modelItems = response.data
          this.$store.commit('toggleSpinner', false)
        })
        .catch(error => {
          let message = error.response.statusText
          this.notify('Помилка', message, 'error')
          this.$store.commit('toggleSpinner', false)
        })
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
    parseToFloat(id) {
      let input = new Event('input', {bubbles: true})
      let el = document.getElementById(id)
      let value = el.value
      let intVal = el.value.replace(/\.\d\.|\.+\d+\.+|\.{2,2}|[^\d.]/g, '')
      if(value !== intVal) {
        el.value = intVal
        el.dispatchEvent(input)
      }
    },
    notify(title, text, group) {
			this.$notify({
				group: group || 'standard',
				title: title,
				text: text,
			})
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
    submit() {
      console.log(this.calcObj)
      this.highlightErrors()
      !this.$v.$invalid
      && this.$v.$dirty
          && !this.$v.$invalid
          && this.$v.$dirty
            ? this.sendRequest()
            : this.notify('', 'Заповнiть даннi', 'error')

    },
    checkIfHasCurrency() {
      if(this.hasForeignCurrency) return
      this.calcObj.leasingCurrencyCourse = 1
    },
    checkIfHasAnnuity() {
      if(!this.hasAnnuity) {
        delete this.calcObj.gainEvenGraphicMonths
        delete this.calcObj.gainEvenGraphicPercent
        delete this.calcObj.UnsrMonths
      }
    },
    sendRequest() {
      this.checkIfHasAnnuity()
      this.checkIfHasCurrency()
      this.$store.commit('toggleSpinner', true)
      axios
        .post('/calculate', this.calcObj)
          .then(response => {
            console.log(response)
            this.$store.commit('toggleSpinner', false)
            // this.$router.push('/calculator/chart')
            let data = response.data
            this.$router.push({name: 'Графiки', params: {data: data}})
          })
          .catch(error => {
            console.log(error.response)
            const message = error.response.statusText
            this.notify('Помилка', message, 'error')
            this.$store.commit('toggleSpinner', false)
          })
    },
    test() {
      // console.log(this.calcObj)
    },



    valueTotal(value, min, max) {
			return ((value - min) / (max - min))
		},
		getGradient(ratio, leftColor, rightColor) {
			return [
				'-webkit-gradient(',
				'linear, ',
				'left top, ',
				'right top, ',
				'color-stop(' + ratio + ', ' + leftColor + '), ',
				'color-stop(' + ratio + ', ' + rightColor + ')',
				')'
			].join('')
		},
    checkValue(val, elRange, dataSelector) {
			let check = true
			if(this.toInt(val) > this.toInt(elRange.max)) {
				elRange.value = elRange.max
				this.syncValue(elRange.value, dataSelector)
				check = false
			} else if(this.toInt(val) < this.toInt(elRange.min)) {
				elRange.value = elRange.min
				this.syncValue(elRange.value, dataSelector)
				check = false
			}
			return check
		},
		syncValue(val, dataSelector) {
			switch(dataSelector) {
				case 'advance-payment': this.carCost.current = val; break
				case 'leasing-term': this.calcObj.leasingTerm = val; break
				// case 'termFinancing': this.termFinancing.current = val; break
			}
		},
		updateElRange(elRange, val) {
			let ratio = this.valueTotal(val, elRange.min, elRange.max)
			elRange.style.backgroundImage = this.getGradient(
				ratio, 
				this.input.currentProgress, 
				this.input.remainingProgress
			)
		},
    toInt(val, dataSelector) {
      if(dataSelector === 'franchise') {
        return parseFloat(val.replace(/^\D/g, ''))
      } else {
        return parseInt(val.replace(/^\D/g, ''))
      }
		},
		initInputValues() {
			let elArr = document.querySelectorAll('.slider')
			let event = new Event('input', {bubbles: true})
			elArr.forEach(el => {
				el.value = el.min
				el.dispatchEvent(event)
			})
		},
    initFranchiseInput() {
      setTimeout(() => {
        let el = document.querySelector('#franchise')
        console.log(el.value)
        if(el.value == 0) {
          let event = new Event('input', {bubbles: true})
          el.value = el.min
          el.dispatchEvent(event)
        }
      }, 40)
    },
		switchSelector(e) {
			let dataSelector, elRange
			if(e.target.name === 'advance-payment') {
				elRange = document.getElementById('advance-payment')
				dataSelector = 'advance-payment'
			} else if(e.target.name === 'leasing-term') {
				elRange = document.getElementById('leasing-term')
				dataSelector = 'leasing-term'
			} else if(e.target.name === 'franchise') {
				elRange = document.getElementById('franchise')
				dataSelector = 'franchise'
			} 
      // else if(e.target.name === 'termFinancing-input' || e.target.name === 'termFinancing-range') {
			// 	elRange = document.getElementById('termFinancing')
			// 	dataSelector = 'termFinancing'
			// }
			return {dataSelector, elRange}
		},
		initElRange(e) {
			let {elRange, dataSelector} = this.switchSelector(e) 
			if(!e) {
				elRange.value = this.toInt(elRange.min, dataSelector)
				this.syncValue(elRange.value, dataSelector) 
				this.updateElRange(elRange, elRange.value)
				return
			}
			elRange.value = this.toInt(e.target.value, dataSelector)
			let check = this.checkValue(elRange.value, elRange, dataSelector)
			if(!check) {
				this.updateElRange(elRange, elRange.value)
				return
			}
			this.syncValue(elRange.value, dataSelector) 
			this.updateElRange(elRange, elRange.value)
      console.log(this.calcObj.insuranceFranchise)
		},



    displayWindowSize() {
      this.windowInnerWidth = window.innerWidth
      console.log(this.xs)
      console.log(this.$vuetify.breakpoint.name)
      console.log(window.innerWidth)
      console.log(this.mediumAndDown)
    }
  },
  watch: {
    insuranceProgram(val) {
      console.log(val)
      // this.calcObj.insuranceProgram = val.value
      this.calcObj.insuranceProgram = val.value
    },
    'calcObj.leasingCurrencyCourse': function (course) {
      if(course == null){
        !this.hasForeignCurrency
          ? this.calcObj.leasingCurrencyCourse = 1
          : false
      } else {
        if(Number.isNaN(parseFloat(course))) return
        this.calcObj.leasingCurrencyCourse = parseFloat(course)
      }
    },
    'calcObj.gainEvenGraphicMonths': function(value) {
      if(!value) return value
      this.calcObj.gainEvenGraphicMonths = parseInt(value)
    },
    'calcObj.gainEvenGraphicPercent': function(value) {
      if(!value) return value
      else if(Number.isNaN(parseFloat(value))) return value
      this.calcObj.gainEvenGraphicPercent = parseFloat(value)
    },
    'calcObj.UnsrMonths': function(value) {
      if(!value) return value
      this.calcObj.UnsrMonths = parseInt(value)
    },
    'calcObj.leasingQuantity': function(value) {
      if(!value) return value
      this.calcObj.leasingQuantity = parseInt(value)
    },
    'calcObj.leasedAssertEngine': function(value) {
      if(!value) return value
      this.calcObj.leasedAssertEngine = parseInt(value)
    },
    'calcObj.leasingObjectType': function(value) {
      console.log(value)
    },
    user() {
      if(this.user) {
        this.calcObj.agentId = this.$store.state.user.agent.id
        console.log(this.$store.state.user)
      } else {
        return
      }
    },
  },
  created() {
    window.addEventListener("resize", this.displayWindowSize)
  },
  mounted() {
    let franchiseInputEl = document.querySelector('.v-expansion-panel-header')
    console.log(franchiseInputEl)
    franchiseInputEl.addEventListener("click", () => {
      this.initFranchiseInput()
    })
    this.displayWindowSize()
    if(this.$router.currentRoute.params.edit === true) {
    axios
      .get(`/calculation/${this.$router.currentRoute.params.id}`)
      .then(response => {
        let data = response.data.request_data
        this.brandItems.push(data.leasedAssertMark)
        this.modelItems.push(data.leasedAssertModel)
        this.insuranceProgram = this.selects.insurancePrograms
          .find(
            obj => obj.value === data.insuranceProgram
          )
        // console.log(data)
        Object.assign(this.calcObj, data)
        console.log(this.calcObj)
        this.getMarksByType()
        this.getModelByMark()
      })
      .catch(error => {
        console.log(error.response)
        this.$notify({
          group: 'error',
          title: 'Виникла помилка',
          text: error.response.data.message,
        })
      })
    }

    this.calcObj._token = this.getCsrf()
    this.initInputValues()
    this.calcObj.agentId = this.$store.state.user.agent.id
  }
}
</script>

<style lang="scss">
  .calculator-block {
    position: relative; 
    border-radius: 8px 8px 0 0; 
    overflow: hidden; 
    margin-right: 15px;
    box-shadow: 0 5px 6px -3px rgba(0,0,0,.2),
      0 9px 12px 1px rgba(0,0,0,.14),
      0 3px 16px 2px rgba(0,0,0,.12)!important;
    .section-title {
      font-size: 1rem;
    }
    .advance-range-scale {
      display: flex; 
      position: relative
    }
    .current-currency-label {
      font-size: 1.1rem; 
      transition: color 0.15s ease-in;
    }
    .leasing-term-append-label {
      color: grey !important; 
      display: block; 
      font-size: 1.4rem; 
      margin-top: 0;
    }
    .advance-range-wrapper {
      width: 7%; 
      height: 10px; 
      color: #969599; 
      position: relative;
      .advance-range-cell {
        z-index: 3; 
        transition: color 0.25s ease-in; 
        background: #ffffff; 
        position: relative;
      }
      .range-black-dot {
        width: 14px; 
        height: 14px; 
        background: #201600; 
        border-radius: 100%; 
        z-index: 1; 
        position: relative;
        .range-dashed-line {
          position: absolute; 
          border:none; 
          border-left: 1px dashed #201600; 
          height: 95px; 
          top: 0; 
          left: 48%; 
          right: 52%;
        }
        .arrow-directions-wrapper {
          position: absolute; 
          top: 70px; 
          display: flex; 
          justify-content: space-between;
          .arrow-directions-content {
            display: inline-flex; 
            color: #969599; 
            align-items: center; 
            margin-right: 40px;
          }
        }
      }
    }
    .step-schedule-label {
      display: block; 
      font-size: 1.1rem; 
      padding-top: 5px;
    }
  }
  
  .v-input__slot {
    fieldset  {
      border: 2px solid #efefef!important;
    }
  }
  .v-card{ 
    &.black-border-left {
      border-left: 3px solid #ff0000!important;
    }
  }
  .v-input--checkbox {
    label {
      margin-bottom: 0;
      padding-top: 3px;
      font-size: 1.1rem;
    }
  }
  .calculator-red-block {
    background-color: #d24a43; 
    z-index: 0;
    .claculator-block-border {
      position: absolute; 
      top: 19px; 
      right: 19px; 
      left: 19px; 
      bottom: 19px; 
      border:1px solid #e5e5e5; 
      border-radius: 8px;
    }
    .v-messages__message {
      color: white!important;
    }
    .leasing-type-radio,
    .auto-type-radio {
      .v-messages__message {
        text-align: center;
      }
      .v-input--radio-group__input {
        justify-content: center;
      }
      &.xs {
        .v-input--radio-group__input {
          justify-content: flex-start;
        }
      }
      .leasing-type-radio-wrapper,
      .auto-type-radio-wrapper {
        
        display: flex;
        .v-radio {
          padding: 7px 0;
        }
        .red-block-radio-label {
          font-size: 1.2rem; 
          color: white;
          white-space: nowrap;
        }
        &.small {
          flex-direction: column;
        }
      }
    }
    .v-input {
      fieldset {
        border: none!important;
      }
    }
    .discount-price {
      .v-input__slot  {
        margin-bottom: 0;
      }
      label {
        padding-top: 8px;
        margin-bottom: 5px;
        color: white!important;
      }
    }
    .v-select__selection, input {
      font-size: 1.28rem!important;
      font-weight: bold!important;
    }
    .v-select__slot, .v-text-field__slot {
      label {
        font-size: 1.28rem!important;
      }
    }
    .v-input--selection-controls__ripple:before {
      background: none!important;
    }
    .v-radio, label {
      color: white;
    }
    .v-select__slot, .v-text-field__slot {
      label {
        &.v-label--active {
          top: -3px!important;
          color: white!important;
        }
      }
    }
    .v-input .v-input__slot {
      border-radius: 8px!important;
    }
    .v-input .v-input__control {
      .v-text-field__details {
        margin-bottom: 0;
      }
    }
    .leasing-types {
      padding-top: 55px;
      .leasing-type-icon {
        height: 50px; 
        margin-left: 0.40rem;
      }
    }
    .leasing-types input {
      display: none;
      margin-bottom: 18px;
    }
    .leasing-type-block {
      display: inline-block;
      padding: 10px 12px 15px 12px; 
      max-width: 140px;
      border-radius: 6px;
      color: white;
      padding-bottom: 0.35rem;
      transition: border-bottom 0.25s ease-in, color 0.25s ease-in;
      span {
        display: block;
        border-bottom: 4px solid white;
        padding: 0.4rem;
      }
      &.active {
        opacity: 1!important;
        background: white!important;
        color: #d24a43;
        span {
          border-bottom: 4px solid #d24a43;
        }
      }
      &:hover {
        transition: opacity 0.15s ease-in;
        cursor: pointer;
        opacity: 0.8;
        background: rgb(255, 255, 255);
        color: #d24a43;
        span {
          border-bottom: 4px solid #d24a43;
        }
      }
    }
  }
  .calculator-white-block {
    margin: 0 39px 39px 39px;
    color: #424242;
    .leasing-term-sm {
      .v-text-field__details {
        margin-bottom: 0!important;
      }
    }
    .financing-currency {
      .v-messages__message {
        text-align: center;
      }
    }
    .graphs {
      label {
        margin-bottom: 8px;
      }
    }
    .v-input--selection-controls__ripple:before {
      background: none!important;
    }
    .v-list-item__content {
      .v-list-item__title {
        font-size: 1rem!important;
      }
    }
    .v-input .v-input__slot {
      border-radius: 8px!important;
      .v-label {
        margin-bottom: 0!important;
      }
    }
    .v-select__selection, input {
      font-size: 1.28rem!important;
      font-weight: bold!important;
    }
    .v-select__slot, .v-text-field__slot {
      label {
        font-size: 1.2rem!important;
        // font-weight: bold!important;
      }
    }
    .v-input {
      fieldset {
        border: 1px solid #f5f5f5;
      }
    }
  }
  
  /*  Slider */
  #tickmarks {
    display: block;
  }
  .slider {
		-webkit-appearance: none;
		// position: absolute;
		top: 60px;
		// border-radius: 0 0 5px 5px;
		left: 0px;
		width: 100%;
		height: 6px;
		background: #d24a43;
		-webkit-transition: .2s;
		transition: opacity .2s;
	} 
	.slider::-webkit-slider-thumb {
		// border: 8px solid #FAFAFA;
    position: relative;
    z-index:5;
		box-shadow: -1px -1px 9px -4px rgba(0,0,0,0.75);
    appearance: none;
    width: 9px;
		height: 25px!important;
		// border-radius: 50%;
		background: #d24a43;
		cursor: pointer;
	}
</style>