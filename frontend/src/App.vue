<template>
  <v-app
  style="background-color: #fff;"
  >

    <v-toolbar app>
      <v-toolbar-title class="headline text-uppercase">
        <span>QR-Code</span>
        <span class="font-weight-light">Attendance</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>

    <v-content>
      <router-view></router-view>
    </v-content>

    <v-bottom-nav
      :active.sync="bottomNav"
      :value="true"
      fixed
      v-if="$store.state.showTemplate"
    >

      <div
        v-for="item in items"
        :key="item.title"
      >
        <v-btn
        v-if="item.isRoute"
        color="teal"
        flat
        :value="item.value"
        @click="$router.push({'name': item.value})"
        >
          <span>{{item.title}}</span>
          <v-icon>{{item.icon}}</v-icon>
        </v-btn>

        <v-btn
        v-if="!item.isRoute"
        color="teal"
        flat
        :value="item.value"
        @click="openDialog(item.value)"
        >
          <span>{{item.title}}</span>
          <v-icon>{{item.icon}}</v-icon>
        </v-btn>
      </div>
    </v-bottom-nav>

    <v-dialog persistent v-model="scanner" transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon @click="closeScanner">
                  <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Scan QR Code</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <qrcode-stream fullscreen @decode="onDecode" v-if="scanner"></qrcode-stream>
            </v-card-text>
        </v-card>
    </v-dialog>

    <v-dialog
      v-model="qrLoader.status"
      hide-overlay
      persistent
      width="300"
    >
      <v-card>
        <v-card-text v-if="!qrLoader.complete">
          Mohon Tunggu..
          <v-progress-linear
            indeterminate
          ></v-progress-linear>
        </v-card-text>
        <v-card-text class="text-xs-center" v-if="qrLoader.complete">
          <p>Absen Berhasil</p>
          <v-btn @click="qrLoader.status = false">Tutup</v-btn>
        </v-card-text>
      </v-card>
    </v-dialog>

  </v-app>
</template>

<script>
export default {
  name: 'App',
  data () {
    return {
      drawer: null,
      items: [
        { title: 'Home', icon: 'dashboard', value: 'Dashboard', isRoute: true },
        { title: 'Presensi', icon: 'camera', value: 'scanner', isRoute: false },
        { title: 'Kehadiran', icon: 'class', value: 'Attendance', isRoute: true },
        { title: 'Akun', icon: 'account_circle', value: 'Account', isRoute: true }
      ],
      bottomNav: 'Dashboard',
      scanner: false,
      qrLoader: {status: false, complete: false}
    }
  },
  methods: {
    openDialog: function (dialogName) {
      let DIALOG = {
        'scanner': function (obj) {
          obj.scanner = true
        }
      }

      DIALOG[dialogName](this)
    },
    closeScanner: function () {
      this.bottomNav = this.$route.name
      this.scanner = false
    },
    onDecode: function (decodedString) {
      this.axios.post(process.env.BASE_URL + '/students/presences', {
        token: localStorage.getItem('jwt'),
        qrtoken: decodedString,
        geolocation: {
          lat: 10,
          lng: 5
        }
      })
        .then(response => {
          this.closeScanner()
        })
      this.qrLoader.status = true
    }
  },
  watch: {
    'qrLoader.status' (val) {
      if (!val) {
        this.qrLoader.complete = false
        return
      }
      setTimeout(() => (this.qrLoader.complete = true), 2000)
    }
  },
  created () {
    const token = localStorage.getItem('jwt')
    const isValid = token !== null ? (Date.now() / 1000 | 0) <= JSON.parse(atob(localStorage.getItem('jwt').split('.')[1])).exp : true
    if (!isValid) {
      localStorage.removeItem('jwt')
      this.$router.push({'name': 'Login'})
    }
  }
}
</script>

<style>
</style>

