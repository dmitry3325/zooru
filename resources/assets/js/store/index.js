import Vue from 'vue'
import Vuex from 'vuex'
import plugins from './plugins'
import {mutations, state, actions, getters} from './mutations'

Vue.use(Vuex);

export default new Vuex.Store({
    state,
    actions,
    getters,
    mutations,
    plugins,
})
