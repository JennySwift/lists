var Item = Vue.component('item', {
    template: '#item-template',
    data: function () {
        return {
            //showLoading: false,
            //addingNewItem: false,
            //editingItem: false,
            //selectedItem: {}
        };
    },
    components: {},
    methods: {
        /**
         *
         * @param $item
         */
        getChildren: function ($item) {
            this.showLoading = true;
            this.$http.get($item.path, function (response) {
                    $item.children = response.children;
                    this.showLoading = false;
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },
        /**
         *
         * @param $item
         */
        zoom: function ($item) {
            this.showLoading = true;
            this.$http.get($item.path, function (response) {
                    $item.children = response.children;
                    this.showChildren(response, $item);
                    this.showLoading = false;
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         * @param response
         * @param $item
         */
        showChildren: function (response, $item) {
            $item.children = response.children;
            this.items = [$item];
            this.breadcrumb = response.breadcrumb;
            this.zoomed_item = $item;
        },

        /**
         *
         * @param $item
         */
        openItemPopup: function ($item) {
            this.showItemPopup = true;
            this.itemPopup = $item;
        },

        /**
         *
         * @param $event
         * @param $popup
         */
        closePopup: function ($event, $popup) {
            var $target = $event.currentTarget;
            if ($target.className === 'popup-outer') {
                //show.popups[$popup] = false;
                this[$popup] = false;
            }
        },


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
        'itemPopup',
        'zoomedItem',
        'categories'
    ],
    ready: function () {

    }
});
