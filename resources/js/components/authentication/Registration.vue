<template>
  <div class="app">
    <register-block></register-block>
    <presentation-block></presentation-block>
  </div>
</template>

<script>
import RegisterBlock from './register'
import PresentationBlock from './presentation'

export default {
  components: {
    RegisterBlock,
    PresentationBlock
  }
}
</script>