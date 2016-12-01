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

                <h3>Title (id: {{ selectedItem.id }}, parentId: {{ selectedItem.parent_id }}</h3>

                <item-fields
                    :item="selectedItem"
                    action="update"
                    :show="true"
                    :enter="updateItem"
                >
                </item-fields>

                <!--<textarea-->
                    <!--v-model="selectedItem.title"-->
                    <!--rows="2">-->
                <!--</textarea>-->

                <!--<h3>Note</h3>-->

                <!--<textarea-->
                    <!--v-model="selectedItem.body"-->
                    <!--rows="10">-->
        <!--</textarea>-->

                <!--<div class="input-group-container">-->
                    <!--<input-group-->
                        <!--label="Not Before:"-->
                        <!--:model.sync="selectedItem.notBefore"-->
                        <!--:enter="updateItem"-->
                        <!--id="selected-item-not-before"-->
                    <!--&gt;-->
                    <!--</input-group>-->

                    <!--<div>{{ selectedItem.notBefore | userFriendlyDateTimeFilter }}</div>-->

                    <!--<input-group-->
                        <!--label="Category:"-->
                        <!--:model.sync="selectedItem.category"-->
                        <!--:enter="updateItem"-->
                        <!--id="selected-item-category"-->
                        <!--:options="shared.categories"-->
                        <!--options-prop="name"-->
                    <!--&gt;-->
                    <!--</input-group>-->

                    <!--<input-group-->
                        <!--label="Alarm:"-->
                        <!--:model.sync="selectedItem.alarm"-->
                        <!--:enter="updateItem"-->
                        <!--id="selected-item-alarm"-->
                    <!--&gt;-->
                    <!--</input-group>-->

                    <!--<input-group-->
                        <!--label="Recurring Unit:"-->
                        <!--:model.sync="selectedItem.recurringUnit"-->
                        <!--:enter="updateItem"-->
                        <!--id="selected-item-recurring-unit"-->
                        <!--:options="shared.recurringUnits"-->
                    <!--&gt;-->
                    <!--</input-group>-->

                    <!--<input-group-->
                        <!--label="Recurring Frequency:"-->
                        <!--:model.sync="selectedItem.recurringFrequency"-->
                        <!--:enter="updateItem"-->
                        <!--id="selected-item-recurring-frequency"-->
                    <!--&gt;-->
                    <!--</input-group>-->

                    <!--<input-group-->
                        <!--label="Priority:"-->
                        <!--:model.sync="selectedItem.priority"-->
                        <!--:enter="updateItem"-->
                        <!--id="selected-item-priority"-->
                    <!--&gt;-->
                    <!--</input-group>-->

                    <!--<input-group-->
                        <!--label="Urgency:"-->
                        <!--:model.sync="selectedItem.urgency"-->
                        <!--:enter="updateItem"-->
                        <!--id="selected-item-urgency"-->
                        <!--type="number"-->
                    <!--&gt;-->
                    <!--</input-group>-->

                    <input-group
                        label="New Parent:"
                        :model.sync="selectedItem.newParent"
                        :enter="updateItem"
                        id="selected-item-new-parent"
                        url="/api/items"
                        options-prop="title"
                    >
                    </input-group>

                    <!--<div id="item-autocomplete">-->
                    <!--<autocomplete-->
                    <!--url="/api/items"-->
                    <!--autocomplete-field="new parent"-->
                    <!--autocomplete-field-id="item-popup-autocomplete"-->
                    <!--&gt;-->
                    <!--</autocomplete>-->
                    <!--</div>-->

                    <input-group
                        label="Parent Id:"
                        :model.sync="selectedItem.parent_id"
                        :enter="updateItem"
                        id="selected-item-parent-id"
                        tooltip-id="selected-item-parent-id-tooltip"
                    >
                    </input-group>

                    <div class="tooltip_templates">
                        <div id="selected-item-parent-id-tooltip">
                            To move home, make field empty
                        </div>
                    </div>
                </div>

                <buttons
                    :save="updateItem"
                    :destroy="deleteItem"
                    :redirect-to="redirectTo"
                >
                </buttons>

                <!--<div class="buttons">-->
                <!--<button v-on:click="showPopup = false" class="btn btn-danger">Cancel</button>-->
                <!--<button v-on:click="updateItem(selectedItem)" class="btn btn-success">Save</button>-->
                <!--</div>-->

            </div>
        </div>
    </popup>

</template>





<script>
    var DateTimeRepository = require('../repositories/DateTimeRepository');
    var ItemsRepository = require('../repositories/ItemsRepository');

    module.exports = {
        template: '#item-popup-template',
        data: function () {
            return {
                selectedItem: {},
                selectedItemInItemsArray: {},
                showPopup: false,
                shared: store.state
            };
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
            *
            */
            updateItem: function () {
                var data = ItemsRepository.setData(this.selectedItem);

                helpers.put({
                    url: '/api/items/' + this.selectedItem.id,
                    data: data,
                    property: 'items',
                    message: 'Item updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        ItemsRepository.updateProperties(this.selectedItemInItemsArray, response);

                        $.event.trigger('item-updated', [response]);

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
             *
             * @param response
             */
            jsMoveToNewParent: function (response) {
                var parent = ItemsRepository.findParent(this.items, response);
                if (parent && !parent.children) {
                    this.getItems('expand', parent);
                    //parent.children.push(response);
                }
                else if (parent) {
                    parent.children.push(response);
                }

                //Remove item from old parent
                var oldParent = ItemsRepository.findParent(this.items, response, this.selectedItem.oldParentId);
                if (oldParent) {
                    oldParent.children = _.without(oldParent.children, this.selectedItem);
                }
                else {
                    this.items = _.without(this.items, this.selectedItem);
                }
            },

            /**
             * Todo: If the item is an alarm,
             * delete it from the alarm with the JS, too
             * @param item
             */
            deleteItem: function () {
                ItemsRepository.deleteItem(this, this.selectedItem);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-item-popup', function (event, item) {
                    that.selectedItem = helpers.clone(item);
                    that.selectedItem.oldParentId = item.parent_id;
                    that.selectedItem.oldAlarm = item.alarm;

                    that.selectedItemInItemsArray = item;

                    that.showPopup = true;
                });
            }
        },
        props: [
            'items',
            'getItems',
        ],
        events: {
            'option-chosen': function (option) {
                this.selectedItem.parent_id = option.id;
            }
        },
        ready: function () {
            setTimeout(function () {
                helpers.tooltips();
            }, 2000);

            this.listen();
        }
    };
</script>