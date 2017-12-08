window.Vue = require('vue');

import Events from './classes/events.js';

import axios from 'axios';
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

// import VueMask from 'v-mask'
// Vue.use(VueMask);

Vue.use(Events);

Vue.component('cart', require('./components/Cart.vue'));
Vue.component('cartbutton', require('./components/CartButton.vue'));
Vue.component('login', require('./components/Login.vue'));
Vue.component('notification', require('./components/Notifications.vue'));

import store from './store'

const app = new Vue({
    el: '#app',
    store
});