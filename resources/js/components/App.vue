<template>
<v-app>
	<div :class="{ 'nav-open': $sidebar.showSidebar }">
		<notifications group="standard" position="top center" />
		<notifications group="error" position="top center" :speed="460" classes="custom-notification error" />
		<notifications group="warning" position="top center" :speed="460" classes="custom-notification warn" />
		<notifications group="success" position="top center" :speed="460" classes="custom-notification success" />
		<transition name="fade" mode="out-in">
			<router-view></router-view>
		</transition>
	</div>
</v-app>
</template>


<script>
export default {
  name: 'Main',

  methods: {
    goToRoute() {
      // setTimeout(() => {
      //   let route = localStorage.getItem('route')
      //   let currentRoute = this.$router.currentRoute.name

      //   const check = currentRoute !== 'Aдмiн'

      //   route && check
      //     ? this.$router.push({name: route})
      //     : false
      // }, 0)
    } 
  },

  created() {
    this.$store.dispatch('getCurrentUser')
  },

  mounted() {
    this.goToRoute()
  },
  
}
</script>

<style lang="scss">
	@import "../assets/scss/fonts.scss";
	@import "../assets/scss/colors.scss";
	@import "../assets/scss/modifiers.scss";
	@import "../assets/scss/app-predefined.scss";
	@import "../assets/scss/buttons.scss";
	@import "../assets/scss/form-block.scss";
	@import "../assets/scss/presentation-block.scss";
	@import "../assets/scss/wait-answer.scss";
	@import "../assets/scss/check-box.scss";
	@import "../assets/scss/media-queries.scss";
	@import "../assets/scss/loader.scss";
  @import "../assets/scss/scroll.scss";
  .v-dialog {
    overflow-x: hidden!important;
  }
  .calculations-table,
  .dashboard-table,
  .reward-aplication-table {
    thead th {
      vertical-align: top!important;
      text-align: center!important;
      & span {
        font-size: 0.94rem!important;
        // font-weight: bold!important;
        color: #2b2a28!important;
      }
    }
  }
  .view-charts {
    thead th {
      text-align: center!important;
      & span {
        font-size: 0.95rem!important;
        font-weight: bold!important;
        color: #2b2a28!important;
      }
    }
  }
  .v-data-table td, .v-data-table th {
    padding: 0 10px!important;
  }
  .custom-notification {
    padding: 10px;
    margin: 20px 5px;

    font-size: 12px;

    color: #ffffff;
    background: #44A4FC;
    border-left: 5px solid #187FE7;
    border-radius: 4px;
    .notification-title {
      font-size: 0.97rem;
      padding: 3px 0;
      letter-spacing: 0.03rem;
    }

    .notification-content {
      font-size: 0.85rem;
    }
    &.warn {
      background: #ffb648;
      border-left-color: #f48a06;
    }

    &.error {
      background: #E54D42;
      border-left-color: #B82E24;
    }

    &.success {
      background: #68CD86;
      border-left-color: #42A85F;
    }
  }
</style>