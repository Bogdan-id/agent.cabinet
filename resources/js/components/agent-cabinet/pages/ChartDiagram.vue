<template>
  <v-card>
    <v-tabs
      v-model="currentTab"
      background-color="red"
      dark grow>
      <!-- <v-tabs-slider></v-tabs-slider> -->
      <v-tab href="#annuity">Ануїтет</v-tab>
      <v-tab href="#even">Класичний</v-tab>
      <v-tab href="#irregular">Iндивiдуальний</v-tab>
    </v-tabs>
    <v-tabs-items v-model="currentTab">
      <v-tab-item :key="1" value="annuity">
        <v-card>
          <v-card-text>Ануїтет</v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item :key="2" value="even">
        <v-card>
          <v-card-text>Класичний</v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item :key="3" value="irregular">
        <v-card>
          <v-card-text>Iндивiдуальний</v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </v-card>
</template>

<script>
export default {
  
  data: () => ({
    currentTab: "even"
  }),

}
</script>
