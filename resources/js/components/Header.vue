<template>
    <header>
        <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap flex-wrap flex-row p-md-2 p-1 shadow">
            <button aria-controls="sidebar"
                    aria-expanded="true"
                    aria-label="Toggle navigation"
                    class="navbar-toggler d-md-none collapsed col-2"
                    data-bs-target="#sidebar"
                    data-bs-toggle="collapse"
                    type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand col-6 col-sm-3 col-md-1 admin-brand-logo text-right" href="#">
                &nbsp;
            </a>
            <ul class="navbar-nav flex-row px-3">
                <!--                <li class="nav-item text-nowrap me-3 mt-2">
                                    <i class="nav-fill text-white" dir="ltr">{{ admin.now }}</i>
                                </li>-->
                <li class="nav-item text-nowrap ml-1">
                    <a class="nav-link" href="#" @click.prevent="logout">
                        {{ admin.first_name }} {{ admin.last_name }}
                        <i class="fa fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
</template>

<script>

import {Link, useForm} from '@inertiajs/vue2';

export default {
    name: "Header",

    components: {
        Link,
    },
    props: {
        admin: Object,
    },

    methods: {
        logout() {
            this.$swal({
                title: 'خروج از پنل ادمین',
                icon: 'warning',
                text: 'آیا از خارج شدن مطمئن هستید؟',
                showCancelButton: true,
                showConfirmButton: true,
                cancelButtonText: 'انصراف',
                confirmButtonText: 'تایید'
            })
                .then(ok => {
                    if (ok.isConfirmed) {
                        useForm().post(this.$route('logout'));
                    }
                });
        }
    },
}
</script>

<style scoped>
.admin-brand-logo {
    /* width: 50px; */
    height: 50px;
    background-size: 62px;
    text-align: right;
    background: url(/logo/logo-admin.svg) no-repeat right;
    margin-right: 10px;
}
</style>
