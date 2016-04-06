var UrgentItems = Vue.component('urgentItems', {
    template: '#urgent-items-template',
    data: function () {
        return {
            items: []
        };
    },
    components: {},
    filters: {
        order: function (items) {
            return FiltersRepository.order(items);
        }
    },
    methods: {

        /**
         *
         */
        getUrgentItems: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/items?urgent=true', function (response) {
                    this.items = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
        },

        /**
         * Todo: If the urgent item is visible in the items or the alarms
         * delete it from those places with the JS, too
         * @param item
         */
        deleteItem: function (item) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/items/' + item.id, function (response) {
                    this.items = _.without(this.items, item);
                    $.event.trigger('provide-feedback', ['Item deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('urgent-item-created', function (event, item) {
                that.items.push(item);
            });
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
        this.listen();
    }
});
