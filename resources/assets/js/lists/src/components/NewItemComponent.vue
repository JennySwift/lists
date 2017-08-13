<template>
    <div v-show="shared.showNewItemFields" id="new-item">

        <item-fields
            :item="newItem"
            action="insert"
            :enter="insertItem"
            :focus="showFields"
        >
        </item-fields>

        <div class="form-group">
            <!--<button-->
                <!--v-on:click="showNewItemFields = false"-->
                <!--class="btn btn-default"-->
            <!--&gt;-->
                <!--Cancel-->
            <!--</button>-->
            <button
                v-on:click="insertItem(13)"
                :disabled="!newItem.title || !newItem.category || !newItem.priority"
                class="btn btn-success"
            >
                Add
            </button>
        </div>

    </div>

</template>

<script>
    import DateTimeRepository from '../repositories/DateTimeRepository'
    import ItemsRepository from '../repositories/ItemsRepository'
    import store from '../repositories/Store'
    import helpers from '../repositories/Helpers'
//    import eventHub from '../EventHub'
    var $ = require('jquery');

    export default {
        template: '#new-item-template',
        data: function () {
            return {
                me: {},
                shared: store.state,
                addingNewItems: false,
                newItem: {
                    title: '',
                    body: '',
                    favourite: false,
                    pinned: false,
                    category: {},
                    priority: 1,
                    parent_id: null
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
            showFields: function () {
                store.set(true, 'showNewItemFields');
            },

            /**
             * For determining whether or not to insert the item with the JS at the current location
             */
            insertItemAtCurrentLocation: function (data) {
                if (!this.shared.zoomedItem && !data.parent_id) {
                    //At home level with no parent specified
                    return true;
                }
                else if (this.shared.zoomedItem && this.shared.zoomedItem.id === data.parent_id) {
                    return true;
                }
                return false;
            },

            /**
            *
            */
            insertItem: function () {
                var data = ItemsRepository.setData(this.newItem, this.zoomedItem);

                var array = 'items';
                //Only add item to the array with JS if the new item is added to the current location
                if (!this.insertItemAtCurrentLocation(data)) {
                    array = null;
                }

                helpers.post({
                    url: '/api/items',
                    data: data,
                    array: array,
                    message: 'Item created',
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

            clearFields: function () {
                this.newItem.parent_id = null;
                this.newItem.title = '';
                this.newItem.body = '';
                this.newItem.parent = false;
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

            optionChosen: function (option, inputId) {
                console.log("Detected");
                if (inputId === 'new-item-parent') {
                    this.newItem.parent_id = option.id;
                }
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
        created: function () {
            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
        },
        mounted: function () {
            this.getUser();
            this.listen();
        }
    }
</script>