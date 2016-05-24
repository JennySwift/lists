var CustomerPage = Vue.component('customer-page', {
    template: '#customer-page-template',
    data: function () {
        return {
            card: {
                number: '4242424242424242',
                cvc: '123',
                expirationMonth: '12',
                expirationYear: this.getCurrentYear()
            },
            token: '',
            months: [
                {name: 'January', value: 1},
                {name: 'February', value: 2},
                {name: 'March', value: 3},
                {name: 'April', value: 4},
                {name: 'May', value: 5},
                {name: 'June', value: 6},
                {name: 'July', value: 7},
                {name: 'August', value: 8},
                {name: 'September', value: 9},
                {name: 'October', value: 10},
                {name: 'November', value: 11},
                {name: 'December', value: 12}
            ],
            years: this.getYears()
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        generateToken: function () {
            Stripe.setPublishableKey(stripePublishableKey);

            Stripe.card.createToken({
                number: this.card.number,
                cvc: this.card.cvc,
                exp_month: this.card.expirationMonth,
                exp_year: this.card.expirationYear
            }, this.handleGenerateTokenResponse);
        },

        /**
         *
         * @param status
         * @param response
         */
        handleGenerateTokenResponse: function (status, response) {
            console.log(status, response);
            if (response.error) {
                HelpersRepository.handleResponseError(null, status, response);
            }
            else {
                this.token = response.id;
                this.saveCustomer();
            }
        },

        /**
         *
         */
        saveCustomer: function () {
            if (me.stripe_id) {
                this.updateCustomer();
            }
            else {
                this.insertCustomer();
            }
        },

        /**
        *
        */
        insertCustomer: function () {
            $.event.trigger('show-loading');
            var data = {
                token: this.token
            };

            this.$http.post('/api/customers', data, function (response) {
                me = response;
                $.event.trigger('provide-feedback', ['Details added', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
        *
        */
        updateCustomer: function () {
            $.event.trigger('show-loading');

            var data = {
                token: this.token
            };

            this.$http.put('/api/customers/' + me.stripe_id, data, function (response) {
                $.event.trigger('provide-feedback', ['Details updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
         *
         * @returns {*}
         */
        getCurrentYear: function () {
            return moment().format('YYYY')
        },

        /**
         *
         * @returns {Array}
         */
        getYears: function () {
            var years = [];
            var year = this.getCurrentYear();
            years.push(year);

            for (var i = 0; i < 10; i++) {
                year++;
                years.push(year);
            }

            return years;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});