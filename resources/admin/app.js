/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import langFa from 'element-ui/lib/locale/lang/fa';
import locale from 'element-ui/lib/locale';
import Element, {Loading} from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

import router from './router';
import VueRouter from 'vue-router';
import Vue from 'vue';
import store from "./store";
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VueRouter);
Vue.use(VueSweetalert2);
locale.use(langFa);
Vue.use(Element, {locale});


Vue.component('panel-sidebar', require('./components/Sidebar').default);
Vue.component('page-header', require('./components/Header').default);
Vue.component('loading', require('./components/Loading').default);
Vue.component('directive-loading', require('./components/DirectiveLoading').default);

Vue.mixin({
    methods: {
        startLoading() {
            this.$root.loading = true;
        },
        stopLoading() {
            this.$root.loading = false;
        }
    },
});

// Vue.directive('loading', {
//     inserted: (el, binding) => {
//         if (binding.name == 'loading' && binding.value == true) {
//             $(el).addClass('directive-loading-parent');
//             $(el).append("<div class=\"directive-loading-wrapper\">\n" +
//                 "        <div class=\"d-flex justify-content-center h-100 position-relative\">\n" +
//                 "            <div class=\"spinner-border\" role=\"status\">\n" +
//                 "                <span class=\"visually-hidden\">Loading...</span>\n" +
//                 "            </div>\n" +
//                 "        </div>\n" +
//                 "    </div>");
//         } else if (binding.name == 'loading' && binding.value == false) {
//             $(el).addClass('directive-loading-parent');
//             $(el).append("<div class=\"directive-loading-wrapper\" style='display: none'>\n" +
//                 "        <div class=\"d-flex justify-content-center h-100 position-relative\">\n" +
//                 "            <div class=\"spinner-border\" role=\"status\">\n" +
//                 "                <span class=\"visually-hidden\">Loading...</span>\n" +
//                 "            </div>\n" +
//                 "        </div>\n" +
//                 "    </div>");
//         }
//     },
//     update: (el, binding) => {
//         if (binding.name == 'loading') {
//             if (binding.value == false) {
//                 $(el).children('.directive-loading-wrapper').hide();
//             } else if (binding.value == true) {
//                 $(el).children('.directive-loading-wrapper').show();
//             }
//         }
//     }
// })


router.beforeEach((to, from, next) => {
    let loadingInstance = Loading.service();
    store.state.loading = loadingInstance;
    // store.state.loading = true;
    if (to.meta.middleware == 'guest') {
        if (store.state.auth.authenticated) {
            next({name: 'dashboard'});
        } else {
            next();
        }

    } else {
        if (store.state.auth.authenticated) {
            next();
        } else {
            next({name: 'login-form'});
        }
    }
});

router.afterEach(() => {
    store.state.loading.close();
});

const app = new Vue({
    el: '#app',
    router,
    store,
    data: {
        loading: store.state.loading,
    },
    mounted() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }
})


const token = store.state.auth.token;
window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

if (token) {
    axios.get('/api/admin').then((result) => {
        const admin = result.data;
        store.state.auth.authenticated = true;
        store.state.auth.user = admin;
        router.push({
            name: 'dashboard'
        });
    });
}


// window.axios.interceptors.request.use((config) => {
//     app.startLoading();
//     return config;
// }, (err) => {
//     app.stopLoading();
//     return Promise.reject(err);
// })
//
// window.axios.interceptors.response.use((response) => {
//     app.stopLoading();
//     return response;
// }, (err) => {
//     app.stopLoading();
//     return Promise.reject(err);
// })
window.Vue = Vue;
window.router = router;
window.store = store;
