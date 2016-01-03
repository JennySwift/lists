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
         * @param $item
         */
        getChildren: function ($item) {
            this.showLoading = true;
            this.$http.get($item.path, function (response) {
                    $item.children = response.children;
                    this.showLoading = false;
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },
        /**
         *
         * @param $item
         */
        zoom: function ($item) {
            this.showLoading = true;
            this.$http.get($item.path, function (response) {
                    $item.children = response.children;
                    this.showChildren(response, $item);
                    this.showLoading = false;
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         * @param response
         * @param $item
         */
        showChildren: function (response, $item) {
            $item.children = response.children;
            this.items = [$item];
            this.breadcrumb = response.breadcrumb;
            this.zoomed_item = $item;
        },

        /**
         *
         */
        updateItem: function () {
            this.showLoading = true;

            var data = {
                title: item.title,
                body: item.body,
                priority: item.priority,
                favourite: item.favourite,
                pinned: item.pinned,
                parent_id: item.parent_id
            };

            this.$http.put('/api/items/' + item.id, data, function (response) {
                    jsUpdateItem(response);
                    this.showItemPopup = false;
                    this.toggleFavourite();
                    this.itemPopup = {};
                    this.$broadcast('provide-feedback', 'Item updated', 'success');
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
        jsUpdateItem: function (response) {
            var $parent = SortableFactory.findParent(items, itemPopup);
            if ($parent) {
                var $index = _.indexOf($parent.children, _.findWhere($parent.children, {id: itemPopup.id}));
                $parent.children[$index] = response.data;
            }
            else {
                var $index = _.indexOf(items, _.findWhere(items, {id: itemPopup.id}));
                items[$index] = response.data;
            }
        },

        /**
         * For when item is deleted from the item popup
         */
        closeItemPopup: function () {
            if (showItemPopup) {
                showItemPopup = false;
                itemPopup = {};
            }
        },

        collapseItem: function ($item) {
            $item.children = [];
        },

        //var $parent;
        /**
         *
         * @param $array
         * @param $item
         * @returns {*}
         */
        findParent: function($array, $item) {
            if (!$item.parent_id) {
                return false;
            }
            $($array).each(function () {
                if (this.id === $item.parent_id) {
                    $parent = this;
                    return false;
                }
                if (this.children) {
                    findParent(this.children, $item);
                }
            });
            return $parent;
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
            var parent = this.findParent(this.items, item);
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
            var $target = $event.currentTarget;
            if ($target.className === 'popup-outer') {
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
        'categories'
    ],
    ready: function () {

    }
});
