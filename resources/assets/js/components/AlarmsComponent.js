var Alarms = Vue.component('alarms', {
    template: '#alarms-template',
    data: function () {
        return {
            alarms: []
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
                    this.alarms = response;
                    this.showLoading = false;

                    for (var i = 0; i < this.alarms.length; i++) {
                        this.startAlarmCountDown(this.alarms[i]);
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
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
    },
    props: [

    ],
    ready: function () {
        this.getItemsWithAlarm();
    }
});
