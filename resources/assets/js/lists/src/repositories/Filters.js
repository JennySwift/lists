var TimeRepository = require('./TimeRepository');
var DateTimeRepository = require('./DateTimeRepository');
var moment = require('moment');
require('sugar');

export default {
    /**
     *
     * @param alarm
     * @returns {*}
     */
    formatAlarm: function (alarm) {
        if (!alarm) {
            return false;
        }

        var days = TimeRepository.days;

        for (var i = 0; i < days.length; i++) {
            if (alarm.indexOf(days[i].day) != -1) {
                var match = days[i];
            }
        }

        //Format contains hours, mins, or secs
        if (alarm.indexOf('mins') != -1 || alarm.indexOf('hours') != -1 || alarm.indexOf('secs') != -1 || alarm.indexOf('weeks') != -1 || alarm.indexOf('months') != -1) {
            alarm = TimeRepository.getTimeFromNow(alarm);
        }

        else if (match) {
            //Format contains a day of the week from the days array
            alarm = TimeRepository.getTimeFromDayAndTime(alarm, match);
        }


        //else if (alarm.indexOf('mon') != -1 || alarm.indexOf('mon') != -1) {
        //    alarm = TimeRepository.getTimeFromDayAndTime(alarm);
        //
        //}
        //
        else {
            alarm = Date.create(alarm).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');
            // alarm = Date.parse(alarm).toString('yyyy-MM-dd HH:mm:ss');
        }
        //console.log(alarm);
        return alarm;
    },

    /**
     *
     * @param items
     * @returns {*}
     */
    order: function (items) {
        //Sort first by priority, then by urgency, then by notBefore, then by category name, then by id
        //Reverse is so the more recently added items are on top of the older items
        return _.chain(items).sortBy('id').reverse()
            .sortBy(function (item) {
                return item.category.name;
            })
            .sortBy('notBefore').sortBy('urgency').partition('urgency').flatten().sortBy('priority').value();
    },

    filter: function (items, that) {
        //var that = this;

        //Sort
        //items = _.chain(items).sortBy('id').sortBy('priority').sortBy('urgency').partition('urgency').flatten().value();
        items = filters.order(items);

        //Filter
        return items.filter(function (item) {
            //Title filter
            var filteredIn = item.title.toLowerCase().indexOf(that.shared.filters.title.toLowerCase()) !== -1;

            //Body filter
            if (that.shared.filters.body) {
                if (item.body && item.body.toLowerCase().indexOf(that.shared.filters.body.toLowerCase()) === -1) {
                    filteredIn = false;
                }
                else if (!item.body) {
                    filteredIn = false;
                }

            }

            //Priority filter-at or higher than priority specified
            if (that.shared.filters.minimumPriority && item.priority > that.shared.filters.minimumPriority) {
                filteredIn = false;
            }

            //Priority filter
            if (that.shared.filters.priority && item.priority != that.shared.filters.priority) {
                filteredIn = false;
            }
            //Urgency filter
            else if (that.shared.filters.urgency && item.urgency != that.shared.filters.urgency) {
                filteredIn = false;
            }
            //Urgency out filter
            else if (that.shared.filters.urgencyOut && item.urgency >= that.shared.filters.urgencyOut) {
                filteredIn = false;
            }
            //Category filter
            else if (that.shared.filters.category.id && item.category_id !== that.shared.filters.category.id) {
                filteredIn = false;
            }
            //Not before filter (if checked, do not show items with a not before value after the current time)
            else if (that.shared.filters.notBefore && filters.notBeforeTimeIsAfterCurrentTime(item.notBefore, that.currentTime)) {
                filteredIn = false;
            }
            //Not before date filter
            else if (that.shared.filters.notBeforeDate) {
                //Only show items with a not before date on the specified date
                if (!item.notBefore) {
                    filteredIn = false;
                }
                else {
                    var filterDate = DateTimeRepository.convertFromDateTime(DateTimeRepository.convertToDateTime(that.shared.filters.notBeforeDate));
                    var itemDate = DateTimeRepository.convertFromDateTime(item.notBefore);
                    if (filterDate !== itemDate) {
                        filteredIn = false;
                    }
                }
            }

            //Show trashed items filter
            else if (!that.shared.filters.showTrashed && item.deletedAt) {
                filteredIn = false;
            }

            return filteredIn;
        });
    },

    /**
     *
     * @param notBeforeTime
     * @param currentTime
     * @returns {*}
     */
    notBeforeTimeIsAfterCurrentTime: function (notBeforeTime, currentTime) {
        if (!currentTime) {
            //Doing it like this so that the currentTime is a property on the component,
            //so the filter will run regularly when currentTime is updated in the component
            currentTime = moment();
        }
        return moment(notBeforeTime).isAfter(currentTime);
    },

    /**
     *
     * @param seconds
     * @returns {string}
     */
    timeLeftFilter: function (seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor(seconds / 60) % 60;
        seconds = seconds % 60;

        return this.addZeros(hours) + ':' + this.addZeros(minutes) + ':' + this.addZeros(seconds);
    },

    /**
     *
     * @param number
     * @returns {*}
     */
    addZeros: function (number) {
        if (number < 10) {
            return '0' + number;
        }

        return number;
    },

}