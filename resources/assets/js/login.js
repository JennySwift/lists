// require('./bootstrap');

import Vue from 'vue'
import Framework7 from 'framework7/dist/framework7.esm.bundle.js';
import Framework7Vue from 'framework7-vue/dist/framework7-vue.esm.bundle.js';
Vue.use(Framework7Vue, Framework7)

const app = new Vue({
    el: '#app',
    framework7: {
        root: '#app',
        id: 'lists-app',
        name: 'Lists',
        theme: 'ios',
        // routes: routes,
    }
});


