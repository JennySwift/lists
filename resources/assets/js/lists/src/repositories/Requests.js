import Vue from 'vue'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'
Vue.use(VueResource);
Vue.use(VueRouter);
const swal = require('sweetalert2');
// var store = require('./Store');
import store from './Store'
import helpers from './Helpers'




export default {
    /**
     * storeProperty is the store property to set once the items are loaded.
     * loadedProperty is the store property to set once the items are loaded, to indicate that the items are loaded.
     * todo: allow for sending data: add {params:data} as second argument
     */
    get: function (options) {
        store.showLoading();
        Vue.http.get(options.url).then(function (response) {
            if (options.storeProperty) {
                if (options.updatingArray) {
                    //Update the array the item is in
                    store.update(response.data, options.storeProperty);
                }
                else {
                    store.set(response.data, options.storeProperty);
                }
            }

            if (options.callback) {
                options.callback(response.data);
            }

            if (options.loadedProperty) {
                store.set(true, options.loadedProperty);
            }

            store.hideLoading();
        }, function (response) {
            helpers.handleResponseError(response.data, response.status);
        });
    },

    /**
     * options:
     * array: store array to add to
     */
    post: function (options) {
        store.showLoading();
        Vue.http.post(options.url, options.data).then(function (response) {
            if (options.callback) {
                options.callback(response.data);
            }

            store.hideLoading();

            if (options.message) {
                $.event.trigger('provide-feedback', [options.message, 'success']);
            }

            if (options.array) {
                store.add(response.data, options.array);
            }

            if (options.clearFields) {
                options.clearFields();
            }

            if (options.redirectTo) {
                router.push(options.redirectTo);
            }
        }, function (response) {
            helpers.handleResponseError(response.data, response.status);
        });
    },

    /**
     *
     */
    put: function (options) {
        store.showLoading();
        var that = this;
        Vue.http.put(options.url, options.data).then(function (response) {
            if (options.callback) {
                options.callback(response.data);
            }

            store.hideLoading();

            if (options.message) {
                $.event.trigger('provide-feedback', [options.message, 'success']);
            }

            if (options.property) {
                store.update(response.data, options.property);
            }

            if (options.redirectTo) {
                that.getRouter().push(options.redirectTo);
            }

        }, function (response) {
            helpers.handleResponseError(response.data, response.status);
        });
    },

    /**
     *
     */
    delete: function (options) {
        swal({
            title: 'Are you sure?',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-default',
            buttonsStyling: false,
            reverseButtons: true,
            showCloseButton: true
        }).then(function() {
            store.showLoading();
            Vue.http.delete(options.url).then(function (response) {
                if (options.callback) {
                    options.callback(response);
                }

                store.hideLoading();

                if (options.message) {
                    $.event.trigger('provide-feedback', [options.message, 'success']);
                }

                if (options.array) {
                    store.delete(options.itemToDelete, options.array);
                }

                if (options.redirectTo) {
                    router.push(options.redirectTo);
                }
            }, function (response) {
                helpers.handleResponseError(response.data, response.status);
            });
        }, function(dismiss) {

        });
    },
}