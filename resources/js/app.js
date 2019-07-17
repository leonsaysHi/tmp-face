import 'babel-polyfill';
require('./lodash');

window.moment = require('moment');

window.Vue = require('vue');

/* Bootstrap */
import BootstrapVue from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue.css';
Vue.use(BootstrapVue);

/* Fontawesome */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
require('./faIcons')
Vue.component('fa-icon', FontAwesomeIcon)

/* Vuex store */
import Vuex from 'vuex';
Vue.use(Vuex);
import store from './store';

/* Dev only */
Vue.component('styleguide', require('./components/Styleguide.vue').default);

/* Components */
Vue.component('topbar', require('./components/Topbar.vue').default);
Vue.component('navbar', require('./components/Navbar.vue').default);
Vue.component('input-date-picker', require('./components/form/InputDatePicker.vue').default);
Vue.component('input-typeahead', require('./components/form/InputTypeahead.vue').default);

/* App */

const app = new Vue({
  el: '#app',
  store: new Vuex.Store(store),
});
