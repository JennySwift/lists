var ItemsPage = Vue.component('items-page', {
    template: '#items-page-template',
    data: function () {
        return {
            showItemPopup: false,
            selectedItem: {},
            items: [],
            categories: [],
            alarms: [],
            zoomedItem: {},
            pinnedItems: [],
            breadcrumb: [],
            editingItems: false,
            newIndex: -1,
            currentTime: moment(),
            recurringUnits: ['minute', 'hour', 'day', 'week', 'month', 'year'],

            filters: {
                priority: '',
                category: '',
                title: '',
                urgency: '',
                urgencyOut: '',
                notBefore: true
            }

        }
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
         * Run the items filter every minute so that the not before filter
         * keeps up with the current time
         */
        //runFilterRegularly: function () {
        //    var that = this;
        //    var interval = setInterval(function () {
        //        that.items = ItemsRepository.filter(items, this);
        //    }, 6000);
        //},

        /**
         * Update the currentTime every minute so the not-before filter stays up to date
         */
        keepCurrentTimeUpToDate: function () {
            var that = this;
            var interval = setInterval(function () {
                that.currentTime = moment();
            }, 60000);
        },

        /**
         *
         */
        getCategories: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/categories', function (response) {
                this.categories = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

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
            $.event.trigger('show-loading');
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
                HelpersRepository.handleResponseError(response);
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

            $.event.trigger('hide-loading');
        },

        /**
         *
         */
        getPinnedItems: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/items?pinned=true', function (response) {
                this.pinnedItems = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
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
        }

    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getCategories();
        this.getItems('zoom');
        this.getPinnedItems();
        this.keepCurrentTimeUpToDate();
        //this.runFilterRegularly();

        console.log(Date.parse('2pm 21 march').toString('yyyy-MM-dd HH:mm:ss'));
    }
});