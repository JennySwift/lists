<template>
    <div id="urgent-items">

        <div v-if="items.length > 0">
            <h5>Urgent</h5>

            <item
                v-for="item in items | order"
                :show-loading.sync="showLoading"
                :show-item-popup.sync="showItemPopup"
                :item="item"
                :categories="categories"
                :delete-item="deleteItem"
                :selected-item.sync="selectedItem"
                class="item-with-children"
            >
            </item>
        </div>

    </div>

</template>

<script>
    module.exports = {
        template: '#urgent-items-template',
        data: function () {
            return {
                items: []
            };
        },
        components: {},
        filters: {
            order: function (items) {
                return FiltersRepository.order(items);
            }
        },
        methods: {

            /**
            *
            */
            getUrgentItems: function () {
                helpers.get({
                    url: '/api/items?urgent=true',
//                    storeProperty: 'urgentItems',
//                    loadedProperty: 'urgentItemsLoaded',
                    callback: function (response) {
                        this.items = response;
                    }.bind(this)
                });
            },

            /**
            * Todo: If the urgent item is visible in the items or the alarms
             * delete it from those places with the JS, too
            */
            deleteItem: function (item) {
                helpers.delete({
                    url: '/api/items/' + item.id,
                    array: 'items',
                    itemToDelete: this.item,
                    message: 'Item deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showPopup = false;
                    }.bind(this)
                });
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('urgent-item-created', function (event, item) {
                    that.items.push(item);
                });
            }
        },
        props: [
            'showLoading',
            'showItemPopup',
            'selectedItem'
        ],
        ready: function () {
            this.getUrgentItems();
            this.listen();
        }
    };
</script>