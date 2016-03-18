
var App = Vue.component('app', {

});

var router = new VueRouter({
    hashbang: false
});

router.map({
    '/': {
        component: Items,
        subRoutes: {
            //default for if no id is specified
            '/': {
                component: Item
            },
            '/:id': {
                component: Item
            }
        }
    },
    '/items': {
        component: Items,
        subRoutes: {
            //default for if no id is specified
            '/': {
                component: Item
            },
            '/:id': {
                component: Item
            }
        }
    },
    '/categories': {
        component: Categories
    },
    '/trash': {
        component: Trash
    }
});

router.start(App, 'body');

//new Vue({
//    el: 'body',
//    events: {
//
//    }
//});

