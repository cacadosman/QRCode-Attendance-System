<template>
    <div>
      <v-container>
        <v-layout wrap>
          <v-flex xs12>
            <p class="headline">Selamat datang dosen</p>
          </v-flex>
          <v-flex xs12>
            <p class="subheading">Daftar Matkul</p>
            <v-expansion-panel>
              <v-expansion-panel-content
                v-for="(item,i) in courses"
                :key="i"
              >
                <template v-slot:header>
                  <div>{{item.name}}</div>
                </template>
                <v-card>
                  <v-card-text>
                    <p>Daftar Pertemuan:</p>
                    <ul>
                      <li v-for="(session, j) in item.session">Pertemuan {{j+1}} ({{session.date}})</li>
                    </ul>
                    <p>Daftar Jadwal:</p>
                    <ul>
                      <li v-for="(schedule, j) in item.schedule">
                        {{schedule.time}}
                        <v-btn @click="createSession(i, schedule.id, axios)">
                          Buat Pertemuan
                        </v-btn>
                      </li>
                    </ul>
                    <v-btn @click="generateQr(i)">
                      Buat QRCode
                    </v-btn>
                    <qr-code v-if="hasQr" :text="qr"></qr-code>
                  </v-card-text>
                </v-card>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-flex>
        </v-layout>
      </v-container>
    </div>
</template>

<script>
export default {
  name: 'Dosen',
  data () {
    return {
      courses: [
      ],
      hasQr: false,
      qr: ''
    }
  },
  created () {
    this.$store.commit('showTemplate', false)
    this.axios.get(process.env.BASE_URL + '/lecturers/courses?token=' + localStorage.getItem('jwt'))
      .then(response => {
        const courses = response.data.data
        courses.forEach(data => {
          let counter = 0
          const courses = []
          courses.push(data)

          this.axios.get(process.env.BASE_URL + '/lecturers/attendances?token=' + localStorage.getItem('jwt') + '&class_id=' + data.id)
            .then(response => {
              courses[courses.length - 1].session = response.data.data
              counter += 1
              if (counter === 2) {
                this.courses = courses
                console.log(this.courses)
              }
            })
          this.axios.get(process.env.BASE_URL + '/lecturers/schedules?token=' + localStorage.getItem('jwt') + '&class_id=' + data.id)
            .then(response => {
              courses[courses.length - 1].schedule = response.data.data
              counter += 1
              if (counter === 2) {
                this.courses = courses
                console.log(this.courses)
              }
            })
        })
      })
  },
  methods: {
    createSession: function (index, id, axios) {
      axios.post(process.env.BASE_URL + '/lecturers/sessions', {
        token: localStorage.getItem('jwt'),
        class_schedule_id: id
      })
        .then(response => {
          this.courses[index].session.push({
            'id': response.data.data.id,
            'date': response.data.data.date
          })
        })
    },
    startInterval: function (index) {
      setInterval(() => {
        let course = this.courses[index]
        this.axios.post(process.env.BASE_URL + '/lecturers/generate', {
          token: localStorage.getItem('jwt'),
          class_attendance_id: course.session[course.session.length - 1].id,
          time: 10000
        })
          .then(response => {
            this.hasQr = true
            this.qr = response.data.data.token
          })
      }, 5000)
    },
    generateQr: function (index) {
      this.startInterval(index)
      let course = this.courses[index]
      // this.interval = setInterval(generate(this), 5000)
      // function generate (vm) {
      this.axios.post(process.env.BASE_URL + '/lecturers/generate', {
        token: localStorage.getItem('jwt'),
        class_attendance_id: course.session[course.session.length - 1].id,
        time: 10000
      })
        .then(response => {
          this.hasQr = true
          this.qr = response.data.data.token
        })
      // }
    }
  }
}
</script>

<style scoped>
    divider{
        margin-bottom: 10px;
    }

    .jadwal-matkul{
        padding: 10px;
        margin-bottom: 5px;
    }

    .matkul-title{
        padding: 10px 0px;
    }
</style>

