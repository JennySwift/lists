var ItemsRepository = {

    /**
     *
     * @returns {boolean}
     */
    shouldFilterBeShownOnPageLoad: function () {
        var width = $(window).width();
        if (width > 1040) {
            return true;
        }
        return false;
    },

    /**
     *
     * @param item
     * @returns {{title: *, body: (*|string|Array|HTMLElement), priority: *, favourite: (*|boolean), pinned: (*|boolean), category_id: *, parent_id: (*|null)}}
     */
    setData: function (item, zoomedItem) {
        var data = {
            title: item.title,
            body: item.body,
            priority: item.priority,
            urgency: item.urgency,
            favourite: item.favourite,
            pinned: item.pinned,
            category_id: item.category.id,
            alarm: false,
            not_before: this.formatNaturalLanguageDateTime(item.notBefore),
            recurring_unit: item.recurringUnit,
            recurring_frequency: item.recurringFrequency
        };

        //So the urgency can be removed
        if (item.urgency === '') {
            data.urgency = false;
        }

        if (item.alarm && item.alarm !== '') {
            //This check is here because if item.alarm was false,
            //I think PHP was getting value 0, making the item have an alarm
            //at '0000-00-00 00:00:00.'
            data.alarm = this.formatAlarm(item.alarm)
        }

        if (!data.pinned) {
            //Make sure it's a boolean for the PHP
            data.pinned = 0;
        }

        if (item.parent) {
            data.parent_id = item.parent.id;
        }
        else if (item.parent_id) {
            data.parent_id = item.parent_id;
        }
        else if (zoomedItem) {
            data.parent_id = zoomedItem.id;
        }
        else {
            data.parent_id = null;
        }

        return data;
    },

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
            alarm = Date.parse(alarm).toString('yyyy-MM-dd HH:mm:ss');
        }
        //console.log(alarm);
        return alarm;
    },

    /**
     *
     * @param dateTime
     * @returns {*}
     */
    dateTimeFilter: function (dateTime) {
        return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YY hh:mma');
    },

    /**
     * Format to datetime string
     * @param dateAndTime
     * @returns {string}
     */
    formatNaturalLanguageDateTime: function (dateAndTime) {
        if (Date.parse(dateAndTime)) {
            return Date.parse(dateAndTime).toString('yyyy-MM-dd HH:mm:ss');
        }
        return null;
    },

    /**
     *
     * @param dateAndTime
     * @returns {string}
     */
    userFriendlyDateTimeFilter: function (dateAndTime) {
        var dateTime = this.formatNaturalLanguageDateTime(dateAndTime);
        if (dateTime) {
            return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YY hh:mma');
        }
        else {
            return 'Invalid date/time';
        }
    },

    /**
     * If url is /items/:2, return 2
     * @param that
     * @returns {*}
     */
    getIdFromUrl: function (that) {
        //For some reason $route.params was undefined.
        //if (that.$route.params.id) {
        //    return that.$route.params.id.slice(1);
        //}
        var path = that.$route.path;
        var index = path.indexOf(':');
        if (index != -1) {
            return that.$route.path.slice(index+1);
        }
        return false;
    },

    /**
     *
     * @param items
     * @returns {*}
     */
    order: function (items) {
        //Sort first by priority, then by urgency, then by notBefore, then by category name, then by id
        return _.chain(items).sortBy('id')
            .sortBy(function (item) {
                return item.category.name;
            })
            .sortBy('notBefore').sortBy('urgency').partition('urgency').flatten().sortBy('priority').value();
    },

    filter: function (items, that) {
        //var that = this;

        //Sort
        //items = _.chain(items).sortBy('id').sortBy('priority').sortBy('urgency').partition('urgency').flatten().value();
        items = ItemsRepository.order(items);

        //Filter
        return items.filter(function (item) {
            //Title filter
            var filteredIn = item.title.toLowerCase().indexOf(that.filters.title.toLowerCase()) !== -1;

            //Priority filter-at or higher than priority specified
            if (that.filters.minimumPriority && item.priority > that.filters.minimumPriority) {
                filteredIn = false;
            }

            //Priority filter
            if (that.filters.priority && item.priority != that.filters.priority) {
                filteredIn = false;
            }
            //Urgency filter
            else if (that.filters.urgency && item.urgency != that.filters.urgency) {
                filteredIn = false;
            }
            //Urgency out filter
            else if (that.filters.urgencyOut && item.urgency >= that.filters.urgencyOut) {
                filteredIn = false;
            }
            //Category filter
            else if (that.filters.category && item.category_id !== that.filters.category) {
                filteredIn = false;
            }
            //Not before filter
            else if (that.filters.notBefore && ItemsRepository.notBeforeTimeIsAfterCurrentTime(item.notBefore, that.currentTime)) {
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

    /**
     * For when item is deleted from the item popup
     */
    closeItemPopup: function (that) {
        if (that.showItemPopup) {
            that.showItemPopup = false;
            that.selectedItem = {};
        }
    },

    /**
     *
     * @param that
     * @param item
     */
    deleteItem: function (that, item) {
        if (confirm("Are you sure?")) {
            $.event.trigger('show-loading');

            if (item.recurringUnit) {
                //It's a recurring item, so we're updating the not-before time of the item, rather than actually deleting the item
                var data = {
                    updatingNextTimeForRecurringItem: true
                };

                that.$http.put('/api/items/' + item.id, data, function (response) {
                    item.notBefore = response.notBefore;
                    ItemsRepository.closeItemPopup(that);
                    $.event.trigger('provide-feedback', ['Item has been rescheduled, not deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
            }

            else {
                //We are actually deleting the item
                that.$http.delete('/api/items/' + item.id, function (response) {
                    ItemsRepository.deleteJsItem(that, item);
                    ItemsRepository.closeItemPopup(that);
                    $.event.trigger('provide-feedback', ['Item deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
            }
        }
    },

    /**
     *
     * @param item
     */
    deleteJsItem: function (that, item) {
        var parent = ItemsRepository.findParent(that.items, item);
        if (parent) {
            parent.children = _.without(parent.children, item);
        }
        else {
            that.items = _.without(that.items, item);
        }
    },

    /**
     * This works. It seems kind of complicated, but I tried other ways
     * and they both had problems.
     *
     * Finding parent by path broke down when zoomed on an item, because path was not the full path.
     *
     * Finding parent with _.flatten broke down when not zoomed on an item
     * and items were expanded several levels.
     *
     * @param array
     * @param item
     * @param oldParentId
     * @returns {*}
     */
    findParent: function (array, item, oldParentId) {
        var parent;
        var that = this;
        var parentId = item.parent_id;

        if (oldParentId) {
            parentId = oldParentId;
        }

        if (!parentId || oldParentId === null) {
            return false;
        }
        $(array).each(function () {
            if (this.id === parentId) {
                parent = this;
                that.parent = this;
                return false;
            }
            if (this.children) {
                return that.findParent(this.children, item, oldParentId);
            }
        });

        console.log(that.parent);
        return that.parent;
    }
};