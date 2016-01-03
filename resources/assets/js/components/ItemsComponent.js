var Items = Vue.component('items', {
    template: '#items-template',
    data: function () {
        return {
            showLoading: false,
            showItemPopup: false,
            itemPopup: {},
            items: [],
            pinnedItems: [],
            breadcrumb: [],
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
                favourites: false
            },
            newIndex: -1,
            filterPriority: '',
            filterCategory: '',
            filterTitle: ''
        }
    },
    components: {},
    filters: {
        itemsFilter: function (items) {
            var that = this;

            return items.filter(function (item) {
                var filteredIn = item.title.indexOf(that.filterTitle) !== -1;

                if (that.filterPriority && item.priority != that.filterPriority) {
                    filteredIn = false;
                }
                else if (that.filterCategory && item.category_id !== that.filterCategory) {
                    filteredIn = false;
                }

                return filteredIn;
            });
        }
    },
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
                if (zoomedItem) {
                    this.showChildren(response, zoomedItem);
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
            zoomedItem = null;
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
         * @param $item
         * @param $index
         */
        moveUp: function ($item, $index) {
            items.splice($index, 1);
            items.splice($index - 1, 0, $item);
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