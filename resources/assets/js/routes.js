module.exports = [
    {
        path: '/',
        component: require('./components/ItemsPageComponent.vue')
    },
    {
        path: '/items',
        component: require('./components/ItemsPageComponent.vue'),
        // children: [
        //     {
        //         path: '/',
        //         component: require('./components/ItemComponent.vue')
        //     },
        //     {
        //         path: '/:id',
        //         component: require('./components/ItemComponent.vue')
        //     }
        // ]
    },
    {
        path: '/items/:id',
        component: require('./components/ItemComponent.vue')
    },
    {
        path: '/categories',
        component: require('./components/CategoriesComponent.vue')
    },
    {
        path: '/trash',
        component: require('./components/TrashComponent.vue')
    },
    {
        path: '/feedback',
        component: require('./components/FeedbackPageComponent.vue')
    },
    {
        path: '/help',
        component: require('./components/HelpPageComponent.vue')
    }

];
