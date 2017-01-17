var Vue = require('vue');
var VueRouter = require('vue-router');
Vue.use(VueRouter);
global.router = new VueRouter({hashbang: false});

router.map({
    // '/': {
    //     component: require('./components/ItemsPageComponent.vue'),
    //     subRoutes: {
    //         //default for if no id is specified
    //         '/': {
    //             component: require('./components/ItemComponent.vue')
    //         },
    //         '/:id': {
    //             component: require('./components/ItemComponent.vue')
    //         }
    //     }
    // },
    '/welcome': {
        name: 'welcome',
        component: require('./components/WelcomePageComponent.vue'),
    },
    '/items': {
        component: require('./components/ItemsPageComponent.vue'),
        subRoutes: {
            //default for if no id is specified
            '/': {
                component: require('./components/ItemComponent.vue')
            },
            '/:id': {
                component: require('./components/ItemComponent.vue')
            }
        }
    },
    '/categories': {
        component: require('./components/CategoriesComponent.vue')
    },
    '/trash': {
        component: require('./components/TrashComponent.vue')
    },
    '/feedback': {
        component: require('./components/FeedbackPageComponent.vue')
    },
    '/help': {
        component: require('./components/HelpPageComponent.vue')
    }
});

router.redirect({
    '/': '/welcome'
});

// router.redirect({
//     '/': '/timers'
// });

var App = Vue.component('app', require('./components/AppComponent'));

router.start(App, 'body');

