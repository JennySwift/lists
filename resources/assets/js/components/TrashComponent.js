var Trash = Vue.component('trash', {
    template: '#trash-template',
    data: function () {
        return {
            showLoading: false,
            items: []
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getTrashedItems: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/items?trashed=true', function (response) {
                this.items = response;
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
        this.getTrashedItems();
    }
});
