<template>
    <div id="trash-page">

        <item-popup></item-popup>

        <div id="trash">
            <div class="left-side">
                <div>You have {{trashedItems.length}} items in the trash.</div>
                <ul id="items">

                    <item
                        v-for="item in filteredTrashedItems"
                        :item="item"
                        :key="item.id"
                    >
                    </item>

                </ul>
            </div>

            <div class="right-side">
                <items-filter></items-filter>
            </div>

        </div>
    </div>

</template>

<script>
    import helpers from '../repositories/Helpers'
    import store from '../repositories/Store'
    import filters from '../repositories/Filters'
    export default {
        data: function () {
            return {
                trashedItems: [],
                shared: store.state
            };
        },
        components: {},
        computed: {
            filteredTrashedItems: function () {
                return filters.filter(this.trashedItems, this);
            }
        },
        methods: {

            /**
            *
            */
            getTrashedItems: function () {
                helpers.get({
                    url: '/api/items?trashed=true',
                    callback: function (response) {
                        this.trashedItems = response;
                    }.bind(this)
                });
            }
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            this.getTrashedItems();
        }
    }
</script>