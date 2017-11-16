window.Vue = require('vue');

Vue.component('cart', require('./components/Cart.vue'));
Vue.component('cartbutton', require('./components/CartButton.vue'));

import store from './store'

const app = new Vue({
    el: '#app',
    store
});