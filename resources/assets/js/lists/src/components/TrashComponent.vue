<template>
    <div id="trash-page">

        <item-popup></item-popup>

        <div id="trash">
            <div class="left-side">
                <div>You have {{shared.trashedItems.length}} items in the trash.</div>
                <div>If an item's parent has been deleted, the 'restore' button for that item will be disabled. You will need to restore the parent before restoring the child.</div>
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
    import _ from 'lodash'
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        computed: {
            filteredTrashedItems: function () {
                var sortedAndFiltered = filters.filter(this.shared.trashedItems, this);
                return _.sortBy(sortedAndFiltered, 'deleted_at').reverse();
            }
        },
        methods: {

            emptyTrash () {
                helpers.delete({
                    url: '/api/items/emptyTrash',
                    message: 'Trash emptied',
                    confirmTitle: 'Are you sure?',
                    confirmText: 'All items will be removed from the trash, and you will no longer have them.',
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