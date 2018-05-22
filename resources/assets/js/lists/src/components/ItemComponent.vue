<template>

    <!--<li v-if="item">-->

        <!--<div class="item" v-bind:class="{'deleted': item.deletedAt || item.deleting}">-->

            <!--&lt;!&ndash;Before Item&ndash;&gt;-->
            <!--<div class="before-item">-->

                <!--<span class="badge priority">{{ item.priority }}</span>-->

                <!--<span v-if="item.urgency"-->
                      <!--v-bind:class="{'urgency-one': item.urgency == 1}"-->
                      <!--class="badge"-->
                <!--&gt;-->
                    <!--{{ item.urgency }}-->
                 <!--</span>-->

                <!--<span v-if="!item.urgency"-->
                      <!--class="badge my-hidden"-->
                <!--&gt;-->
                    <!--0-->
                <!--</span>-->

                <!--<button-->
                    <!--v-on:click="deleteItem(item)"-->
                    <!--v-if="!isTrashPage"-->
                    <!--class="btn-danger btn-xs delete-item big-screen"-->
                <!--&gt;-->
                    <!--<span class="fa fa-times"></span>-->
                <!--</button>-->

                <!--<f7-link-->
                    <!--v-if="!isTrashPage"-->
                    <!--v-bind:href="'/items/:' + item.id"-->
                <!--&gt;-->
                    <!--Expand-->
                <!--</f7-link>-->

                <!--<i-->
                    <!--v-if="!isTrashPage && item.has_children && (!item.children.data || item.children.data.length === 0)"-->
                    <!--v-on:click="expand(item)"-->
                    <!--class="fa fa-plus big-screen"-->
                <!--&gt;-->
                <!--</i>-->

                <!--<i-->
                    <!--v-if="!isTrashPage && item.has_children && item.children.data && item.children.data.length > 0"-->
                    <!--v-on:click="collapseItem(item)"-->
                    <!--class="fa fa-minus big-screen"-->
                <!--&gt;-->
                <!--</i>-->

                <!--<i-->
                    <!--v-if="!isTrashPage && !item.has_children"-->
                    <!--class="fa fa-plus my-hidden big-screen"-->
                <!--&gt;-->
                <!--</i>-->



                <!--&lt;!&ndash;Actions dropdown for small screens&ndash;&gt;-->
                <!--<div class="btn-group small-screen" v-if="!isTrashPage">-->

                    <!--<button-->
                        <!--type="button"-->
                        <!--class="btn btn-default btn-xs dropdown-toggle"-->
                        <!--data-toggle="dropdown"-->
                        <!--aria-haspopup="true"-->
                        <!--aria-expanded="false"-->
                    <!--&gt;-->
                        <!--<span class="caret"></span>-->
                    <!--</button>-->

                    <!--<ul class="dropdown-menu">-->
                        <!--<li>-->
                            <!--<a-->
                                <!--href="#"-->
                                <!--v-on:click="deleteItem(item)"-->
                                <!--class="fa fa-times"-->
                            <!--&gt;-->
                            <!--</a>-->
                        <!--</li>-->

                        <!--<li>-->
                            <!--<f7-link-->
                                <!--v-bind:href="'/items/:' + item.id"-->
                            <!--&gt;-->
                                <!--Expand-->
                            <!--</f7-link>-->
                        <!--</li>-->
                    <!--</ul>-->

                <!--</div>-->

                <!--<i-->
                    <!--v-if="!isTrashPage && item.has_children && (!item.children.data || item.children.data.length === 0)"-->
                    <!--v-on:click="expand(item)"-->
                    <!--class="fa fa-plus small-screen"-->
                <!--&gt;-->
                <!--</i>-->

                <!--<i-->
                    <!--v-if="!isTrashPage && item.has_children && item.children.data && item.children.data.length > 0"-->
                    <!--v-on:click="collapseItem(item)"-->
                    <!--class="fa fa-minus small-screen"-->
                <!--&gt;-->
                <!--</i>-->

                <!--<i-->
                    <!--v-if="!isTrashPage && !item.has_children"-->
                    <!--class="fa fa-plus my-hidden small-screen"-->
                <!--&gt;-->
                <!--</i>-->



                <!--<i v-if="item.body" class="fa fa-sticky-note note small-screen"></i>-->
                <!--<i v-if="item.pinned" class="fa fa-map-pin pinned small-screen"></i>-->
                <!--<i v-if="item.alarm" class="fa fa-bell alarm small-screen"></i>-->
                <!--<i v-if="item.timeLeft" class="small-screen">{{ item.timeLeft | timeLeftFilter }}</i>-->

                <!--<span-->
                    <!--v-if="item.category"-->
                    <!--class="label label-primary small-screen"-->
                <!--&gt;-->
                    <!--{{ item.category.name }}-->
                <!--</span>-->

            <!--</div>-->


            <!--<div-->
                <!--v-if="!item.html"-->
                <!--v-on:click="selectItem(item)"-->
                <!--class="item-content"-->
            <!--&gt;-->

                <!--<div class="title">{{ item.title }}</div>-->

                <!--<div class="big-screen">-->
                    <!--<i v-if="item.body" class="fa fa-sticky-note note"></i>-->
                    <!--<i v-if="item.pinned" class="fa fa-map-pin pinned"></i>-->
                    <!--<i v-if="item.alarm" class="fa fa-bell alarm"></i>-->
                    <!--<i v-if="item.timeLeft" class="">{{ item.timeLeft | timeLeftFilter }}</i>-->
                    <!--<span v-if="item.notBefore" class="not-before">Not before {{ item.notBefore | dateTimeFilter}}</span>-->

                    <!--<div v-if="item.recurringUnit" class="recurring">-->
                        <!--<i class="fa fa-refresh"></i>-->
                        <!--<span>Repeats every {{ item.recurringFrequency }} {{ item.recurringUnit }}</span>-->
                        <!--<span v-if="item.recurringFrequency > 1">s</span>-->
                    <!--</div>-->
                <!--</div>-->

            <!--</div>-->

            <!--&lt;!&ndash;After Item&ndash;&gt;-->
            <!--<div class="after-item big-screen">-->

                <!--<span v-if="item.category" class="label label-primary category">{{ item.category.name }}</span>-->
                <!--<span v-if="item.deleted_at && isTrashPage" class="deleted-at">Deleted at {{item.deleted_at}}</span>-->
                <!--<button v-if="isTrashPage" v-on:click="restoreItem(item)" :disabled="!item.canBeRestored" class="btn btn-sm btn-default">Restore</button>-->

            <!--</div>-->

        <!--</div>-->
        <!---->
        <!--<ul v-if="item.children && item.children.data.length > 0">-->

            <!--<ul v-if="item.children && item.children.data.length > 0">-->
                <!--<item-->
                    <!--v-for="item in item.children.data"-->
                    <!--:key="item.id"-->
                    <!--:show-children="showChildren"-->
                    <!--:item="item"-->
                    <!--class="item-with-children"-->
                <!--&gt;-->
                <!--</item>-->

                <!--<li key="pagination">-->
                    <!--<div class="child-pagination-btns">-->
                        <!--<button v-bind:disabled="!item.children.pagination.prev_page_url" @click="prevPage()" class="btn btn-xs btn-warning">Prev</button>-->
                        <!--<button v-bind:disabled="!item.children.pagination.next_page_url" @click="nextPage()" class="btn btn-xs btn-warning">Next</button>-->
                    <!--</div>-->
                <!--</li>-->

            <!--</ul>-->

        <!--</ul>-->
    <!--</li>-->
