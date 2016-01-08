var TimeRepository = {

    /**
     *
     * @param alarm
     * @returns {*}
     */
    getTimeFromNow: function (alarm) {
        if (alarm.indexOf('mins') != -1) {
            var index = alarm.indexOf('mins');
            var minutesFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(minutesFromNow, 'minutes').format('YYYY-MM-DD HH:mm:ss');
        }
        else if (alarm.indexOf('hours') != -1) {
            var index = alarm.indexOf('hours');
            var hoursFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(hoursFromNow, 'hours').format('YYYY-MM-DD HH:mm:ss');
        }
        else if (alarm.indexOf('secs') != -1) {
            var index = alarm.indexOf('secs');
            var secondsFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(secondsFromNow, 'seconds').format('YYYY-MM-DD HH:mm:ss');
        }
        else if (alarm.indexOf('weeks') != -1) {
            var index = alarm.indexOf('weeks');
            var weeksFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(weeksFromNow, 'weeks').format('YYYY-MM-DD HH:mm:ss');
        }
        else if (alarm.indexOf('months') != -1) {
            var index = alarm.indexOf('months');
            var monthsFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(monthsFromNow, 'months').format('YYYY-MM-DD HH:mm:ss');
        }

        return alarm;
    },

    /**
     *
     * @param alarm
     * @param match = a day of the week
     * @returns {string}
     */
    getTimeFromDayAndTime: function (alarm, match) {
        var date = moment().day(match.num).format('YYYY-MM-DD');
        var time = alarm.replace(match.day, '').trim();
        time = Date.parse(time).toString('HH:mm:ss');
        return date + ' ' + time;
    },

    days: [
        {
            day: 'sun',
            num: 7
        },
        {
            day: 'mon',
            num: 8
        },
        {
            day: 'tue',
            num: 9
        },
        {
            day: 'wed',
            num: 10
        },
        {
            day: 'thu',
            num: 11
        },
        {
            day: 'fri',
            num: 12
        },
        {
            day: 'sat',
            num: 13
        }
    ]
};