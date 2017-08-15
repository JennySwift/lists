<template>

    <new-popup
        id="item-popup"
        :redirect-to="redirectTo"
    >
        <div slot="content">
            <div class="top-btns">
                <div>
                    <button
                        v-if="!shared.selectedItemClone.favourite"
                        v-on:click="toggleFavourite"
                        class="favourite fa fa-star-o btn btn-sm btn-default">
                    </button>

                    <button
                        v-if="shared.selectedItemClone.favourite"
                        v-on:click="toggleFavourite"
                        class="favourite fa fa-star">
                    </button>

                    <button
                        v-if="shared.selectedItemClone.deletedAt"
                        v-on:click="restore()"
                        class="btn btn-success"
                    >
                        Restore
                    </button>
                </div>
            </div>

            <div>
                <item-fields
                    :item="shared.selectedItemClone"
                    action="update"
                    :show="true"
                    :enter="updateItem"
                >
                </item-fields>
            </div>

        </div>

        <buttons slot="buttons"
                 v-if="!shared.selectedItemClone.deletedAt"
                 :save="updateItem"
                 :destroy="deleteItem"
                 :redirect-to="redirectTo"

        >
        </buttons>

    </new-popup>

</template>

<script>
    import DateTimeRepository from '../repositories/DateTimeRepository'
    import ItemsRepository from '../repositories/ItemsRepository'
    import store from '../repositories/Store'
    import helpers from '../repositories/Helpers'

    export default {
        template: '#item-popup-template',
        data: function () {
            return {
                shared: store.state,
                modalProps: true
            };
        },
        computed: {
            redirectTo: function () {
                return this.$route.path;
            }
        },
        filters: {
            /**
             *
             * @param dateTime
             * @returns {*|string}
             */
            userFriendlyDateTimeFilter: function (dateTime) {
                return DateTimeRepository.convertFromDateTime(DateTimeRepository.convertToDateTime(dateTime));
            }
        },
        components: {},
        methods: {

            /**
             * Make the selected item a favourite item if it wasn't already, and vice versa
             */
            toggleFavourite () {
                store.update(!this.shared.selectedItemClone.favourite, 'selectedItemClone.favourite');
            },

            /**
             * Restore a deleted item
             */
            restore: function () {
                var data = {deleted_at: null};

                helpers.put({
                    url: '/api/items/' + this.shared.selectedItemClone.id,
                    data: data,
                    message: 'Item restored',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        store.set(null, 'selectedItem.deletedAt');
                        this.updateFavourites(response);
                        this.showPopup = false;
                    }.bind(this)
                });
            },

            updateFavourites: function (item) {
                var itemInFavourites = helpers.findById(this.shared.favouriteItems, item.id);
                if (item.favourite && !itemInFavourites) {
                    //Add the item to the favourites
                    store.add(item, 'favouriteItems');
                }
                else if (!item.favourite && itemInFavourites) {
                    //Remove the item from the favourites
                    store.delete(itemInFavourites, 'favouriteItems');
                }
            },

            /**
             *
             */
            updateItem: function () {
                var data = ItemsRepository.setData(this.shared.selectedItemClone);

                helpers.put({
                    url: '/api/items/' + this.shared.selectedItemClone.id,
                    data: data,
                    message: 'Item updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        store.update(response, 'items');

                        this.updateFavourites(response);

                        if (this.shared.selectedItemClone.oldParentId != response.parent_id) {
                            this.jsMoveToNewParent(response);
                        }
                    }.bind(this)
                });
            },


            /**
             * Todo: use the store instead of this.items
             * @param response
             */
            jsMoveToNewParent: function (response) {
                var newParent = ItemsRepository.findParent(this.shared.items, response);
                if (newParent && newParent.children) {
                    newParent.children.push(response);
                }

                if (newParent) {
                    //So the plus symbol shows
                    newParent.has_children = true;
                }

                this.removeFromOldParent(response);
            },

            /**
             *
             */
            removeFromOldParent: function (response) {
                var oldParent = ItemsRepository.findParent(this.shared.items, this.shared.selectedItemClone, this.shared.selectedItemClone.oldParentId);
                if (oldParent) {
                    var ancestorIds = ItemsRepository.getAncestorIds(this.shared.selectedItemClone, []);


                    var path = ItemsRepository.getPath(null, ancestorIds, [], 0);

                    var stringPath = ItemsRepository.createPathAsString(path);
                    console.log('stringPath: ' + stringPath);

                    store.delete(response, stringPath);

                    //Check if the parent still has children, so the plus sign isn't displayed if it doesn't
                    if (oldParent.children.length < 1) {
                        console.log('huh');
                        oldParent.has_children = false;
                    }
                }
                else {
                    store.delete(this.shared.selectedItemClone, 'items');
                }
            },

            /**
             * Todo: If the item is an alarm,
             * delete it from the alarm with the JS, too
             * @param item
             */
            deleteItem: function () {
                ItemsRepository.deleteItem(this.shared.selectedItem, this);
            },

            /**
             * to do: rewrite
             */
//            listen: function () {
//                var that = this;
//                $(document).on('show-item-popup', function (event, item) {
//                    that.selectedItemClone = helpers.clone(item);
//                    that.selectedItemClone.oldParentId = item.parent_id;
//                    that.selectedItemClone.oldAlarm = item.alarm;
//                    that.selectedItemCloneInItemsArray = item;
//                    that.showPopup = true;
//                });
//            },

            optionChosen (option, inputId) {
                if (inputId === 'selected-item-new-parent') {
                    this.shared.selectedItemClone.parent_id = option.id;
                }
                else if (inputId === 'selected-item-recurring-unit') {
                    this.shared.selectedItemClone.recurringUnit = option;
                }
            },

            dateChosen (date, inputId) {
                if (inputId === 'selected-item-not-before') {
                    this.shared.selectedItemClone.notBefore = date;
                }
            }
        },
        created: function () {
            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
            this.$bus.$on('date-chosen', this.dateChosen);
        },
        mounted: function () {
//            this.listen();
        }
    }
</script>


