<template>
    <popup
        :show-popup.sync="showPopup"
        id="item-popup"
        :redirect-to="redirectTo"
        :update="updateItem"
        :destroy="deleteItem"
    >
        <div slot="content">
            <div id="item-popup" class="popup-inner">

                <div class="top-btns">
                    <!--<button v-on:click="deleteItem(selectedItem)" class="btn btn-danger delete-item">Delete</button>-->

                    <div>
                        <button
                            v-if="!selectedItem.favourite"
                            v-on:click="selectedItem.favourite = !selectedItem.favourite"
                            class="favourite fa fa-star-o">
                        </button>

                        <button
                            v-if="selectedItem.favourite"
                            v-on:click="selectedItem.favourite = !selectedItem.favourite"
                            class="favourite fa fa-star">
                        </button>

                    </div>

                </div>

                <div>
                    <item-fields
                        :item="selectedItem"
                        action="update"
                        :show="true"
                        :enter="updateItem"
                    >
                    </item-fields>
                </div>

                <buttons
                    v-if="!selectedItem.deletedAt"
                    :save="updateItem"
                    :destroy="deleteItem"
                    :redirect-to="redirectTo"
                    :show-popup.sync="showPopup"

                >
                </buttons>

                <button
                    v-else
                    v-on:click="restore()"
                    class="btn btn-success"
                >
                    Restore
                </button>

            </div>
        </div>
    </popup>

</template>





<script>
    var DateTimeRepository = require('../../../repositories/DateTimeRepository');
    var ItemsRepository = require('../repositories/ItemsRepository');

    module.exports = {
        template: '#item-popup-template',
        data: function () {
            return {
                selectedItem: {},
                selectedItemInItemsArray: {},
                showPopup: false,
                shared: store.state,
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
             * Restore a deleted item
             */
            restore: function () {
                var data = {deleted_at: null};

                helpers.put({
                    url: '/api/items/' + this.selectedItem.id,
                    data: data,
//                    property: ItemsRepository.getPathAsString(this.selectedItem),
                    message: 'Item restored',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        this.selectedItemInItemsArray.deletedAt = null;
//                        this.selectedItemInItemsArray = response;
//                        ItemsRepository.updateProperties(this.selectedItemInItemsArray, response);
                        this.updateFavourites(response);
                        this.showPopup = false;
                    }.bind(this)
                });
            },

            updateFavourites: function (item) {
                var itemInFavourites = helpers.findById(this.shared.favouriteItems, item.id);
//                    var indexOfItemInFavourites = _.indexOf(that.favouriteItems, _.findWhere(that.favouriteItems, {id: item.id}));
                if (item.favourite && !itemInFavourites) {
                    //Add the item to the favourites
                    store.add(item, 'favouriteItems');
                }
                else if (!item.favourite && itemInFavourites) {
                    //Remove the item from the favourites
                    store.delete(itemInFavourites, 'favouriteItems');
//                        that.favouriteItems = _.without(that.favouriteItems, that.favouriteItems[indexOfItemInFavourites]);
                }
            },

            /**
            *
            */
            updateItem: function () {
                var data = ItemsRepository.setData(this.selectedItem);

                helpers.put({
                    url: '/api/items/' + this.selectedItem.id,
                    data: data,
//                    property: ItemsRepository.getPathAsString(this.selectedItem),
                    message: 'Item updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        ItemsRepository.updateProperties(this.selectedItemInItemsArray, response);

                        this.updateFavourites(response);

                        if (this.selectedItem.oldParentId != response.parent_id) {
                            this.jsMoveToNewParent(response);
                        }
                        if (this.selectedItem.oldAlarm === null && this.selectedItem.alarm) {
                            //the alarm has been created
                            $.event.trigger('alarm-created', [response]);
                        }
                        else if (this.selectedItem.oldAlarm && this.selectedItem.oldAlarm != this.selectedItem.alarm) {
                            //the alarm has been changed
                            $.event.trigger('alarm-updated', [response]);
                        }

                        this.showPopup = false;
                    }.bind(this)
                });
            },


            /**
             * Todo: use the store instead of this.items
             * @param response
             */
            jsMoveToNewParent: function (response) {
                var newParent = ItemsRepository.findParent(this.shared.items, response);
//                if (parent && !parent.children) {
//                    this.getItems('expand', parent);
//                    //parent.children.push(response);
//                }
                //Add the item to the new parent if it is already expanded

//                console.log('\n\n new parent: ' + JSON.stringify(newParent, null, 4) + '\n\n');
//                console.log('new parent: ' + newParent);
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
                var oldParent = ItemsRepository.findParent(this.shared.items, this.selectedItem, this.selectedItem.oldParentId);
//                console.log('\n\n old parent: ' + JSON.stringify(oldParent, null, 4) + '\n\n');
                console.log('\n\n selected item: ' + JSON.stringify(this.selectedItem, null, 4) + '\n\n');
                if (oldParent) {
                    var ancestorIds = ItemsRepository.getAncestorIds(this.selectedItem, []);

                    console.log('ancestor ids: ' + ancestorIds);

                    var path = ItemsRepository.getPath(null, ancestorIds, [], 0);

                    console.log('path: ' + path);

//                    var stringPath = 'items[' + path + '].children';
//                    var stringPath = 'items[0].children[0].children[1].children[3].children';
                    var stringPath = ItemsRepository.createPathAsString(path);
                    console.log('stringPath: ' + stringPath);

                    store.delete(response, stringPath);

                    //Check if the parent still has children, so the plus sign isn't displayed if it doesn't
                    if (oldParent.children.length < 1) {
                        console.log('huh');
                        oldParent.has_children = false;
                    }
                    console.log('children: ' + oldParent.children.length);
//                    helpers.deleteById(oldParent.children, response.id);
//                    oldParent.children = _.without(oldParent.children, response);
                }
                else {
                    store.delete(this.selectedItem, 'items');
                }
            },

            /**
             * Todo: If the item is an alarm,
             * delete it from the alarm with the JS, too
             * @param item
             */
            deleteItem: function () {
                ItemsRepository.deleteItem(this.selectedItemInItemsArray, this);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-item-popup', function (event, item) {
//                    store.set(item, 'selectedItem');

                    that.selectedItem = helpers.clone(item);
                    that.selectedItem.oldParentId = item.parent_id;
                    that.selectedItem.oldAlarm = item.alarm;

                    that.selectedItemInItemsArray = item;

                    that.showPopup = true;
//                    store.set(true, 'showItemPopup');
                });
            }
        },
        events: {
            'option-chosen': function (option, inputId) {
                if (inputId === 'selected-item-new-parent') {
                    this.selectedItem.parent_id = option.id;
                }
            }
        },
        mounted: function () {
            setTimeout(function () {
                helpers.tooltips();
            }, 1000);

            this.listen();
        }
    };
</script>