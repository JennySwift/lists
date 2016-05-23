var PaymentsPage = Vue.component('payments-page', {
    template: '#payments-page-template',
    data: function () {
        return {
            card: {
                number: '',
                cvc: '',
                expirationMonth: '12',
                expirationYear: this.getCurrentYear()
            },
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
        bill: function () {
            Stripe.setPublishableKey(stripePublishableKey);

            Stripe.card.createToken({
                number: this.card.number,
                cvc: this.card.cvc,
                exp_month: this.card.expirationMonth,
                exp_year: this.card.expirationYear
            }, this.stripeResponseHandler);
        },

        /**
         *
         * @param status
         * @param response
         */
        stripeResponseHandler: function (status, response) {
            console.log(status, response);
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