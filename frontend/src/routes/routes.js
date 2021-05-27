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
        component: Test
      },
      {
        path: 'datos_mina',
        name: 'DatosMina',
        component: DatosMina
      },
      {
        path: 'cronograma',
        name: 'Cronograma',
        component: Cronograma
      },
      {
        path: 'overview',
        name: 'Overview',
        component: Overview
      },
      {
        path: 'user',
        name: 'User',
        component: UserProfile
      },
      {
        path: 'table-list',
        name: 'Table List',
        component: TableList
      },
      {
        path: 'typography',
        name: 'Typography',
        component: Typography
      },
      {
        path: 'icons',
        name: 'Icons',
        component: Icons
      },
      {
        path: 'maps',
        name: 'Maps',
        component: Maps
      },
      {
        path: 'notifications',
        name: 'Notifications',
        component: Notifications
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
