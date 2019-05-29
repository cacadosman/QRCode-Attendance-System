<template>
    <div>
      <v-container>
        <v-layout wrap>
          <v-flex xs12>
            <p class="title">Login Page</p>
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="email"
              label="Email"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="password"
              label="Password"
              type="password"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12 text-xs-center>
            <v-btn @click="login">Login</v-btn>
            <p v-if="message">{{message}}</p>
          </v-flex>
        </v-layout>
      </v-container>
    </div>
</template>

<script>
export default {
  name: 'Login',
  data () {
    return {
      email: '',
      password: '',
      message: null
    }
  },
  methods: {
    login: function () {
      const errMsg = 'Username/Password Salah'
      this.axios.post(process.env.BASE_URL + '/auth', {
        email: this.email,
        password: this.password
      })
        .then(response => {
          if (!response.data.status) {
            this.message = errMsg
          } else {
            localStorage.setItem('jwt', response.data.token)
            this.message = null

            const role = JSON.parse(atob(localStorage.getItem('jwt').split('.')[1]))
            if (role === 'dosen') {
              this.$router.push({'name': 'Dosen'})
            } else {
              this.$router.push({'name': 'Dashboard'})
            }
          }
        })
        .catch(() => {
          this.message = errMsg
        })
    }
  },
  created () {
    this.$store.commit('showTemplate', false)
  }
}
</script>
