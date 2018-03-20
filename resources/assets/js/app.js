window.Vue = require('vue');

import Events from './classes/events.js';

import axios from 'axios';
window.Axios = axios;

import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

// import VueMask from 'v-mask'
// Vue.use(VueMask);

Vue.use(Events);


let CartButton = require('./components/CartButton.vue');
Vue.component('cartbutton', CartButton);
Vue.component('cart', require('./components/Cart.vue'));
Vue.component('login', require('./components/Login.vue'));
Vue.component('notification', require('./components/Notifications.vue'));

import store from './store'
window.Store = store;

const app = new Vue({
    el: '#app',
    store
});