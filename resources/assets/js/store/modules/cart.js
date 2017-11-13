export const STORAGE_KEY = 'cart-list';

const state = {
    added: JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]')
};


const mutations = {
    addToCart(state, data) {
        let product = state.added.find(product => product.id === data.product.id);

        if (product) {
            product.quantity += data.product.quantity;
            product.quantity = (product.quantity >= data.product.maxItems) ? data.product.maxItems : product.quantity;
        } else {
            state.added = [data.product, ...state.added]
        }

        return state;
    },
    removeFromCart(state, id) {
        state.added = state.added.filter(product => product.id !== id);
    }
};

const actions = {
    addToCart({commit}, payload) {
        commit('addToCart', payload)
    },
    removeFromCart({commit}, payload) {
        commit('removeFromCart', payload.id)
    }
};

const getters = {
    totalPrice: state => {
        return state.added.reduce(function(sum, current) {
            return sum + (current.price * current.quantity);
        }, 0);
    },
    totalCount: state => {
        return Object.keys(state.added).length;
    }
};



export default {
    state,
    getters,
    actions,
    mutations
}
