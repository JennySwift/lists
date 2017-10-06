import ItemsPageComponent from './lists/src/components/ItemsPageComponent.vue'
import CategoriesPageComponent from './lists/src/components/CategoriesComponent.vue'
import HelpPageComponent from './lists/src/components/HelpPageComponent.vue'
import TrashComponent from './lists/src/components/TrashComponent.vue'
import WelcomePageComponent from './lists/src/components/WelcomePageComponent.vue'


export default [
    // {
    //     path: '/',
    //     component: ItemsPageComponent
    // },
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
    {
        path: '/help',
        component: HelpPageComponent
    },
    {
        path: '/welcome',
        component: WelcomePageComponent
    },
    {
        path: '/trash',
        component: TrashComponent
    },

    { path: '/', redirect: '/welcome' }
]
