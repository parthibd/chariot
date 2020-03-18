import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {

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
