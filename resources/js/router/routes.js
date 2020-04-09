/* Authenticate components */
import Authorization from '../components/authentication/Authorization'
import Registration from '../components/authentication/Registration'
import WaitAnswer from '../components/authentication/Wait-answer'

/* Dashboard components */
import DashboardLayout from "../components/agent-cabinet/layout/dashboard/DashboardLayout.vue"
// import NotFound from "../components/agent-cabinet//pages/NotFoundPage.vue"
import Dashboard from "../components/agent-cabinet/pages/Dashboard.vue"
import UserProfile from "../components/agent-cabinet/pages/UserProfile.vue"
import Notifications from "../components/agent-cabinet/pages/Notifications.vue"
import Icons from "../components/agent-cabinet/pages/Icons.vue"
import Maps from "../components/agent-cabinet/pages/Maps.vue"
import Typography from "../components/agent-cabinet/pages/Typography.vue"
import Calculator from "../components/agent-cabinet/pages/Calculator.vue"

const routes = [
  { path: '/', component: Authorization },
	{ path: '/login', component: Authorization, props: true, name: 'authorization' },
  { path: '/register', component: Registration},
  { path: '/verification', component: WaitAnswer },
	{ 
		path: '/home', 
    component: DashboardLayout,
    redirect: "/dashboard",
		children: [
      {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard
      },
      {
        path: '/stats',
        name: 'stats',
        component: UserProfile
      },
      {
        path: '/notifications',
        name: 'notifications',
        component: Notifications
      },
      {
        path: '/icons',
        name: 'icons',
        component: Icons
      },
      {
        path: '/maps',
        name: 'maps',
        component: Maps
      },
      {
        path: '/typography',
        name: 'typography',
        component: Typography
      },
      {
        path: '/calculator',
        name: 'Калькулятор лізинг',
        component: Calculator
      }
    ]
  },
]

export default routes