var ItemsPage = Vue.component('items-page', {
    template: '#items-page-template',
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
            return ItemsRepository.filter(items, this);
        }
    },
    methods: {

        /**
         * If the url specifies an item, zoom on that item
         */
        zoomItemThatMatchesRoute: function () {
            this.zoomedItem = this.findItemThatMatchesRoute();
            if (!this.zoomedItem) {
                $.event.trigger('provide-feedback', ['There is no item with an id of ' + this.$route.params.id.slice(1), 'error']);
                //this.$broadcast('provide-feedback', 'There is no item with an id of ' + this.$route.params.id.slice(1), 'error');
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
         * Should be named something else?
         * It's kind of a show method. (Shows the item with its children.)
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
         */
        getCategories: function () {
            this.showLoading = true;
            this.$http.get('/api/categories', function (response) {
                this.categories = response;
                this.showLoading = false;
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
         * @param keycode
         * @returns {boolean}
         */
        insertItem: function () {
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
            this.showNewItemFields = false;
            this.items.push(response);
            this.clearNewItemFields();
            $.event.trigger('provide-feedback', ['Item created', 'success']);
            //this.$broadcast('provide-feedback', 'Item created', 'success');
            if (response.alarm) {
                $.event.trigger('alarm-created', [response]);
            }
            if (response.urgency == 1) {
                $.event.trigger('urgent-item-created', [response]);
            }
            this.showLoading = false;
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
         * @returns {boolean}
         */
        filter: function () {
            this.showLoading = true;

            var filter = $("#filter").val();

            this.$http.get('/api/items?filter=' + filter, function (response) {
                //this.items = this.highlightLetters(response, filter);
                this.items = response;
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
            this.newItem.title = '';
            this.newItem.body = '';
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
         * Todo: If the item is an alarm,
         * delete it from the alarm with the JS, too
         * @param item
         */
        deleteItem: function (item) {
            ItemsRepository.deleteItem(this, item);
        },

        /**
         * For inserting an item into my lists app.
         * The item has been received from one of my apps, using Pusher.
         * To allow users of my apps to provide feedback
         */
        insertItemFromFeedback: function (feedback) {
            data = {
                title: feedback.title,
                body: feedback.body,
                priority: 1,
                //The id of my budget app item in my lists app
                parent_id: 468,
                //The id of my coding category in my lists app
                category_id: 1,
                favourite: 0,
                pinned: 0
                //'urgency' => 1,
                //'alarm' => $alarm
            };

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
         */
        listen: function () {
            var that = this;
            $(document).on('toggle-filter', function (event) {
                that.showFilter = !that.showFilter;
            });

            var pusher = new Pusher('0559aebf9ae96524872b');

            var myChannel = pusher.subscribe('myChannel');

            myChannel.bind('itemCreated', function(data) {
                alert(data);
                $.event.trigger('provide-feedback', [data]);
            });

            myChannel.bind('accountCreated', function(data) {
                alert(data);
            });

            myChannel.bind('budgetAppFeedbackSubmitted', function(data) {
                that.insertItemFromFeedback(data);
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
        this.listen();
        this.getItems('zoom');
        this.getCategories();
        this.getPinnedItems();
        this.getFavouriteItems();
        this.showFilter = ItemsRepository.shouldFilterBeShownOnPageLoad();
        //ItemsRepository.formatAlarm('thu 1pm');
    }
});