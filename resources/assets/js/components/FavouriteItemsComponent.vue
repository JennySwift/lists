<template>
    <ul v-show="shared.showFavourites" id="favourite-items" class="list-group">
        <li
            v-for="item in shared.favouriteItems"
            :key="item.id"
            v-link="{ path: '/items/:' + item.id }"
            v-on:click="toggleFavourites()"
            class="list-group-item">
            <div class="title">{{ item.title }}</div>
            <div><span class="badge">{{ item.id }}</span></div>
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
            }
        },
        props: [
            //data to be received from parent
        ],
        ready: function () {

        }
    };
</script>