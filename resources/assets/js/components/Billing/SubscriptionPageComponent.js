var SubscriptionPage = Vue.component('subscription-page', {
    template: '#subscription-page-template',
    data: function () {
        return {
            me: me,
            subscriptionPlans: [],
            subscriptionPlan: me.stripe_plan
        };
    },
    components: {},
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
                this.me = response;
                $.event.trigger('provide-feedback', ['Subscription updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getSubscriptionPlans();
    }
});
