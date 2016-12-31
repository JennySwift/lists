<template>
    <ul v-show="shared.showFavourites" id="favourite-items" class="list-group">
        <li
            v-for="item in shared.favouriteItems"
            v-link="{ path: '/items/:' + item.id }"
            v-on:click="toggleFavourites()"
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
                shared: store.state
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            toggleFavourites: function () {
                store.toggle('showFavourites');
            },

            /**
             *
             */
            listen: function () {
                var that = this;

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
        }
    };
</script>