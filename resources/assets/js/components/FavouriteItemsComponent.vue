<template>
    <ul v-show="showFavourites" id="favourite-items" class="list-group">
        <li
            v-for="item in favouriteItems"
            v-link="{ path: '/items/:' + item.id }"
            v-on:click="showFavourites = false"
            class="list-group-item">
            {{ item.title }}
            <span class="badge">{{ item.id }}</span>
        </li>
    </ul>
</template>

<script>
    module.exports = {
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
                helpers.get({
                    url: '/api/items?favourites=true',
//                    storeProperty: 'favouriteItems',
//                    loadedProperty: 'favouriteItemsLoaded',
                    callback: function (response) {
                        this.favouriteItems = response;
                    }.bind(this)
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
    };
</script>