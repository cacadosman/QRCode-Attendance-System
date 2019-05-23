import Vue from 'vue'
import Router from 'vue-router'

import Dashboard from '@/components/Dashboard'
import Login from '@/components/Login'

// import auth from '@/middlewares/auth'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Dashboard',
      component: Dashboard
      // beforeEnter: auth.redirectIfNotAuthenticated
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
      // beforeEnter: auth.redirectIfNotAuthenticated
    }
  ],
  mode: 'history'
})
