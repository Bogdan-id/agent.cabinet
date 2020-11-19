/* Admin */
import Admin from '../components/admin/main'
import EditCategory from '../components/admin/components/routes/UsefulMaterialsEdit'
import RewardApplications from '../components/admin/components/routes/RewardApplication'
import Agents from '../components/admin/components/routes/Agents'
import ApplicationsToRegister from '../components/admin/components/routes/ApplicationsToRegister'
import EditMaterials from '../components/admin/components/routes/EditMaterials'
import EditNews from '../components/admin/components/routes/EditNews'
import Managers from '../components/admin/components/routes/Managers'
import AdminNews from '../components/admin/components/routes/AdminNews'
import Slider from '../components/admin/components/routes/slider'
import RejectedUsers from '../components/admin/components/routes/RejectedUsers'

/* Authenticate components */
import Authorization from '../components/authentication/Authorization'
import Registration from '../components/authentication/Registration'
import WaitAnswer from '../components/authentication/Wait-answer'
import CompleteRegistration from '../components/authentication/CompleteRegistration'
import ForgotPassword from '../components/authentication/ForgotPassword'
import Rejected from '../components/authentication/Rejected.vue'

/* Dashboard components */
import DashboardLayout from "../components/agent-cabinet/layout/DashboardLayout"
import DashboardNews from '../components/agent-cabinet/pages/News/DashboardNews'
import DashboardCurrentNews from "../components/agent-cabinet/pages/News/DashboardCurrentNews"
import Dashboard from "../components/agent-cabinet/pages/Dashboard"
import UserProfile from "../components/agent-cabinet/pages/UserProfile/UserProfile"
import UsefulMaterialsView from "../components/agent-cabinet/pages/Materials/UsefulMaterialsView"
import RewardApplicationsDashboard from "../components/agent-cabinet/pages/RewardApplications"
import Calculator from "../components/agent-cabinet/pages/Calculator/Calculator"
import CurrentMaterial from "../components/agent-cabinet/pages/Materials/CurrentMaterial"
import LeasingApplications from "../components/agent-cabinet/pages/LeasingApplications"
import NewCalculation from '../components/agent-cabinet/pages/Calculator/NewCalculation'
// import CalculationEdit from '../components/agent-cabinet/pages/CalculationEdit.vue'
import UsefulMaterialsCategoryMaterials from '../components/agent-cabinet/pages/Materials/UsefulMaterialsCategoryMaterials'
import ChartDiagram from '../components/agent-cabinet/pages/Charts/ChartDiagram'
import UserNotifications from '../components/agent-cabinet/pages/UserNotifications'
import DashboardSlider from '../components/agent-cabinet/pages/DashboardSlider'
import Reporting from '../components/agent-cabinet/pages/Reporting'

import NotFoundPage from '../components/agent-cabinet/pages/NotFoundPage.vue'

const routes = [
  { 
    // Admin
    path: '/admin', 
    component: Admin,
    name: 'Aдмiн',
    meta: {
      title: 'Адмiн панель'
    },
    children: [
      {
        path: 'useful-materials',
        name: 'Роздiл Кориснi матерiали',
        component: EditCategory,
        meta: {
          title: 'Кориснi матерiали'
        },
        children: [
          {
            path: 'new',
            name: 'Новый матерiал',
            component: EditMaterials,
            meta: {
              title: 'Новый матерiал'
            },
          },
          {
            path: 'edit-material',
            name: 'edit-material',
            component: EditMaterials,
            meta: {
              title: 'Редагувати матерiал'
            },
          }
        ]
      },
      {
        path: 'admin-managers',
        name: 'admin-managers',
        component: Managers,
        meta: {
          title: 'Менеджери'
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
        path: 'news',
        name: 'news',
        component: AdminNews,
        meta: {
          title: 'Новини'
        },
        children: [
          {
            path: 'new',
            name: 'new-news',
            component: EditNews,
            meta: {
              title: 'Створити новину'
            },
          },
          {
            path: 'edit-news',
            name: 'edit-news',
            component: EditNews,
            meta: {
              title: 'Редагувати новину'
            },
          }
        ]
      },
      {
        path: 'slider',
        name: 'slider',
        component: Slider,
        meta: {
          title: 'Слайдер'
        }
      },
      {
        path: 'rejected-users',
        name: 'rejected-users',
        meta: {
          title: 'Заблокованi користувачи'
        },
        component: RejectedUsers,
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

  {path: '*', component: NotFoundPage},
  //Authentication (dashboard)
	{ 
    path: '/login', 
    component: Authorization, 
    props: true, 
    name: 'authorization',
    meta: {
      title: 'Увiйти'
    },
  },
  {
    path: '/login/forget',
    name: 'Відновлення паролю',
    component: ForgotPassword,
    meta: {
      title: 'Відновлення паролю'
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
    path: '/banned',
    component: Rejected,
    meta: {
      title: 'Вiдмовлено в користуваннi'
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
		path: '/', 
    component: DashboardLayout,
    name: 'Dashboard Layout',
    redirect: "/dashboard",
    meta: {
      title: 'Головна'
    },
		children: [
      {
        path: '/dashboard',
        name: 'Main',
        component: Dashboard,
        meta: {
          title: 'Головна'
        }
      },
      {
        path: '/news',
        name: 'Новини',
        component: DashboardNews,
        meta: {
          title: 'Новини'
        },
        children: [
          {
            path: ':slug',
            name: 'Детально ', // indentation important
            component: DashboardCurrentNews,
            meta: {
              title: 'Новини'
            }
          }
        ]
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
        path: '/slides/:slug',
        name: 'DashboardSlider',
        component: DashboardSlider,
        meta: {
          title: 'Пропозицiя'
        },
      },
      {
        path: '/reporting', // /reporting/:report?/:year?
        name: 'reporting',
        component: Reporting,
        meta: {
          title: 'Звiтнiсть'
        }
      },
      {
        path: '/useful-materials-categories',
        name: 'Кориснi матерiали',
        component: UsefulMaterialsView,
        meta: {
          title: 'Категорії корисних матерiалiв'
        },
        children: [
          {
            path: ':category',
            name: 'Матерiали категорiї',
            component: UsefulMaterialsCategoryMaterials,
            meta: {
              title: "Матерiали категорiї"
            },
            children: [
              {
                path: ':material',
                name: 'Детально',
                component: CurrentMaterial,
                meta: {
                  title: "Детально"
                },
              }
            ]
          }
        ]
      },
      {
        path: '/commission-requests',
        name: 'Заявки на винагороду',
        component: RewardApplicationsDashboard,
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
                path: 'chart/:id',
                name: 'Графiки',
                component: ChartDiagram,
                meta: {
                  title: 'Графiк розрахункiв'
                }
              }
            ]
          },
          {
            path: 'edit/:id',
            name: 'Редагувати',
            component: NewCalculation,
            meta: {
              title: 'Редагувати'
            }
          },
          {
            path: 'charts/:id',
            name: 'Графiки ', // indentation important
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
      },
      {
        path: '/notifications',
        name: 'Повiдомлення',
        component: UserNotifications,
        meta: {
          title: 'Повiдомлення'
        }
      }
    ]
  },
]

export default routes