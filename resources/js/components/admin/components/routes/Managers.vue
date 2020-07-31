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
      <v-btn color="#e94949" dark @click="getManagerList()" :loading="managerLoading">Оновити список менеджерiв</v-btn>
      <v-data-table
        v-if="managers"
        :items="managers"
        class="managers-table"
        :headers="tableHeader">
        <template v-slot:item.photo="{ item }">
          <img width="40" :src="item.photo" />
        </template>
      </v-data-table>
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
  data: () => ({
    managers: null,
    tableHeader: [
      { text: 'Фото', value: 'photo', align: 'center', sortable: false },
      { text: 'Им`я', value: 'name', align: 'center', sortable: false },
      { text: 'Телефон', value: 'phone', align: 'center', sortable: false },
      { text: 'Email', value: 'email', align: 'center', sortable: false },
    ],
    managerLoading: false,
  }),
  methods: {
    getManagerList() {
      this.managerLoading = true
      axios
        .get('/admin/managers/update')
        .then(() => {
          this.managerLoading = false
          this.$notify({
            group: 'success',
            title: 'Список менеджерiв оновлено',
            text: ``,
          })
        })
        .catch(error => {
          console.log(error.response)
          this.managerLoading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
        })
    },
    getManagers() {
      axios
        .get('/admin/managers/all')
        .then(response => {
          this.managers = response.data
        })
        .catch(error => {
          console.log(error)
        })
    }
  },
  created() {
    this.getManagers()
  }
}
</script>

<style lang="scss">
  .managers-table {
    .v-data-table-header {
      th {
        span {
          color: black;
          font-size: 0.8rem;
        }
      }
    }
  }
</style>