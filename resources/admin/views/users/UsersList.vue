<template>
    <div class="container">
        <div class="row border-bottom">
            <div class="col-6">
                <h2 class="text-muted">کاربران</h2>
            </div>
        </div>
        <div class="row mt-3 panel" v-loading="loading">
            <div class="row border-bottom">
                <div class="col-12">
                    <h4 class="text-muted">جستجو</h4>
                </div>
            </div>
            <form class="row mt-3" @submit.prevent="submitSearch">
                <div class="col-lg-1 mt-lg-1 mt-2">
                    <label class="form-label" for="first_name">نام: </label>
                </div>
                <div class="col-lg-2 mt-lg-0 mt-2">
                    <input class="form-control" id="first_name" v-model="search.first_name"
                           placeholder="نام">
                </div>
                <div class="col-lg-2 mt-lg-1 mt-2">
                    <label class="form-label" for="last_name">نام خانوادگی: </label>
                </div>
                <div class="col-lg-2 mt-lg-0 mt-2">
                    <input class="form-control" id="last_name" v-model="search.last_name"
                           placeholder="نام خانوادگی">
                </div>
                <div class="col-lg-2 mt-lg-1 mt-2">
                    <label class="form-label" for="phone_number">شماره تماس:</label>
                </div>
                <div class="col-lg-2 mt-lg-0 mt-2">
                    <input class="form-control" id="phone_number" v-model="search.phone_number"
                           placeholder="شماره تماس">
                </div>
                <div class="col-lg-1 mt-lg-0 mt-2">
                    <button class="btn btn-secondary" type="submit">
                        <em class="fa fa-search"></em>
                    </button>
                </div>
            </form>
        </div>
        <div class="mt-4 panel row p-0" v-loading="loading">
            <el-table :data="users.data" @cell-click="cellClick">
                <el-table-column prop="first_name"
                                 label="نام" align="right">
                </el-table-column>
                <el-table-column prop="last_name"
                                 label="نام خانوادگی" align="right"></el-table-column>
                <el-table-column prop="phone_number" label="شماره تلفن" align="right"></el-table-column>
                <el-table-column prop="created_at" label="تاریخ ثبت نام" align="right"></el-table-column>
            </el-table>
<!--            <div class="table-responsive">-->
<!--                <table class="table table-hover table-striped">-->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                        <td>نام</td>-->
<!--                        <td>نام خانوادگی</td>-->
<!--                        <td>شماره تلفن</td>-->
<!--                        <td>تاریخ ثبت نام</td>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    <router-link tag="tr" :to="{name: 'user', params: {id: user.id}}" v-for="user in users.data"-->
<!--                                 :key="user.id">-->
<!--                        <td>{{ user.first_name }}</td>-->
<!--                        <td>{{ user.last_name }}</td>-->
<!--                        <td>{{ user.phone_number }}</td>-->
<!--                        <td dir="ltr">{{ user.created_at }}</td>-->
<!--                    </router-link>-->
<!--                    </tbody>-->
<!--                </table>-->
<!--            </div>-->

            <div>
                <el-pagination
                    v-if="users.total > 0"
                    layout="next, pager, prev"
                    :total="users.total"
                    :page-size="users.per_page"
                    v-on:current-change="changePage"
                    :current-page="users.current_page"></el-pagination>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "UsersList",
    data: () => ({
        users: store.state.users,
        search: {
            first_name: null,
            last_name: null,
            phone_number: null,
        },
        loading: false,
    }),

    methods: {
        cellClick(user) {
            this.$router.push({
                name: 'edit-users',
                params: {
                    id: user.id,
                }
            });
        },
        async changePage(page) {
            this.loading = true;
            try {
                let result = await axios.get('/api/admin/users', {
                    params: {
                        page: page
                    }
                });
                this.users = result.data;
                this.loading = false;
            } catch (e) {
                this.loading = false;
                this.$swal({
                    title: 'مشکلی پیش آمد',
                    icon: 'error'
                });
            }
        },
        async submitSearch() {
            this.loading = true;
            try {
                let data = {};
                if (this.search.first_name) {
                    data.first_name = this.search.first_name;
                }
                if (this.search.last_name) {
                    data.last_name = this.search.last_name;
                }
                if (this.search.phone_number) {
                    data.phone_number = this.search.phone_number;
                }
                let result = await axios.get('/api/admin/users', {
                    params: data,
                });
                this.users = result.data;
                this.loading = false;
            } catch (e) {
                this.$swal({
                    title: 'مشکلی پیش آمد',
                    icon: 'error'
                });

                this.loading = false;

            }
        }
    },


    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/users');
            store.state.users = result.data;
            next();
        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمد',
                    icon: 'error'
                });
            });
        }

    }
}
</script>

<style scoped>
table tbody tr {
    cursor: pointer;
}
</style>
