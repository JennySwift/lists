import Vue from 'vue'
// import FeedbackComponent from '@jennyswift/feedback'
// import AutocompleteComponent from '@jennyswift/vue-autocomplete'

// import VModal from 'vue-js-modal'
// Vue.use(VModal, { dialog: true })

var vueTippy = require('vue-tippy');
Vue.use(vueTippy);

import ToolbarComponent from './lists/src/components/shared/ToolbarComponent.vue'
import NavbarComponent from './lists/src/components/shared/NavbarComponent.vue'
import LoadingComponent from './lists/src/components/shared/LoadingComponent.vue'
import PopupComponent from './lists/src/components/shared/PopupComponent.vue'
import NewPopupComponent from './lists/src/components/shared/NewPopupComponent.vue'
import ButtonsComponent from './lists/src/components/shared/ButtonsComponent.vue'
import InputGroupComponent from './lists/src/components/shared/InputGroupComponent.vue'
import DatePickerComponent from './lists/src/components/shared/DatePickerComponent.vue'
import AutocompleteComponent from './lists/src/components/shared/AutocompleteComponent.vue'
import FeedbackComponent from './lists/src/components/shared/FeedbackComponent.vue'
import SelectorComponent from './lists/src/components/shared/SelectorComponent.vue'

import ItemPopupComponent from './lists/src/components/ItemPopupComponent.vue'
import BreadcrumbComponent from './lists/src/components/BreadcrumbComponent.vue'
import FilterComponent from './lists/src/components/FilterComponent.vue'
import NewItemComponent from './lists/src/components/NewItemComponent.vue'
import NewCategoryComponent from './lists/src/components/NewCategoryComponent.vue'
import ItemComponent from './lists/src/components/ItemComponent.vue'
import ItemFieldsComponent from './lists/src/components/ItemFieldsComponent.vue'

//api
import Clients from './components/passport/Clients.vue'
import AuthorizedClients from './components/passport/AuthorizedClients.vue'
import PersonalAccessTokens from './components/passport/PersonalAccessTokens.vue'

//Shared components
Vue.component('toolbar', ToolbarComponent);
Vue.component('navbar', NavbarComponent);
Vue.component('popup', PopupComponent);
Vue.component('new-popup', NewPopupComponent);
Vue.component('autocomplete', AutocompleteComponent);
Vue.component('selector', SelectorComponent);

Vue.component('buttons', ButtonsComponent);
Vue.component('input-group', InputGroupComponent);
Vue.component('date-picker', DatePickerComponent);

// Components
Vue.component('item-popup', ItemPopupComponent);
Vue.component('breadcrumb', BreadcrumbComponent);
// Vue.component('trash', TrashComponent);
// Vue.component('category-popup', CategoryPopupComponent);
// Vue.component('alarms', require('./lists/src/components/AlarmsComponent.vue'));
// Vue.component('urgent-items', require('./lists/src/components/UrgentItemsComponent.vue'));
// Vue.component('favourite-items', FavouriteItemsComponent);
Vue.component('items-filter', FilterComponent);
Vue.component('new-item', NewItemComponent);
Vue.component('new-category', NewCategoryComponent);
Vue.component('item', ItemComponent);
// Vue.component('category', CategoryComponent);
Vue.component('item-fields', ItemFieldsComponent);

Vue.component('passport-clients', Clients);
Vue.component('passport-authorized-clients', AuthorizedClients);
Vue.component('passport-personal-access-tokens', PersonalAccessTokens);

// Vue.component(
//     'passport-clients',
//     require('./components/passport/Clients.vue')
// );

// Vue.component(
//     'passport-authorized-clients',
//     require('./components/passport/AuthorizedClients.vue')
// );
//
// Vue.component(
//     'passport-personal-access-tokens',
//     require('./components/passport/PersonalAccessTokens.vue')
// );

// Vue.component('app', require('./lists/src/components/AppComponent.js'));