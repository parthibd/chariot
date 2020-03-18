/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import {TOKEN_KEY} from "./constants";

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('app', require('./App.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import vuetify from './plugins/vuetify'
import {router} from "./router/router";
import VueRouter from 'vue-router'
import store from './store/store'

Vue.use(VueRouter);

router.beforeEach((to, from, next) => {
    let requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    if (requiresAuth && localStorage.getItem(TOKEN_KEY) == null) {
        next({name: "login"});
        store.commit('SET_LAYOUT', "simple-layout");
    } else if (to.name == "login" && localStorage.getItem(TOKEN_KEY) != null) {
        next({name: "dashboard"});
        store.commit('SET_LAYOUT', "app-layout");
    } else {
        if (to.name == "login") {
            store.commit('SET_LAYOUT', "simple-layout");
        } else {
            store.commit('SET_LAYOUT', "app-layout");
        }
        next()
    }
});

const app = new Vue({
    router,
    store,
    vuetify,
    el: '#app',
});
