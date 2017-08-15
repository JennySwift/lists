import helpers from './Helpers'
import ItemsRepository from './ItemsRepository'
var object = require('lodash/object');
require('sugar');
Date.setLocale('en-AU');
import Vue from 'vue'


export default {

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
            body: '',
            urgency: '',
            urgencyOut: '',
            notBefore: true,
            notBeforeDate: '',
            showTrashed: false
        },
        //For editing fields in item popup before the item is saved
        selectedItemClone: {
            oldParentId: null,
            favourite: '',
            deletedAt: ''
        },
        selectedItem: {
            oldParentId: null,
            favourite: '',
            deletedAt: ''
        },
        newItem: {
            title: '',
            body: '',
            favourite: false,
            pinned: false,
            category: {},
            priority: 1,
            parent_id: '',
            notBefore: '',
            recurringUnit: '',
            recurringFrequency: ''
        },
        showFavourites: false,
        showPopup: false,
        showNewItemFields: false,
        favouriteItems: [],
        favourteItemsLoaded: false
    },

    /**
     *
     */
    clearNewItemFields () {
        this.set('', 'newItem.title');
        this.set('', 'newItem.body');
        this.set(false, 'newItem.favourite');
        this.set(this.state.zoomedItem.id, 'newItem.parent_id');
        this.set(this.state.zoomedItem, 'newItem.parent');
        this.set('', 'newItem.notBefore');
        this.set('none', 'newItem.recurringUnit');
        this.set('', 'newItem.recurringFrequency');
    },

    getCategories() {
        helpers.get({
            url: '/api/categories',
            storeProperty: 'categories',
            loadedProperty: 'categoriesLoaded'
        });
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
            this.setNewItemParent();
        }
        else {
            this.goHome(response);
        }
    },

    setNewItemParent () {
        this.set(this.state.zoomedItem, 'newItem.parent');
        this.set(this.state.zoomedItem.id, 'newItem.parent_id');
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
     * Update an item that is in an array that is in the store
     * @param item
     * @param path
     */
    update: function (item, path) {
        var index = helpers.findIndexById(object.get(this.state, path), item.id);

        Vue.set(object.get(this.state, path), index, item);
    },

    /**
     * Set a property that is in the store (can be nested)
     * @param data
     * @param path
     */
    set: function (data, path) {
        object.set(this.state, path, data);
    },

    /**
     * Toggle a property that is in the store (can be nested)
     * @param path
     */
    toggle: function (path) {
        object.set(this.state, path, !object.get(this.state, path));
    },

    /**
     * Delete an item from an array in the store
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
}