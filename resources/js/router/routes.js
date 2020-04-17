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
import UsefulMaterials from "../components/agent-cabinet/pages/UsefulMaterials.vue"
import Reporting from "../components/agent-cabinet/pages/Reporting.vue"
import Calculator from "../components/agent-cabinet/pages/Calculator.vue"
import LeasingApplications from "../components/agent-cabinet/pages/LeasingApplications.vue"
import NewCalculaton from '../components/agent-cabinet/pages/NewCalculation.vue'
import CalculationDetail from '../components/agent-cabinet/pages/CalculationDetail.vue'
import UsefulMaterialsDetail from '../components/agent-cabinet/pages/UsefulMaterialsDetail.vue'

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
        path: '/useful-materials',
        name: 'Кориснi матерiали',
        component: UsefulMaterials
      },
      {
        path: '/useful-materials/:detail',
        name: 'Кориснi матерiали вiд Best-leasing',
        component: UsefulMaterialsDetail
      },
      {
        path: '/reporting',
        name: 'Звiтнiсть',
        component: Reporting
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