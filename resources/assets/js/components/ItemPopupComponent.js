var ItemPopup = Vue.component('item-popup', {
    template: '#item-popup-template',
    data: function () {
        return {

        };
    },
    filters: {
        /**
         *
         * @param dateAndTime
         * @returns {*|string}
         */
        userFriendlyDateTimeFilter: function (dateAndTime) {
            return ItemsRepository.userFriendlyDateTimeFilter(dateAndTime);
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
            this.selectedItem.notBefore = response.notBefore;
            this.selectedItem.recurringUnit = response.recurringUnit;
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
            this.showItemPopup = false;
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

        }
    },
    props: [
        //data to be received from parent
        'showLoading',
        'showItemPopup',
        'selectedItem',
        'categories',
        'closePopup',
        'items',
        'getItems',
        'deleteItem',
        'recurringUnits'
    ],
    ready: function () {

    }
});
