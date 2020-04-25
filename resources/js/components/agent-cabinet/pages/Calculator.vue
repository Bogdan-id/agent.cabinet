<template>
    <div class="row">
      <div class="col-12">
        <v-card class="pb-4">
        <v-card-title class="d-block">
          <div>Калькулятор лiзингу</div>
          <v-divider></v-divider>
          </v-card-title>
            <v-card-actions>
              <v-tooltip right>
                <template v-slot:activator="{ on }">
                  <v-btn
                      v-on="on"
                      to="/calculator/new"
                      color="error" 
                      fab dark>
                      <v-icon dark>mdi-plus-thick</v-icon>
                  </v-btn>
                </template>
                <span>Новий розрахунок</span>
              </v-tooltip>
          </v-card-actions>
          <v-card-text class="calculations-table">
            <v-card-title class="headline">
              Iсторiя розрахункiв
              <v-spacer></v-spacer>
              <v-text-field
                color="black"
                v-model="search"
                append-icon="mdi-magnify"
                label="Пошук"
                single-line
                hide-details>
              </v-text-field>
            </v-card-title>
            <v-data-table
              :search="search"
              color="black"
              :headers="tableHeader"
              :items="tabledata"
              :loading="!tableDataPresent"
              :items-per-page="5"
              class="elevation-1">
              <template v-slot:item.actions="{ item }">
                <v-icon
                  class="mr-2"
                  @click="toEdit(item)"
                  >
                  mdi-pencil
                </v-icon>
              </template>
            </v-data-table>
            <!-- <v-btn @click="test()">test</v-btn> -->
          </v-card-text>
        </v-card>
      </div>
    </div>
</template>


<script>
// <v-tooltip top>
//   <template v-slot:activator="{ on }">
//     <v-btn 
//         color="grey" 
//         v-on="on " 
//         icon 
//         :to="{ 
//             name: 'Детально', 
//             params: {
//               table: table,
//               id: row.id
//             } 
//         }">
//       <v-icon color="red lighten-1" v-text="'mdi-square-edit-outline'"></v-icon>
//     </v-btn>
//   </template>
//   <span>Редагувати</span>
// </v-tooltip>
import axios from 'axios'

export default {
  data:() => ({
    tableHeader: [
      { text: 'Тип об`єкту лiзингу', value: 'Тип', align: 'start'},
      { text: 'Марка', value: 'Марка', align: 'start'},
      { text: 'Модель', value: 'Модель', align: 'start' },
      { text: 'Сума', value: 'Сума', align: 'start' },
      { text: 'Дата', value: 'Дата', align: 'start' },
      { text: 'Редагувати', value: 'actions' },
    ],
    tabledata: [],
    search: '',
  }),
  computed: {
    user() {
      return this.$store.state.user
    },
    userData() {
      return Object.keys(this.user).length > 0
    },
    tableDataPresent() {
      return this.tabledata.length > 0
    }
  },
  methods: {
    toEdit(id) {
      this.$router.push({name: 'Детально', params: {id: id}})
    },
    getUserCalculations() {
      console.log('get user calc')
      if(this.userData){
        const agentId = this.$store.state.user.user_id
        axios
          .get(`calculations/agent/${agentId}`)
          .then(response => {
            this.data = response.data
            console.log(response)
            this.createTableData(response.data)
          })
          .catch(error => {
            console.log(error.response)
            this.$notify({
              
            })
          })
        }
    },
    test() {
      console.log(this.tableDataPresent)
      console.log(this.tabledata)
    },
    async createTableData(object) {
      await object.map(val => {
        console.log(val)
        let dataObj = {
          'Тип': val.request_data['leasing-object-type'],
          'Марка': val.request_data['leased-assert-mark'],
          'Модель': val.request_data['leased-assert-model'],
          'Сума': val.request_data['leasing-amount'],
          'Дата': val.request_data['leasing-start-date'],
          'id': val.request_id
        }
        this.tabledata.push(dataObj)
      })
    },
  },
  created() {
    this.getUserCalculations()
  },
  watch: {
    tabledata() {
      console.log(this.tabledata)
    },
    user() {
      if(this.userData) this.getUserCalculations()
      return
    }
  }
}
</script>


<style lang="scss">
  .calculations-table {
    .text-start {
      font-size: 1rem!important;
    }
    .v-data-footer__select,
    .v-data-footer__pagination {
      font-size: 14px!important;
    }
  }
  .new-calculation-btn {
    .v-btn__content {
      text-transform: capitalize;
    }
  }
  .calculator__new-calculation {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
  }
  .calculator__new-calculation .v-input {
    display: inline-block;
    width: 28%;
    margin: 0 12px;
  }

  .cust-form-group .form-control {
    border-bottom: 1px solid black;
    border-radius: 0;
  }
  .form-group--sm .form-control {
    height: 26px;
    max-width: 70px;
    padding: 7px 8px;
    margin: 0 4px;
  }

  .calculator__btn-wrapper {
    display: block;
    margin: 12px 10px;
  }
  .card-cust .card-body {
    transition: all 0.2s;
  }

  @media(max-width: 757px) {
    .calculator__new-calculation .v-input {
      width: 40%;
    }
  }
  @media(max-width: 637px) {
    .calculator__new-calculation .v-input {
      width: 90%;
    }
  }
</style>
