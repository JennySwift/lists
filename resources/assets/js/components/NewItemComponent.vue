<template>
    <div id="new-item">

        <!--First column-->
        <div>
            <!--Title-->
            <div class="form-group">
                <label for="new-item-title">Title <span class="fa fa-asterisk"></span></label>
                <input
                    v-model="newItem.title"
                    v-on:keyup.13="insertItem()"
                    v-on:focus="showNewItemFields = true"
                    type="text"
                    id="new-item-title"
                    name="new-item-title"
                    placeholder="title"
                    class="form-control"
                >
            </div>

            <!--Body-->
            <div v-show="showNewItemFields" class="form-group">
                <label for="new-item-body">Body</label>
                <textarea
                    v-model="newItem.body"
                    placeholder="note"
                    class="note"
                    name="new-item-body"
                >
            </textarea>
            </div>
        </div>

        <!--Second column-->

        <div>
            <!--Category-->
            <div v-show="showNewItemFields" class="form-group">
                <label for="new-item-category">Category <span class="fa fa-asterisk"></span></label>

                <select
                    v-model="newItem.category"
                    v-on:keyup.13="insertItem()"
                    id="new-item-category"
                    class="form-control"
                >
                    <option
                        v-for="category in categories"
                        v-bind:value="category"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!--Priority-->
            <div v-show="showNewItemFields" class="form-group">
                <label for="new-item-priority">Priority <span class="fa fa-asterisk"></span></label>
                <input
                    v-model="newItem.priority"
                    v-on:keyup.13="insertItem()"
                    type="number"
                    id="new-item-priority"
                    name="new-item-priority"
                    placeholder=""
                    class="form-control priority"
                >
            </div>

            <!--Urgency-->
            <div v-show="showNewItemFields" class="form-group">
                <label for="new-item-urgency">Urgency</label>
                <input
                    v-model="newItem.urgency"
                    v-on:keyup.13="insertItem()"
                    type="number"
                    id="new-item-urgency"
                    name="new-item-urgency"
                    placeholder=""
                    class="form-control urgency"
                >
            </div>

            <!--Alarm-->
            <div v-show="showNewItemFields" class="form-group">
                <label for="new-item-alarm">Alarm</label>
                <input
                    v-model="newItem.alarm"
                    v-on:keyup.13="insertItem()"
                    type="text"
                    id="new-item-alarm"
                    name="new-item-alarm"
                    placeholder="alarm"
                    class="form-control"
                >
            </div>

            <!--Not before-->
            <div v-show="showNewItemFields" class="form-group">
                <label for="new-item-not-before">Not before</label>
                <input
                    v-model="newItem.notBefore"
                    v-on:keyup.13="insertItem()"
                    type="text"
                    id="new-item-not-before"
                    name="new-item-not-before"
                    placeholder=""
                    class="form-control"
                >
                <div>{{ newItem.notBefore | userFriendlyDateTimeFilter }}</div>
            </div>

            <!--Recurring unit-->
            <div v-show="showNewItemFields" class="form-group">
                <label for="new-item-recurring-unit">Recurring unit</label>

                <select
                    v-model="newItem.recurringUnit"
                    v-on:keyup.13="insertItem()"
                    id="new-item-recurring-unit"
                    class="form-control"
                >
                    <option
                        v-for="recurringUnit in shared.recurringUnits"
                        v-bind:value="recurringUnit"
                    >
                        {{ recurringUnit }}
                    </option>
                </select>
            </div>

            <!--Recurring Frequency-->
            <div v-show="showNewItemFields" class="form-group">
                <label for="new-item-recurring-frequency">Recurring frequency</label>
                <input
                    v-model="newItem.recurringFrequency"
                    v-on:keyup.13="insertItem()"
                    type="number"
                    id="new-item-recurring-frequency"
                    name="new-item-recurring-frequency"
                    placeholder=""
                    class="form-control"
                >
            </div>

            <div v-show="showNewItemFields" class="form-group">
                <button
                    v-on:click="insertItem(13)"
                    :disabled="!newItem.title || !newItem.category || !newItem.priority"
                    class="btn btn-success"
                >
                    Add item
                </button>
                <button
                    v-on:click="showNewItemFields = false"
                    class="btn btn-default"
                >
                    Cancel
                </button>
            </div>
        </div>

    </div>

</template>

