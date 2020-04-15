<template>
    <div class="row user-profile">
      <v-row v-if="!hasUser" style="position: relative!important;">
        <v-progress-circular class="user-profile-loader" indeterminate size="34"
        color="error"></v-progress-circular>
      </v-row>
      <div class="col-12" v-if="hasUser">
        <edit-profile-form :userData="user" />
      </div>
    </div>
</template>

<script>
import EditProfileForm from "./UserProfile/EditProfileForm.vue"
import axios from 'axios'

export default {
  components: {
    EditProfileForm,
  },
  data: () => ({
    user: {},
  }),
  computed: {
    hasUser() {
      return Object.keys(this.user).length > 0
    }
  },
  created() {
  axios.get('/getUserAgent')
    .then(response => {
      let user = response.data.user
      let {document, ...userAgent} = response.data.agent
      this.user = Object.assign(user, userAgent, document)
    })
    .catch(error => {
      console.log(error.response)
    })
  }
}
</script>
<style lang="scss">
  .user-profile {
    .user-profile-loader {
      position: absolute; 
      right: 15px; 
      top: 5px;
    }
  }
</style>
