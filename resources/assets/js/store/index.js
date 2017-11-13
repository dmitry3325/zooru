import Vue from 'vue'
import Vuex from 'vuex'
import plugins from './plugins'
import cart from './modules/cart'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        cart
    },
    plugins,
})
