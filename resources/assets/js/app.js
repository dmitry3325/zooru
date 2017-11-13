window.Vue = require('vue');

Vue.component('cart', require('./components/Cart.vue'));
Vue.component('cartbutton', require('./components/CartButton.vue'));

import store from './store'

const app = new Vue({
    el: '#app',
    store
});
//
Vue.use({
    install(Vue){
        Vue.prototype.setTarget = function (el, set) {
            if(typeof el === "string"){
                el = document.querySelector(el);
            }
            if(!el){
                console.log('Warning! Target element not found!');
                return false;
            }
            let vueEl = document.createElement('div');
            el.appendChild(vueEl);
            if(set === true) this.$options.el = vueEl;
            return vueEl;
        };

        Vue.prototype.call = function (func) {
            if (typeof this[func] === 'function') {
                this[func]();
            }
        };

        Vue.prototype.log = function(data){
            console.log(data);
        }
    }
});

window.AppNotifications = new Vue(require('./components/Notifications.vue'));