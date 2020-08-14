<template>
<div class="calculator-block">
  <div :class="`${mediumAndDown ? 'calculator-red-block small' : 'calculator-red-block' }`">
    <div class="claculator-block-border">
    </div>
    <div style="margin: 0 39px">
      <v-row v-show="!smallerThenMedium" :class="`${mediumAndDown ? 'leasing-types small' : 'leasing-types'}`">
        <v-col style="display: flex; justify-content: space-around;">
          <input
            @change="
              getMarksByType($event);
              addActiveClass($event);"
            type="radio"
            class="radio-objectType"
            id="car"
            name="leasing-type"
            checked
            value="Легкові та комерційні авто">
          <label for="car" id="Легкові та комерційні авто" :class="mediumAndDown ? 'leasing-type-block small' : 'leasing-type-block'">
            <car :width="mediumAndDown ? '40' : '47'" :heiht="mediumAndDown ? '18' : '21'" class="leasing-type-icon"></car>
            <span class="leasing-type-label" style="white-space: nowrap">ЛЕГКОВI АВТО</span>
          </label>
          <input
            @change="
              getMarksByType($event);
              addActiveClass($event)"
            type="radio"
            class="radio-objectType"
            id="cargo"
            name="leasing-type"
            value="Вантажні авто">
          <label for="cargo" id="Вантажні авто" :class="mediumAndDown ? 'leasing-type-block small' : 'leasing-type-block'">
            <cargo :width="mediumAndDown ? '44' : '65'" :heiht="mediumAndDown ? '23' : '31'" class="leasing-type-icon"></cargo>
            <span class="leasing-type-label">ВАНТАЖIВКИ</span>
          </label>
          <input
            @change="
              getMarksByType($event);
              addActiveClass($event);"
            class="radio-objectType"
            type="radio"
            id="special"
            name="leasing-type"
            value="СПЕЦІАЛЬНІ ТЗ">
          <label for="special" id="СПЕЦІАЛЬНІ ТЗ" :class="mediumAndDown ? 'leasing-type-block small' : 'leasing-type-block'">
            <special :width="mediumAndDown ? '39' : '50'" :heiht="mediumAndDown ? '39' : '50'" class="leasing-type-icon"></special>
            <span class="leasing-type-label">СПЕЦТЕХНIКА</span>
          </label>
          <input
            @change="
              getMarksByType($event);
              addActiveClass($event);"
            type="radio"
            class="radio-objectType"
            id="agricultural"
            name="leasing-type"
            value="Сільгосптехніка">
          <label for="agricultural" id="Сільгосптехніка" :class="mediumAndDown ? 'leasing-type-block small' : 'leasing-type-block'">
            <agricultural :width="mediumAndDown ? '35' : '45'" :heiht="mediumAndDown ? '35' : '39'" class="leasing-type-icon"></agricultural>
            <span class="leasing-type-label" style="white-space: nowrap">С/Г ТЕХНIКА</span>
          </label>
          <input
            @change="
              getMarksByType($event);
              addActiveClass($event);"
            type="radio"
            class="radio-objectType"
            id="bus"
            name="leasing-type"
            checked
            value="Автобуси">
          <label for="bus" id="Автобуси" :class="mediumAndDown ? 'leasing-type-block small' : 'leasing-type-block'">
            <bus :width="mediumAndDown ? '40' : '47'" :heiht="mediumAndDown ? '18' : '21'" class="leasing-type-icon"></bus>
            <span class="leasing-type-label" style="white-space: nowrap">АВТОБУСИ</span>
          </label>
          <input
            @change="
              addActiveClass($event);
              getMarksByType($event, 0);
            "
            type="radio"
            class="radio-objectType"
            id="equipment"
            name="leasing-type"
            value="Обладнання">
          <label for="equipment" id="Обладнання" :class="mediumAndDown ? 'leasing-type-block small' : 'leasing-type-block'">
            <equipment :width="mediumAndDown ? '34' : '40'" :heiht="mediumAndDown ? '34' : '40'" class="leasing-type-icon"></equipment>
            <span class="leasing-type-label">ОБЛАДНАННЯ</span>
          </label>
          <input
            @change="
              getMarksByType($event);
              addActiveClass($event);"
            type="radio"
            class="radio-objectType"
            id="trailer"
            name="leasing-type"
            value="Причепи та Напівпричепи">
          <label for="trailer" id="Причепи та Напівпричепи" :class="mediumAndDown ? 'leasing-type-block small' : 'leasing-type-block'">
            <trailer :style=" mediumAndDown ? 'width: 34px; height: 50px; margin-left: 8px' : 'width: 47px; height: 50px; margin-left: 8px'"></trailer>
            <span class="leasing-type-label">ПРИЧЕПИ</span>
          </label>
        </v-col>
      </v-row>
      <v-row v-show="smallerThenMedium" class="pt-8">
        <v-col class="pb-0">
          <v-select
            v-model="calcObj.leasingObjectType"
            @change="getMarksByType($event)"
            label="Предмет лiзингу"
            background-color="white"
            append-icon="mdi-chevron-down"
            itemColor="red darken-4"
            :items="selects.itemTypes"
            item-text="label"
            item-value="label"
            return-object
            outlined
            dense>
          </v-select>
        </v-col>
      </v-row>
      <div :class="`client-type-wrapper ${mediumAndDown ? 'pb-0' : 'pb-4'}`">
        <div>
          <v-row :class="`justify-space-around ${smallerThenMedium ? 'client-type-radio small' : 'client-type-radio'}`">
            <v-col cols="12" md="6" sm="4" class="pt-0 pb-0">
              <!-- :disabled="calcObj.leasingObjectType === null" -->
              <v-radio-group
                :class="`leasing-type-radio ${mediumAndDown && !xs ? 'small' : mediumAndDown && xs ? 'small xs' : ''}`"
                dark
                color="white"
                v-model="calcObj.leasingClientType"
                :error-messages="leasingClientTypeErr"
                row :dense="mediumAndDown" >
                <div :class="`leasing-type-radio-wrapper ${mediumAndDown ? 'small' : ''}`" :style="smallerThenMedium ? 'flex-direction: column' : ''">
                  <v-radio :value="2" color="white" dense>
                    <template #label>
                      <span class="red-block-radio-label">Юридична особа</span>
                    </template>
                  </v-radio>
                  <v-radio :value="1" color="white" dense>
                    <template #label>
                      <span class="red-block-radio-label">Фiзична особа</span>
                    </template>
                  </v-radio>
                </div>
              </v-radio-group>
            </v-col>
            <v-col cols="12" md="6" sm="4" class="pt-0 pb-0">
              <!-- :disabled="calcObj.leasingObjectType === null" -->
              <v-radio-group
                dark
                :class="`auto-type-radio ${mediumAndDown && !xs ? 'small' : mediumAndDown && xs ? 'small xs' : ''}`"
                color="white"
                v-model="calcObj.isNew"
                :error-messages="leasingClientTypeErr"
                row :dense="mediumAndDown" >
                <div :class="`auto-type-radio-wrapper ${mediumAndDown ? 'small' : xs ? 'small xs' : ''}`" :style="smallerThenMedium ? 'flex-direction: column' : ''">
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
        <v-col cols="12" md="3" sm="6" xs="12"  :class="`pb-0 ${mediumAndDown ? 'pt-2' : ''}`">
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
            :readonly="calcObj.leasingObjectType.label === 'Обладнання'"
            color="grey darken-2"
            outlined :dense="mediumAndDown">
          </v-autocomplete>
        </v-col>

        <v-col 
          v-if="calcObj.leasingObjectType.label !== 'Обладнання'" 
          cols="12" md="3" sm="6" xs="12"  
          :class="`pb-0 ${mediumAndDown ? 'pt-2' : ''}`">
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
            :loading="modelLoader && modelOfItem"
            :disabled="calcObj.leasedAssertMark === null"
            color="grey darken-2"
            outlined :dense="mediumAndDown">
          </v-autocomplete>
        </v-col>

        <v-col
          v-if="calcObj.leasingObjectType.label === 'Обладнання'"
          cols="12" md="3" sm="6" xs="12"  
          :class="`pb-0 ${mediumAndDown ? 'pt-2' : ''}`">
          <v-text-field
            v-model="falsyLeasedAssertModel"
            :error-messages="leasedAssertModelErr"
            itemColor="red darken-4"
            background-color="white"
            label="Назва обладнання"
            loaderHeight="1"
            color="grey darken-2"
            outlined :dense="mediumAndDown">
          </v-text-field>
        </v-col>

        <v-col cols="12" md="3" sm="6" xs="12"  :class="`pb-0 ${mediumAndDown ? 'pt-2' : ''}`">
          <v-select
            v-model="calcObj.leasingObjectYear"
            :items=" calcObj.isNew ? selects.itemYears : selects.oldItemYears"
            background-color="white"
            append-icon="mdi-chevron-down"
            itemColor="red darken-4"
            :error-messages="leasingObjectYearErr"
            label="Рік"
            color="red darken-4"
            outlined :dense="mediumAndDown">
          </v-select>
        </v-col>
        <v-col cols="12" md="3" sm="6" xs="12"  :class="`pb-0 ${mediumAndDown ? 'pt-2' : ''}`">
          <v-text-field
            v-show="calcObj.leasingObjectType.label !== 'Причепи та Напівпричепи' && calcObj.leasingObjectType.label !== 'Обладнання'"
            @input="amountToLocalStr('leasedAssertEngine')"
            v-model="calcObj.leasedAssertEngine"
            :error-messages="leasedAssertEngineErr"
            background-color="white"
            id="leasedAssertEngine"
            label="Об'єм двигуна"
            :disabled="!yearOfModel"
            color="red darken-4"
            outlined :dense="mediumAndDown">
            <template v-slot:append>
              <span style="color: grey!important; display: block; margin-top: 5px;">см3</span>
            </template>
          </v-text-field>
        </v-col>
      </v-row>
      <!-- <v-row class="justify-center">
        <v-col cols="11" class="pa-0">
          <v-divider dark></v-divider>
        </v-col>
      </v-row> -->
      <v-row>
        <v-col cols="12" md="3" sm="6" xs="12"  :class="`pb-0 ${mediumAndDown ? 'pt-2' : ''}`">
          <!-- @input="$v.calcObj.leasingAmount.$touch();
              parseToInt('leasingAmount')" -->
          <!-- v-model="calcObj.leasingAmount" -->
          <!--  -->
          <v-text-field
            @input="amountToLocalStr('leasing-amount')"
            v-model="calcObj.leasingAmount"
            :error-messages="itemCostErrors"
            background-color="white"
            id="leasing-amount"
            label="Вартість"
            color="red darken-4"
            maxlength="20"
            outlined :dense="mediumAndDown">
            <!-- <template v-slot:append>
              <span style="color: grey!important; display: block; margin-top: 5px;">грн</span>
            </template> -->
          </v-text-field>
        </v-col>
        <v-col cols="12" md="3" sm="6" xs="12" :class="`pb-0 ${mediumAndDown ? 'pt-2' : ''}`">
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
            :dense="mediumAndDown">
          </v-select>
        </v-col>
        <v-col cols="12" md="3" sm="6" xs="12" v-if="hasForeignCurrency"  :class="`pb-0 ${mediumAndDown ? 'pt-2' : ''}`">
          <v-text-field
            v-model="calcObj.leasingCurrencyCourse"
            :error-messages="leasingCurrencyCourseErr"
            @input="parseToFloat('leasingCurrencyCourse')"
            background-color="white"
            itemColor="red darken-4"
            id="leasingCurrencyCourse"
            label="Курс"
            color="red darken-4"
            outlined :dense="mediumAndDown"
            :disabled="calcObj.currency === null">
          </v-text-field>
        </v-col>
        <v-col cols="12" md="3" sm="6" xs="12" :class="`pb-0 ${mediumAndDown ? 'pt-2' : ''}`">
          <v-text-field
            v-model="calcObj.leasingQuantity"
            :error-messages="leasingQuantityErr"
            @input="parseToInt('leasingQuantity')"
            background-color="white"
            id="leasingQuantity"
            label="Кiлькiсть"
            min="1"
            color="red darken-4"
            outlined :dense="mediumAndDown"
            :disabled="calcObj.currency === null">
          </v-text-field>
        </v-col>
      </v-row>
      <v-row :class="`${mediumAndDown ? 'pb-0' : 'pb-2'}`">
        <v-col cols="12" class="pt-0 pb-0">
          <!--  -->
          <v-checkbox
            :disabled="calcObj.leasingAmount === null || calcObj.leasingAmount === ''"
            v-model="discountPrice"
            :value="true"
            :class="`${mediumAndDown ? 'discount-price small' : 'discount-price'} mt-0 pt-0 white--text`"
            label="Вартiсть зi знижкою"
            :false-value="false"
            dark :dense="mediumAndDown">
          </v-checkbox>
        </v-col>
        <v-col :class="`${mediumAndDown ? 'pb-0 pt-0' : ''}`" cols="12" md="4" sm="6" xs="12" v-if="discountPrice && calcObj.leasingAmount !== ''">
          <v-text-field
            @input="amountToLocalStr('discount-price')"
            id="discount-price"
            :error-messages="discountPriceErr"
            v-model="calcObj.leasingAmountDkp"
            background-color="white"
            color="red darken-4"
            outlined :dense="mediumAndDown">
          </v-text-field>
        </v-col>
      </v-row>
    </div>
  </div>
  <div :class="`${mediumAndDown ? 'calculator-white-block small' : 'calculator-white-block' }`">
    <v-row style="color: #505050">
      <v-col cols="12" class="pb-0 graphs">
        <div>
          <span class="section-title">Графiк платежiв</span>
        </div>
        <v-row :class="`d-flex pt-1 ${ mediumAndDown?  'graph-checkbox small' : 'graph-checkbox'}  justify-space-around pl-5 pr-5`">
          <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
            <v-checkbox
              v-model="calcObj.graphType"
              :error-messages="graphsErr"
              class="mt-0 pt-0"
              label="Класичний"
              color="red darken-3"
              value="even"
              :dense="mediumAndDown">
            </v-checkbox>
          </v-col>
          <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
            <v-checkbox
              v-model="calcObj.graphType"
              :error-messages="graphsErr"
              class="mt-0 pt-0"
              label="Ануїтет"
              color="red darken-3"
              value="annuity"
              :dense="mediumAndDown">
            </v-checkbox>
          </v-col>
          <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
            <v-checkbox
              v-model="calcObj.graphType"
              :error-messages="graphsErr"
              class="mt-0 pt-0"
              label="Iндивiдуальний"
              color="red darken-3"
              value="irregular"
              :dense="mediumAndDown">
            </v-checkbox>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-row :style="`display: flex; justify-content: ${mediumAndDown ? 'center;' : 'space-between;'}`">
      <v-col cols="12">
        <v-row :style="`color: #505050; ${mediumAndDown ? 'flex-direction: column-reverse;' : ''}`">
          <v-col :cols="mediumAndDown ? 12 : 7" style="align-self: flex-start;" :class="mediumAndDown ? 'pt-0' : ''">
            <v-row>
              <v-col cols="12" class="pt-0 pb-0" style="min-height: 232px;">
                <div>
              <span class="section-title">Авансовий платiж</span>
            </div>
            <v-text-field
              v-model="calcObj.advance"
              label=""
              color="red darken-4"
              readonly
              outlined
              :dense="mediumAndDown">
              <template v-slot:append>
                <percent style="margin-top: 5px;"></percent>
              </template>
            </v-text-field>
            <div style="position: relative">
              <input
                type="range"
                id="advance-payment"
                name="advance-payment"
                :min="elRange.min"
                :max="elRange.max"
                v-model="calcObj.advance"
                step="1"
                class="slider"
                @input="initElRange($event)"
                :disabled="advanceDisabled">
              <div class="advance-range-scale pt-6">
                <div
                  v-for="v in advanceDevision"
                  :key="v"
                  :class="`advance-range-wrapper ${$vuetify.breakpoint.xs ? 'small' : ''}`">
                  <span
                    class="advance-range-cell"
                    :style="`color: ${calcObj.advance == ((v - 1) * advanceRangeCell) ? 'black; font-weight: bold;' : '#969599;' } font-size: ${xs ? '0.78rem' : '0.725rem'}`">
                      {{ (v - 1) * advanceRangeCell + '%' }}
                  </span>
                  <div v-if="v === middleOfAdvanceRange" style="position: absolute; top: -34px;">
                    <div class="range-black-dot">
                      <advance-hint style="position: absolute; right: -61px; margin-right: 1px; color: black; transform: translateX(-50%)"></advance-hint>
                      <div class="arrow-directions-wrapper">
                        <div class="arrow-directions-content">
                          <div :class="calcObj.advance <= 29 ? 'range-active-label' : ''" :style="`display: inline-block; margin-right: 1.2rem; text-align: right; font-size: ${xs ? '0.5rem;' : '0.7rem;'}`">
                            <span style="white-space: nowrap; transition: color 0.4s">З ФIНАНСОВИМИ</span> ДОКУМЕНТАМИ
                          </div>
                          <!-- <div>
                            <calculator-left-arrow></calculator-left-arrow>
                          </div> -->
                        </div>
                        <div style="display: inline-flex; align-items: center;">
                          <!-- <div style="display: inline-block;">
                            <calculator-right-arrow></calculator-right-arrow>
                          </div> -->
                          <div style="display: inline-block; margin-left: 1.2rem">
                            <span :class="calcObj.advance >= 30 ? 'range-active-label' : ''" :style="`font-size: ${xs ? '0.5rem;' : '0.7rem;'}`">
                              <span style="white-space: nowrap; transition: color 0.4s">БЕЗ ФIНАНСОВИХ</span> ДОКУМЕНТIВ
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  :style="`position: absolute; right:${xs ? '-20px;' : '-19px;'} ; color: ${calcObj.advance == '70' ? 'black; font-weight: bold;' : '#969599;'} font-size: ${xs ? '0.78rem' : '0.725rem'}`">
                  {{ `70%` }}
                </div>
              </div>
            </div>
              </v-col>
            </v-row>

            <v-row>
              <v-col :class="`pb-0 pt-0 ${mediumAndDown ? '' : 'mt-6'} leasing-term-sm`" cols="12" md="4" xs="12" sm="5">
                <div class="pb-1">
                  <span class="section-title" style="white-space: nowrap">Термiн фiнансування</span>
                </div>
                <v-select
                  v-model="calcObj.leasingTerm"
                  :error-messages="leasingTermErr"
                  :items="[12, 24, 36, 48, 60]"
                  color="red darken-4"
                  itemColor="red darken-4"
                  outlined :dense="mediumAndDown">
                  <template v-slot:append>
                    <span class="leasing-term-append-label">мiс</span>
                  </template>
                </v-select>
              </v-col>
              <v-col 
                :class="`pt-0 pb-0 ${mediumAndDown 
                  ? '' 
                  : 'mt-6'}`" 
                cols="12" md="4" xs="12" sm="5" 
                :style="!xs 
                  ? 'margin-left: 1rem;' 
                  : ''">
                <div class="pb-1">
                  <span class="section-title" style="white-space: nowrap">Залишкова вартiсть</span>
                </div>
                <!-- :error-messages="residualValueErr" -->
                <v-select
                  v-model="calcObj.leasingRest"
                  :placeholder="`${calcObj.leasingRest}`"
                  @input="restrictToPercentAdvance('residual-value')"
                  :error-messages="leasingRestErr"
                  :items="leasingRestItems"
                  label="Вiдсоток"
                  id="residual-value"
                  color="red darken-4"
                  itemColor="red darken-4"
                  outlined :dense="mediumAndDown">
                  <template v-slot:append>
                    <percent style="margin-top: 5px;"></percent>
                  </template>
                </v-select>
              </v-col>
              <v-col cols="12" md="6" sm="12" class="pb-0 pt-0 leasing-term-sm">
                <span class="section-title" :style="mediumAndDown ? 'display: block' : 'display: inline-block;'">Валюта фiнансування</span>
                <v-radio-group
                  :class="`financing-currency d-inline-block mt-0 ${xs ? ' pt-2' : ''}`"
                  :error-messages="leasingCurrencyErr"
                  color="red darken-4"
                  v-model="calcObj.leasingCurrency"
                  dense>
                  <v-row class="pl-2" :style="`display: flex; ${$vuetify.breakpoint.width < 450 ? 'flex-direction: column;' : 'flex-direction: row;'}`">
                    <div>
                      <v-radio value="UAH" color="red darken-3" class="ml-3">
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
                      <v-radio value="USD" color="red darken-3" class="ml-3">
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
                      <v-radio value="EURO" color="red darken-3" class="ml-3">
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
          <v-col v-if="hasIrregular" :cols="mediumAndDown ? 12 : 5" :class="`${mediumAndDown ? '' : 'pl-6'} `">
            <div class="pb-1" :style="`text-align: ${mediumAndDown ? '' : 'center;'};`">
              <span class="section-title">Оберiть тип нестандартного графiку</span>
            </div>
            <v-row class="d-flex justify-space-around">
              <v-col cols="5" xs="12" class="text-center">
                <span
                  @click="changeCustomGraph(3)"
                  text
                  :style="`cursor: pointer; color: ${calcObj.customGraphicType === 3 ? '#d24a43' : 'black'}`"
                  ><b>СТУПIНЧАТЕ ПОСИЛЕННЯ</b>
                </span>
              </v-col>
              <v-col cols="6" xs="12" class="text-center">
                <span
                  @click="changeCustomGraph(5)"
                  text
                  :style="`cursor: pointer;  color: ${calcObj.customGraphicType === 5 ? '#d24a43' : 'black'}`"
                  ><b>УНIВЕРСАЛЬНЕ ПОСИЛЕННЯ</b>
                </span>
              </v-col>
            </v-row>
            <v-col cols="12" v-show="calcObj.customGraphicType === 3" class="pt-0">
              <v-row style="display: flex; justify-content: space-around">
                <v-col cols="12" md="6" sm="6"  class="pt-0 pb-0">
                  <span style="font-size: 1rem; color: #787878;">Параметри ступеневого графiку</span>
                  <v-text-field
                    :error-messages="oneThirdErr"
                    color="red darken-3"
                    type="number"
                    id="stepGain-oneThird"
                    name="stepGain-oneThird"
                    @input="setGraphProportion($event, 'stepGain-oneThird');
                      $v.calcObj.customStepOptionFirst.$touch()"
                    @blur="$v.calcObj.customStepOptionFirst.$touch()"
                    min="0"
                    max="100"
                    :dense="mediumAndDown"
                    v-model="customStepOptionFirst"
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
                <v-col cols="12" md="6" sm="6" class="pt-0 pb-0">
                  <v-text-field
                    @input="setGraphProportion($event, 'stepGain-twoThirds');
                      $v.calcObj.customStepOptionMiddle.$touch()"
                    @blur="$v.calcObj.customStepOptionMiddle.$touch()"
                    :error-messages="twoThirdsErr"
                    color="red darken-3"
                    type="number"
                    id="stepGain-twoThirds"
                    name="stepGain-twoThirds"
                    min="0"
                    max="100"
                    :dense="mediumAndDown"
                    v-model="customStepOptionMiddle"
                    class="pt-0">
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
                <v-col cols="12" md="6" sm="6" class="pt-0 pb-0">
                  <v-text-field
                    color="red darken-3"
                    :dense="mediumAndDown"
                    v-model="threeThirds"
                    class="pt-0"
                    readonly>
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
            <v-col cols="12" v-show="calcObj.customGraphicType === 5" class="pt-0">
              <v-row style="justify-content: center;">
                <v-col cols="12" md="6" sm="6" class="pt-0 pb-0">
                  <span style="font-size: 1rem; color: #787878">Параметри унiверсального посилення</span>
                  <v-text-field
                    color="red darken-3"
                    :error-messages="universalGainErr"
                    v-model="calcObj.customUniversalOption"
                    id="universalGain"
                    dense>
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
      <v-col cols="12" class="pt-0 pb-0">
        <div class="wrap-collabsible">
          <input id="insurance" class="toggle" type="checkbox">
          <label for="insurance" class="lbl-toggle">Страхування</label>
          <div class="collapsible-content">
            <div class="content-inner">
              <v-row class="d-flex justify-space-between">
                <v-col cols="12" md="5" sm="6" xs="12" class="pb-0">
                  <!--  -->
                  <v-select
                    v-model="calcObj.insuranceProgram"
                    append-icon="mdi-chevron-down"
                    :items="calcObj.leasingObjectType.label === 'Легкові та комерційні авто' 
                      ? selects.insurancePrograms 
                      : [{text: 'Стандарт', value: 1}]"
                    :error-messages="insuranceProgramErr"
                    item-text="text"
                    item-value="value"
                    label="Програма страхування"
                    itemColor="red darken-4"
                    color="red darken-4"
                    outlined
                    :dense="mediumAndDown">
                  </v-select>
                </v-col>
                <v-col cols="12" md="6" sm="6" xs="12" style="padding-top: 3px;">
                  <div style="margin-bottom: 9px; padding-left: 15px;">
                    <span style="font-size: 0.95rem; color: #757575; ">Франшиза (%)</span>
                  </div>
                  <input
                    type="range"
                    id="franchise"
                    name="franchise"
                    :min="franchise.min"
                    :max="franchise.max"
                    v-model="insuranceFranchise"
                    step="0.5"
                    class="slider"
                    @input="initElRange($event)">
                  <div style="display: flex; position: relative; margin-right: 14px; font-size: 1rem" class="pt-4">
                    <div
                      v-for="v in ['0', '0.5']"
                      :key="v"
                      style="position: relative; width: 50%;'">
                      <span :style="`color: #969599; transition: color 0.2s ease-in; color: ${insuranceFranchise == v ? 'black; font-weight: bold;' : '#969599;' }`">{{ v }}</span>
                    </div>
                    <div :style="`position: absolute; transition: color 0.2s ease-in; right: -15px; color: ${insuranceFranchise == '1' ? 'black; font-weight: bold;' : '#969599;'} `">
                      {{ `1` }}
                    </div>
                  </div>
                </v-col>
              </v-row>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>
    <v-row>
      <!-- Додатковi умови -->
      <v-col cols="12">
        <div class="wrap-collabsible">
          <input id="additional-conditions" class="toggle" type="checkbox">
          <label for="additional-conditions" class="lbl-toggle">Додатковi умови</label>
          <div class="collapsible-content">
            <div class="content-inner">
              <v-row>
                <v-col cols="12" md="5" sm="6" class="pt-0 pb-0">
                  <v-select
                    v-model="calcObj.stock"
                    append-icon="mdi-chevron-down"
                    :items="selects.stock"
                    item-text="text"
                    item-value="value"
                    label="Акцiя"
                    itemColor="red darken-4"
                    color="red darken-4"
                    outlined
                    :dense="mediumAndDown">
                  </v-select>
                </v-col>
                <v-col cols="12" md="7" sm=6 class="pt-1">
                  <div style="padding-left: 10px;">
                    <v-checkbox
                      v-model="calcObj.holidays"
                      :value="1"
                      :false-value="2"
                      color="red darken-3"
                      class="mt-0"
                      :style="mediumAndDown ? '' : 'padding-top: 14px;'"
                      :dense="mediumAndDown">
                      <template #prepend>
                        <span 
                          :style="`display: inline-block; 
                            margin-top: 2px; 
                            font-size: 1.2rem; 
                            color: #757575; 
                            white-space: nowrap;
                            position: relative;`">
                          Канiкули &nbsp;
                          <v-tooltip top>
                            <template v-slot:activator="{ on }">
                              <v-icon v-on="on" size="21" style="position: absolute; right: -8px; top: -5px; cursor: pointer;" color="#e04d45" v-text="'mdi-information-variant'"></v-icon>
                            </template>
                            <span>3 місяці в графіку без оплат</span>
                          </v-tooltip>
                        </span>
                      </template>
                    </v-checkbox>
                  </div>
                </v-col>
              </v-row>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>
    <v-card-actions class="d-flex justify-center ">
      <span>
        <v-btn @click="submit()" class="mb-3" dark color="grey darken-3 calculate-btn" :dense="xs" :loading="calculationLoader">
        {{'Розрахувати'}}
        </v-btn>
        <!-- <v-btn @click="test()">test</v-btn> -->
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
import Bus from '../../assets/svg-icons/bus.vue'
import Trailer from '../../assets/svg-icons/trailer.vue'
import Percent from '../../assets/svg-icons/percent'
import advanceHint from '../../assets/svg-icons/avans-hint'

export default {
  mixins: [validationMixin],
  components: {
    Car,
    Agricultural,
    Equipment,
    Special,
    Bus,
    Cargo,
    Trailer,
    Percent,
    advanceHint
  },
  data:() => ({
    selects: selectsItemAndValue,
    minCarCost: 150000,
    insuranceProgram: null,
    commonErr: ['Обов`язкове поле'],
    windowInnerWidth: null,
    discountPrice: false,
    modelLoader: false,
    calculationLoader: false,
    falsyLeasedAssertModel: null,

    category: null,

    brandItems: [],
    modelItems: [],

    elRange: {
      min: 0,
      max: 70
    },
    franchise: {
      min: 0,
      max: 1
    },
    input: {
			currentProgress: '#d24a43',
			remainingProgress: '#efefef'
		},
    advanceDisabled: false,

    customStepOptionFirst: 33,
    customStepOptionMiddle: 33,
    threeThirds: 34,

    insuranceFranchise: 0,

    calcObj: {
      customStepOptionFirst: null,
      customStepOptionMiddle: null,
      threeThirds: null,

      agentId: null,
      leasedAssertMark: null,
      leasedAssertModel: null,
      isNew: true,
      leasingObjectType: {label: "Легкові та комерційні авто", value: 1},
      leasingQuantity: 1,
      leasingObjectYear: 2020,
      leasedAssertEngine: '2 000',
      leasingClientType: 2,
      currency: 'UAH',
      leasingCurrency: 'UAH',
      leasingCurrencyCourse: null,
      leasingAmount: null,
      graphType: [],
      advance: 15,
      leasingTerm: 12,
      customUniversalOption: null,

      // new fields
      customGraphicType: 3,
      leasingRest: 0,
      stock: null,
      holidays: 2,
      insuranceProgram: 2,
      insuranceFranchise: 1,
      leasingAmountDkp: null,

      // token
      _token: null
    }
  }),
  validations() {
    return {
      calcObj: this.validationRules,
    }
  },
  computed: {
    customGraphicType() {
      return this.calcObj.customGraphicType
    },
    middleOfAdvanceRange() {
      return this.$vuetify.breakpoint.xs ? 4 : 7
    },
    advanceDevision() {
      return this.$vuetify.breakpoint.xs ? 7 : 14
    },
    advanceRangeCell() {
      return this.$vuetify.breakpoint.xs ? 10 : 5
    },
    leasingRestItems() {
      let arr = []
      let test = this.maxResidualValue / 10
      for (let i = 0; i <= test; i++) {
        arr.push(i * 10);
      }
      return arr
    },
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
        leasedAssertEngine: (() => { 
          if(this.calcObj.leasingObjectType.label !== "Причепи та Напівпричепи" && this.calcObj.leasingObjectType.label !== 'Обладнання') {
            return { required }
          } else return true
        })(),
        leasingCurrency: { required },
        leasingQuantity: { required },
        leasingAmount: { required },
        leasingRest: { required },
        //   minCost: val => {
        //     if(val == null) return false
        //     return parseInt(val.replace(/[^\d]/g, '')) >= this.minCarCost
        //   },
        // },
        leasingCurrencyCourse: (() => { 
          if(this.hasForeignCurrency) {
            return { required }
          } else return true
        })(),
        customStepOptionFirst: (() => {
          if (this.hasIrregular && this.calcObj.customGraphicType === 3){
            return { required }
          } else return true
        })(),
        customStepOptionMiddle: (() => {
          if (this.hasIrregular && this.calcObj.customGraphicType === 3){
            return { required }
          } else return true
        })(),
        // vehicleOwnerTax: { required },
        // paymentPf: { required },
        leasingTerm: { required },

        customUniversalOption: (() => { 
          if(this.hasIrregular && this.calcObj.customGraphicType === 5) {
            return { required }
          } else return true
        })(),
        leasingAmountDkp: (() => { 
          if(this.discountPrice) {
            return { required }
          } else return true
        })(),
        graphType: {
          hasIndex: (v) => {
            if(v === null) return false
            return v.length > 0
          }
        },
        advance: { required },
        insuranceProgram: { required },
        insuranceFranchise: { required },
        // residualValue: { required }
        // gainEvenGraphicMonths: { required },
        // gainEvenGraphicPercent: { required },
        // UnsrMonths: { required },
      }
    },
    computedThreeThirds() {
      let threeThirds = 100
      if (!Number.isNaN(parseInt(this.customStepOptionFirst))) {
        threeThirds = threeThirds - this.customStepOptionFirst
      }
      if (!Number.isNaN(parseInt(this.customStepOptionMiddle))) {
        threeThirds = threeThirds - this.customStepOptionMiddle
      }
      return threeThirds
    },

    maxResidualValue() {
      return -Math.ceil(-(100 - parseInt(this.calcObj.advance) - 10) / 10) * 10
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
      // const errors = []
      if (!this.$v.calcObj.leasingAmount.$error) return
      // !this.$v.calcObj.leasingAmount.minCost && errors.push(`Вартiсть має бути бильше нiж 150 000грн`)
			return this.commonErr
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
    // vehicleOwnerTaxErr() {
    //   if (!this.$v.calcObj.vehicleOwnerTax.$error) return
    //   return this.commonErr
    // },
    leasingRestErr() {
      if (!this.$v.calcObj.leasingRest.$error ) return
      return this.commonErr
    },
    paymentPfErr() {
      if (!this.$v.calcObj.paymentPf.$error ) return
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
    graphsErr() {
      if (!this.$v.calcObj.graphType.$error) return
      return this.commonErr
    },
    // residualValueErr() {
    //   if (!this.$v.calcObj.residualValue.$error) return
    //   return this.commonErr
    // },
    discountPriceErr() {
      if (!this.$v.calcObj.leasingAmountDkp.$error) return
      return this.commonErr
    },

    universalGainErr() {
      if (!this.$v.calcObj.customUniversalOption.$error) return
      return this.commonErr
    },

    oneThirdErr() {
      if (!this.$v.calcObj.customStepOptionFirst.$error) return
      return this.commonErr
    },
    twoThirdsErr() {
      if (!this.$v.calcObj.customStepOptionMiddle.$error) return
      return this.commonErr
    },

    mediumAndDown() {
      return this.windowInnerWidth <= 1185 // 1145
    },
    smallerThenMedium() {
      return this.windowInnerWidth <= 823
    },
    mediumAndDownCurrency() {
      return this.windowInnerWidth <= 1200
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
      return this.xs ? '6' : this.mediumAndDown && !this.xs ? '2' : '2'
    },
    leasingTypeClass() {
      return `d-flex justify-center ${this.xs ? 'pt-0 pb-0' : ''}`
    },
  },
  methods: {
    test() {
      axios
        .get('https://developers.ria.com/auto/categories/?api_key=oFHzd0nlvshTQ2XXufmeaAyqyaEOHV4HUKJbsXbE')
        .then(res => {
          console.log(res.data)
        })
        .catch(err => {
          console.log(err)
        })
    },
    resetForm() {
      this.calcObj = {
        customStepOptionFirst: null,
        customStepOptionMiddle: null,
        threeThirds: null,
        leasedAssertMark: null,
        leasedAssertModel: null,
        isNew: true,
        leasingObjectType: {label: "Легкові та комерційні авто", value: 1},
        leasingQuantity: 1,
        leasingObjectYear: 2020,
        leasedAssertEngine: '2 000',
        leasingClientType: 2,
        currency: 'UAH',
        leasingCurrency: 'UAH',
        leasingCurrencyCourse: null,
        leasingAmount: null,
        graphType: [],
        advance: 15,
        leasingTerm: 12,
        customUniversalOption: null,
        customGraphicType: 3,

        // new fields
        leasingRest: 0,
        stock: null,
        holidays: 2,
        insuranceProgram: 2,
        insuranceFranchise: 1,
        leasingAmountDkp: null,
        agentId: this.$store.state.user.agent.id,
        _token: this.getCsrf()
      }
      this.initAdvanceInputValue()
      this.initFranchiseInput()
    },
    changeActiveClass() {
      let el = document.querySelectorAll('.leasing-type-block')
      el.forEach(val => {
        val.classList.remove('active')
      })
      setTimeout(()=> {
        let elements = document.querySelectorAll('.radio-objectType')
        elements
        .forEach(val => {
          if(val.value == this.calcObj.leasingObjectType.label) {
            if(val.nextSibling.nextSibling.id == this.calcObj.leasingObjectType.label) {
              console.log(val.nextSibling.nextSibling.id, this.calcObj.leasingObjectType.label)
              val.nextSibling.nextSibling.classList.add('active')
            }
          }
        })
      }, 200)
    },
    restrictToPercentAdvance(id) {
      let el = document.getElementById(id)
      let inputEvent = new Event('input', {bubbles: true})
      let temp = parseInt(el.value.replace(/[^\d]/g, ''))
      if(Number.isNaN(temp) && el.value !== '') {
        el.value = ''
        el.dispatchEvent(inputEvent)
        return
      }
      if(parseInt(temp) > 100 - (parseInt(this.calcObj.advance) + 10)) {
        temp = Math.ceil((100 - parseInt(this.calcObj.advance) - 10) / 5) * 5
      }

      if(el.value != temp && !isNaN(parseInt(temp))) {
        el.value = temp
        el.dispatchEvent(inputEvent)
       }
    },
    restrictToPercent(id) {
      let el = document.getElementById(id)
      let inputEvent = new Event('input', {bubbles: true})
      let temp = parseInt(el.value.replace(/[^\d]/g, ''))
      if(Number.isNaN(temp) && el.value !== '') {
        el.value = ''
        el.dispatchEvent(inputEvent)
        return
      }
      if(parseInt(temp) > 100) {
        temp = 100
      }
      if(el.value != temp && !isNaN(parseInt(temp))) {
        el.value = temp
        el.dispatchEvent(inputEvent)
       } 
    },
    amountToLocalStr(id) {
      let el = document.getElementById(id)
      let discountPriceEl = document.getElementById('discount-price')
      let inputEvent = new Event('input', {bubbles: true})
      let temp = parseInt(el.value.replace(/\s/g, '' ))
        .toLocaleString()
        .replace(/,/g, ' ')
      let tempWithoutSpaces = parseInt(temp.replace(/[^\d]/g, ''))
      if(el.value !== temp && !Number.isNaN(parseInt(temp))) {
        if(id === 'discount-price' && this.calcObj.leasingAmount) {
          if(tempWithoutSpaces > parseInt(this.calcObj.leasingAmount.toString().replace(/[^\d]/g, '')) ){
            temp = this.calcObj.leasingAmount
          }
        } else if(id === 'leasing-amount' && this.calcObj.leasingAmountDkp) {
          // if(!this.calcObj.leasingAmountDkp) return
          if(tempWithoutSpaces < parseInt(this.calcObj.leasingAmountDkp.toString().replace(/[^\d]/g, ''))){
            this.calcObj.leasingAmountDkp = temp
          }
        }
        el.value = temp
        el.dispatchEvent(inputEvent)
      } else if(el.value != temp.replace(/[^\d ]/g, '') && Number.isNaN(parseInt(temp))) {
        el.value = temp.replace(/[^\d ]/g, '')
        el.dispatchEvent(inputEvent)
      } else {
        if(id === 'discount-price' && this.calcObj.leasingAmount) {
          if(tempWithoutSpaces > parseInt(this.calcObj.leasingAmount.toString().replace(/[^\d]/g, '')) ){
            discountPriceEl.value = this.calcObj.leasingAmount
            discountPriceEl.dispatchEvent(inputEvent)
          } 
        } else if(id === 'leasing-amount' && this.calcObj.leasingAmountDkp) {
          // if(!this.calcObj.leasingAmountDkp) return
          if(tempWithoutSpaces < parseInt(this.calcObj.leasingAmountDkp.toString().replace(/[^\d]/g, ''))){
            discountPriceEl.value = this.calcObj.leasingAmount
            discountPriceEl.dispatchEvent(inputEvent)
          } else if (this.calcObj.leasingAmount === '') {
            discountPriceEl.value = ''
            discountPriceEl.dispatchEvent(inputEvent)
          }
        }
      }
    },
    setGraphProportion(event, selector) {
      let oneThird = document.querySelector('#stepGain-oneThird')
      let twoThirds = document.querySelector('#stepGain-twoThirds')
      let currentEl = document.getElementById(selector)
      let inputEvent = new Event('input', {bubbles: true})
      currentEl.value = parseInt(currentEl.value.replace(/[^\d]/g, ''))
      if(currentEl.value > 100) {
        currentEl.value = 100
        currentEl.dispatchEvent(inputEvent)
      }
      if(Number.isNaN(currentEl.value)) {
        currentEl.value = 0
        currentEl.dispatchEvent(inputEvent)
      }
      if(selector == 'stepGain-oneThird') {
        if(parseInt(currentEl.value) + parseInt(twoThirds.value) > 100) {
          twoThirds.value = 100 - currentEl.value
          twoThirds.dispatchEvent(inputEvent)
        }
        return
      } 
      else if(selector == 'stepGain-twoThirds') {
        if(parseInt(currentEl.value) + parseInt(oneThird.value) > 100) {
          oneThird.value = 100 - currentEl.value
          oneThird.dispatchEvent(inputEvent)
        } 
        return
      }
    },
    changeCustomGraph(id) {
      this.$set(this.calcObj, 'customGraphicType', id)
    },
    closeSelect() {
      this.$refs.graphType.blur()
    },
    addActiveClass(event) {
      let el = document.querySelectorAll('.leasing-type-block')
      el.forEach(val => {
        val.classList.remove('active')
      })
      if(event) event.target.nextSibling.nextSibling.classList.add('active')
    },
    getMarksByType(event, needRequest) {
      if(event) {
        this.resetForm()
        if(this.smallerThenMedium) {
          this.category = parseInt(event.value)
          this.calcObj.leasingObjectType = event
          if(event.label === "Обладнання") {
            needRequest = 0
            this.brandItems.push({name: 'Iнше', value: 0});
            this.calcObj.leasedAssertMark = {name: 'Iнше', value: 0};
          }
        } else {
          this.calcObj.leasingObjectType = this.selects.itemTypes
            .filter(val => {
              return val.label === event.target.value
            })[0]
          this.category = this.calcObj.leasingObjectType.value
        }
      } else if(this.calcObj.leasingObjectType && this.calcObj.leasingObjectType.value) {
        this.category = this.calcObj.leasingObjectType.value
      }
      if(needRequest === 0) {
        this.brandItems.push({name: 'Iнше', value: 0});
        this.calcObj.leasedAssertMark = {name: 'Iнше', value: 0};
        return
      }
      this.brandItems = []
      this.$store.commit('toggleSpinner', true)
      axios.get(`/mark?category=${this.category}`)
        .then(response => {
          this.brandItems = response.data
          this.$store.commit('toggleSpinner', false)
          console.log(this.brandItems)
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
          let message = error.response.statusText
          this.notify('Помилка', message, 'error')
          this.$store.commit('toggleSpinner', false)
        })
    },
    getModelByMark() {
      this.modelItems = []
      this.$store.commit('toggleSpinner', true)
      this.modelLoader = true
      axios.get(`/models?category=${this.category}&mark=${this.calcObj.leasedAssertMark.value}`)
        .then(response => {
          this.modelItems = response.data
          this.modelLoader = false
          this.$store.commit('toggleSpinner', false)
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error.response)
          let message = error.response.statusText
          this.notify('Помилка', message, 'error')
          this.$store.commit('toggleSpinner', false)
          this.modelLoader = false
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
    deleteStepData() {
      delete this.calcObj.customStepOptionFirst
      delete this.calcObj.customStepOptionMiddle
    },
    deleteUnneccessaryFields() {
      delete this.calcObj.threeThirds
      this.calcObj.leasingObjectType.label === 'Причепи та Напівпричепи' || this.calcObj.leasingObjectType.label === 'Обладнання'
        ? delete this.calcObj.leasedAssertEngine : false
      this.calcObj.leasingAmountDkp === null 
        ? delete this.calcObj.leasingAmountDkp : false
      this.calcObj.stock === null 
        ? delete this.calcObj.stock : false
      this.hasIrregular && this.calcObj.customGraphicType === 3
        ? delete this.calcObj.customUniversalOption : false
      this.hasIrregular && this.calcObj.customGraphicType === 5
        ? delete this.deleteStepData() : false
    },
    submit() {
      console.log(this.$v)
      this.checkIfHasIrregular()
      this.checkIfHasCurrency()
      this.deleteUnneccessaryFields()
      this.highlightErrors()
      !this.$v.$invalid
        && this.$v.$dirty
          ? this.sendRequest()
          : this.notify('', 'Заповнiть данi', 'error')

    },
    checkIfHasCurrency() {
      if(this.hasForeignCurrency) return
      this.calcObj.leasingCurrencyCourse = 1
    },
    checkIfHasIrregular() {
      if(!this.hasIrregular) {
        this.$delete(this.calcObj, 'customGraphicType')
        this.$delete(this.calcObj, 'customUniversalOption')
        this.$delete(this.calcObj, 'customStepOptionFirst')
        this.$delete(this.calcObj, 'customStepOptionMiddle')
      }
    },
    sendRequest() {
      this.calcObj._token = this.getCsrf()
      this.calculationLoader = true
      axios
        .post('/calculate', this.calcObj)
          .then(response => {
            this.calculationLoader = false
            // this.$router.push('/calculator/chart')
            let data = response.data
            this.$router.push({name: 'Графiки', params: {data: data}})
          })
          .catch(error => {
            this.calculationLoader = false
            this.$catchStatus(error.response.status)
            const message = error.response.statusText
            this.notify('Помилка', message, 'error')
          })
    },
    valueTotal(value, min, max) {
			return ((value - min) / (max - min))
		},
		getGradient(ratio, leftColor, rightColor) {
      this.advanceDisabled = false
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
				case 'advance-payment': this.calcObj.advance = val; break
				case 'leasing-term': this.calcObj.leasingTerm = val; break
				// case 'termFinancing': this.termFinancing.current = val; break
			}
		},
		updateElRange(elRange, val, dataSelector) {
      if (dataSelector === 'advance-payment' && this.calcObj.leasingRest > this.maxResidualValue) {
        this.calcObj.leasingRest = this.maxResidualValue
      } else if (dataSelector === 'advance-payment' && this.calcObj.leasingRest < this.maxResidualValue) {
        if(this.calcObj.leasingRest != 0) {
          this.calcObj.leasingRest = this.maxResidualValue
        }
      }
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
      } else  {
        return parseInt(val.replace(/^\D/g, ''))
      }
		},
		initAdvanceInputValue(val) {
			let el = document.querySelector('#advance-payment')
			let event = new Event('input', {bubbles: true})
      if(val) {
        el.value = val
      } else {
        el.value = 15
      }
			el.dispatchEvent(event)
		},
    initFranchiseInput(val) {
      let el = document.querySelector('#franchise')
      let event = new Event('input', {bubbles: true})
      if(val) {
        el.value = val
      } else {
        el.value = el.min
      }
      el.dispatchEvent(event)
    },
		switchSelector(e) {
			let dataSelector, elRange
			if(e.target.name === 'advance-payment') {
				elRange = document.getElementById('advance-payment')
        if(parseInt(elRange.value) < 15) {
          elRange.value = 15
          this.advanceDisabled = true
        }
				dataSelector = 'advance-payment'
			} else if(e.target.name === 'franchise') {
				elRange = document.getElementById('franchise')
				dataSelector = 'franchise'
			}
			return {dataSelector, elRange}
		},
		initElRange(event) {
			let {elRange, dataSelector} = this.switchSelector(event)
			if(!event) {
				elRange.value = this.toInt(elRange.min, dataSelector)
				this.syncValue(elRange.value, dataSelector)
				this.updateElRange(elRange, elRange.value)
				return
			}
			elRange.value = this.toInt(event.target.value, dataSelector)
			let check = this.checkValue(elRange.value, elRange, dataSelector)
			if(!check) {
				this.updateElRange(elRange, elRange.value)
				return
			}
			this.syncValue(elRange.value, dataSelector)
			this.updateElRange(elRange, elRange.value, dataSelector)
		},
    setIndentation(value) {
      if(value) {
        return parseInt(value.replace(/\s/g, '' ))
          .toLocaleString()
          .replace(/,/g, ' ')
      } else return
    },
    displayWindowSize() {
      this.windowInnerWidth = window.innerWidth
    },
    getUserCalculations() {
      axios
      .get(`/calculation/${this.$router.currentRoute.params.id}`)
      .then(response => {
        this.calcObj.calculation_id = response.data.id
        let data = response.data.request_data
        let advance = response.data.request_data.advance
        if(response.data.request_data.insuranceFranchise){
          this.insuranceFranchise = this.switchFranchiseFromRequest(
              response.data.request_data.insuranceFranchise
          )
        }
        this.initAdvanceInputValue(advance)
        this.initFranchiseInput(
            this.insuranceFranchise
          )
        this.brandItems.push(data.leasedAssertMark)
        this.modelItems.push(data.leasedAssertModel)

        this.insuranceProgram = this.selects.insurancePrograms
          .find(obj => obj.value === data.insuranceProgram)
        Object.assign(this.calcObj, data)
        

        this.calcObj.leasingAmountDkp = this.setIndentation(this.calcObj.leasingAmountDkp)
        this.calcObj.leasingAmount = this.setIndentation(this.calcObj.leasingAmount)
        this.calcObj.leasedAssertEngine = this.setIndentation(this.calcObj.leasedAssertEngine)
        
        this.getMarksByType()
        this.getModelByMark()
        this.changeActiveClass()
      })
      .catch(error => {
        this.$catchStatus(error.response.status)
        this.$notify({
          group: 'error',
          title: 'Виникла помилка',
          text: error.response.data.message,
        })
      })
    },
    switchFranchiseFromRequest(value) {
      switch(value) {
        case 1: return '0'
        case 2: return '0.5'
        case 3: return '1'
      }
    }
  },
  watch: {
    
    falsyLeasedAssertModel(val) {
      this.calcObj.leasedAssertModel = {}
      this.calcObj.leasedAssertModel.name = val
      this.calcObj.leasedAssertModel.value = 0
    },
    insuranceProgram(val) {
      this.calcObj.insuranceProgram = val.value
    },
    hasIrregular(val) {
      if(val === true) {
        // this.calcObj.customUniversalOption = null
        this.calcObj.customStepOptionFirst = 33,
        this.calcObj.customStepOptionMiddle = 33,
        this.calcObj.threeThirds = 34
        this.calcObj.customGraphicType = 3
      }
    },
    insuranceFranchise(value) {
      switch(value) {
        case '0': return this.calcObj.insuranceFranchise = 1
        case '0.5': return this.calcObj.insuranceFranchise = 2
        case '1': return this.calcObj.insuranceFranchise = 3
      }
    },
    'calcObj.leasingObjectType': function(value) {
      if(value.label !== 'Легкові та комерційні авто') {
        this.calcObj.insuranceProgram = 1
      }
    },
    'calcObj.holidays': function(value) {
      if(value != 1) {
        this.calcObj.holidays = 2
      }
    },
    'customStepOptionFirst': function(value) {
      if(isNaN(parseInt(value))) {
        this.calcObj.customStepOptionFirst = null
      } else { 
        this.calcObj.customStepOptionFirst = parseInt(value) 
      }
    },
    'customStepOptionMiddle': function(value) {
      if(isNaN(parseInt(value))) { 
        this.calcObj.customStepOptionMiddle = null 
      } else {
        this.calcObj.customStepOptionMiddle = parseInt(value)
      }
    },
    'calcObj.customUniversalOption': function(value) {
      if(!value) return
      this.calcObj.customUniversalOption = parseInt(value)
    },
    computedThreeThirds(val) {
      this.threeThirds = val
    },
    'calcObj.leasingTerm': function (value) {
      if(!value) return
      this.calcObj.leasingTerm = parseInt(value)
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
    'calcObj.leasingQuantity': function(value) {
      if(!value) return value
      this.calcObj.leasingQuantity = parseInt(value)
    },
    'calcObj.advance': function(value) {
      if(!value) return value
      this.calcObj.advance = parseInt(value)
    },
    // 'calcObj.leasingObjectType': function(value) {
    //   if(Number.isInteger(value)) {
    //     let leasingObjType = this.selects.itemTypes
    //       .filter(val => {
    //         return val.value === this.calcObj.leasingObjectType
    //       })
    //     this.calcObj.leasingObjectType = leasingObjType[0]
    //   } else return
    // },
    smallerThenMedium(state) {
      if(state === false)  {
        this.changeActiveClass()
      } else return
    },
    mediumAndDown() {
      this.changeActiveClass()
    },
    user() {
      if(this.user) {
        this.calcObj.agentId = this.$store.state.user.agent.id
      } else {
        return
      }
    },
  },
  created() {
    window.addEventListener("resize", this.displayWindowSize)
  },
  mounted() {
    if(this.$router.currentRoute.params.edit === true) {
      this.getUserCalculations()
      this.changeActiveClass()
      this.initFranchiseInput()
      this.displayWindowSize()
      this.initAdvanceInputValue()
      return
    } else {
      this.calcObj.leasingObjectType = {label: "Легкові та комерційні авто", value: 1}
    }
    this.changeActiveClass()
    this.initFranchiseInput()
    this.getMarksByType()
    this.displayWindowSize()
    this.initAdvanceInputValue()
    
    this.calcObj._token = this.getCsrf()
    this.calcObj.agentId = this.$store.state.user.agent.id
  }
}
</script>

<style lang="scss">
.calculate-btn {
  .v-btn__content {
    font-size: 1.05rem
  }
}
.calculator-block {
  position: relative;
  border-radius: 8px 8px 0 0;
  overflow: hidden;
  // margin-right: 15px;
  box-shadow: 0 5px 6px -3px rgba(0,0,0,.2),
    0 9px 12px 1px rgba(0,0,0,.14),
    0 3px 16px 2px rgba(0,0,0,.12)!important;
  // ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  //   color: black!important;
  //   opacity: 1; /* Firefox */
  // }
  // :-ms-input-placeholder { /* Internet Explorer 10-11 */
  //   color: black!important;
  // }
  // ::-ms-input-placeholder { /* Microsoft Edge */
  //   color: black!important;
  // }
  .section-title {
    font-size: 1rem;
    display: inline-block;
  }
  .advance-range-scale {
    width: 100%;
    display: flex;
    position: absolute;
    right: 6px;
  }
  .current-currency-label {
    font-size: 1.1rem;
    transition: color 0.15s ease-in;
  }
  .leasing-term-append-label {
    color: grey !important;
    display: block;
    font-size: 1.2rem;
    margin-top: 3px;
  }
  .advance-range-wrapper {
    // width: 7%;
    width: 7.1%;
    &.small {
      width: 14.2%
    }
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
        left: 50%;
        transform: translate(-50%, -50%);
        top: 90px;
        display: flex;
        justify-content: space-between;
        .arrow-directions-content {
          display: inline-flex;
          color: #969599;
          align-items: center;
          margin-right: 40px;
        }
        .range-active-label {
          text-decoration: underline; 
          font-weight: bold;
          color: #d24a43; 
          font-weight: bold;
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

.v-input--checkbox {
  label {
    margin-bottom: 0;
    padding-top: 3px;
  }
}
.calculator-red-block {
  background-color: #d24a43;
  z-index: 0;
  .leasing-type-radio, .auto-type-radio .leasing-type-radio-wrapper .v-radio {
    padding: 1px 0!important;
  }
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
      // .v-radio {
      //   padding: 7px 0!important;
      // }
      .red-block-radio-label {
        font-size: 1.2rem;
        color: white;
        white-space: nowrap;
      }
      &.small {
        // flex-direction: column;
        .red-block-radio-label {
          font-size: 0.9rem
        }
      }
    }
  }
  .client-type-radio  {
    &.small {
      .v-input--selection-controls {
        margin-top: 0!important;
      }
      .v-messages {
        display: none!important;
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
    .v-messages {
      display: none!important;
    }
    label {
      padding-top: 5px;
      margin-bottom: 5px;
      color: white!important;
    }
    &.small {
      label {
        font-size: 0.9rem;
      }
    }
  }
  .v-select__selection, input {
    font-size: 1.28rem;
    font-weight: bold;
  }
  
  .v-select__slot, .v-text-field__slot {
    label {
      font-size: 1.28rem!important;
      font-weight: bold;
      &.v-label--active {
        top: 0!important;
        color: white!important;
      }
    }
  }
  .v-input--selection-controls__ripple:before {
    background: none!important;
  }
  .v-radio, label {
    color: white;
  }
  .v-input .v-input__slot {
    border-radius: 8px!important;
  }
  .v-input .v-input__control {
    .v-text-field__details {
      margin-bottom: 0;
    }
  }
  &.small {
    .v-select__selection, input {
      font-size: 1.1rem;
      font-weight: bold;
    }
    .v-select__slot, .v-text-field__slot {
      label {
        font-size: 0.95rem!important;
      }
    }
  }
  .leasing-types {
    padding-top: 55px;
    .leasing-type-icon {
      height: 50px;
      margin-left: 0.40rem;
    }
    &.small {
      .leasing-type-label {
        font-size: 0.73rem;
      }
      padding-top: 15px;
      .col {
        padding-bottom: 0!important;
      }
    }
    input {
      display: none;
      margin-bottom: 18px;
    }
  }
  .leasing-type-block {
    display: inline-block;
    padding: 10px 12px 15px 12px;
    max-width: 140px;
    border-radius: 6px;
    color: white;
    padding-bottom: 0.35rem;
    transition: border-bottom 0.25s ease-in, color 0.25s ease-in;
    &.small {
      padding: 10px 3px 10px 3px;
    }
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
  &.small {
    .v-select__selection, input {
      font-size: 1.1rem;
      font-weight: bold;
    }
    .v-select__slot, .v-text-field__slot {
      label {
        font-size: 1.05rem!important;
      }
    }
  }
  .v-input__slot {
    fieldset  {
      border: 2px solid #efefef!important;
    }
  }
  .graph-checkbox {
    .v-input--checkbox {
      margin-top: 8px;
    }
    .v-input__slot {
      display: flex;
      justify-content: center;
      margin-bottom: 0!important;
      label {
        font-size: 1.1rem;
      }
    }
    &.small {
      .v-input__slot {
        display: flex;
        justify-content: flex-start;
        label {
          font-size: 0.95rem!important;
        }
      }
      .v-select__selection, input {
        font-size: 1.07rem;
        font-weight: bold!important;
      }
    }
    label {
      display: inline-flex!important;
    }
    .v-input--selection-controls .v-input__slot>.v-label, .v-input--selection-controls .v-radio>.v-label {
      flex: 0!important;
    }
  }
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
    font-size: 1.28rem;
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
// input[type=range]::-webkit-slider-thumb {
//   -webkit-appearance: none;
// }
.slider::-webkit-slider-thumb {
  // border: 8px solid #FAFAFA;
  position: relative;
  z-index:5;
  box-shadow: -1px -1px 9px -4px rgba(0,0,0,0.75);
  appearance: none;
  width: 6px!important;
  padding: 0px;
  margin: 0;
  height: 25px!important;
  // border-radius: 50%;
  background: #d24a43!important;
  cursor: pointer;
  -webkit-appearance: none;
}

  /* Collapsible */
.wrap-collabsible {
  margin-bottom: 1.2rem 0;
}

input[type='checkbox'] {
  display: none;
}

.lbl-toggle {
  display: block;

  font-size: 1rem;
  text-transform: uppercase;
  text-align: center;

  padding: 0.6rem;

  color: white;
  background: #e04d45;

  cursor: pointer;

  border-radius: 7px;
  transition: all 0.25s ease-out;
}

// .lbl-toggle:hover {
//   color: #7C5A0B;
// }

.lbl-toggle::before {
  content: ' ';
  display: inline-block;
  margin-top: 3px;
  border-top: 6px solid transparent;
  border-bottom: 6px solid transparent;
  border-left: 6px solid currentColor;
  vertical-align: middle;
  margin-right: .7rem;
  transform: translateY(-2px);

  transition: transform .2s ease-out;
}

.toggle:checked + .lbl-toggle::before {
  transform: rotate(90deg) translateX(-3px);
}

.collapsible-content {
  max-height: 0px;
  overflow: hidden;
  transition: max-height .25s ease-in-out;
}

.toggle:checked + .lbl-toggle + .collapsible-content {
  max-height: 100vh;
}

.toggle:checked + .lbl-toggle {
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.collapsible-content .content-inner {
  // background: rgba(250, 224, 66, .2);
  border-bottom: 1px solid #efefef;
  border-bottom-left-radius: 7px;
  border-bottom-right-radius: 7px;
  padding: .5rem 1rem;
}
</style>