var NewItem = Vue.component('new-item', {
    template: '#new-item-template',
    data: function () {
        return {
            showNewItemFields: false,
            addingNewItems: false,
            newItem: {
                title: '',
                body: '',
                favourite: false,
                pinned: false
            },
            categories: [],
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getCategories: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/categories', function (response) {
                    this.categories = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        insertItem: function () {
            var data = ItemsRepository.setData(this.newItem, this.zoomedItem);

            $.event.trigger('show-loading');
            this.$http.post('/api/items', data, function (response) {
                    this.insertItemSuccess(response);
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         * @param response
         */
        insertItemSuccess: function (response) {
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
            $.event.trigger('hide-loading');
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
         */
        insertItemFromFeedback: function (feedback) {
            data = {
                title: feedback.title,
                body: feedback.body,
                priority: 1,
                //The id of my budget app item in my lists app
                parent_id: 468,
                //The id of my coding category in my lists app
                category_id: 1,
                favourite: 0,
                pinned: 0
                //'urgency' => 1,
                //'alarm' => $alarm
            };

            $.event.trigger('show-loading');
            this.$http.post('/api/items', data, function (response) {
                    this.sendPushNotification(response);
                    this.insertItemSuccess(response);
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
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
         *
         */
        getUser: function () {
            this.$http.get('/api/users/', function (response) {
                    this.me = response;
                    console.log(this.me);
                    if (this.me.id === 1 && this.me.email === 'cheezyspaghetti@gmail.com') {
                        this.listenForFeedback();
                    }
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
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
                that.insertItemFromFeedback(data);
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

            this.$http.post('/api/pushNotifications', data, function (response) {

                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },


    },
    props: [
        'items'
    ],
    ready: function () {
        this.getUser();
        this.getCategories();
    }
});
