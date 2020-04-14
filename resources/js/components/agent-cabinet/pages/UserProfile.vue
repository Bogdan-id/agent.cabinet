<template>
    <div class="row">
      <!-- <div class="col-xl-4 col-lg-5 col-md-6">
        <user-card :user="user" />
        <members-card />
      </div> -->
      <v-row v-if="!hasUser" style="position: relative!important;">
        <v-progress-circular style="position: absolute; right: 15px; top: 5px;" indeterminate size="34"
        color="primary"></v-progress-circular>
      </v-row>
      <div class="col-12" v-if="hasUser">
        <edit-profile-form :user="user" :agent="agent" />
      </div>
    </div>
</template>
<script>
import EditProfileForm from "./UserProfile/EditProfileForm.vue"
// import UserCard from "./UserProfile/UserCard.vue"
import axios from 'axios'
// import MembersCard from "./UserProfile/MembersCard.vue";
export default {
  components: {
    EditProfileForm,
    // UserCard,
    // MembersCard
  },
  data: () => ({
    user: {},
    agent: {}
  }),
  computed: {
    hasUser() {
      return Object.keys(this.user).length > 0
    }
  },
  created() {
  axios.get('/getUserAgent')
    .then(response => {
      console.log(response)
      this.user = response.data.user
      this.agent= response.data.agent
    })
    .catch(error => {
      console.log(error.response)
    })
  }
};
</script>
<style>
  .card-body ::placeholder {
  color: red;
  opacity: 1; /* Firefox */
}
</style>
