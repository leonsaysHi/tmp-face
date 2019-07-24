import 'babel-polyfill';
require('./lodash');
// require('./bootstrap');

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

/* Screens */
Vue.component('styleguide', require('./screens/Styleguide.vue').default);
Vue.component('home', require('./screens/Home.vue').default);
Vue.component('payment', require('./screens/payment/Payment.vue').default);
Vue.component('satellite-visit', require('./screens/satellite-visit/SatelliteVisit.vue').default);

/* Components */
Vue.component('topbar', require('./components/Topbar.vue').default);
Vue.component('navbar', require('./components/Navbar.vue').default);
Vue.component('input-date-picker', require('./components/form/InputDatePicker.vue').default);
Vue.component('input-typeahead', require('./components/form/InputTypeahead.vue').default);
Vue.component('input-file', require('./components/form/InputFile.vue').default);

/* App */
import { mapState } from "vuex";
const app = new Vue({
  el: '#app',
  store: new Vuex.Store(store),
  computed: {
    ...mapState({
      currentScreen: state => state.Navigation.currentScreen,
    }),
  }
});
