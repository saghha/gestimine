import DashboardLayout from '../layout/DashboardLayout.vue'
import AppLayout from '../AppLayout'
import LoginLayout from '../layout/LoginLayout'
// GeneralViews
import NotFound from '../pages/NotFoundPage.vue'
import Login from '../pages/Login/Login'

// Admin pages
import Overview from 'src/pages/Overview.vue'
import UserProfile from 'src/pages/UserProfile.vue'
import TableList from 'src/pages/TableList.vue'
import Typography from 'src/pages/Typography.vue'
import Icons from 'src/pages/Icons.vue'
import Maps from 'src/pages/Maps.vue'
import Notifications from 'src/pages/Notifications.vue'
import Upgrade from 'src/pages/Upgrade.vue'
// custom Page
import Test from 'src/pages/Test'
import DatosMina from 'src/pages/DatosMina/DatosMina'
import Cronograma from 'src/pages/Cronograma/Cronograma'
import PlanMinero from 'src/pages/PlanMinero/PlanMinero'
import Perforacion from 'src/pages/Perforacion/Perforacion'
import Tronaduras from 'src/pages/Tronadura/Tronadura'
import Carguios from 'src/pages/Carguio/Carguio'
import Transporte from 'src/pages/Transporte/Transporte'

const routes = [
  {
    path: '/',
    component: AppLayout,
    redirect: '/admin/overview'
  },
  {
    path: '/auth',
    component: LoginLayout,
    redirect: '/auth/login',
    children: [
      {
        path: 'login',
        name: 'Login',
        component: Login,
        meta: {
          requiresAuth: false
        }
      }
    ]
  },
  {
    path: '/admin',
    component: DashboardLayout,
    redirect: '/admin/overview',
    children: [
      {
        path: 'test',
        name: 'Test',
        component: Test,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'datos_mina',
        name: 'DatosMina',
        component: DatosMina,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'cronograma',
        name: 'Cronograma',
        component: Cronograma,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'plan_minero',
        name: 'Plan Minero',
        component: PlanMinero,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'perforaciones',
        name: 'Perforaciones',
        component: Perforacion,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'tronaduras',
        name: 'Tronaduras',
        component: Tronaduras,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'carguios',
        name: 'Cargios',
        component: Carguios,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'transportes',
        name: 'Transporte',
        component: Transporte,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'overview',
        name: 'Overview',
        component: Overview,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'user',
        name: 'User',
        component: UserProfile,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'table-list',
        name: 'Table List',
        component: TableList,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'typography',
        name: 'Typography',
        component: Typography,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'icons',
        name: 'Icons',
        component: Icons,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'maps',
        name: 'Maps',
        component: Maps,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'notifications',
        name: 'Notifications',
        component: Notifications,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'upgrade',
        name: 'Upgrade to PRO',
        component: Upgrade
      }
    ]
  },
  { path: '*', component: NotFound }
]

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * The specified component must be inside the Views folder
 * @param  {string} name  the filename (basename) of the view to load.
function view(name) {
   var res= require('../components/Dashboard/Views/' + name + '.vue');
   return res;
};**/

export default routes
