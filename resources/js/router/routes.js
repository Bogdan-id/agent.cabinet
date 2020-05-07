/* Admin */
import Admin from '../components/admin/main'
import AdminAuth from '../components/admin/components/routes/Auth'
import EditCategory from '../components/admin/components/routes/UsefulMaterialsEdit'
import RewardApplications from '../components/admin/components/routes/RewardApplication'
import Agents from '../components/admin/components/routes/Agents'
import ApplicationsToRegister from '../components/admin/components/routes/ApplicationsToRegister'

/* Authenticate components */
import Authorization from '../components/authentication/Authorization'
import Registration from '../components/authentication/Registration'
import WaitAnswer from '../components/authentication/Wait-answer'
import CompleteRegistration from '../components/authentication/CompleteRegistration'

/* Dashboard components */
import DashboardLayout from "../components/agent-cabinet/layout/dashboard/DashboardLayout"
// import NotFound from "../components/agent-cabinet//pages/NotFoundPage.vue"
import Dashboard from "../components/agent-cabinet/pages/Dashboard"
import UserProfile from "../components/agent-cabinet/pages/UserProfile"
import UsefulMaterials from "../components/agent-cabinet/pages/UsefulMaterials"
import Reporting from "../components/agent-cabinet/pages/Reporting"
import Calculator from "../components/agent-cabinet/pages/Calculator"
import LeasingApplications from "../components/agent-cabinet/pages/LeasingApplications"
import NewCalculation from '../components/agent-cabinet/pages/Calculator/NewCalculation'
// import CalculationEdit from '../components/agent-cabinet/pages/CalculationEdit.vue'
import UsefulMaterialsDetail from '../components/agent-cabinet/pages/UsefulMaterialsDetail'
import ChartDiagram from '../components/agent-cabinet/pages/ChartDiagram'

const routes = [
  { 
    // Admin
    path: '/admin', 
    component: Admin,
    meta: {
      title: 'Адмiн панель'
    },
    children: [
      {
        path: 'useful-materials-edit',
        name: 'useful-materials-edit',
        component: EditCategory,
        meta: {
          title: 'Кориснi матерiали'
        }
      },
      {
        path: 'application-for-registration',
        name: 'application-for-registration',
        component: ApplicationsToRegister,
        meta: {
          title: 'Заявки на реєстрацію'
        }
      },
      {
        path: 'reward-applications',
        name: 'reward-applications',
        component: RewardApplications,
        meta: {
          title: 'Заявки на винагороду'
        }
      },
      {
        path: 'agents-edit',
        name: 'agents-edit',
        component: Agents,
        meta: {
          title: 'Агенти'
        }
      }
    ]
  }, 
  { 
    path: '/admin/auth/login', 
    component: AdminAuth,
    meta: {
      title: 'Увiйти'
    }
  },
  //Authentication (dashboard)
  { 
    path: '/', 
    component: Authorization,
    meta: {
      title: 'Увiйти'
    }
  },
	{ 
    path: '/login', 
    component: Authorization, 
    props: true, 
    name: 'authorization',
    meta: {
      title: 'Увiйти'
    }
  },
  { 
    path: '/register', 
    component: Registration,
    meta: {
      title: 'Реєстрація'
    }
  },
  { 
    path: '/verification', 
    component: WaitAnswer,
    meta: {
      title: 'Очiкуйте зворотнього зв`язку'
    }
  },
  { 
    path: '/finish-register', 
    component: CompleteRegistration,
    meta: {
      title: 'Завершення реєстрації'
    }
  },

  /* Головна */
	{ 
		path: '/home', 
    component: DashboardLayout,
    name: 'Головна',
    redirect: "/dashboard",
    meta: {
      title: 'Головна'
    },
		children: [
      {
        path: '/dashboard',
        name: 'Головна',
        component: Dashboard,
        meta: {
          title: 'Головна'
        }
      },
      {
        path: '/agent-profile',
        name: 'Профiль',
        component: UserProfile,
        meta: {
          title: 'Профiль'
        }
      },
      {
        path: '/useful-materials',
        name: 'Кориснi матерiали',
        component: UsefulMaterials,
        meta: {
          title: 'Кориснi матерiали'
        },
        children: [
          {
            path: ':detail',
            name: 'Кориснi матерiали вiд Best-leasing',
            component: UsefulMaterialsDetail,
            meta: {
              title: "Кориснi матерiалы вiд Best leasing"
            }
          }
        ]
      },
      {
        path: '/reporting',
        name: 'Звiтнiсть',
        component: Reporting,
        meta: {
          title: 'Звiтнiсть'
        }
      },
      // calculator
      {
        path: '/calculator',
        name: 'Калькулятор лізингу',
        component: Calculator,
        meta: {
          title: 'Калькулятор лiзингу'
        },
        children: [
          {
            path: 'new',
            name: 'Новий розрахунок',
            component: NewCalculation,
            meta: {
              title: 'Новый розрахунок'
            },
            children: [
              {
                path: 'chart',
                name: 'Графiки',
                component: ChartDiagram,
                meta: {
                  title: 'Графiк розрахункiв'
                }
              }
            ]
          },
          {
            path: 'edit',
            name: 'Редагувати',
            component: NewCalculation,
            meta: {
              title: 'Редагувати'
            }
          },
          {
            path: 'charts',
            name: 'Графiки ',// indentation important
            component: ChartDiagram,
            meta: {
              title: 'Графiк розрахункiв'
            }
          }
        ]
      },
      {
        path: '/leasing-requests',
        name: 'Заявки на лiзинг',
        component: LeasingApplications,
        meta: {
          title: 'Заявки на лiзинг'
        }
      }
      
    ]
  },
]

export default routes