var InvoicesPage = Vue.component('invoices-page', {
    template: '#invoices-page-template',
    data: function () {
        return {
            invoices: []
        };
    },
    components: {},
    filters: {
        centsToDollars: function (cents) {
            return accounting.formatMoney(cents / 100);
        },
        formatDate: function (date) {
            return moment(date, 'X').format('DD/MM/YY');
        }
    },
    methods: {

        /**
        *
        */
        getInvoices: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/invoices', function (response) {
                this.invoices = response;
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
        this.getInvoices();
    }
});
