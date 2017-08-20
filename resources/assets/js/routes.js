import ItemsPageComponent from './lists/src/components/ItemsPageComponent.vue'
import CategoriesPageComponent from './lists/src/components/CategoriesComponent.vue'
import HelpPageComponent from './lists/src/components/HelpPageComponent.vue'
import TrashComponent from './lists/src/components/TrashComponent.vue'

export default [
    {
        path: '/',
        component: ItemsPageComponent
    },
    {
        path: '/items',
        component: ItemsPageComponent
    },
    {
        path: '/items/:id',
        component: ItemsPageComponent
    },
    {
        path: '/categories',
        component: CategoriesPageComponent
    },
    // {
    //     path: '/trash',
    //     component: require('./lists/src/components/TrashComponent.vue')
    // },
    // {
    //     path: '/feedback',
    //     component: require('./lists/src/components/FeedbackPageComponent.vue')
    // },
    {
        path: '/help',
        component: HelpPageComponent
    },
    {
        path: '/trash',
        component: TrashComponent
    },

]
