<template>
    <f7-page :page-content="false" with-subnavbar>
        <navbar title="Items" :page-has-search="true" add=".new-item-popup"></navbar>
        <breadcrumb></breadcrumb>

        <f7-page-content>
            <!--<f7-list v-if="screenWidth <= smallScreenWidth" contacts-list media-list class="no-chevron items">-->
                <!--<f7-list-group>-->
                    <!--<f7-list-item-->
                        <!--swipeout-->
                        <!--v-for="item in shared.items"-->
                        <!--:key="item.id"-->
                        <!--:link="'/items/' + item.id"-->
                        <!--v-bind:text="item.title"-->
                        <!--class="item"-->
                        <!--v-bind:class="{'deleted': item.deletedAt || item.deleting}"-->
                    <!--&gt;-->
                        <!--<div slot="header" class="item-before">-->
                            <!--<f7-badge color="yellow">{{item.priority}}</f7-badge>-->
                            <!--<i v-if="item.body" class="fas fa-sticky-note"></i>-->
                            <!--&lt;!&ndash;<f7-chip :text="item.category.data.name"></f7-chip>&ndash;&gt;-->
                        <!--</div>-->

                        <!--<div slot="subtitle">-->
                            <!--<div v-if="item.notBefore">Not before {{ item.notBefore | dateTimeFilter}}</div>-->
                            <!--<div v-if="item.recurringUnit">Repeats every {{ item.recurringFrequency }} {{item.recurringUnit}}s</div>-->
                        <!--</div>-->

                        <!--<div class="chevron-container">-->
                            <!--<f7-icon f7="chevron_right"  class="chevron" size="14" :class="{'has-children': item.has_children}"></f7-icon>-->
                        <!--</div>-->


                        <!--<div slot="root-end" class="action-btns" v-if="screenWidth > 1024">-->
                            <!--<div class="action-btn" v-on:click="openItemPopup(item)"><span>View/Edit</span></div>-->
                            <!--<div class="action-btn" v-on:click="deleteItem(item)"><span>Delete</span></div>-->
                        <!--</div>-->

                        <!--<f7-swipeout-actions left>-->
                            <!--<f7-swipeout-button close color="blue" overswipe v-on:click="openItemPopup(item)">View/Edit</f7-swipeout-button>-->
                        <!--</f7-swipeout-actions>-->

                        <!--<f7-swipeout-actions right>-->
                            <!--<f7-swipeout-button close color="red" overswipe v-on:click="deleteItem(item)">Delete</f7-swipeout-button>-->
                        <!--</f7-swipeout-actions>-->

                    <!--</f7-list-item>-->
                <!--</f7-list-group>-->
            <!--</f7-list>-->

            <f7-list contacts-list class="no-chevron items">
                <f7-list-group>
                    <f7-list-item
                        swipeout
                        v-for="item in shared.items"
                        :key="item.id"
                        :link="'/items/' + item.id"
                        v-bind:title="item.title"
                        class="item"
                        v-bind:class="{'deleted': item.deletedAt || item.deleting}"
                    >
                        <div slot="footer">
                            <i v-if="item.body" class="fas fa-sticky-note"></i>
                            <span v-if="item.notBefore">Not before {{ item.notBefore | dateTimeFilter}}</span>
                            <span v-if="item.recurringUnit">Repeats every {{ item.recurringFrequency }} {{item.recurringUnit}}s</span>
                        </div>

                        <div slot="after" class="category">
                            <span v-if="screenWidth > 700">{{item.category.data.name}}</span>
                        </div>

                        <div slot="root-end" class="action-btns" v-if="screenWidth > 1024">
                            <div class="action-btn" v-on:click="openItemPopup(item)"><span>View/Edit</span></div>
                            <div class="action-btn" v-on:click="deleteItem(item)"><span>Delete</span></div>
                        </div>

                        <div slot="inner-start" class="item-before">
                            <f7-badge color="yellow">{{item.priority}}</f7-badge>
                        </div>

                        <f7-icon f7="chevron_right" slot="inner-end" class="chevron" size="14" :class="{'has-children': item.has_children}"></f7-icon>

                        <f7-swipeout-actions left>
                            <f7-swipeout-button close color="blue" overswipe v-on:click="openItemPopup(item)">View/Edit</f7-swipeout-button>
                        </f7-swipeout-actions>

                        <f7-swipeout-actions right>
                            <f7-swipeout-button close color="red" overswipe v-on:click="deleteItem(item)">Delete</f7-swipeout-button>
                        </f7-swipeout-actions>

                    </f7-list-item>
                </f7-list-group>
            </f7-list>


            <div v-if="shared.items.length === 0">No items here</div>
        </f7-page-content>

        <f7-toolbar class="flex-container">
            <span class="pagination-info">Page {{shared.pagination.current_page}} of {{shared.pagination.last_page}}</span>
            <f7-button v-on:click="go()">Go</f7-button>
            <f7-button @click="prevPage()" v-bind:disabled="!shared.pagination.prev_page_url" class="btn btn-warning">Prev</f7-button>
            <f7-button @click="nextPage()" v-bind:disabled="!shared.pagination.next_page_url" class="btn btn-warning">Next</f7-button>
        </f7-toolbar>

    </f7-page>


</template>

<script>
    var moment = require('moment');
    import ItemsRepository from '../repositories/ItemsRepository'
    import store from '../repositories/Store'
    import filters from '../repositories/Filters'
    import DateTimeRepository from '../repositories/DateTimeRepository'

    export default {
        data: function () {
            return {
                shared: store.state,
                editingItems: false,
                newIndex: -1,
                currentTime: moment(),
                screenWidth: helpers.getScreenWidth(),
                smallScreenWidth: 1500
            }
        },
        computed: {
            //Commenting out after upgrade
            // path: function () {
            //     return this.$route.path;
            // }
        },
        filters: {
            dateTimeFilter: function (dateTime) {
                return DateTimeRepository.convertFromDateTime(dateTime);
            },
        },
        watch: {
            /**
             * This is so that if the item id in the URL is changed without
             * reloading the page, the zoomedItem changes
             * @param val
             * @param oldVal
             */
            // 'path': function (val, oldVal) {
            //     //So this doesn't run on page load before the companies are loaded
            //     if (oldVal) {
            //         store.getItems();
            //     }
            // }
        },
        components: {},
        methods: {
            deleteItem: function (item) {
                ItemsRepository.deleteItem(item);
            },

            prevPage: function () {
                store.goToPreviousPage();
            },

            nextPage: function () {
                store.goToNextPage();
            },

            go: function () {
                store.getItems();
            },

            openItemPopup: function (item) {
                store.openItemPopup(item);
            },

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
            // findItemThatMatchesRoute: function () {
            //     return ItemsRepository.findModelThatMatchesRoute(this, this.items);
            // },

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
            setTimeout(function () {
                store.getItems();
            }, 500);
            this.keepCurrentTimeUpToDate();
        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../../../sass/shared/index';
    .ios .fab[class*="-bottom"] {
        bottom: 54px;
    }
    .items {
        @include item;
    }


</style>