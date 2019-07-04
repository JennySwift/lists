require('./bootstrap');

import Vue from 'vue'

import Framework7 from 'framework7/dist/framework7.esm.bundle.js';
import Framework7Vue from 'framework7-vue/dist/framework7-vue.esm.bundle.js';
// import Framework7Styles from 'framework7/dist/css/framework7.css';

import 'framework7-icons';
Vue.use(Framework7Vue, Framework7)

import store from './lists/src/repositories/Store'
import helpers from './lists/src/repositories/Helpers'
import filters from './lists/src/repositories/Filters'
import routes from './routes'

window.Event = new Vue();

require('./components');
require('./config.js');

global.store = store;
global.helpers = helpers;
global.filters = filters;


const bus = new Vue();
Vue.prototype.$bus = bus;

const app = new Vue({
    el: '#app',
    mounted: function () {
        store.getCategories();
        store.getFavouriteItems();
        store.createPopups();
        store.getCurrentUser();

        // store.setNoteHeight();

        // this.$f7.views.create('.item-popup-view');
        // this.$f7.views.create('.filter-view');
        // this.$f7.views.create('.new-item-popup-view');
        // setTimeout(function () {
        //     store.getItems();
        // }, 500);
    },
    framework7: {
        root: '#app',
        id: 'lists-app',
        name: 'Lists',
        theme: 'ios',
        routes: routes,
        view: {
            pushState: true,
            animate: false
        },
        panel: {
            swipe: 'right'
        }
    }
});






