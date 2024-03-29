import helpers from './Helpers'
import ItemsRepository from './ItemsRepository'
var object = require('lodash/object');
require('sugar');
Date.setLocale('en-AU');
import Vue from 'vue'


export default {

    state: {
        me: {gravatar: ''},
        // loading: false,
        categories: [],
        category: {},
        categoryClone: {},
        categoriesLoaded: false,
        selectorOptions: {
            data: [],
            // propToDisplay: 'name'
        },

        items: [],
        pagination: {},
        itemsLoaded: false,
        itemsWithAlarm: [],
        itemsWithAlarmLoaded: false,
        zoomedItem: {},
        breadcrumb: [],
        recurringUnits: ['none', 'minute', 'hour', 'day', 'week', 'month', 'year'],
        filters: {
            minimumPriority: '',
            priority: '',
            category: {

            },
            max: 100,
            title: '',
            body: '',
            urgency: '',
            urgencyOut: '',
            includeFutureItems: false,
            notBeforeDate: '',
            showTrashed: false,
            favouriteItem: {}
        },
        //For editing fields in item popup before the item is saved
        selectedItemClone: {
            oldParentId: null,
            favourite: '',
            deletedAt: '',
            category: {
                data: {}
            },
        },
        selectedItem: {
            oldParentId: null,
            favourite: '',
            deletedAt: '',
            category: {
                data: {}
            },
        },
        newItem: {
            title: '',
            body: '',
            favourite: false,
            pinned: false,
            category: {
                data: {}
            },
            priority: 1,
            parent_id: '',
            notBefore: '',
            recurringUnit: '',
            recurringFrequency: ''
        },
        showFavourites: false,
        showPopup: false,
        showFilter: true,
        showNewItemFields: false,
        favouriteItems: [],
        favourteItemsLoaded: false,
        trashedItems: []
    },

    /**
    *
    */
    updateLastRoute: function (route) {
        var data = {
            last_route: route
        };

        helpers.put({
            url: '/api/users/',
            data: data
        });
    },

    /**
    * @param user
    */
    getCurrentUser: function () {
        helpers.get({
            url: '/api/users/',
            storeProperty: 'me',
            callback: function (response) {
                helpers.goToRoute(response.last_route);
            }.bind(this)
        });
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

    createPopups: function () {
        app.f7.popup.create({
            el: '.item-popup'
        });
        app.f7.popup.create({
            el: '.new-item-popup'
        });
        app.f7.popup.create({
            el: '.filter-popup'
        });
        app.f7.popup.create({
            el: '.selector-popup'
        });
    },

    setSelectorOptions: function (options) {
        store.set(options, 'selectorOptions.data');
    },

    openPopup: function (popup) {
        app.f7.popup.get(popup).open();
        store.setNoteHeight();
    },

    closePopup: function (popup) {
        app.f7.popup.get(popup).close();
        //Doing it this way because the popup wouldn't close if it was in the main view
        if ($(popup).hasClass('modal-in')) {
            $(popup).addClass('modal-out').removeClass('modal-in');
        }
    },

    /**
     * Set the height of the textarea field for the note in the item popup
     */
    setNoteHeight: function () {
        var popupHeight = $('.item-popup-page').height();
        $('.item-popup-page .tab2 .item-content textarea').height(popupHeight - 132);
    },

    openItemPopup: function (item) {
        item.favourite = helpers.convertIntegerToBoolean(item.favourite);
        store.set(helpers.clone(item), 'selectedItemClone');
        store.set(item.parent_id, 'selectedItemClone.oldParentId');
        store.set(item, 'selectedItem');

        store.openPopup('.item-popup');
    },

    /**
     *
     */
    getTrashedItems: function () {
        helpers.get({
            url: '/api/items?trashed=true',
            storeProperty: 'trashedItems',
        });
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
        if (app.f7) {
            app.f7.preloader.show();
        }
    },

    /**
     *
     */
    hideLoading: function () {
        if (app.f7) {
            app.f7.preloader.hide();
        }
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

    getItems: function (pageNumber) {
        helpers.get({
            url: ItemsRepository.getUrl(pageNumber),
            storeProperty: 'items',
            loadedProperty: 'itemsLoaded',
            callback: function (response) {
                this.zoom(response.data);
                this.set(response.pagination, 'pagination');
            }.bind(this)
        });
    },

    goToPreviousPage: function () {
        this.getItems(this.state.pagination.current_page - 1);
    },

    goToNextPage: function () {
        this.getItems(this.state.pagination.current_page + 1);
    },

    getItemWithChildren: function (item, pageNumber) {
        var url = '/api/items/' + item.id;
        if (pageNumber) {
            url +='?page=' + pageNumber;
        }
        helpers.get({
            url: url,
            callback: function (response) {
                // var parent = ItemsRepository.findParent(this.state.items, item);
                // console.log(parent);
                item.children = response.data.children;
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
            this.state.items = response.children.data;
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
        this.setNewItemParent();
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