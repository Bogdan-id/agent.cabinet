<template>
<v-app>
	<div :class="{ 'nav-open': $sidebar.showSidebar }">
		<notifications></notifications>
		<router-view></router-view>
	</div>
</v-app>
</template>

<style lang="scss">
	@import "../assets/scss/fonts.scss";
	@import "../assets/scss/vue-notify-notifications.scss";
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
</style>
