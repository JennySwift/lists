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
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
        'showItemPopup',
        'selectedItem',
        'categories',
        'closePopup'
    ],
    ready: function () {

    }
});
