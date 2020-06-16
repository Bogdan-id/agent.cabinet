<template>
<v-app>
	<div :class="{ 'nav-open': $sidebar.showSidebar }">
		<notifications group="standard" position="top center" />
		<notifications group="error" position="top center" classes="vue-notification error" />
		<notifications group="warning" position="top center" classes="vue-notification warn" />
		<notifications group="success" position="top center" classes="vue-notification success" />
		<transition name="fade" mode="out-in">
			<router-view></router-view>
		</transition>
	</div>
</v-app>
</template>

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
  .calculations-table,
  .dashboard-table {
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
  .v-data-table {
    tbody {
      // tr:last-child {
      //   height: 70px!important;
      // }
    }
  }
  // .v-data-table-header th {
  //   white-space: nowrap;
  // }
</style>

<script>
  export default {
    mounted() {
      console.log(this.$route)
    }
  }
</script>