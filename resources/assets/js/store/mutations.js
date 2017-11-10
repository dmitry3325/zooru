export const STORAGE_KEY = 'cart-list';

export const state = {
    cart: JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]')
};


export const mutations = {
    addToCart(state, data) {
        let product = state.cart.find(product => product.id === data.product.id);

        if (product) {
            product.quantity += data.product.quantity;
            product.quantity = product.quantity >= data.product.maxItems ? data.product.maxItems : product.quantity;
        } else {
            state.cart = [data.product, ...state.cart]
        }
    },
    removeFromCart(state, id) {
        state.cart = state.cart.filter(product => product.id !== id);
    }
};

export const actions = {
    addToCart({commit}, payload) {
        commit('addToCart', payload)
    },
    removeFromCart({commit}, payload) {
        commit('removeFromCart', payload.id)
    }
};

export const getters = {
    totalPrice: state => {
        return state.cart.reduce(function(sum, current) {
            return sum + (current.price * current.quantity);
        }, 0).toLocaleString('ru');
    },
    totalCount: state => {
        return Object.keys(state.cart).length;
    }
};

