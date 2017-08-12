import Vue from 'vue'
import FeedbackComponent from '@jennyswift/feedback'
import AutocompleteComponent from '@jennyswift/vue-autocomplete'

import NavbarComponent from './lists/src/components/shared/NavbarComponent.vue'
import ToolbarComponent from './lists/src/components/shared/ToolbarComponent.vue'
import LoadingComponent from './lists/src/components/shared/LoadingComponent.vue'
import PopupComponent from './lists/src/components/shared/PopupComponent.vue'
import ButtonsComponent from './lists/src/components/shared/ButtonsComponent.vue'
import InputGroupComponent from './lists/src/components/shared/InputGroupComponent.vue'
import DatePickerComponent from './lists/src/components/shared/DatePickerComponent.vue'

import ItemPopupComponent from './lists/src/components/ItemPopupComponent.vue'
import BreadcrumbComponent from './lists/src/components/BreadcrumbComponent.vue'
import CategoryPopupComponent from './lists/src/components/CategoryPopupComponent.vue'
import FavouriteItemsComponent from './lists/src/components/FavouriteItemsComponent.vue'
import FilterComponent from './lists/src/components/FilterComponent.vue'
import NewItemComponent from './lists/src/components/NewItemComponent.vue'
import NewCategoryComponent from './lists/src/components/NewCategoryComponent.vue'
import ItemComponent from './lists/src/components/ItemComponent.vue'
import CategoryComponent from './lists/src/components/CategoryComponent.vue'
import ItemFieldsComponent from './lists/src/components/ItemFieldsComponent.vue'

//Shared components
Vue.component('navbar', NavbarComponent);
Vue.component('toolbar', ToolbarComponent);
Vue.component('feedback', FeedbackComponent);
Vue.component('loading', LoadingComponent);
Vue.component('popup', PopupComponent);
Vue.component('autocomplete', AutocompleteComponent);

Vue.component('buttons', ButtonsComponent);
Vue.component('input-group', InputGroupComponent);
Vue.component('date-picker', DatePickerComponent);

// Components
Vue.component('item-popup', ItemPopupComponent);
Vue.component('breadcrumb', BreadcrumbComponent);
Vue.component('category-popup', CategoryPopupComponent);
// Vue.component('alarms', require('./lists/src/components/AlarmsComponent.vue'));
// Vue.component('urgent-items', require('./lists/src/components/UrgentItemsComponent.vue'));
Vue.component('favourite-items', FavouriteItemsComponent);
Vue.component('items-filter', FilterComponent);
Vue.component('new-item', NewItemComponent);
Vue.component('new-category', NewCategoryComponent);
Vue.component('item', ItemComponent);
Vue.component('category', CategoryComponent);
Vue.component('item-fields', ItemFieldsComponent);

// Vue.component('app', require('./lists/src/components/AppComponent.js'));