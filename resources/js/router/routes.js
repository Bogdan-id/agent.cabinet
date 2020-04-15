/* Authenticate components */
import Authorization from '../components/authentication/Authorization'
import Registration from '../components/authentication/Registration'
import WaitAnswer from '../components/authentication/Wait-answer'
import CompleteRegistration from '../components/authentication/CompleteRegistration'

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
import LeasingApplications from "../components/agent-cabinet/pages/LeasingApplications.vue"
import NewCalculaton from '../components/agent-cabinet/pages/NewCalculation.vue'
import CalculationDetail from '../components/agent-cabinet/pages/CalculationDetail.vue'

const routes = [
  { path: '/', component: Authorization },
	{ path: '/login', component: Authorization, props: true, name: 'authorization' },
  { path: '/register', component: Registration},
  { path: '/verification', component: WaitAnswer },
  { path: '/finish-register', component: CompleteRegistration },
	{ 
		path: '/home', 
    component: DashboardLayout,
    redirect: "/dashboard",
		children: [
      {
        path: '/dashboard',
        name: 'Головна',
        component: Dashboard
      },
      {
        path: '/agent-profile',
        name: 'Профiль',
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
        name: 'Звiтнiсть',
        component: Typography
      },
      {
        path: '/calculator',
        name: 'Калькулятор лізингу',
        component: Calculator,
      },
      {
        path: '/calculator/new',
        name: 'Новий розрахунок',
        component: NewCalculaton
      },
      {
        path: '/calculator/detail',
        name: 'Детально',
        component: CalculationDetail
      },
      {
        path: '/leasing-requests',
        name: 'Заявки на лiзинг',
        component: LeasingApplications
      }
      
    ]
  },
]

export default routes