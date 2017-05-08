<template>
    <div>
        <!--<item-popup></item-popup>-->

        <!--<alarms-->
            <!--:show-item-popup.sync="showItemPopup"-->
            <!--:selected-item.sync="selectedItem"-->
            <!--:close-popup="closePopup"-->
        <!--&gt;-->
        <!--</alarms>-->

        <!--<urgent-items-->
            <!--:item="item"-->
        <!--&gt;-->
        <!--</urgent-items>-->

        <div id="lists" class="">
            <breadcrumb></breadcrumb>

            <favourite-items></favourite-items>

            <!--<filter></filter>-->

            <!--<new-item></new-item>-->

            <!--Items-->
            <ul id="items">

                <item
                    v-for="item in shared.items"
                    :key="item.id"
                    :item="item"
                    class="item-with-children"
                >
                </item>

                <div v-if="shared.items.length === 0">No items here</div>
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
                editingItems: false,
                newIndex: -1,
                currentTime: moment()
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
                    store.getItems('zoom');
                }
            }
        },
        components: {},
        filters: {
//            itemsFilter: function (items) {
//                return filters.filter(items, this);
//            }
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
//            zoomItemThatMatchesRoute: function () {
//                this.zoomedItem = this.findItemThatMatchesRoute();
//                if (!this.zoomedItem) {
//                    $.event.trigger('provide-feedback', ['There is no item with an id of ' + this.$route.params.id.slice(1), 'error']);
//                    //this.$broadcast('provide-feedback', 'There is no item with an id of ' + this.$route.params.id.slice(1), 'error');
//                }
//            },

            /**
             * Called on on page load (from getItemsSuccess-todo), and when the url is changed
             * @returns {*}
             */
            findItemThatMatchesRoute: function () {
                return ItemsRepository.findModelThatMatchesRoute(this, this.items);
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
            this.keepCurrentTimeUpToDate();
        }
    };
</script>