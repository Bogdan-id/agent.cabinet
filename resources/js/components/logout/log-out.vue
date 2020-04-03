<template>
  <div class="sign-out">
    <span>
      <button 
        @click="signOut()" 
        class="app__sign-in-btn"
        >Выйти
      </button>
    </span>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  methods: {
    signOut() {
      console.log('logout')
      this.logOut(this.getCsrf())
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    logOut(token) {
			axios.post(`/logout`, token)
			.then(response => {
				console.log(token)
			})
			.catch(e => {
				console.log(e)
			})
		},
  }
}
</script>

<style>
  .sign-out {
    display: flex;
    width: 100%;
    height: 300px;
    justify-content: flex-end;
  }
</style>