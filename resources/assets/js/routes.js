import ItemsPageComponent from './lists/src/components/ItemsPageComponent.vue'
import CategoriesPageComponent from './lists/src/components/CategoriesComponent.vue'
import HelpPageComponent from './lists/src/components/HelpPageComponent.vue'
import TrashComponent from './lists/src/components/TrashComponent.vue'

var on = {
    pageAfterIn: function (e, page) {
        // store.updateRouteHistory(page.route.path);
    }
};

export default [
    {
        name: 'items',
        path: '/items',
        component: ItemsPageComponent,
        alias: '/',
        on: on
    },
    {
        name: 'items',
        path: '/items',
        component: ItemsPageComponent,
        on: on
    },
    {
        name: 'trash',
        path: '/trash',
        component: TrashComponent,
        on: on
    },
    {
        name: 'help',
        path: '/help',
        component: HelpPageComponent,
        on: on
    },
    {
        name: 'categories',
        path: '/categories',
        component: CategoriesPageComponent,
        on: on
    },
    {
        name: 'item',
        path: '/items/:id',
        component: ItemsPageComponent,
        on: on
    }
]