</template>

<script>
    import DateTimeRepository from '../repositories/DateTimeRepository'
    import ItemsRepository from '../repositories/ItemsRepository'
    import filters from '../repositories/Filters'
    import store from '../repositories/Store'
    import helpers from '../repositories/Helpers'
    export default {
        template: '#item-template',
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        computed: {
            isTrashPage () {
                return helpers.getCurrentPath() === '/trash';
            },
//            filteredChildren: function () {
//                return filters.filter(this.item.children.data, this);
//            }
        },
        filters: {
            timeLeftFilter: function (seconds) {
                return filters.timeLeftFilter(seconds);
            },
            dateTimeFilter: function (dateTime) {
                return DateTimeRepository.convertFromDateTime(dateTime);
            },
        },
        methods: {
            collapseItem: function ($item) {
                $item.children.data = [];
            },

            prevPage: function () {
                this.expand(this.item.children.pagination.current_page - 1);
            },

            nextPage: function () {
                this.expand(this.item.children.pagination.current_page + 1);
            },

            expand: function (pageNumber) {
                store.getItemWithChildren(this.item, pageNumber);
            },

            selectItem: function (item) {
                store.set(helpers.clone(item), 'selectedItemClone');
                store.set(item.parent_id, 'selectedItemClone.oldParentId');

                store.set(item, 'selectedItem');
                helpers.showPopup('item-popup');
            },

            /**
             * Todo: If the item is an alarm,
             * delete it from the alarm with the JS, too
             * @param item
             */
            deleteItem: function (item) {
                ItemsRepository.deleteItem(item, this);
            },

            restoreItem (item) {
                helpers.put({
                    url: '/api/items/restore/' + item.id,
                    message: 'Item restored',
                    callback: function (response) {
                        store.delete(item, 'trashedItems');
                        //Double equals in case one is null and another is undefined
                        if (item.parent_id == this.shared.zoomedItem.id) {
                            item.deletedAt = false;
                            store.update(item, 'items');
                        }
//                        store.getItems();
                    }.bind(this)
                });
            },
        },
        props: [
            //data to be received from parent
            'zoom',
            'showChildren',
            'item'
        ],
        mounted: function () {
            //this.listen();
        }
    }
</script>