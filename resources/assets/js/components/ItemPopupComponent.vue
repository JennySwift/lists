<template>
    <div
        v-if="showPopup"
        v-on:click="closePopup($event)"
        class="popup-outer">

        <div id="item-popup" class="popup-inner">

            <div class="top-btns">
                <button v-on:click="deleteItem(selectedItem)" class="btn btn-danger delete-item">Delete</button>

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

            <textarea
                v-model="selectedItem.title"
                rows="2">
        </textarea>

            <h3>Note</h3>

            <textarea
                v-model="selectedItem.body"
                rows="10">
        </textarea>

            <div class="flex">
                <div class="form-group">
                    <label for="item-popup-category">Category</label>

                    <select
                        v-model="selectedItem.category"
                        id="item-popup-category"
                        class="form-control"
                    >
                        <option v-for="category in categories" v-bind:value="category">
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="selected-item-alarm">Alarm</label>
                    <input
                        v-model="selectedItem.alarm"
                        type="text"
                        id="selected-item-alarm"
                        name="selected-item-alarm"
                        placeholder="alarm"
                        class="form-control"
                    >
                </div>

                <!--Not before-->
                <div class="form-group">
                    <label for="selected-item-not-before">Not before</label>
                    <input
                        v-model="selectedItem.notBefore"
                        type="text"
                        id="selected-item-not-before"
                        name="selected-item-not-before"
                        placeholder=""
                        class="form-control"
                    >
                    <div>{{ selectedItem.notBefore | userFriendlyDateTimeFilter }}</div>
                </div>

            </div>

            <div class="flex">
                <div class="form-group">
                    <label for="selected-item-recurring-unit">Recurring unit</label>

                    <select
                        v-model="selectedItem.recurringUnit"
                        id="selected-item-recurring-unit"
                        class="form-control"
                    >
                        <option
                            v-for="recurringUnit in recurringUnits"
                            v-bind:value="recurringUnit"
                        >
                            {{ recurringUnit }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="selected-item-recurring-frequency">Recurring frequency</label>
                    <input
                        v-model="selectedItem.recurringFrequency"
                        type="number"
                        id="selected-item-recurring-frequency"
                        name="selected-item-recurring-frequency"
                        placeholder=""
                        class="form-control"
                    >
                </div>

            </div>

            <div class="flex">
                <div class="form-group">
                    <label for="selected-item-priority">Priority</label>
                    <input
                        v-model="selectedItem.priority"
                        type="number"
                        id="selected-item-priority"
                        name="selected-item-priority"
                        placeholder="priority"
                        class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="selected-item-urgency">Urgency</label>
                    <input
                        v-model="selectedItem.urgency"
                        type="number"
                        id="selected-item-urgency"
                        name="selected-item-urgency"
                        placeholder="urgency"
                        class="form-control"
                    >
                </div>
            </div>

            <div class="flex">
                <div id="item-autocomplete">
                    <autocomplete
                        url="/api/items"
                        autocomplete-field="new parent"
                        autocomplete-field-id="item-popup-autocomplete"
                    >
                    </autocomplete>
                </div>

                <div class="form-group">
                    <label for="selected-item-parent">Parent Id (To move home, make field empty)</label>
                    <input
                        v-model="selectedItem.parent_id"
                        type="text"
                        id="selected-item-parent"
                        name="selected-item-parent"
                        placeholder=""
                        class="form-control"
                    >
                </div>
            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-danger">Cancel</button>
                <button v-on:click="updateItem(selectedItem)" class="btn btn-success">Save</button>
            </div>

        </div>

    </div>
</template>

<script>
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
            updateItem: function (item) {
                var data = ItemsRepository.setData(item);

                helpers.put({
                    url: '/api/items/' + item.id,
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
            deleteItem: function (item) {
                ItemsRepository.deleteItem(this, item);
            },

            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this);
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
            'recurringUnits',
        ],
        events: {
            'option-chosen': function (option) {
                this.selectedItem.parent_id = option.id;
            }
        },
        ready: function () {
            this.listen();
        }
    };
</script>