<template>
  <div class="content">
    <!-- <v-btn small to="/admin">Адмiн панель</v-btn>
    <v-btn small to="/admin/auth/login">Увiйти</v-btn> -->
    <div 
      v-if="loading"
      class="spinner-wrapper">
      <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
      </div>
    </div>
    <div>
      <transition name="fade" mode="out-in">
        <!-- <keep-alive include="Графiкии">  -->
        <router-view></router-view>
        <!-- </keep-alive> -->
      </transition>
    </div>
  </div>
</template>
<script>
export default {
  computed: {
    loading() {
      return this.$store.state.loader
    }
  }
}
</script>
<style>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.1s
  }
  .fade-enter,
  .fade-leave-to { /* .fade-leave-active in <2.1.8 */
    opacity: 0
  }
  /* spinner */
  .spinner-wrapper {
    position: fixed; 
    top: var(--header-height); 
    right: 0; 
    z-index: 3000;
  }
  .spinner {
    margin-top: 9px;
    width: 70px;
    text-align: center;
  }
  .spinner > div {
    width: 12px;
    height: 12px;
    background-color: rgba(0, 0, 0, 0.726);

    border-radius: 100%;
    display: inline-block;
    -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
    animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  }

  .spinner .bounce1 {
    -webkit-animation-delay: -0.32s;
    animation-delay: -0.32s;
  }

  .spinner .bounce2 {
    -webkit-animation-delay: -0.16s;
    animation-delay: -0.16s;
  }

  @-webkit-keyframes sk-bouncedelay {
    0%, 80%, 100% { -webkit-transform: scale(0) }
    40% { -webkit-transform: scale(1.0) }
  }

  @keyframes sk-bouncedelay {
    0%, 80%, 100% { 
      -webkit-transform: scale(0);
      transform: scale(0);
    } 
    40% { 
      -webkit-transform: scale(1.0);
      transform: scale(1.0);
    }
  }
</style>
