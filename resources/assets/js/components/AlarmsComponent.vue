<template>
    <div>
        
        <div v-if="shared.itemsWithAlarm.length > 0" id="alarms">
            <h5>Alarms</h5>

            <div id="alarm"></div>

            <item
                v-for="item in shared.itemsWithAlarm | order" track-by="$index"
                :key="item.id"
                :show-item-popup.sync="showItemPopup"
                :item="item"
                :selected-item.sync="selectedItem"
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
                shared: store.state
            };
        },
        components: {},
        filters: {
            order: function (itemsWithAlarm) {
                return filters.order(itemsWithAlarm);
            }
        },
        methods: {

            /**
            *
            */
            getItemsWithAlarm: function () {
                helpers.get({
                    url: '/api/items?alarm=true',
                    storeProperty: 'itemsWithAlarm',
                    loadedProperty: 'itemsWithAlarmLoaded',
                    callback: function (response) {
                        for (var i = 0; i < this.shared.itemsWithAlarm.length; i++) {
                            this.startAlarmCountDown(this.shared.itemsWithAlarm[i]);
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
                    store.add(item, 'itemsWithAlarm');
                    that.startAlarmCountDown(item);
                });
                $(document).on('alarm-updated', function (event, item) {
                    //Updating didn't work so I'm instead deleting then adding it.
                    store.delete(item, 'itemsWithAlarm');
                    store.add(item, 'itemsWithAlarm');
                    that.startAlarmCountDown(item);
                });
            },

            /**
             * Todo: If the alarm item is visible in the items or the urgent itemsWithAlarm
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
        mounted: function () {
            this.getItemsWithAlarm();
            this.listen();
        }
    };
</script>