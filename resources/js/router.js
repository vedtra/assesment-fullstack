import VueRouter from 'vue-router'
import store from './store'
// Pages
import Home from './components/Home'
import Register from './components/Register'
import Login from './components/Login'
import Dashboard from './components/Dashboard'


// Routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: {
      auth: undefined
    }
  },

  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {
      auth: false
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      auth: false
    }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: {
      requiresAuth: true
    }
  },
]
const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
})
router.beforeEach((to, from, next) => {
 

  if (to.matched.some(route => route.meta.requiresAuth) && !store.state.isLoggedIn) {
    
      next({ name: 'login' })
      return
  }

  if(to.path === '/login' && store.state.isLoggedIn) {
      next({ name: 'dashboard' })
      return
  }

  next()
})
export default router