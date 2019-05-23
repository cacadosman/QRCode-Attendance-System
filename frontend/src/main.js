// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import VueQrcodeReader from 'vue-qrcode-reader'
import Vuex from 'vuex'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'vuetify/dist/vuetify.min.js'

import auth from '@/middlewares/auth'

Vue.config.productionTip = false
Vue.use(VueQrcodeReader)
Vue.use(Vuetify)
Vue.use(Vuex)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App },
  store: new Vuex.Store({
    state: {
      hasLoggedIn: auth.isAuthenticated(),
      date: new Date(),
      isLoginPage: false
    },
    mutations: {
      onLoginPage: (state, value) => {
        state.isLoginPage = value
      }
    },
    getters: {
      getDate: state => {
        let days = [
          'Sunday', 'Monday', 'Tuesday',
          'Wednesday', 'Friday', 'Saturday'
        ]
        let months = [
          'January', 'February', 'March', 'April',
          'May', 'June', 'July', 'August',
          'September', 'October', 'November', 'December'
        ]

        let currentDay = days[state.date.getDay()]
        let currentMonth = months[state.date.getMonth()]
        let currentDate = state.date.getDate()

        let completeDate = currentDay + ', ' + currentDate + ' ' + currentMonth

        return completeDate
      }
    }
  })
})
