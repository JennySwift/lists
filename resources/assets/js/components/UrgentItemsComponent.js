var UrgentItems = Vue.component('urgentItems', {
    template: '#urgent-items-template',
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
        getUrgentItems: function () {
            this.showLoading = true;
            this.$http.get('/api/items?urgent=true', function (response) {
                    this.items = response;
                    this.showLoading = false;
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         * Todo: If the urgent item is visible in the items or the alarms
         * delete it from those places with the JS, too
         * @param item
         */
        deleteItem: function (item) {
            if (confirm("Are you sure?")) {
                this.showLoading = true;
                this.$http.delete('/api/items/' + item.id, function (response) {
                    this.items = _.without(this.items, item);
                    $.event.trigger('provide-feedback', ['Item deleted', 'success']);
                    //this.$broadcast('provide-feedback', 'Item deleted', 'success');
                    this.showLoading = false;
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
            }
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
        //'itemsFilter',
        //'titleFilter',
        //'priorityFilter',
        //'categoryFilter',
        'showLoading',
        'showItemPopup',
        'categories',
        'selectedItem'
    ],
    ready: function () {
        this.getUrgentItems();
    }
});
