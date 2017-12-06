window.Vue = require('vue');

import axios from 'axios';
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

// import VueMask from 'v-mask'
// Vue.use(VueMask);

Vue.component('cart', require('./components/Cart.vue'));
Vue.component('cartbutton', require('./components/CartButton.vue'));
Vue.component('login', require('./components/Login.vue'));

import store from './store'

const app = new Vue({
    el: '#app',
    store
});