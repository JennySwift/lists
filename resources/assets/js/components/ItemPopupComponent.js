var ItemPopup = Vue.component('item-popup', {
    template: '#item-popup-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateItem: function (item) {
            this.showLoading = true;

            var data = ItemsRepository.setData(item);

            this.$http.put('/api/items/' + item.id, data, function (response) {
                    this.updateItemSuccess(response);
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         * @param response
         */
        updateItemSuccess: function (response) {
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
            //this.$broadcast('provide-feedback', 'Item updated', 'success');
            this.showLoading = false;
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
         *
         * @param item
         */
        //deleteItem: function (item) {
        //    $.event.trigger('delete-item', [item]);
        //},

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
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
        'deleteItem'
    ],
    ready: function () {

    }
});
