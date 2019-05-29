const auth = {
  redirectIfNotAuthenticated: (to, from, next) => {
    if (!localStorage.getItem('jwt')) {
      next({name: 'Login'})
      return true
    }
    next()
  },
  redirectIfAuthenticated: (to, from, next) => {
    if (localStorage.getItem('jwt')) {
      next({name: 'Dashboard'})
      return true
    }
    next()
  },
  isStudent: (to, from, next) => {
    const token = localStorage.getItem('jwt')
    if (!token) {
      next({name: 'Login'})
      return true
    }
    const data = JSON.parse(atob(token.split('.')[1]))
    if (data.sub.role === 'dosen') {
      next({name: 'Dosen'})
    }
    next()
  },
  isLecturer: (to, from, next) => {
    const token = localStorage.getItem('jwt')
    if (!token) {
      next({name: 'Login'})
      return true
    }
    const data = JSON.parse(atob(token.split('.')[1]))
    if (data.sub.role === 'mahasiswa') {
      next({name: 'Dashboard'})
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
