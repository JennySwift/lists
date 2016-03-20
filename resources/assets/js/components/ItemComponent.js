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
         * @param $item
         */
        openItemPopup: function ($item) {
            this.showItemPopup = true;
            this.selectedItem = $item;
            this.selectedItem.oldParentId = $item.parent_id;
            this.selectedItem.oldAlarm = $item.alarm;
        },
    },
    props: [
        //data to be received from parent
        'showLoading',
        'showItemPopup',
        'items',
        'item',
        'selectedItem',
        'zoomedItem',
        'zoom',
        'categories',
        'showChildren',
        'getItems',
        'itemsFilter',
        'filters',
        'deleteItem'
    ],
    ready: function () {
        //this.listen();
    }
});
