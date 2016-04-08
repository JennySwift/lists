var ItemPopup = Vue.component('item-popup', {
    template: '#item-popup-template',
    data: function () {
        return {
            selectedItem: {},
            selectedItemInItemsArray: {},
            showPopup: false
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
            $.event.trigger('show-loading');

            var data = ItemsRepository.setData(item);

            this.$http.put('/api/items/' + item.id, data, function (response) {
                    this.updateItemSuccess(response);
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
        },

        /**
         *
         * @param response
         */
        updateItemSuccess: function (response) {
            //var index = _.indexOf(this.items, _.findWhere(this.items, {id: response.id}));
            //ItemsRepository.updateProperties(this.items[index], response);
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
            $.event.trigger('provide-feedback', ['Item updated', 'success']);
            $.event.trigger('hide-loading');
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
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-item-popup', function (event, item) {
                that.selectedItem = HelpersRepository.clone(item);
                that.selectedItem.oldParentId = item.parent_id;
                that.selectedItem.oldAlarm = item.alarm;

                that.selectedItemInItemsArray = item;

                that.showPopup = true;
            });
        }
    },
    props: [
        'categories',
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
});
