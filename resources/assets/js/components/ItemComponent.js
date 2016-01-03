var Item = Vue.component('item', {
    template: '#item-template',
    data: function () {
        return {
            //showLoading: false,
            //addingNewItem: false,
            //editingItem: false,
            //selectedItem: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateItem: function (item) {
            this.showLoading = true;

            var data = ItemsRepository.setData(item);

            this.$http.put('/api/items/' + item.id, data, function (response) {
                    this.updateItemSuccess(response);
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        updateItemSuccess: function (response) {
            //jsUpdateItem(response);
            this.showItemPopup = false;
            this.toggleFavourite();
            this.itemPopup = {};
            this.$broadcast('provide-feedback', 'Item updated', 'success');
            this.showLoading = false;
        },

        /**
         *
         * @param response
         */
        //jsUpdateItem: function (response) {
        //    var $parent = ItemsRepository.findParent(items, itemPopup);
        //    if ($parent) {
        //        var $index = _.indexOf($parent.children, _.findWhere($parent.children, {id: itemPopup.id}));
        //        $parent.children[$index] = response.data;
        //    }
        //    else {
        //        var $index = _.indexOf(items, _.findWhere(items, {id: itemPopup.id}));
        //        items[$index] = response.data;
        //    }
        //},

        /**
         * For when item is deleted from the item popup
         */
        closeItemPopup: function () {
            if (this.showItemPopup) {
                this.showItemPopup = false;
                this.itemPopup = {};
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
                        //$.event.trigger('provide-feedback', ['Item deleted', 'success']);
                        this.$broadcast('provide-feedback', 'Item deleted', 'success');
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
            this.itemPopup = $item;
        },

        /**
         *
         * @param $event
         * @param $popup
         */
        closePopup: function ($event, $popup) {
            if ($event.target.className === 'popup-outer') {
                //show.popups[$popup] = false;
                this[$popup] = false;
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
        //data to be received from parent
        'showLoading',
        'showItemPopup',
        'items',
        'item',
        'itemPopup',
        'zoomedItem',
        'zoom',
        'categories',
        'showChildren',
        'getItems'
    ],
    ready: function () {

    }
});
