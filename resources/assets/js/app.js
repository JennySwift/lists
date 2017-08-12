require('./config');

import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

global.$ = require('jquery');
global.jQuery = require('jquery');
global._ = require('underscore');
import store from './lists/src/repositories/Store'

// window.Event = new Vue();

require('./components.js');

store.getCategories();
store.getFavouriteItems();
setTimeout(function () {
    store.getItems('zoom');
}, 500);

window.router = new VueRouter({
    routes: require('./routes')
});

var app = new Vue({
    router: router,
    el: '#app',
});



