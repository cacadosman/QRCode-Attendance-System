import Vue from 'vue'
import Router from 'vue-router'

import Dashboard from '@/components/Dashboard'
import Login from '@/components/Login'
import Dosen from '@/components/Dosen'

import auth from '@/middlewares/auth'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Dashboard',
      component: Dashboard,
      beforeEnter: auth.isStudent
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      beforeEnter: auth.redirectIfAuthenticated
    },
    {
      path: '/dosen',
      name: 'Dosen',
      component: Dosen,
      beforeEnter: auth.isLecturer
    }
  ],
  mode: 'history'
})
