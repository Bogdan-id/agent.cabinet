<template>
  <div class="app">
    <forget-password></forget-password>
    <presentation></presentation>
  </div>
</template>

<script>
import ForgetPassword from './forget'
import Presentation from './presentation'

export default {
  data: () => ({
    forceReload: false,
  }),
  components: {
    ForgetPassword,
    Presentation
  },
}
</script>