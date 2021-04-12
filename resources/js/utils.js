import {
  startOfToday,
  eachDayOfInterval,
  subYears,
  subDays,
  isSunday,
  format
} from 'date-fns'

const endDate = startOfToday()
let startDate = subYears(endDate, 1)
startDate = subDays(startDate, 7)

const getDates = () => {
  let dates = eachDayOfInterval({
      start: startDate,
      end: endDate
    })
    .sort((d1, d2) => d1 < d2 ? -1 : d1 > d2 ? 1 : 0)
  
  dates = dates.slice(dates.indexOf(dates.find(d => isSunday(d))))
  if (dates.length / 7 > 53) {
    dates = dates.slice(7)
  }
  return dates
}

const getSummaries = async () => {
  let params = new URLSearchParams
  params.set('start_date', format(startDate, 'yyyy-MM-dd'))
  params.set('end_date', format(endDate, 'yyyy-MM-dd'))
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
