var ItemsRepository = {

    initialData: {
        showLoading: false,
        showItemPopup: false,
        showFavourites: false,
        selectedItem: {},
        items: [],
        alarms: [],
        zoomedItem: {},
        pinnedItems: [],
        breadcrumb: [],
        addingNewItems: false,
        editingItems: false,
        //selectedItems: {}
        categories: categories,
        favouriteItems: [],
        paths: {
            base: base_path,
            test: base_path + '/resources/views/test.php'
        },
        newItem: {
            title: '',
            body: '',
            favourite: false,
            pinned: false
        },
        newIndex: -1,
        priorityFilter: '',
        categoryFilter: '',
        titleFilter: '',
        urgencyFilter: '',
        urgencyOutFilter: ''
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
            alarm: this.formatAlarm(item.alarm)
        };

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
        else {
            alarm = Date.parse(alarm).toString('yyyy-MM-dd HH:mm:ss');
        }
        console.log(alarm);
        return alarm;
    },

    //This should be uncommented but I commented it during switch to Vue
    //var $parent;

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
                return false;
            }
            if (this.children) {
                that.findParent(this.children, item);
            }
        });

        return parent;
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

    filter: function (items, that) {
        //var that = this;

        //Sort
        //items = _.chain(items).sortBy('id').sortBy('priority').sortBy('urgency').partition('urgency').flatten().value();
        items = _.chain(items).sortBy('id').sortBy('urgency').partition('urgency').flatten().sortBy('priority').value();

        //Filter
        return items.filter(function (item) {
            //Title filter
            var filteredIn = item.title.toLowerCase().indexOf(that.titleFilter.toLowerCase()) !== -1;

            //Priority filter
            if (that.priorityFilter && item.priority != that.priorityFilter) {
                filteredIn = false;
            }
            //Urgency filter
            else if (that.urgencyFilter && item.urgency != that.urgencyFilter) {
                filteredIn = false;
            }
            //Urgency out filter
            else if (that.urgencyOutFilter && item.urgency >= that.urgencyOutFilter) {
                filteredIn = false;
            }
            //Category filter
            else if (that.categoryFilter && item.category_id !== that.categoryFilter) {
                filteredIn = false;
            }

            return filteredIn;
        });
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

    //findModelThatMatchesRoute: function (that, array) {
        //Get the id from the url
        //var path = that.$route.path;
        //var index = path.indexOf(':');
        //if (index != -1) {
        //    return that.$route.path.slice(index+1);
        //}
        //For some reason $route.params was undefined.
        //if (that.$route.params.id) {
        //    var id = that.$route.params.id.slice(1);
        //
        //    //The ids in that[resource] were a string for Chris,
        //    //and an integer for me, so check the datatype here
        //    if (typeof array[0].id == 'number') {
        //        id = parseInt(id, 10);
        //    }
        //
        //    var something = _.findWhere(array, {id: id});
        //    return something;
        //}
        //return {};
    //}
};