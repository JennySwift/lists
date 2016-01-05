var ItemPopup = Vue.component('item-popup', {
    template: '#item-popup-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateItem: function (item) {
            this.showLoading = true;

            var data = ItemsRepository.setData(item);

            this.$http.put('/api/items/' + item.id, data, function (response) {
                    this.updateItemSuccess(response);
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         * @param response
         */
        updateItemSuccess: function (response) {
            this.showItemPopup = false;
            this.selectedItem = {};
            $.event.trigger('provide-feedback', ['Item updated', 'success']);
            //this.$broadcast('provide-feedback', 'Item updated', 'success');
            this.showLoading = false;
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
        'selectedItem',
        'categories',
        'closePopup'
    ],
    ready: function () {

    }
});
