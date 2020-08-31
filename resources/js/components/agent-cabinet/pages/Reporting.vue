<template>
  <v-card class="chart-card" style="min-height: 200px; position: relative" elevation="8">
    <!-- <img style="width: 100%; filter: blur(7px);" src="../assets/img/graphs.png" />
    <div style="position: absolute; top: 24%; position: absolute; left: 50%; text-align: center; transform: translate(-50%, 0);">
      <span style="margin: 0 auto; color: #555555; font-size: 1.8rem">Скоро тут буде звiт...</span>
    </div> -->
    <v-card-title>
      <v-select
        :items="[
          {text: 'Звіт заявок на лізинг', value: 'leasingRequestReport'},
          {text: 'Звiт заявок та виплат', value: 'RequestsAndPaymentsReports'},
        ]"
        item-text="text"
        item-value="value"
        style="max-width: 280px;"
        v-model="currentReport"
        color="#e65048"
        item-color="#e65048"
        :hide-details="true"
        dense>
      </v-select>
    </v-card-title>
    <component 
      :currentYear="currentYear"
      :is="currentReport">
    </component>
  </v-card>
</template>

<script>
import leasingRequestReport from './LeasingRequestReport'
import RequestsAndPaymentsReports from './RequestsAndPaymentsReports'

export default {
  data: () => ({
    currentReport: 'leasingRequestReport',
    currentYear: null,
  }),

  components: {
    RequestsAndPaymentsReports,
    leasingRequestReport
  }
}

</script>
