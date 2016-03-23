var FavouriteItems = Vue.component('favourite-items', {
    template: '#favourite-items-template',
    data: function () {
        return {
            showFavourites: false,
            favouriteItems: []
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getFavouriteItems: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/items?favourites=true', function (response) {
                    this.favouriteItems = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
        },


        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('toggle-favourite-items', function (event) {
                that.showFavourites = !that.showFavourites;
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
        this.getFavouriteItems();
    }
});
