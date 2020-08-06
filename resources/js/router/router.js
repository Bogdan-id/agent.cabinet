import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'

Vue.use(VueRouter)

const router = new VueRouter({ 
	routes,
	mode: 'history',
	linkActiveClass: "active"
})

router.beforeEach((to, from, next) => {
  document.title = to.meta.title
  if(from.name === 'Редагувати' && to.name === 'Графiки ') next({name: 'Калькулятор лізингу'})
  if(from.name === 'Відновлення паролю' && to.name === 'authorization') next(router.go())
  else next()
})

export default router