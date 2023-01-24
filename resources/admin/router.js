import VueRouter from 'vue-router';

const routes = [
    {
        path: '/',
        component: require('./views/Dashboard').default,
        name: 'dashboard',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/products',
        component: require('./views/products/ProductsList').default,
        name: 'products',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/categories',
        component: require('./views/categories/ListCategory').default,
        name: 'categories',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/categories/new',
        component: require('./views/categories/NewCategory').default,
        name: 'new-category',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/categories/:id',
        component: require('./views/categories/EditCategory').default,
        name: 'edit-category',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/brands',
        component: require('./views/brands/ListBrand').default,
        name: 'brands',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/brands/new',
        component: require('./views/brands/NewBrand').default,
        name: 'new-brand',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/brands/:id',
        component: require('./views/brands/EditBrand').default,
        name: 'edit-brand',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/products/new',
        component: require('./views/products/NewProduct').default,
        name: 'new-product',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/products/:id',
        component: require('./views/products/EditProduct').default,
        name: 'edit-product',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/orders',
        component: require('./views/orders/OrdersList').default,
        name: 'orders',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/orders/show/:id',
        component: require('./views/orders/ShowOrder').default,
        name: 'orders-show',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/orders/edit/:id',
        component: require('./views/orders/EditOrder').default,
        name: 'orders-edit',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/orders/print-paid-orders',
        component: require('./views/orders/PrintPaidOrders').default,
        name: 'print-paid-orders',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/users',
        component: require('./views/users/UsersList').default,
        name: 'users',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/user/:id',
        component: require('./views/users/ShowUser').default,
        name: 'edit-users',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/addresses',
        component: require('./views/addresses/List').default,
        name: 'addresses',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/attributes',
        component: require('./views/attributes/ListAttribute').default,
        name: 'attributes',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/attributes/:id',
        component: require('./views/attributes/ListAttributeValues.vue').default,
        name: 'attributes-values',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/discounts',
        component: require('./views/discounts/ListDiscount.vue').default,
        name: 'discounts',
        meta: {
            middleware: 'auth',
        }
    },
    {
        path: '/discounts/new',
        component: require('./views/discounts/NewDiscount.vue').default,
        name: 'new-discount',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/discounts/:id',
        component: require('./views/discounts/EditDiscount.vue').default,
        name: 'edit-discount',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/articles',
        component: require('./views/articles/ListArticle.vue').default,
        name: 'articles',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/articles/new',
        component: require('./views/articles/NewArticle.vue').default,
        name: 'new-article',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/articles/:id',
        component: require('./views/articles/EditArticle.vue').default,
        name: 'edit-article',
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/login',
        component: require('./views/login').default,
        name: 'login-form',
        meta: {
            middleware: 'guest'
        }
    },
];


const router = new VueRouter({
    routes,
    mode: 'hash',
    linkActiveClass: 'active',
});



export default router;
