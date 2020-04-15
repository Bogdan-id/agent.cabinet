<template>
    <div class="row user-profile">
      <v-row v-if="!hasUser" style="position: relative!important;">
        <v-progress-circular class="user-profile-loader" indeterminate size="34"
        color="error"></v-progress-circular>
      </v-row>
      <div class="col-12" v-if="hasUser">
        <edit-profile-form />
      </div>
    </div>
</template>

<script>
import EditProfileForm from "./UserProfile/EditProfileForm.vue"

export default {
  components: {
    EditProfileForm,
  },
  computed: {
    hasUser() {
      return Object.keys(this.$store.state.user).length > 0
    }
  },
  created() {

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
