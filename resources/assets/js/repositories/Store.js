var Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);
var helpers = require('./Helpers');
var ItemsRepository = require('./ItemsRepository');
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
        },
        showFavourites: false,
        favouriteItems: [],
        favourteItemsLoaded: false
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
    getFavouriteItems: function () {
        helpers.get({
            url: '/api/items?favourites=true',
            storeProperty: 'favouriteItems',
            loadedProperty: 'favouriteItemsLoaded'
        });
    },

    getItems: function () {
        helpers.get({
            url: ItemsRepository.getUrl(),
            storeProperty: 'items',
            loadedProperty: 'itemsLoaded',
            callback: function (response) {
                this.zoom(response);
            }.bind(this)
        });
    },

    getItemWithChildren: function (item) {
        helpers.get({
            url: '/api/items/' + item.id,
            callback: function (response) {
                // var parent = ItemsRepository.findParent(this.state.items, item);
                // console.log(parent);
                item.children = response.children;
            }.bind(this)
        });
    },

    /**
     *
     * @param response
     */
    zoom: function (response) {
        if (response.children) {
            this.state.zoomedItem = response;
            this.state.items = response.children;
            this.state.breadcrumb = response.breadcrumb;
        }
        else {
            this.goHome(response);
        }
    },

    /**
     *
     * @param response
     */
    goHome: function (response) {
        this.state.zoomedItem = false;
        this.state.items = response;
        this.state.breadcrumb = [];
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
        // console.log('\n\n get: ' + JSON.stringify(object.get(this.state, path), null, 4) + '\n\n');
        // console.log('\n\n item to delete: ' + JSON.stringify(itemToDelete, null, 4) + '\n\n');
        object.set(this.state, path, helpers.deleteById(object.get(this.state, path), itemToDelete.id));
    }
};