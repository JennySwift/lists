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
                    this.startAlarmCountDown();
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         */
        startAlarmCountDown: function () {
            var that = this;
            var timer = setInterval(function () {
                var timeLeft = moment(that.alarms[0].alarm, 'YYYY-MM-DD HH:mm:ss')
                    .diff(moment(), 'seconds');
                that.alarms[0].timeLeft = timeLeft;

                if (timeLeft < 1) {
                    alert(that.alarms[0].title);
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
