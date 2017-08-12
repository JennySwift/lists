import Vue from 'vue'

//Shared components
Vue.component('navbar', require('./lists/src/components/shared/NavbarComponent.vue'));
Vue.component('toolbar', require('./lists/src/components/shared/ToolbarComponent.vue'));
Vue.component('feedback', require('@jennyswift/feedback'));
Vue.component('loading', require('./lists/src/components/shared/LoadingComponent.vue'));
Vue.component('popup', require('./lists/src/components/shared/PopupComponent.vue'));
// Vue.component('autocomplete', require('@jennyswift/vue-autocomplete'));

Vue.component('buttons', require('./lists/src/components/shared/ButtonsComponent.vue'));
Vue.component('input-group', require('./lists/src/components/shared/InputGroupComponent.vue'));
Vue.component('date-picker', require('./lists/src/components/shared/DatepickerComponent.vue'));

// Components
Vue.component('item-popup', require('./lists/src/components/ItemPopupComponent.vue'));
Vue.component('breadcrumb', require('./lists/src/components/BreadcrumbComponent.vue'));
Vue.component('category-popup', require('./lists/src/components/CategoryPopupComponent.vue'));
Vue.component('alarms', require('./lists/src/components/AlarmsComponent.vue'));
Vue.component('urgent-items', require('./lists/src/components/UrgentItemsComponent.vue'));
Vue.component('favourite-items', require('./lists/src/components/FavouriteItemsComponent.vue'));
Vue.component('items-filter', require('./lists/src/components/FilterComponent.vue'));
Vue.component('new-item', require('./lists/src/components/NewItemComponent.vue'));
Vue.component('new-category', require('./lists/src/components/NewCategoryComponent.vue'));
Vue.component('item', require('./lists/src/components/ItemComponent.vue'));
Vue.component('category', require('./lists/src/components/CategoryComponent.vue'));
Vue.component('item-fields', require('./lists/src/components/ItemFieldsComponent.vue'));

Vue.component('app', require('./lists/src/components/AppComponent.js'));