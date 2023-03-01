import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        loading: false,
        auth: {
            authenticated: false,
            user: {},
            categories: undefined,
            token: window.localStorage.getItem('authToken'),
        }
    },

    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.auth.authenticated = value;
        },
        SET_TOKEN(state, value) {
            state.auth.token = value;
            window.localStorage.setItem('authToken', value);
        },
        DELETE_TOKEN(state) {
            state.auth.authenticated = false;
            state.auth.user = null;
            state.auth.token = null;
            window.localStorage.removeItem('authToken');
        },
        SET_USER(state, value) {
            state.auth.user = value;
        }
    }
});


export default store;
