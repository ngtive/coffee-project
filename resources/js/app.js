import './utils';
import Vue from 'vue'
import {createInertiaApp} from '@inertiajs/vue2'
import AdminPanelLayout from "./layouts/AdminPanelLayout.vue";

import langFa from 'element-ui/lib/locale/lang/fa';
import locale from 'element-ui/lib/locale';
import Element from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

createInertiaApp({
    resolve: name => {
        let page = require(`./Pages/${name}`);
        page.default.layout = page.default.layout || AdminPanelLayout;
        return page;
    },
    setup({el, App, props, plugin}) {

        Vue.use(plugin);
        Vue.use(VueSweetalert2);
        locale.use(langFa);
        Vue.use(Element, {locale});

        Vue.prototype.$route = route;

        new Vue({
            render: h => h(App, props),
        }).$mount(el)
    }
})
