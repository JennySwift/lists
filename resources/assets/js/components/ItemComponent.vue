<template>
    <li>

        <div class="item">

            <!--Before Item-->
            <div class="before-item">

                <span class="badge priority">{{ item.priority }}</span>

                <span v-if="item.urgency"
                      v-bind:class="{'urgency-one': item.urgency == 1}"
                      class="badge"
                >
        {{ item.urgency }}
    </span>

                <span v-if="!item.urgency"
                      class="badge my-hidden"
                >
        0
    </span>

                <button
                    v-on:click="deleteItem(item)"
                    class="btn-danger btn-xs delete-item big-screen"
                >
                    <span class="fa fa-times"></span>
                </button>

                <i
                    v-link="{ path: '/items/:' + item.id }"
                    class="fa fa-search-plus big-screen"
                >
                </i>

                <i
                    v-if="item.has_children && (!item.children || item.children.length === 0)"
                    v-on:click="getItems('expand', item)"
                    class="fa fa-plus big-screen"
                >
                </i>

                <i
                    v-if="item.has_children && item.children && item.children.length > 0"
                    v-on:click="collapseItem(item)"
                    class="fa fa-minus big-screen"
                >
                </i>

                <i
                    v-if="!item.has_children"
                    class="fa fa-plus my-hidden big-screen"
                >
                </i>



                <!--Actions dropdown for small screens-->
                <div class="btn-group small-screen">

                    <button
                        type="button"
                        class="btn btn-default btn-xs dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                href="#"
                                v-on:click="deleteItem(item)"
                                class="fa fa-times"
                            >
                            </a>
                        </li>

                        <li>
                            <a
                                href="#"
                                v-link="{ path: '/items/:' + item.id }"
                                class="fa fa-search-plus"
                            >
                            </a>
                        </li>
                    </ul>

                </div>

                <i
                    v-if="item.has_children && (!item.children || item.children.length === 0)"
                    v-on:click="getItems('expand', item)"
                    class="fa fa-plus small-screen"
                >
                </i>

                <i
                    v-if="item.has_children && item.children && item.children.length > 0"
                    v-on:click="collapseItem(item)"
                    class="fa fa-minus small-screen"
                >
                </i>

                <i
                    v-if="!item.has_children"
                    class="fa fa-plus my-hidden small-screen"
                >
                </i>



                <i v-if="item.body" class="fa fa-sticky-note note small-screen"></i>
                <i v-if="item.pinned" class="fa fa-map-pin pinned small-screen"></i>
                <i v-if="item.alarm" class="fa fa-bell alarm small-screen"></i>
                <i v-if="item.timeLeft" class="small-screen">{{ item.timeLeft | timeLeftFilter }}</i>

                <span
                    v-if="item.category"
                    class="label label-primary small-screen"
                >
        {{ item.category.name }}
    </span>

            </div>


            <div
                v-if="!item.html"
                v-on:click="showItemPopup(item)"
                class="item-content"
            >

                <div class="title">{{ item.title }}</div>

                <div class="big-screen">
                    <i v-if="item.body" class="fa fa-sticky-note note"></i>
                    <i v-if="item.pinned" class="fa fa-map-pin pinned"></i>
                    <i v-if="item.alarm" class="fa fa-bell alarm"></i>
                    <i v-if="item.timeLeft" class="">{{ item.timeLeft | timeLeftFilter }}</i>
                    <span v-if="item.notBefore" class="not-before">Not before {{ item.notBefore | dateTimeFilter}}</span>

                    <div v-if="item.recurringUnit" class="recurring">
                        <i class="fa fa-refresh"></i>
                        <span>Repeats every {{ item.recurringFrequency }} {{ item.recurringUnit }}</span>
                        <span v-if="item.recurringFrequency > 1">s</span>
                    </div>
                </div>

            </div>

            <!--After Item-->
            <div class="after-item big-screen">

                <span v-if="item.category" class="label label-primary category">{{ item.category.name }}</span>

                <span class="badge">ID: {{ item.id }}</span>

            </div>

        </div>

        <!--Children-->
        <ul v-if="item.children.length > 0">

            <item
                v-if="!breadcrumb || breadcrumb.length < 1"
                v-for="item in item.children | itemsFilter"
                :filters="filters"
                :show-children="showChildren"
                :items.sync="items"
                :item="item"
                :selected-item.sync="selectedItem"
                :zoomed-item="zoomedItem"
                :get-items="getItems"
                :zoom="zoom"
                class="item-with-children"
            >
            </item>
        </ul>
    </li>
</template>

<script>
    var DateTimeRepository = require('../repositories/DateTimeRepository');
    var ItemsRepository = require('../repositories/ItemsRepository');

    module.exports = {
        template: '#item-template',
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        filters: {
            itemsFilter: function (items) {
                return shared.filter(items, this);
            },
            timeLeftFilter: function (seconds) {
                return shared.timeLeftFilter(seconds);
            },
            dateTimeFilter: function (dateTime) {
                return DateTimeRepository.convertFromDateTime(dateTime);
            }
        },
        methods: {

            collapseItem: function ($item) {
                $item.children = [];
            },

            /**
             *
             * @param item
             */
            showItemPopup: function (item) {
                $.event.trigger('show-item-popup', [item]);
            },

            /**
             * Todo: If the item is an alarm,
             * delete it from the alarm with the JS, too
             * @param item
             */
            deleteItem: function (item) {
                ItemsRepository.deleteItem(this, item);
            },
        },
        props: [
            //data to be received from parent
            'items',
            'item',
            'zoomedItem',
            'zoom',
            'showChildren',
            'getItems',
            'itemsFilter',
        ],
        ready: function () {
            //this.listen();
        }
    };
</script>