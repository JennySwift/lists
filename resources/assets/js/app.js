// require('./bootstrap');

var Vue = require('vue');
global.$ = require('jquery');
global.jQuery = require('jquery');
global._ = require('underscore');
global.store = require('./repositories/Store');
var VueResource = require('vue-resource');
Vue.use(VueResource);
require('./config.js');
global.helpers = require('./repositories/Helpers');
global.filters = require('./repositories/Filters');
// Date.setLocale('en-AU');

//Shared components
Vue.component('navbar', require('./components/shared/NavbarComponent.vue'));
Vue.component('feedback', require('@jennyswift/feedback'));
Vue.component('loading', require('./components/shared/LoadingComponent.vue'));
// Vue.component('popup', require('./components/shared/PopupComponent.vue'));
Vue.component('autocomplete', require('@jennyswift/vue-autocomplete'));

// Vue.component('date-navigation', require('./components/shared/DateNavigationComponent.vue'));
// Vue.component('buttons', require('./components/shared/ButtonsComponent.vue'));
Vue.component('input-group', require('./components/shared/InputGroupComponent.vue'));
// Vue.component('checkbox-group', require('./components/shared/CheckboxGroupComponent.vue'));

//Components
Vue.component('item-popup', require('./components/ItemPopupComponent.vue'));
Vue.component('alarms', require('./components/AlarmsComponent.vue'));
Vue.component('urgent-items', require('./components/UrgentItemsComponent.vue'));
Vue.component('favourite-items', require('./components/FavouriteItemsComponent.vue'));
Vue.component('filter', require('./components/FilterComponent.vue'));
Vue.component('new-item', require('./components/NewItemComponent.vue'));
Vue.component('item', require('./components/ItemComponent.vue'));

//Filters

//Transitions
// Vue.transition('fade', require('./transitions/FadeTransition'));

require('./routes');

