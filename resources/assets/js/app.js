require('./config');

import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

global.$ = require('jquery');
global.jQuery = require('jquery');

// import jQuery from 'jquery'
global._ = require('underscore');
import store from './lists/src/repositories/Store'

// window.Event = new Vue();

require('./components.js');

store.getCategories();
store.getFavouriteItems();
setTimeout(function () {
    store.getItems('zoom');
}, 500);


import routes from './routes'

const router = new VueRouter({
    routes // short for `routes: routes`
})

const bus = new Vue()
Vue.prototype.$bus = bus

const app = new Vue({
    router
}).$mount('#app')



