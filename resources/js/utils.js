import {
  startOfToday,
  eachDayOfInterval,
  subYears,
  subDays,
  isSunday,
  format
} from 'date-fns'

const end = startOfToday()
let start = subYears(end, 1)
start = subDays(start, 7)

const getDates = () => {
  let array = eachDayOfInterval({
      start: start,
      end: end
    })
    .sort((d1, d2) => d1 < d2 ? -1 : d1 > d2 ? 1 : 0)
  return array.slice(array.indexOf(array.find(d => isSunday(d))))
}

const getSummaries = async () => {
  let params = new URLSearchParams
  params.set('start_date', format(start, 'yyyy-MM-dd'))
  params.set('end_date', format(end, 'yyyy-MM-dd'))
  return await fetch(`/api/summary?${params.toString()}`, {
      method: 'GET',
      headers: {
        Authorization: `Bearer ${process.env.MIX_API_KEY}`
      }
    })
    .then(function(response) {
      return response.json();
    })
}

export {
  getDates,
  getSummaries
}
