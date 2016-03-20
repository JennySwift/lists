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

        /**
         *
         */
        //listen: function () {
        //    var that = this;
        //    $(document).on('delete-item', function (event, item) {
        //        that.deleteItem(item);
        //    });
        //},

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
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
        'titleFilter',
        'priorityFilter',
        'categoryFilter',
        'deleteItem'
    ],
    ready: function () {
        //this.listen();
    }
});
