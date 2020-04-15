<template>
  <card v-if="dataAvailable" :title="table.title" :subTitle="table.subTitle">
    <div slot="raw-content" class="table-responsive">
      <paper-table 
        :data="tableData"
        :columns="table.columns"
        :title="table.title" 
        :sub-title="table.subTitle">
      </paper-table>
    </div>
  </card>
</template>

<script>
import { PaperTable } from "../components"

export default {
  components: {
    PaperTable,
  },
  data: () => ({
    table: {},
    tableData: null
  }),
  methods: {
    filterData(data, id) {
      return data.filter(obj => {
        return obj['id'] === id
      })
    }
  },
  computed: {
    dataAvailable() {
      return this.tableData != null
    }
  },
  created() {
    const data = this.$router.currentRoute.params
    const index = data.table.columns.indexOf('Детально')
    data.table.columns.splice(index, 1)
    const id = data.id
    Object.assign(this.table, this.$router.currentRoute.params.table)
    this.tableData = this.filterData(data.table.data, id)
  }
}
</script>