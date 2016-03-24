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
            
            $(document).on('item-updated', function (event, item) {
                var indexOfItemInFavourites = _.indexOf(that.favouriteItems, _.findWhere(that.favouriteItems, {id: item.id}));
                if (item.favourite && indexOfItemInFavourites === -1) {
                    //Add the item to the favourites
                    that.favouriteItems.push(item);
                }
                else if (!item.favourite && indexOfItemInFavourites !== -1) {
                    //Remove the item from the favourites
                    that.favouriteItems = _.without(that.favouriteItems, that.favouriteItems[indexOfItemInFavourites]);
                }
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
