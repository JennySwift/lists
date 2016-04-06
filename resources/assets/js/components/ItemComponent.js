var Item = Vue.component('item', {
    template: '#item-template',
    data: function () {
        return {

        };
    },
    components: {},
    filters: {
        itemsFilter: function (items) {
            return ItemsRepository.filter(items, this);
        },
        timeLeftFilter: function (seconds) {
            return ItemsRepository.timeLeftFilter(seconds);
        },
        dateTimeFilter: function (dateTime) {
            return ItemsRepository.dateTimeFilter(dateTime);
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
        'categories',
        'showChildren',
        'getItems',
        'itemsFilter',
        'filters'
    ],
    ready: function () {
        //this.listen();
    }
});
