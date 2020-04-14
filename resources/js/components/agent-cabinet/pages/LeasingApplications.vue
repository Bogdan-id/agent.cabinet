<template>
  <v-card>
    <v-card-title> 
      Заявки на лiзинг
    </v-card-title>
    <v-card-actions>
      <v-tooltip right>
        <template v-slot:activator="{ on }">
          <v-btn class="mx-2" fab dark large v-on="on" @click.stop="newLeasingAplication = true" color="green darken-1">
            <v-icon dark>mdi-plus-thick</v-icon>
          </v-btn>
        </template>
        <span>Створити заявку</span>
      </v-tooltip>
      <v-dialog
        v-model="newLeasingAplication"
        max-width="490">
        <v-card class="pb-1">
          <v-card-title class="headline green lighten-1 white--text">Нова заявка</v-card-title>
          <v-card-text>
            <v-col cols="12">
              <v-text-field
                  label="Клієнт">
              </v-text-field>
              <v-text-field
                  label="Марка">
              </v-text-field>
              <v-text-field
                  label="Модель">
              </v-text-field>
            </v-col>
          </v-card-text>
          <v-card-actions class="justify-center">
            <span><v-btn color="grey darken-1 white--text">сторити</v-btn></span>
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
const tableColumns = ["№", "Дата", "Клієнт", "Марка", "Модель"]
const tableData = [
  {
    '№': 223,
    'дата': "26.06.19",
    'клієнт': "Иванов В.М.",
    'марка': "Renault",
    'модель': "Dokker",
  },
  {
    '№': 227,
    'дата': "26.06.19",
    'клієнт': "Зубенко В.М.",
    'марка': "Ваз",
    'модель': "21.011",
  },
  {
    '№': 290,
    'дата': "26.06.19",
    'клієнт': "Иванов В.М.",
    'марка': "Djeep",
    'модель': "hunter",
  },
  {
    '№': 301,
    'дата': "26.01.20",
    'клієнт': "Иванов В.М.",
    'марка': "Renault",
    'модель': "Dokker",
  },
  {
    '№': 324,
    'дата': "26.02.20",
    'клієнт': "Бабенко Л.Н",
    'марка': "Opel",
    'модель': "Astrra",
  }
]
import { PaperTable } from "../components"
export default {
  data: () => ({
    table: {
      title: "Історія розрахунків",
      subTitle: "Розрахунки за місяць",
      columns: [...tableColumns],
      data: [...tableData]
    },

    /* booleans */
    newLeasingAplication: false,
  }),
  components: {
    PaperTable
  }
}
</script>