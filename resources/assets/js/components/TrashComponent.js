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
            this.showLoading = true;
            this.$http.get('/api/items?trashed=true', function (response) {
                this.items = response;
                this.showLoading = false;
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
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
        //data to be received from parent
    ],
    ready: function () {
        this.getTrashedItems();
    }
});