<script>
    var DateTimeRepository = require('../repositories/DateTimeRepository');

    module.exports = {
        template: '#new-item-template',
        data: function () {
            return {
                me: {},
                shared: store.state,
                showNewItemFields: false,
                addingNewItems: false,
                newItem: {
                    title: '',
                    body: '',
                    favourite: false,
                    pinned: false,
                    category: {},
                    priority: 1
                }
            };
        },
        filters: {
            /**
             *
             * @param dateAndTime
             * @returns {*|string}
             */
            userFriendlyDateTimeFilter: function (dateAndTime) {
                return DateTimeRepository.convertFromDateTime(DateTimeRepository.convertToDateTime(dateAndTime));
            }
        },
        components: {},
        methods: {

            /**
            *
            */
            insertItem: function () {
                var data = ItemsRepository.setData(this.newItem, this.zoomedItem);

                helpers.post({
                    url: '/api/items',
                    data: data,
                    array: 'items',
                    message: 'Item created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showNewItemFields = false;
                        this.items.push(response);
                        this.clearNewItemFields();
                        $.event.trigger('provide-feedback', ['Item created', 'success']);
                        if (response.alarm) {
                            $.event.trigger('alarm-created', [response]);
                        }
                        if (response.urgency == 1) {
                            $.event.trigger('urgent-item-created', [response]);
                        }
                    }.bind(this)
                });
            },

            /**
             *
             */
            showNewItemFields: function () {
                this.addingNewItem = true;
                this.editingItem = false;
            },


            /**
             * For inserting an item into my lists app.
             * The item has been received from one of my apps, using Pusher.
             * To allow users of my apps to provide feedback
             * @param feedback
             * @param itemId The id of the item for the app in my lists app
             */
            insertItem: function (feedback, itemId) {
                data = {
                    title: feedback.title,
                    body: feedback.body,
                    priority: 1,
                    //The id of my budget app item in my lists app
                    parent_id: itemId,
                    //The id of my coding category in my lists app
                    category_id: 1,
                    favourite: 0,
                    pinned: 0,
                    //'urgency' => 1,
                    //'alarm' => $alarm
                };

                helpers.post({
                    url: '/api/items',
                    data: data,
                    array: 's',
                    message: 'Item created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.sendPushNotification(response);
                        this.insertItemSuccess(response);
                    }.bind(this)
                });
            },

            /**
             *
             */
            clearNewItemFields: function () {
                this.newItem.title = '';
                this.newItem.body = '';
            },

            /**
            * @param user
            */
            getUser: function (user) {
                helpers.get({
                    url: '/api/users/',
                    storeProperty: 'user',
                    loadedProperty: 'userLoaded',
                    callback: function (response) {
                        this.me = response;
                        if (this.me.id === 1 && this.me.email === 'cheezyspaghetti@gmail.com') {
                            this.listenForFeedback();
                        }
                    }.bind(this)
                });
            },

            /**
             * Listen for feedback from my apps, for items to insert into my lists app
             */
            listenForFeedback: function () {
                var that = this;
                var pusher = new Pusher('0559aebf9ae96524872b');

                var myChannel = pusher.subscribe('myChannel');

                myChannel.bind('budgetAppFeedbackSubmitted', function(data) {
                    //468 is the id of my budget app item in my lists app
                    that.insertItemFromFeedback(data, 468);
                });

                myChannel.bind('listsAppFeedbackSubmitted', function(data) {
                    //356 is the id of my lists app item in my lists app
                    that.insertItemFromFeedback(data, 356);
                });
            },

            /**
             *
             */
            sendPushNotification: function (response) {
                var data = {
                    title: response.title,
                    message: response.body
                };

                helpers.post({
                    url: '/api/pushNotifications',
                    data: data,
//                    array: 's',
//                    message: 'sendPushNotification created',
//                    clearFields: this.clearFields,
//                    redirectTo: this.redirectTo,
//                    callback: function () {
//                        this.showPopup = false;
//                    }.bind(this)
                });
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('categories-loaded', function (event) {
                    //Set the default category to the first one
                    setTimeout(function () {
                        that.newItem.category = that.categories[0];
                    }, 1000);
                });
            }


        },
        props: [
            'items',
            'zoomedItem'
        ],
        ready: function () {
            this.getUser();
            this.listen();
        }
    };
</script>