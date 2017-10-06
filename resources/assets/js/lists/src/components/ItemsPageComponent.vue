<template>
    <div id="items-page">
        <item-popup></item-popup>

        <div id="items-page-container">

            <div class="left-side">
                <button
                    v-on:click="toggleNewItemFields()"
                    id="new-item-btn"
                    class="btn btn-default btn-sm"
                >
                    New Item
                    <span v-show="shared.showNewItemFields" class="fa fa-caret-up"></span>
                    <span v-show="!shared.showNewItemFields" class="fa fa-caret-down"></span>
                </button>

                <breadcrumb></breadcrumb>

                <new-item></new-item>

                <!--Items-->
                <transition-group name="items" tag="ul" id="items">
                    <item
                    v-for="item in filteredItems"
                    :key="item.id"
                    :item="item"
                    class="item-with-children"
                    >
                    </item>
                </transition-group>





                <div v-if="shared.items.length === 0">No items here</div>
            </div>

            <div class="right-side">
                <!--<favourite-items></favourite-items>-->

                <items-filter></items-filter>
            </div>
        </div>

    </div>
</template>

<script>
    var moment = require('moment');
    import ItemsRepository from '../repositories/ItemsRepository'
    import store from '../repositories/Store'
    import filters from '../repositories/Filters'
//    import _ from 'lodash'

    export default {
        data: function () {
            return {
                shared: store.state,
                editingItems: false,
                newIndex: -1,
                currentTime: moment(),
            }
        },
        computed: {
            filteredItems: function () {
                return filters.filter(this.shared.items, this);
            },
            path: function () {
                return this.$route.path;
            }
        },
        watch: {
            /**
             * This is so that if the item id in the URL is changed without
             * reloading the page, the zoomedItem changes
             * @param val
             * @param oldVal
             */
            'path': function (val, oldVal) {
                //So this doesn't run on page load before the companies are loaded
                if (oldVal) {
                    store.getItems('zoom');
                }
            }
        },
        components: {},
        methods: {
            toggleNewItemFields: function () {
                store.toggle('showNewItemFields');
            },

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
        mounted: function () {
            this.keepCurrentTimeUpToDate();
        }
    }
</script>