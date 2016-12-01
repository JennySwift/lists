<template>
    <div>
        <div v-if="items.length > 0" id="alarms">
            <h5>Alarms</h5>

            <div id="alarm"></div>

            <item
                v-for="item in items | order" track-by="$index"
                :show-loading.sync="showLoading"
                :show-item-popup.sync="showItemPopup"
                :item="item"
                :selected-item.sync="selectedItem"
                :categories="categories"
                :delete-item="deleteItem"
            >
            </item>
        </div>
    </div>
</template>

<script>
    var ItemsRepository = require('../repositories/ItemsRepository');
    var moment = require('moment');

    module.exports = {
        template: '#alarms-template',
        data: function () {
            return {
                items: []
            };
        },
        components: {},
        filters: {
            order: function (items) {
                return filters.order(items);
            }
        },
        methods: {

            /**
            *
            */
            getItemsWithAlarm: function () {
                helpers.get({
                    url: '/api/items?alarm=true',
                    storeProperty: 'items',
                    loadedProperty: 'itemsLoaded',
                    callback: function (response) {
                        this.items = response;
                        for (var i = 0; i < this.items.length; i++) {
                            this.startAlarmCountDown(this.items[i]);
                        }
                    }.bind(this)
                });
            },

            /**
             *
             */
            startAlarmCountDown: function (item) {
                var that = this;
                var timer = setInterval(function () {
                    var timeLeft = moment(item.alarm, 'YYYY-MM-DD HH:mm:ss')
                        .diff(moment(), 'seconds');
                    item.timeLeft = timeLeft;

                    if (timeLeft < 1) {
                        alert(item.title);
                        clearInterval(timer);
                    }
                }, 1000);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('alarm-created', function (event, item) {
                    that.items.push(item);
                    that.startAlarmCountDown(item);
                });
                $(document).on('alarm-updated', function (event, item) {
                    //Updating didn't work so I'm instead deleting then adding it.
                    that.items = _.without(that.items, _.findWhere(that.items, {id: item.id}));
                    that.items.push(item);
                    that.startAlarmCountDown(item);
                });
            },

            /**
             * Todo: If the alarm item is visible in the items or the urgent items
             * delete it from those places with the JS, too
             * @param item
             */
            deleteItem: function (item) {
                ItemsRepository.deleteItem(this, item);
            },
        },
        props: [
            'showItemPopup',
            'selectedItem'
        ],
        ready: function () {
            this.getItemsWithAlarm();
            this.listen();
            //console.log(Date.parse('9am next mon'));
        }
    };
</script>