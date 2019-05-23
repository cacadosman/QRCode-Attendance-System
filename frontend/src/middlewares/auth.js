const auth = {
  redirectIfNotAuthenticated: (to, from, next) => {
    if (!localStorage.getItem('jwt')) {
      next({name: 'Login'})
    }
    next()
  },
  redirectIfAuthenticated: (to, from, next) => {
    if (localStorage.getItem('jwt')) {
      next({name: 'Home'})
    }
    next()
  },
  isAuthenticated: function () {
    if (!localStorage.getItem('jwt')) {
      return false
    }
    return true
  },
  getUser: function () {
    if (this.isAuthenticated()) {
      return JSON.parse(atob(localStorage.getItem('jwt').split('.')[1]))
    } else {
      return null
    }
  }
}

export default auth
