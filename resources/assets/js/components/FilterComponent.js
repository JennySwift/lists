var FilterComponent = Vue.component('filter', {
    template: '#filter-template',
    data: function () {
        return {
            showFilter: undefined
        };
    },
    components: {},
    filters: {
        /**
         *
         * @param date
         * @returns {*|string}
         */
        userFriendlyDateFilter: function (date) {
            return DateTimeRepository.convertFromDateTime(DateTimeRepository.convertToDateTime(date), 'DD/MM/YY');
        }
    },
    methods: {

        /**
         *
         */
        toggleFavouriteItems: function () {
            $.event.trigger('toggle-favourite-items');
        },

        /**
         * For when the 'favourite' button in the item popup is toggled,
         * after the item is saved
         */
        //toggleFavourite: function () {
        //    var $itemInFavourites = _.findWhere(favourites, {id: itemPopup.id});
        //    //Remove the item from the favourites if it is no longer a favourite
        //    if ($itemInFavourites && !itemPopup.favourite) {
        //        favourites = _.without(favourites, $itemInFavourites);
        //    }
        //    //Add the item to favourites if it is now a favourite
        //    else if (!$itemInFavourites && itemPopup.favourite) {
        //        //Todo: put the item in the correct place rather than at the end
        //        favourites.push(itemPopup);
        //    }
        //},

        /**
         *
         * @returns {boolean}
         */
        filter: function () {
            $.event.trigger('show-loading');

            var filter = $("#filter").val();

            this.$http.get('/api/items?filter=' + filter, function (response) {
                    //this.items = this.highlightLetters(response, filter);
                    this.items = response;
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
            $(document).on('toggle-filter', function (event) {
                that.showFilter = !that.showFilter;
            });
        }
    },
    props: [
        'categories',
        'favouriteItems',
        'filters',
        'items'
    ],
    ready: function () {
        this.showFilter = ItemsRepository.shouldFilterBeShownOnPageLoad();
        this.listen();
    }
});
