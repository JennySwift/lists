var Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);
var helpers = require('./Helpers');
var object = require('lodash/object');
require('sugar');
Date.setLocale('en-AU');

module.exports = {

    state: {
        me: {gravatar: ''},
        loading: false,
        categories: [],
        categoriesLoaded: false,
        items: [],
        itemsLoaded: false,
        itemsWithAlarm: [],
        itemsWithAlarmLoaded: false,
        zoomedItem: {},
        breadcrumb: [],
        recurringUnits: ['none', 'minute', 'hour', 'day', 'week', 'month', 'year'],
        filters: {
            minimumPriority: '',
            priority: '',
            category: {},
            title: '',
            urgency: '',
            urgencyOut: '',
            notBefore: true,
            notBeforeDate: ''
        }
    },

    /**
     *
     */
    showLoading: function () {
        this.state.loading = true;
    },

    /**
     *
     */
    hideLoading: function () {
        this.state.loading = false;
    },

    /**
     *
     */
    getCategories: function () {
        helpers.get({
            url: '/api/categories',
            storeProperty: 'categories',
            loadedProperty: 'categoriesLoaded'
        });
    },

    /**
     *
     */
    getItems: function (expandOrZoom, item) {
        console.log('getting items');
        var url;
        if (item) {
            url = '/api/items/' + item.id;
        }
        else {
            var id = helpers.getIdFromUrl(this);
            url = id ? '/api/items/' + id : '/api/items';
        }

        helpers.get({
            url: url,
            storeProperty: 'items',
            loadedProperty: 'itemsLoaded',
            callback: function (response) {
                this.getItemsSuccess(response, expandOrZoom, item);
            }.bind(this)
        });
    },

    /**
     *
     * @param response
     * @param expandOrZoom
     * @param item
     */
    getItemsSuccess: function (response, expandOrZoom, item) {
        if (expandOrZoom === 'zoom') {
            if (response.children) {
                this.state.zoomedItem = response;
                this.state.items = response.children;
                this.state.breadcrumb = response.breadcrumb;
            }
            else {
                //home page
                this.state.zoomedItem = false;
                this.state.items = response;
                this.state.breadcrumb = [];
            }

        }
        else if (expandOrZoom === 'expand') {
            item.children = response.children;
        }
    },

    /**
     * Add an item to an array
     * @param item
     * @param path
     */
    add: function (item, path) {
        object.get(this.state, path).push(item);
    },

    /**
     * Update an item that is in an array
     * @param item
     * @param path
     */
    update: function (item, path) {
        var index = helpers.findIndexById(object.get(this.state, path), item.id);

        object.get(this.state, path).$set(index, item);
    },

    /**
     * Set a property (can be nested)
     * @param data
     * @param path
     */
    set: function (data, path) {
        object.set(this.state, path, data);
    },

    /**
     * Toggle a property (can be nested)
     * @param path
     */
    toggle: function (path) {
        object.set(this.state, path, !object.get(this.state, path));
    },

    /**
     * Delete an item from an array
     * To delete a nested property of store.state, for example a class in store.state.classes.data:
     * store.delete(itemToDelete, 'student.classes.data');
     * @param itemToDelete
     * @param path
     */
    delete: function (itemToDelete, path) {
        object.set(this.state, path, helpers.deleteById(object.get(this.state, path), itemToDelete.id));
    }
};