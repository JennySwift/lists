var Items = Vue.component('items', {
    template: '#items-template',
    data: function () {
        return ItemsRepository.initialData;
    },
    watch: {
        /**
         * This is so that if the item id in the URL is changed without
         * reloading the page, the zoomedItem changes
         * @param val
         * @param oldVal
         */
        '$route': function (val, oldVal) {
            //So this doesn't run on page load before the companies are loaded
            if (oldVal) {
                this.getItems('zoom');
            }
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
         * If the url specifies an item, zoom on that item
         */
        zoomItemThatMatchesRoute: function () {
            this.zoomedItem = this.findItemThatMatchesRoute();
            if (!this.zoomedItem) {
                this.$broadcast('provide-feedback', 'There is no item with an id of ' + this.$route.params.id.slice(1), 'error');
            }
        },

        /**
         * Called on on page load (from getItemsSuccess-todo), and when the url is changed
         * @returns {*}
         */
        findItemThatMatchesRoute: function () {
            return ItemsRepository.findModelThatMatchesRoute(this, this.items);
        },

        /**
         *
         */
        getItems: function (expandOrZoom, item) {
            this.showLoading = true;
            var url;
            if (item) {
                url = '/api/items/' + item.id;
            }
            else {
                var id = ItemsRepository.getIdFromUrl(this);
                if (id) {
                    url = '/api/items/' + id;
                }
                else {
                    url = '/api/items';
                }
            }

            this.$http.get(url, function (response) {
                this.getItemsSuccess(response, expandOrZoom, item);
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         * @param expandOrZoom
         * @param item
         */
        getItemsSuccess: function (response, expandOrZoom, item) {
            if (expandOrZoom === 'zoom') {
                if (response.children) {
                    this.zoomedItem = response;
                    this.items = response.children;
                    this.breadcrumb = response.breadcrumb;
                }
                else {
                    //home page
                    this.zoomedItem = false;
                    this.items = response;
                    this.breadcrumb = [];
                }

            }
            else if (expandOrZoom === 'expand') {
                item.children = response.children;
            }

            this.showLoading = false;
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
        getFavouriteItems: function () {
            this.showLoading = true;
            this.$http.get('/api/items?favourites=true', function (response) {
                this.favouriteItems = response;
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
            this.showFavourites = !this.showFavourites;
        },

        /**
         *
         */
        //goHome: function () {
        //    this.showLoading = true;
        //    this.$http.get('api/items', function (response) {
        //        showHome(response);
        //        this.showLoading = false;
        //    })
        //    .error(function (response) {
        //        this.handleResponseError(response);
        //    });
        //},

        ///**
        // *
        // * @param $favourite
        // */
        //goToFavourite: function ($favourite) {
        //    this.zoom($favourite);
        //    this.showFavourites = false;
        //},

        /**
         *
         * @param keycode
         * @returns {boolean}
         */
        insertItem: function (keycode) {
            if (keycode !== 13) {
                return false;
            }

            var data = ItemsRepository.setData(this.newItem, this.zoomedItem);

            this.showLoading = true;
            this.$http.post('/api/items', data, function (response) {
                this.insertItemSuccess(response);
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        insertItemSuccess: function (response) {
            if (this.zoomedItem) {
                this.zoomedItem.children.push(response);
            }
            else {
                //home page
                this.items.push(response);
            }

            //this.clearNewItemFields();
            this.$broadcast('provide-feedback', 'Item created', 'success');
            this.showLoading = false;
        },

        /**
         *
         * @param response
         * @param $item
         */
        //showChildren: function (response, $item) {
        //    //$item.children = response.children;
        //    this.items = [$item];
        //    this.breadcrumb = response.breadcrumb;
        //    this.zoomedItem = $item;
        //},

        /**
         *
         * @param item
         */
        //zoom: function (item) {
        //    this.showLoading = true;
        //    this.$http.get('/api/items/' + item.id, function (response) {
        //        var parentOfItem = ItemsRepository.findParent(this.items, item);
        //        if (!parentOfItem) {
        //            this.items[0].children = response.children;
        //        }
        //        this.showChildren(response, item);
        //        this.showLoading = false;
        //        })
        //        .error(function (response) {
        //            this.handleResponseError(response);
        //        });
        //},

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
        //showHome: function (response) {
        //    this.items = response;
        //    this.zoomedItem = null;
        //    this.breadcrumb = [];
        //},

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
        //toggleFavourite: function () {
        //    var $itemInFavourites = _.findWhere(favourites, {id: itemPopup.id});
        //    //Remove the item from the favourites if it is no longer a favourite
        //    if ($itemInFavourites && !itemPopup.favourite) {
        //        favourites = _.without(favourites, $itemInFavourites);
        //    }
        //    //Add the item to favourites if it is now a favourite
        //    else if (!$itemInFavourites && itemPopup.favourite) {
        //        //Todo: put the item in the correct place rather than at the end
        //        favourites.push(itemPopup);
        //    }
        //},

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
        this.getItems('zoom');
        this.getPinnedItems();
        this.getFavouriteItems();
    }
});