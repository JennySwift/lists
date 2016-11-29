<template>
    <div id="lists" class="container">
        <ul id="items">

            <item
                v-for="item in items"
                :show-loading.sync="showLoading"
                :items.sync="items"
                :item="item"
                :item-popup="false"
                class="item-with-children"
            >
            </item>

            <div v-if="items.length === 0">No items here</div>
        </ul>
    </div>
</template>

<script>
    module.exports = {
        template: '#trash-template',
        data: function () {
            return {
                showLoading: false,
                items: []
            };
        },
        components: {},
        methods: {

            /**
            *
            */
            getTrashedItems: function () {
                helpers.get({
                    url: '/api/items?trashed=true',
//                    storeProperty: 'trashedItems',
//                    loadedProperty: 'trashedItemsLoaded',
                    callback: function (response) {
                        this.items = response;
                    }.bind(this)
                });
            }
        },
        props: [
            //data to be received from parent
        ],
        ready: function () {
            this.getTrashedItems();
        }
    };
</script>