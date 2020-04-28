<template>
    <div class="row">
      <div class="col-12">
        <v-card class="pb-4" min-height="300">
        <v-card-title class="d-block">
          <div>Калькулятор лiзингу</div>
          <v-divider></v-divider>
          </v-card-title>
          <v-card-actions
            :style="`transition: all 0.5s; opacity: ${!loading ? '1' : '0'}`">
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
          <v-progress-linear
            :height="1"
            :active="loading"
            :indeterminate="loading"
            absolute
            top
            color="grey lighten-1"
          ></v-progress-linear>
          <v-card-title
            v-if="!loading && !tableDataPresent"
            absolute
            class="headline d-block text-center grey--text">
            Iсторiя розрахункiв порожня
          </v-card-title>
          <v-card-text 
            v-show="tableDataPresent" 
            class="calculations-table">
            <v-card-title class="headline">
              Iсторiя розрахункiв
              <v-spacer></v-spacer>
              <v-text-field
                v-show="tableDataPresent"
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
              :items-per-page="5"
              class="elevation-1">
              <template v-slot:item.actions="{ item }">
                <v-icon
                  color="red lighten-1"
                  class="mr-2"
                  @click="toEdit(item.id)"
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
import axios from 'axios'

export default {
  data:() => ({
    tableHeader: [
      { text: 'Тип об`єкту лiзингу', value: 'Тип', align: 'start'},
      { text: 'Марка', value: 'Марка', align: 'start'},
      { text: 'Модель', value: 'Модель', align: 'start' },
      { text: 'Сума', value: 'Сума', align: 'start' },
      { text: 'Дата', value: 'Дата', align: 'start' },
      { text: 'Редагувати', value: 'actions', align: 'end' },
    ],
    tabledata: [],
    search: '',
  }),
  computed: {
    user() {
      return this.$store.state.user.agent
    },
    userData() {
      return Object.keys(this.$store.state.user.agent).length > 0
    },
    tableDataPresent() {
      return this.tabledata.length > 0
    },
    loading() {
      return this.$store.state.loader === true
    }
  },
  methods: {
    toEdit(id) {
      this.$router.push({name: 'Редагувати', params: {id: id, edit: true}})
    },
    getUserCalculations() {
      console.log('get user calc')
      this.$store.commit('toggleSpinner', true)
      this.tabledata = []
      if(this.userData){
        const agentId = this.$store.state.user.agent.id
        console.log(agentId)
        axios
          .get(`calculations/agent/${agentId}`)
          .then(response => {
            console.log(response)
            this.$store.commit('toggleSpinner', false)
            if(response.data.length > 0)  {
              console.log('user has calculations')
              this.createTableData(response.data)
            } else {
              console.log('calculations epsent')
              this.tabledata = []
            }
          })
          .catch(error => {
            console.log(error.response)
            this.$store.commit('toggleSpinner', false)
            this.$notify({
              
            })
          })
        }
    },
    test() {
      console.log(!this.loading)
      console.log(!this.tableDataPresent)
    },
    async createTableData(object) {
      console.log(object)
      await object.map(val => {
        console.log(val)
        let dataObj = {
          'Тип': val.request_data.leasingObjectType.label,
          'Марка': val.request_data.leasedAssertMark.name,
          'Модель': val.request_data.leasedAssertModel.name,
          'Сума': val.request_data.leasingAmount,
          'Дата': val.created_at.substr(0, 10),
          'id': val.id
        }
        this.tabledata.push(dataObj)
        console.log(this.tabledata)
      })
    },
  },
  watch: {
    user() {
      if(this.userData) this.getUserCalculations()
      return
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
  .calculations-table {
    .text-start {
      font-size: 1rem!important;
    }
    .v-data-footer__select,
    .v-data-footer__pagination {
      font-size: 14px!important;
    }
    .v-data-table__empty-wrapper {
      td {
        font-size: 1.2rem!important;
      }
    }
    .v-select__selection {
      font-size: 0.9rem!important;
    }
  }
  .new-calculation-btn {
    .v-btn__content {
      text-transform: capitalize;
    }
  }
  .calculator-radio {
    .v-input--radio-group__input {
      .v-input--selection-controls__ripple {
        height: 0!important;
      }
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
