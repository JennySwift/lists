var Items = Vue.component('items', {
    template: '#items-template',
    data: function () {
        return {
            showLoading: false,
            items: [],
            pinnedItems: [],
            addingNewItems: false,
            editingItems: false,
            //selectedItems: {}
            categories: categories,
            favourites: favourites,
            paths: {
                base: base_path,
                test: base_path + '/resources/views/test.php'
            },
            newItem: {
                title: '',
                body: '',
                favourite: false,
                pinned: false
            },
            show: {
                popups: {
                    item: false
                },
                favourites: false
            },
            newIndex: -1,
        }
    },
    components: {},
    methods: {

        /**
         *
         */
        getItems: function () {
            this.showLoading = true;
            this.$http.get('/api/items', function (response) {
                this.items = response;
                this.showLoading = false;
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         */
        getPinnedItems: function () {
            this.showLoading = true;
            this.$http.get('/api/items?pinned=true', function (response) {
                this.pinnedItems = response;
                this.showLoading = false;
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         */
        toggleFavourites: function () {
            show.favourites = !show.favourites;
        },

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
         */
        goHome: function () {
            this.showLoading = true;
            this.$http.get('api/items', function (response) {
                showHome(response);
                this.showLoading = false;
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        collapseItem: function ($item) {
            $item.children = [];
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
         * @param $favourite
         */
        goToFavourite: function ($favourite) {
            zoom($favourite);
            show.favourites = false;
        },

        /**
         *
         * @param response
         * @param $item
         */
        showChildren: function (response, $item) {
            $item.children = response.children;
            items = [$item];
            breadcrumb = response.breadcrumb;
            zoomed_item = $item;
        },

        /**
         *
         * @param keycode
         * @returns {boolean}
         */
        insertItem: function (keycode) {
            if (keycode !== 13) {
                return false;
            }

            this.showLoading = true;

            if (zoomedItem) {
                parent_id = zoomedItem.id;
            }
            else {
                parent_id = null;
            }

            var data = {
                title: item.title,
                body: item.body,
                priority: item.priority,
                favourite: item.favourite,
                pinned: item.pinned,
                category_id: item.category_id,
                parent_id: parent_id
            };

            this.$http.post('/api/items', data, function (response) {
                if (zoomed_item) {
                    this.showChildren(response, zoomed_item);
                }
                else {
                    this.showHome(response);
                }
                this.clearNewItemFields();
                this.$broadcast('provide-feedback', 'Item created', 'success');
                this.showLoading = false;
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         */
        showNewItemFields: function () {
            this.addingNewItem = true;
            this.editingItem = false;
        },

        /**
         *
         * @param response
         */
        showHome: function (response) {
            items = response;
            zoomed_item = null;
            breadcrumb = [];
        },

        /**
         *
         * @param $keycode
         * @returns {boolean}
         */
        filter: function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }

            this.showLoading = true;

            var data = {
                typing: $("#filter").val()
            };

            this.$http.post('filter', data, function (response) {
                items = highlightLetters(response, typing);
                this.showLoading = false;
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         * @param $response
         * @param $typing
         * @returns {*}
         */
        highlightLetters: function ($response, $typing) {
            $typing = $typing.toLowerCase();

            for (var i = 0; i < $response.length; i++) {
                var $title = $response[i].title;
                var $index = $title.toLowerCase().indexOf($typing);
                var $substr = $title.substr($index, $typing.length);
                var $html = $title.replace($substr, '<span class="highlight">' + $substr + '</span>');
                $response[i].html = $html;
            }
            return $response;
        },

        /**
         *
         * @param item
         */
        deleteItem: function (item) {
            if (confirm("Are you sure?")) {
                this.showLoading = true;
                this.$http.delete('/api/items/' + item.id, function (response) {
                    var $parent = findParent(items, $item);
                    this.deleteJsItem($parent, $item);
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
         * For when item is deleted from the item popup
         */
        closeItemPopup: function () {
            if (show.popups.item) {
                show.popups.item = false;
                itemPopup = {};
            }
        },

        /**
         *
         * @param $parent
         * @param $item
         */
        deleteJsItem: function ($parent, $item) {
            if ($parent) {
                $parent.children = _.without($parent.children, $item);
            }
            else {
                items = _.without(items, $item);
            }
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
         * @param $item
         * @param $index
         */
        moveUp: function ($item, $index) {
            items.splice($index, 1);
            items.splice($index - 1, 0, $item);
        },

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
                    show.popups.item = false;
                    toggleFavourite();
                    itemPopup = {};
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
         * For when the 'favourite' button in the item popup is toggled,
         * after the item is saved
         */
        toggleFavourite: function () {
            var $itemInFavourites = _.findWhere(favourites, {id: itemPopup.id});
            //Remove the item from the favourites if it is no longer a favourite
            if ($itemInFavourites && !itemPopup.favourite) {
                favourites = _.without(favourites, $itemInFavourites);
            }
            //Add the item to favourites if it is now a favourite
            else if (!$itemInFavourites && itemPopup.favourite) {
                //Todo: put the item in the correct place rather than at the end
                favourites.push(itemPopup);
            }
        },

        /**
         *
         */
        clearNewItemFields: function () {
            newItem.title = '';
            newItem.body = '';
        },

        updateItem: function () {
            this.showLoading = true;

            this.$http.put('undoDeleteItem', function (response) {
                this.jsRestoreItem(response.data);
                this.$broadcast('provide-feedback', 'Item restored', 'success');
                this.showLoading = false;
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         * After undoing delete item, restored item is returned in the response.
         * Add this item to the items with the JS.
         * @param $item
         */
        jsRestoreItem: function ($item) {
            if (!$item.parent_id) {
                //Restore the item back home.
                if (!breadcrumb || breadcrumb.length < 1) {
                    //We are home
                    items.push($item);
                }

            }
            else {
                var $parent = SortableFactory.findParentById($item, items);
                if ($parent) {
                    //Todo: put it in the right spot, not just at the end
                    $parent.children.push($item);
                }
            }
        },

        /**
         *
         * @param $event
         * @param $popup
         */
        closePopup: function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                show.popups[$popup] = false;
            }
        },

        /**
         *
         * @param $item
         */
        showItemPopup: function ($item) {
            show.popups.item = true;
            itemPopup = $item;
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
        this.getItems();
        this.getPinnedItems();
    }
});