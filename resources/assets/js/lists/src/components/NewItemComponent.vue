<template>

    <div class="popup new-item-popup">
        <div class="view new-item-popup-view">
            <f7-page>
                <f7-navbar>
                    <f7-nav-title>New Item</f7-nav-title>
                    <f7-nav-right>
                        <f7-link popup-close>Close</f7-link>
                    </f7-nav-right>
                </f7-navbar>

                <item-fields
                    :item="shared.newItem"
                    action="insert"
                    :enter="insertItem"
                    :focus="showFields"
                    store-path="newItem"
                >
                </item-fields>

                <f7-toolbar>
                    <buttons
                        :save="insertItem"
                    >
                    </buttons>
                    <!--<f7-button-->
                    <!--v-on:click="insertItem(13)"-->
                    <!--:disabled="!shared.newItem.title || !shared.newItem.category || !shared.newItem.priority"-->
                    <!--&gt;-->
                    <!--Save-->
                    <!--</f7-button>-->
                </f7-toolbar>
            </f7-page>
        </div>

    </div>

</template>

<script>
    import DateTimeRepository from '../repositories/DateTimeRepository'
    import ItemsRepository from '../repositories/ItemsRepository'
    import store from '../repositories/Store'
    import helpers from '../repositories/Helpers'
    var $ = require('jquery');

    export default {
        template: '#new-item-template',
        data: function () {
            return {
                me: {},
                shared: store.state,
                addingNewItems: false
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
            showFields: function () {
                store.set(true, 'showNewItemFields');
            },

            /**
             * For determining whether or not to insert the item with the JS at the current location
             */
            insertItemAtCurrentLocation: function (data) {
                var parentId = data.parent_id === 'none' ? false : data.parent_id;

                if (!this.shared.zoomedItem && !parentId) {
                    //At home level with no parent specified
                    return true;
                }
                else if (this.shared.zoomedItem && this.shared.zoomedItem.id === parentId) {
                    return true;
                }
                return false;
            },

            /**
            *
            */
            insertItem: function () {
                var data = ItemsRepository.setData(this.shared.newItem, this.shared.zoomedItem);

                var array = 'items';
                var message;
                //Only add item to the array with JS if the new item is added to the current location
                if (!this.insertItemAtCurrentLocation(data)) {
                    array = null;
                    message = 'Item created in another location';
                }
                else {
                    message = 'Item created in current location';
                }

                helpers.post({
                    url: '/api/items',
                    data: data,
                    array: array,
                    message: message,
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        if (response.alarm) {
                            $.event.trigger('alarm-created', [response]);
                        }
                        if (response.urgency == 1) {
                            $.event.trigger('urgent-item-created', [response]);
                        }
                        $.event.trigger('set-tab', [1]);
                        setTimeout(function () {
                            $("#new-item-title").focus();
                        }, 200);
                    }.bind(this)
                });
            },

            /**
             * For inserting an item into my lists app.
             * The item has been received from one of my apps, using Pusher.
             * To allow users of my apps to provide feedback
             * @param feedback
             * @param itemId The id of the item for the app in my lists app
             */
            insertItemFromFeedback: function (feedback, itemId) {
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

            clearFields: function () {
                store.clearNewItemFields();
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
                            //Commenting out Pusher code to see if it speeds up the app.
//                            this.listenForFeedback();
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

//            optionChosen: function (option, inputId) {
//                if (inputId === 'new-item-parent') {
//                    store.set(option.id, 'newItem.parent_id');
//                }
//                else if (inputId === 'new-item-category') {
//                    store.set(option, 'newItem.category');
//                }
//                else if (inputId === 'new-item-recurring-unit') {
//                    store.set(option, 'newItem.recurringUnit');
//                }
//            },

//            dateChosen: function (date, inputId) {
//                if (inputId === 'new-item-not-before') {
//                    store.set(date, 'newItem.notBefore');
//                }
//            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('categories-loaded', function (event) {
                    //Set the default category to the first one
                    setTimeout(function () {
                        store.set(that.categories[0], 'newItem.category');
                    }, 1000);
                });
            }


        },
        created: function () {
//            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
//            this.$bus.$on('date-chosen', this.dateChosen);
        },
        mounted: function () {
            this.getUser();
            this.listen();
            var that = this;
            setTimeout(function () {
//                console.log("zoomedItem.id: " + that.shared.zoomedItem.id);
//                that.newItem.parent_id = that.shared.zoomedItem.id;
            }, 3000);

        }
    }
</script>