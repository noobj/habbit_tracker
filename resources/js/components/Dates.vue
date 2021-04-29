<template>
  <div id="app" class="flex justify-center items-center">
    <div class="flex-auto grid grid-flow-col grid-rows-7 grid-cols-53 gap-x-1/500 gap-y-1/50 mx-8 pt-6 px-10">
      <Date
        v-for="(date, index) in dates"
        :key="date.getTime()"
        :date="date"
        :summary="findDuration(date)"
        :showMonth="showMonth(date)"
        :showWeekday="index < 7 && index % 2 === 1"
      />
    </div>
  </div>
</template>

<script>
import Date from './Date'
import {
  getDates,
  getSummaries
} from '../utils'

export default {
  name: 'Dates',
  components: { Date },
  data () {
    return {
      dates: getDates(),
      summaries: [],
      totalLastYear: '',
      totalThisMonth: '',
      longestRecord: ''
    }
  },
  methods: {
    findDuration (date) {
      return this.summaries.find(s => s.timestamp === date.getTime())
        || {
          level: 0,
          duration: '0h'
        }
    },
    showMonth (date) {
      return date.getDate() <=7 && date.getDay() === 0
    }
  },
  async mounted () {
    let {
      summaries,
      total_last_year,
      total_this_month,
      longest_record
    } = await getSummaries()
    this.summaries = summaries
    this.totalLastYear = total_last_year
    this.totalThisMonth = total_this_month
    this.longestRecord = `${longest_record.duration} on ${longest_record.date}`
  }
}
</script>

<style scoped>
.grid-rows-7 {
  grid-template-rows: repeat(7, minmax(0, 1fr));
}
.grid-cols-53 {
  grid-template-columns: repeat(53, minmax(0, 1fr));
}
.gap-x-1\/500 {
  column-gap: 0.5%;
}
.gap-y-1\/50 {
  row-gap: 5%;
}
</style>
