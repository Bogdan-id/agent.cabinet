<template>
<v-card>
  <v-card-title>
    Роздiл - Заявки на винагороду
  </v-card-title>
  <v-card-text v-if="$store.state.adminLoader">
    <v-progress-circular
      color="red"
      indeterminate>
    </v-progress-circular>
  </v-card-text>
</v-card>
</template>