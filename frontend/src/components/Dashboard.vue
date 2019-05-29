<template>
    <div>
        <v-list>
            <template v-for="(item, index) in schedule">
                <v-subheader
                v-if="item.header"
                :key="item.header"
                >
                {{ item.header }}
                </v-subheader>

                <v-divider
                v-else-if="item.divider"
                :key="index"
                :inset="item.inset"
                ></v-divider>

                <v-list-tile
                v-else
                :key="item.title"
                avatar
                @click=""
                >
                <v-list-tile-avatar>
                    {{item.start}} {{item.end}}
                </v-list-tile-avatar>

                <v-list-tile-content>
                    <v-list-tile-title v-html="item.title"></v-list-tile-title>
                </v-list-tile-content>
                </v-list-tile>
            </template>
        </v-list>

        <v-list two-lines>
            <template v-for="(item, index) in notifications">
                <v-subheader
                v-if="item.header"
                :key="item.header"
                >
                {{ item.header }}
                </v-subheader>

                <v-divider
                v-else-if="item.divider"
                :key="index"
                :inset="item.inset"
                ></v-divider>

                <v-list-tile
                v-else
                :key="item.title"
                avatar
                @click=""
                >
                <v-list-tile-avatar>
                    <v-icon>info</v-icon>
                </v-list-tile-avatar>

                <v-list-tile-content>
                    <v-list-tile-title v-html="item.title"></v-list-tile-title>
                    <v-list-tile-title v-html="item.content"></v-list-tile-title>
                </v-list-tile-content>
                </v-list-tile>
            </template>
        </v-list>
    </div>
</template>

<script>
export default {
  name: 'Dashboard',
  data () {
    return {
      schedule: [
        { header: 'Today - ' + this.$store.getters.getDate },
        {
          title: 'Manajemen Proyek',
          start: '14.30',
          end: '17.00'
        },
        { divider: true, inset: true },
        {
          title: 'Kegabutan',
          start: '17.00',
          end: '00.00'
        },
        { divider: true, inset: true }
      ],
      notifications: [
        { header: 'Notifications' },
        {
          title: 'Kegabutan',
          content: 'Presensi sukses.'
        },
        { divider: true, inset: true },
        {
          title: 'Manajemen Proyek',
          content: 'Presensi sukses.'
        },
        { divider: true, inset: true }
      ]
    }
  },
  created () {
    this.$store.commit('showTemplate', true)
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

