module.exports = [
    {
        path: '/',
        component: require('./lists/src/components/ItemsPageComponent.vue')
    },
    {
        path: '/items',
        component: require('./lists/src/components/ItemsPageComponent.vue'),
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
        component: require('./lists/src/components/ItemsPageComponent.vue')
    },
    {
        path: '/categories',
        component: require('./lists/src/components/CategoriesComponent.vue')
    },
    {
        path: '/trash',
        component: require('./lists/src/components/TrashComponent.vue')
    },
    {
        path: '/feedback',
        component: require('./lists/src/components/FeedbackPageComponent.vue')
    },
    {
        path: '/help',
        component: require('./lists/src/components/HelpPageComponent.vue')
    }

];
