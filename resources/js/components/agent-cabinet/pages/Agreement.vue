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
          @click="sendRequest()" 
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
import axios from 'axios'

export default {
  props: ['showAgreement'],
  data: () => ({
    openDialog: false,
    user_id: null,
    loading: false,
  }),
  methods: {
    sendRequest() {
      this.loading = true
      axios
        .post('/...', this.user_id) // ***
        .then(response => {
          this.loading = false
          this.$emit('closeDialog')
          // this.$router.push('...') // ***
          console.log(response)
        })
        .catch(error => {
          this.loading = false
          this.$notify({
            group: 'error',
            title: `${error.response.status} ${error.response.data.message}`,
            text: 'Сторiнку буде оновлено',
          })
          this.$emit('closeDialog') // delete after test
          setTimeout(() => {
            // this.router.go()
          }, 5000)
          console.log(error.response)
        })
    }
  },
  watch: {
    showAgreement(val) {
      this.openDialog = val
    }
  }
}
</script>