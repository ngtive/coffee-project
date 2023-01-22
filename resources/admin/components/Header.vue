<template>
    <header>
        <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap flex-wrap flex-row p-md-2 p-1 shadow">
            <button class="navbar-toggler d-md-none collapsed col-2"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebar"
                    aria-controls="sidebar"
                    aria-expanded="true"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="#" class="navbar-brand col-6 col-sm-3 col-md-1 admin-brand-logo text-right">
                &nbsp;
            </a>
            <ul class="navbar-nav flex-row px-3">
                <li class="nav-item text-nowrap me-3 mt-2">
                    <i class="nav-fill text-white" dir="ltr">{{ admin.now }}</i>
                </li>
                <li class="nav-item text-nowrap ml-1">
                    <a class="nav-link" @click="logout" href="#">
                        {{ admin.first_name }} {{ admin.last_name }}
                        <i class="fa fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
</template>

<script>

export default {
    name: "Header",
    data: () => ({
        admin: store.state.auth.user,
    }),

    methods: {
        logout() {
            this.$swal({
                title: 'آیا مایل به خروج از پنل هستید؟',
                icon: 'warning',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'بله',
                cancelButtonText: 'خیر'
            }).then(async (confirm) => {
                if (confirm.isConfirmed) {
                    let result = await axios.get('/api/admin/logout');
                    if (result.data.ok) {
                        this.$store.commit('DELETE_TOKEN');
                        this.$router.push({
                            name: 'login-form',
                        });
                    }
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
