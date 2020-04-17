<template>
    <div class="row user-profile">
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
