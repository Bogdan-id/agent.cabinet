<template>
  <v-dialog 
    v-model="openDialog" 
    persistent
    max-width="490">
    <v-card>
    <v-card-title class="headline">
      Договiр
    </v-card-title>
    <v-card-text>
      Текст для ознайомлення
    </v-card-text>
    <v-card-actions class="justify-center">
      <span>
        <v-btn
          @click="closeDialog()" 
          color="grey darken-4"
          :loading="loading"
          dark small>
          Ознайомлений
        </v-btn>
      </span>
    </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: ['showAgreement'],
  data: () => ({
    openDialog: false,
    user_id: null,
    loading: false,
  }),
  methods: {
    closeDialog() {
      this.loading = true
      setTimeout(() => {
        this.loading = false
        this.$emit('closeDialog', true)
      }, 700)
    }
  },
  watch: {
    showAgreement(val) {
      this.openDialog = val
    }
  },
  mounted() {
    this.openDialog = this.showAgreement
  }
}
</script>