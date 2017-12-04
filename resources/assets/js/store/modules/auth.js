export const AUTH_TOKEN = 'auth-token';

const state = {
    authToken: JSON.parse(localStorage.getItem(AUTH_TOKEN) || '[]')
};


const mutations = {

};

const actions = {

};

const getters = {

};



export default {
    state,
    getters,
    actions,
    mutations
}
