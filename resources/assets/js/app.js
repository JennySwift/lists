
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./config');
window.Vue = require('vue');

var Vue = require('vue');
import VueRouter from 'vue-router'
Vue.use(VueRouter);

// global.router = new VueRouter({hashbang: false});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

global.$ = require('jquery');
global.jQuery = require('jquery');
global._ = require('underscore');
// global.store = require('./lists/src/repositories/Store');
import store from './lists/src/repositories/Store'

require('./config.js');
Date.setLocale('en-AU');

// window.Event = new Vue();

// //Shared components
// Vue.component('navbar', require('./components/shared/NavbarComponent.vue'));
// Vue.component('toolbar', require('./components/shared/ToolbarComponent.vue'));
// Vue.component('feedback', require('@jennyswift/feedback'));
// Vue.component('loading', require('./components/shared/LoadingComponent.vue'));
// Vue.component('popup', require('./components/shared/PopupComponent.vue'));
// Vue.component('autocomplete', require('@jennyswift/vue-autocomplete'));
//
// Vue.component('buttons', require('./components/shared/ButtonsComponent.vue'));
// Vue.component('input-group', require('./components/shared/InputGroupComponent.vue'));
// Vue.component('date-picker', require('./components/shared/DatepickerComponent.vue'));
//
// // //Components
// Vue.component('item-popup', require('./lists/src/components/ItemPopupComponent.vue'));
// Vue.component('breadcrumb', require('./components/BreadcrumbComponent.vue'));
// Vue.component('category-popup', require('./components/CategoryPopupComponent.vue'));
// Vue.component('alarms', require('./components/AlarmsComponent.vue'));
// Vue.component('urgent-items', require('./components/UrgentItemsComponent.vue'));
// Vue.component('favourite-items', require('./components/FavouriteItemsComponent.vue'));
// Vue.component('items-filter', require('./components/FilterComponent.vue'));
// Vue.component('new-item', require('./components/NewItemComponent.vue'));
// Vue.component('new-category', require('./components/NewCategoryComponent.vue'));
// Vue.component('item', require('./components/ItemComponent.vue'));
// Vue.component('category', require('./components/CategoryComponent.vue'));
// Vue.component('item-fields', require('./components/ItemFieldsComponent.vue'));
//
// Vue.component('app', require('./components/AppComponent.js'));

window.router = new VueRouter({
    routes: require('./routes')
});

require('sugar');
//Set Sugar to use Australian date formatting
Date.setLocale('en-AU');
console.log('\n\n store: ' + JSON.stringify(store, null, 4) + '\n\n');
store.getCategories();
// store.getFavouriteItems();
setTimeout(function () {
    // store.getItems('zoom');
}, 500);

// var app = new Vue({
//     router: router
// }).$mount('#app');



