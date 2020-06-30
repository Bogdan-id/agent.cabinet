<template>
  <v-card>
    <v-card-title>
      Роздiл - Менеджери
    </v-card-title>
    <v-card-text v-if="$store.state.adminLoader">
      <v-progress-circular
        color="red"
        indeterminate>
      </v-progress-circular>
    </v-card-text>
    <v-card-text>
      <v-btn color="#e94949" dark @click="getManagerList()">Оновити список менеджерiв</v-btn>
      <!-- <v-data-table
        v-if="tabledata.length > 0"
        color="black"
        :headers="tableHeader"
        :items="tabledata"
        :hide-default-footer="true"
        class="elevation-1 mr-3 ml-3">
        <template v-slot:item.actions="{ item }">
          <div class="d-flex justify-center">
            <v-btn
              @click="getAgentOperations(item.id);
                findAgent(item.id)"
              color="primary" x-small>
              Змiнити
            </v-btn>
            &nbsp;
            <v-btn
              @click="deactivateUser(item.id)"
              color="error" x-small>
              Деактивувати
            </v-btn>
          </div>
        </template>
      </v-data-table> -->
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'

export default {
  methods: {
    getManagerList() {
      axios
        .get('/admin/managers/update')
        .then(response => {
          console.log(response)
        })
        .catch(error => {
          console.log(error.response)
        })
    }
  }
}
</script>