<template>
    <div id="trash-page">

        <item-popup></item-popup>

        <div id="trash">
            <div class="left-side">
                <div>You have {{shared.trashedItems.length}} items in the trash.</div>
                <button v-on:click="emptyTrash" class="btn btn-default">Empty Trash</button>
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
                shared: store.state
            };
        },
        components: {},
        computed: {
            filteredTrashedItems: function () {
                return filters.filter(this.shared.trashedItems, this);
            }
        },
        methods: {

            emptyTrash () {
                helpers.delete({
                    url: '/api/items/emptyTrash',
                    message: 'Trash emptied',
                    confirmMessage: 'Are you sure you want to empty the trash?',
                    callback: function () {
                        store.getTrashedItems();
                    }.bind(this)
                });
            },

        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            store.getTrashedItems();
        }
    }
</script>