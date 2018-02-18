import DateTimeRepository from './DateTimeRepository'
import helpers from './Helpers'
import store from './Store'

export default {

    /**
     *
     * @returns {boolean}
     */
    shouldFilterBeShownOnPageLoad: function () {
        var width = $(window).width();
        if (width > 1040) {
            return true;
        }
        return false;
    },

    /**
     *
     * @param item
     * @param response
     */
    // updateProperties: function (item, response) {
    //     item.title = response.title;
    //     item.body = response.body;
    //     item.parent_id = response.parent_id;
    //     item.category_id = response.category_id;
    //     item.category = response.category;
    //     item.favourite = response.favourite;
    //     item.alarm = response.alarm;
    //     item.notBefore = response.notBefore;
    //     item.recurringUnit = response.recurringUnit;
    //     item.recurringFrequency = response.recurringFrequency;
    //     item.priority = response.priority;
    //     item.urgency = response.urgency;
    //     item.parent = response.parent;
    // },

    /**
     *
     * @param item
     * @returns {{title: *, body: (*|string|Array|HTMLElement), priority: *, favourite: (*|boolean), pinned: (*|boolean), category_id: *, parent_id: (*|null)}}
     */
    setData: function (item, zoomedItem) {
        var data = {
            title: item.title,
            body: item.body,
            priority: item.priority,
            urgency: item.urgency,
            favourite: helpers.convertBooleanToInteger(item.favourite),
            pinned: item.pinned,
            category_id: item.category.id,
            alarm: false,
            not_before: DateTimeRepository.convertToDateTime(item.notBefore),
            recurring_unit: item.recurringUnit,
            recurring_frequency: item.recurringFrequency
        };

        //So the urgency can be removed
        if (item.urgency === '') {
            data.urgency = false;
        }

        if (item.alarm && item.alarm !== '') {
            //This check is here because if item.alarm was false,
            //I think PHP was getting value 0, making the item have an alarm
            //at '0000-00-00 00:00:00.'
            data.alarm = filters.formatAlarm(item.alarm)
        }

        if (!data.pinned) {
            //Make sure it's a boolean for the PHP
            data.pinned = 0;
        }

        data.parent_id = this.setParentId(item);

        return data;
    },

    /**
     *
     * @param item
     * @returns {*}
     */
    setParentId: function (item) {
        // var parentId = null;

        // if (store.state.zoomedItem) {
        //     parentId = store.state.zoomedItem.id;
        // }
        //
        // if (item.parent) {
        //     parentId = item.parent.id;
        // }
        // else if (item.parent_id) {
        //     parentId = item.parent_id;
        // }

        var parentId = item.parent_id;

        if (item.parent_id === '') {
            parentId = 'none';
        }

        return parentId;
    },

    /**
    *
    */
    deleteItem: function (item, that) {
        if (item.recurringUnit) {
            //It's a recurring item, so we're updating the not-before time of the item, rather than actually deleting the item
            var data = {
                updatingNextTimeForRecurringItem: true
            };

            helpers.put({
                url: '/api/items/' + item.id,
                data: data,
                property: 'items',
                message: 'Item has been rescheduled, not deleted',
                redirectTo: this.redirectTo,
                callback: function (response) {
                    item.notBefore = response.notBefore;
                    helpers.hidePopup();
                }
            });
        }

        else {
            var noConfirm = !item.has_children;

            helpers.delete({
                url: '/api/items/' + item.id,
                // array: 'items',
                // itemToDelete: this.item,
                message: 'Item deleted',
                redirectTo: this.redirectTo,
                noConfirm: noConfirm,
                confirmTitle: 'Are you sure?.',
                confirmText: 'All descendants of this item will be deleted, too.',
                beforeDelete: function () {
                    item.deleting = true;
                    console.log(item);
                }.bind(this),
                callback: function () {
                    this.deleteJsItem(item);
                    helpers.hidePopup();
                }.bind(this),
                onFail: function () {
                    item.deleting = false;
                }
            });
        }
    },

    /**
     * We don't delete the item from the store items array,
     * because we need it there if the user wants to show the completed items
     * @param item
     */
    deleteJsItem: function (item) {
        item.deletedAt = true;
        return item;
    },

    /**
     * This works. It seems kind of complicated, but I tried other ways
     * and they both had problems.
     *
     * Finding parent by path broke down when zoomed on an item, because path was not the full path.
     *
     * Finding parent with _.flatten broke down when not zoomed on an item
     * and items were expanded several levels.
     *
     * @param array
     * @param item
     * @param oldParentId
     * @returns {*}
     */
    findParent: function (array, item, oldParentId, resetParent) {
        var parent;
        var that = this;
        var parentId = item.parent_id;

        if (resetParent) {
            //It's the first time the method is being called, rather than it being called from within itself.
            //So make sure the parent isn't set from a previous time the method was called.
            that.parent = null;
        }

        if (oldParentId) {
            parentId = oldParentId;
        }

        if (!parentId || oldParentId === null) {
            return false;
        }
        $(array).each(function () {
            if (this.id === parentId) {
                parent = this;
                that.parent = this;
                return false;
            }
            if (this.children) {
                return that.findParent(this.children, item, oldParentId);
            }
        });

        return that.parent;
    },

    // findItem: function (array, item) {
    //     var that = this;
    //     var parentId = item.parent_id;
    //
    //     $(array).each(function () {
    //         if (this.id === parentId) {
    //             parent = this;
    //             that.parent = this;
    //             return false;
    //         }
    //         if (this.children) {
    //             return that.findParent(this.children, item, oldParentId);
    //         }
    //     });
    //
    //     console.log(that.parent);
    //     return that.parent;
    // },

    /**
     *
     * @param item
     * @param parentIds
     * @returns {*}
     */
    getAncestorIds: function (item, ancestorIds) {
        var parent = this.findParent(store.state.items, item);
        if (parent) {
            ancestorIds.push(parent.id);
            return this.getAncestorIds(parent, ancestorIds);
        }

        return ancestorIds.reverse();
    },

    createPathAsString: function (array) {
        var string = 'items';

        for (var i = 0; i < array.length; i++) {
            string+= '[' + array[i] + '].children';

        }
        // return 'items[0].children[0].children[1].children[3].children';

        return string;
    },

    getPathAsString: function (item) {
        var ancestorIds = ItemsRepository.getAncestorIds(item, []);

        console.log('ancestor ids: ' + ancestorIds);

        var path = ItemsRepository.getPath(null, ancestorIds, [], 0);

        return ItemsRepository.createPathAsString(path);
    },

    getPath: function (item, ancestorIds, path, indexInPath) {
        console.log('indexINPath: ' + indexInPath);
        console.log('next: ' + ancestorIds[indexInPath]);
        if (!item) {
            // console.log('\n\n item: ' + JSON.stringify(item, null, 4) + '\n\n');
            item = helpers.findById(store.state.items, ancestorIds[0]);
            path.push(helpers.findIndexById(store.state.items, ancestorIds[0]));
            // indexInPath++;
            // item = helpers.findById(item.children, ancestorIds[indexInPath]);
        }

        indexInPath++;

        if (indexInPath < ancestorIds.length) {
            path.push(helpers.findIndexById(item.children, ancestorIds[indexInPath]));
        }

        item = helpers.findById(item.children, ancestorIds[indexInPath]);

        if (indexInPath < ancestorIds.length) {
            return this.getPath(item, ancestorIds, path, indexInPath);
        }

        return path;
    },

    /**
     *
     */
    getUrl: function (pageNumber) {
        var url;
        var id = helpers.getIdFromUrl();
        url = id ? '/api/items/' + id : '/api/items';

        if (!pageNumber) {
            pageNumber = 1;
        }

        url += '?page=' + pageNumber;

        if (store.state.filters.priority) {
            url += '&priority=' + store.state.filters.priority;
        }

        return url;
    }
}