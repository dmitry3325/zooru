import { STORAGE_KEY } from './modules/cart'

const localStoragePlugin = store => {
    store.subscribe((mutation, { cart }) => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cart.added))
    })
};

export default [localStoragePlugin];