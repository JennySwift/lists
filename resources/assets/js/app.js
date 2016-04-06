
var App = Vue.component('app', {
    ready: function () {
        //Set Sugar to use Australian date formatting
        Date.setLocale('en-AU');
    }
});

var router = new VueRouter({
    hashbang: false
});

router.map({
    '/': {
        component: ItemsPage,
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
        component: ItemsPage,
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
    },
    '/feedback': {
        component: FeedbackPage
    },
    '/help': {
        component: HelpPage
    }
});

router.start(App, 'body');

//new Vue({
//    el: 'body',
//    events: {
//
//    }
//});

