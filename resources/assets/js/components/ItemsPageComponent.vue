<template>
    <div>
        <!--Popup-->
        <item-popup
            :items.sync="items"
            :get-items="getItems"
        >
        </item-popup>

        <!--Alarms-->
        <alarms
            :show-loading.sync="showLoading"
            :show-item-popup.sync="showItemPopup"
            :selected-item.sync="selectedItem"
            :close-popup="closePopup"
        >
        </alarms>

        <!--Urgent items-->
        <urgent-items
            :items-filter="itemsFilter"
            :title-filter="titleFilter"
            :priority-filter="priorityFilter"
            :category-filter="categoryFilter"
            :item="item"
        >
        </urgent-items>

        <div id="lists" class="container">
            <!--Breadcrumb-->
            <div id="breadcrumb">
                <div>
                    <a v-link="{ path: '/items/' }">Home</a>
                    <i v-if="breadcrumb.length > 0" class="fa fa-angle-right"></i>
                </div>
                <div v-for="item in breadcrumb">
                    <a v-link="{ path: '/items/:' + item.id }">
                        {{ item.title }}
                    </a>
                    <i v-if="$index !== breadcrumb.length - 1" class="fa fa-angle-right"></i>
                </div>
            </div>

            <!--Favourites-->
            <favourite-items
            >
            </favourite-items>

            <!--Filter-->
            <filter
                :filters.sync="filters"
                :items.sync="items"
            >
            </filter>

            <!--New item-->
            <new-item
                :items.sync="items"
                :zoomed-item="zoomedItem"
            >
            </new-item>

            <!--Items-->
            <ul id="items">

                <item
                    v-for="item in items | itemsFilter"
                    :items-filter="itemsFilter"
                    :filters="filters"
                    :show-children="showChildren"
                    :items.sync="items"
                    :item="item"
                    :zoomed-item="zoomedItem"
                    :get-items="getItems"
                    :zoom="zoom"
                    class="item-with-children"
                >
                </item>

                <div v-if="items.length === 0">No items here</div>
            </ul>

        </div>

    </div>
</template>

<script>
    var moment = require('moment');
    var ItemsRepository = require('../repositories/ItemsRepository');

    module.exports = {
        data: function () {
            return {
                shared: store.state,
                items: [],
                alarms: [],
                zoomedItem: {},
                pinnedItems: [],
                breadcrumb: [],
                editingItems: false,
                newIndex: -1,
                currentTime: moment(),

                filters: {
                    minimumPriority: '',
                    priority: '',
                    category: '',
                    title: '',
                    urgency: '',
                    urgencyOut: '',
                    notBefore: true,
                    notBeforeDate: ''
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
                return filters.filter(items, this);
            }
        },
        methods: {

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
            *
            */
            getItems: function (expandOrZoom, item) {
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

                helpers.get({
                    url: url,
//                    storeProperty: 'items',
//                    loadedProperty: 'itemsLoaded',
                    callback: function (response) {
                        this.items = response;
                        this.getItemsSuccess(response, expandOrZoom, item);
                    }.bind(this)
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
            }

        },
        props: [
            //data to be received from parent
        ],
        ready: function () {
            this.getItems('zoom');
            this.keepCurrentTimeUpToDate();
        }
    };
</script>