<template>
  <div class="styles">
  </div>
</template>

<style>
	/* Modifiers */
	.--link {
		transition: color 0.2s;
		text-decoration: none;
		color: #17a2b8;
	}
	.--link:hover {
		transition: color 0.2s;
		color: #00aeef;
	}


	/* Predefined styles */
	.styles {
		display: none;
	}
	.html, body {
		margin: 0;
	}
	input, button {
		border: 0;
		outline: none;
		padding: 0;
		margin: 0;
	}
	button {
		cursor: pointer;
	}
	::selection {
    background-color: #b3d4fc;
    color: #000;
    text-shadow: none;
	}
	*, :after, :before {
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
	}
	#app {
		font-family: Avenir, Helvetica, Arial, sans-serif;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		text-align: center;
		color: #2c3e50;
		font-weight: 400;
		line-height: 1.5;
		letter-spacing: 0.0125em;
		font-stretch: ultra-expanded;
	}


	/* Styles */
	.app {
		display: flex;
		/* background: green; */
		width: 100%;
		height: 100vh;
		flex-direction: row;
		flex-wrap: wrap;
	}

	/* Form block */
	.app__form-block {
		width: auto;
		height: 100vh;
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
		background: #f5f9ff;
	}
	.app__login-form {
		margin: 0 45px;
		text-align: left;
		width: 450px;
		padding: 30px 50px;
		box-shadow: 
			0 10px 40px 0 rgba(0,46,103,.07)
			, 0 2px 9px 0 rgba(0,46,103, .07);
		background: #fff;
		border-radius: 3px;
	}
	.app__form-logo-wrapper {
		display: none;
		width: 100%;
		justify-content: center;
		margin-bottom: 25px;
	}
	.app__login-form-title {
		font-size: 18px;
		color: #002e67;
		margin: 0;
	}
	.app__login-form-subtitle {
		font-size: 20px;
		margin: 0 0 8px 0;
	}
	.app__login-form-subtitle-2 {
		font-size: 16px;
		margin: 0 0 8px 0;
	}
	.app__form {
		/*  */
	}
	.app__input-text-wrapper {
		position: relative;
		margin-bottom: 15px;
	}
	.app__input-text {
		font-size: 14px;
		height: 45px;
		width: 100%;
		padding-left: 16px;
		border: 1px solid #ced4da;
		border-radius: 3px;
	}
	.app__input-errors {
		color: #f44336;
		font-size: 10px;
		margin: 3px 0 0 5px;
		display: block;
	}
	.app__input-text:focus {
		border: 1px solid #00aeef;
	}
	.app__register-checkbox-wrapper {
		position: relative;
	}
	.app__regiser-checkbox-label {
		display: block;
		padding-top: 0;
		margin-top: 0;
		position: relative;
		padding-left: 35px;
		cursor: pointer;
		font-size: 11px;
		user-select: none;
	}
	/* hide checkbox */
	.app__register-checkbox {
		position: absolute;
		opacity: 0;
		cursor: pointer;
		height: 0;
		width: 0;
	}
	.app__custom-checkbox {
		border: 1px solid grey;
		position: absolute;
		top: 2px;
		left: 0;
		height: 15px;
		width: 15px;
		background-color: #fff;
		transition: all 0.5s;
	}
	input:checked ~ .app__custom-checkbox {
		transition: all 0.5s;
		border: 0;
		background-color: #00aeef;
	}
	.app__custom-checkbox:after {
		content: "";
		position: absolute;
		display: none;
	}
	input:checked ~ .app__custom-checkbox:after {
		display: block;
	}
	.app__custom-checkbox:after {
		left: 5px;
		top: 1px;
		width: 6px;
		height: 10px;
		border: solid white;
		border-width: 0 3px 3px 0;
		transform: rotate(45deg);
	}
	.app__button-wrapper {
		display: flex;
		align-items: flex-start;
		margin: 15px 0;
	}
	.app__sign-in-btn {
		transition: all 0.3s;
		cursor: pointer;
		padding: 10px 25px;
		color: #fff;
		background: #00aeef;
	}
	.app__sign-in-btn:hover {
		transition: all 0.3s;
		background: #039dd4;
		box-shadow: 0px 3px 6px -1px rgba(0,174,239,1);
	}
	.app__sign-navigate {
		margin: 0 0 0 12px;
	}
	.app__sign-navigate-link {
		display: block;
		font-size: 12px;
	}
	.app__login-footer {
		font-size: 12px;
	}


	/* Presentation Block */
	.app__presentation-block {
		flex: 1 1 auto;
		width: auto;
		display: flex;
		flex-direction: column;
		background-image: url('../assets/auto.png'),
			linear-gradient(180deg,#32a3dc 0,#fff);
    background-repeat: no-repeat;
    background-position: 0 100%;
    background-size: 150%;
	}
	.app__presentation-block-wrapper {
		position: relative; 
		height: 100%;
	}
	.app__header-logo {
		margin: 35px 0 0 35px;
		background: url('../assets/best-leasing-logo.png');
		background-size: 160px 45px;
		width: 160px;
		height: 45px;
	}
	.app__header-logo-card {
		background: url('../assets/best-leasing-logo.png');
		background-size: 160px 45px;
		width: 160px;
		height: 45px;
	}
	.app__body-content {
		line-height: 160%;
		font-weight: lighter;
		font-size: 13px;
		top: 50%;   
		left: 50%;
    width: 80%;
		padding: 35px;
		border-radius: 4px;
		backdrop-filter: blur(5px);
		background-color: rgba(0,46,103,.5);
		color: #fff;
		text-align: left;
		position: absolute;  
    transform: translate3d(-50%,-40%,0);
	}
	.app__body-content-header {
		font-size: 29px; 
		margin-top: 0;
		line-height: 30px;
	}
	.app__body-content-subheader {
		line-height: 25px;
		font-size: 25px; 
		font-weight: 400;
	}
	.app__presentation-para {
		font-size: 15px;
	}
	.app__presentation-block-ul {
		list-style-type: disc; 
		margin-bottom: 25px;
	}
	.app__presentation-block-li {
		margin: 5px 0;
		font-size: 15px;
	}
	.app__presentation-block-btn-reg {
		transition: all 0.2s;
		margin-top: 15px;
		font-size: 16px;
		cursor: pointer;
		padding: 8px 16px;
		background: #00aeef;
	}
	.app__presentation-block-btn-reg:hover {
		transition: all 0.2s;
		background: #039dd4;
		box-shadow: 0px 3px 6px -1px rgba(0,174,239,1);
	}
	@media(min-width: 1200px) {
		.app__presentation-block {
			background-size: 100%;
			background-position: 0 100%;
		}
	}
	@media (max-width: 893px), screen and (max-height: 593px) {
		.app__presentation-block {
			display: none;
		}
		.app__form-block {
			width: 100%;
			background-image: url('../assets/auto.png'),
			linear-gradient(180deg,#32a3dc 0,#fff);
			background-repeat: no-repeat;
			background-position: 0 100%;
			background-size: 150%;
		}
		.app__form-logo-wrapper {
			display: flex;
		}
	}
</style>
