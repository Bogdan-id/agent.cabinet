<template>
  <div class="app">
    <authorization></authorization>
    <presentation></presentation>
  </div>
</template>

<script>
import Authorization from './authorize'
import Presentation from './presentation'

export default {
  components: {
    Authorization,
    Presentation
  }
}
</script>