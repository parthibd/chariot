import Vue from 'vue'
import Vuex from 'vuex'
import AppState from "./AppState";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        AppState
    },
    state: {
        layout: 'simple-layout'
    },
    mutations: {
        SET_LAYOUT: (state, payload) => {
            state.layout = payload
        }
    },
    getters: {
        layout: state => {
            return state.layout
        }
    }
})
