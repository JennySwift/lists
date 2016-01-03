
var App = Vue.component('app', {

});

var router = new VueRouter();

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
    }
});

router.start(App, 'body');

new Vue({
    el: 'body',
    events: {

    }
});

