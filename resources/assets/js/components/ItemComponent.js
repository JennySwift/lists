var Item = Vue.component('item', {
    template: '#item-template',
    data: function () {
        return {

        };
    },
    components: {},
    filters: {
        itemsFilter: function (items) {
            return ItemsRepository.filter(items, this);
        }
    },
    methods: {

        /**
         * For when item is deleted from the item popup
         */
        closeItemPopup: function () {
            if (this.showItemPopup) {
                this.showItemPopup = false;
                this.selectedItem = {};
            }
        },

        collapseItem: function ($item) {
            $item.children = [];
        },

        /**
         *
         * @param item
         */
        deleteItem: function (item) {
            if (confirm("Are you sure?")) {
                this.showLoading = true;
                this.$http.delete('/api/items/' + item.id, function (response) {
                        this.deleteJsItem(item);
                        this.closeItemPopup();
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
         * @param item
         */
        deleteJsItem: function (item) {
            var parent = ItemsRepository.findParent(this.items, item);
            if (parent) {
                parent.children = _.without(parent.children, item);
            }
            else {
                this.items = _.without(this.items, item);
            }
        },


        /**
         *
         * @param $item
         */
        openItemPopup: function ($item) {
            this.showItemPopup = true;
            this.selectedItem = $item;
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
        'showLoading',
        'showItemPopup',
        'items',
        'item',
        'selectedItem',
        'zoomedItem',
        'zoom',
        'categories',
        'showChildren',
        'getItems',
        'itemsFilter',
        'titleFilter',
        'priorityFilter',
        'categoryFilter'
    ],
    ready: function () {

    }
});
