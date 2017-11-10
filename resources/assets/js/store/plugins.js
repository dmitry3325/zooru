import { STORAGE_KEY } from './mutations'

const localStoragePlugin = store => {
    store.subscribe((mutation, { cart }) => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cart))
    })
};

export default [localStoragePlugin];