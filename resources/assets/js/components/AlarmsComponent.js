var Alarms = Vue.component('alarms', {
    template: '#alarms-template',
    data: function () {
        return {
            items: []
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getItemsWithAlarm: function () {
            this.showLoading = true;
            this.$http.get('/api/items?alarm=true', function (response) {
                    this.items = response;
                    this.showLoading = false;

                    for (var i = 0; i < this.items.length; i++) {
                        this.startAlarmCountDown(this.items[i]);
                    }

                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         */
        startAlarmCountDown: function (item) {
            var that = this;
            var timer = setInterval(function () {
                var timeLeft = moment(item.alarm, 'YYYY-MM-DD HH:mm:ss')
                    .diff(moment(), 'seconds');
                item.timeLeft = timeLeft;

                if (timeLeft < 1) {
                    alert(item.title);
                    clearInterval(timer);
                }
            }, 1000);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('alarm-created', function (event, item) {
                that.items.push(item);
                that.startAlarmCountDown(item);
            });
            $(document).on('alarm-updated', function (event, item) {
                //Updating didn't work so I'm instead deleting then adding it.
                that.items = _.without(that.items, _.findWhere(that.items, {id: item.id}));
                that.items.push(item);
                that.startAlarmCountDown(item);
            });
        },

        /**
         * Todo: If the alarm item is visible in the items or the urgent items
         * delete it from those places with the JS, too
         * @param item
         */
        deleteItem: function (item) {
            ItemsRepository.deleteItem(this, item);
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
    },
    props: [
        'showLoading',
        'showItemPopup',
        'selectedItem',
    ],
    ready: function () {
        this.getItemsWithAlarm();
        this.listen();
        //console.log(Date.parse('9am next mon'));
    }
});
