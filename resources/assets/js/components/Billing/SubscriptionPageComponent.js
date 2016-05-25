var SubscriptionPage = Vue.component('subscription-page', {
    template: '#subscription-page-template',
    data: function () {
        return {
            userRepository: UserRepository.state,
            subscriptionPlans: [],
            subscriptionPlan: UserRepository.state.me.stripe_plan
        };
    },
    components: {},
    computed: {
        me: function () {
            return this.userRepository.me;
        }
    },
    filters: {
        formatDateTime: function (dateTime) {
            return DateTimeRepository.convertFromDateTime(dateTime, 'ddd DD/MM/YY') + ', at ' + DateTimeRepository.convertFromDateTime(dateTime, 'hh:mma');
        }
    },
    methods: {

        /**
         *
         */
        getSubscriptionPlans: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/subscriptionPlans', function (response) {
                this.subscriptionPlans = response.data;
                $.event.trigger('hide-loading');
            })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
        },

        /**
        *
        */
        updateSubscription: function () {
            $.event.trigger('show-loading');

            var data = {
                plan: this.subscriptionPlan
            };

            this.$http.put('/api/subscriptions', data, function (response) {
                UserRepository.updateUser(response);
                $.event.trigger('provide-feedback', ['Subscription updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
        *
        */
        cancelSubscription: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/subscriptions', function (response) {
                    UserRepository.updateUser(response);
                    $.event.trigger('provide-feedback', ['Subscription cancelled', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
            }
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getSubscriptionPlans();
    }
});
