<template>
    <div class="container">
        <div class="row border-bottom">
            <h1 class="text-muted">کاربر {{ user.id }}</h1>
        </div>

        <form class="row mt-4 panel" @submit.prevent="submitUpdate" v-loading="loading">
            <div class="row">
                <div class="col-md-6 col-lg-2">
                    <label for="first_name" class="form-label">نام</label>
                </div>
                <div class="col-md-6 col-lg-4">
                    <input class="form-control" id="first_name" v-model="user.first_name" placeholder="نام">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 col-lg-2">
                    <label for="last_name" class="form-label">نام خانوادگی</label>
                </div>
                <div class="col-md-6 col-lg-4">
                    <input class="form-control" id="last_name" v-model="user.last_name" placeholder="نام خانوادگی">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 col-lg-2">
                    <label class="form-label" for="phone_number">شماره تلفن</label>
                </div>
                <div class="col-md-6 col-lg-4">
                    <input class="form-control" id="phone_number" v-model="user.phone_number" placeholder="شماره موبایل">
                </div>
            </div>
            <div class="row mt-2">
<!--                <div class="col-md-6 col-lg-1 offset-lg-2">-->
<!--                    <button type="button" class="btn btn-danger" @click="submitDelete">حذف</button>-->
<!--                </div>-->
                <div class="col-md-6 col-lg-1">
                    <button type="submit" class="btn btn-success">بروزرسانی</button>
                </div>
            </div>
        </form>
        <div class="row mt-3 panel">
            <h1 class="col-12 border-bottom text-muted">
                آدرس های ثبت شده
            </h1>
            <div class="col-12 mt-2">
                <div class="address-wrapper mt-1" :class="{'address-default': address.default != '0'}" v-for="address in user.addresses">
                    <div class="row text-muted">
                        <p class="col-12 col-md-4">استان، شهر: {{ address.province.name }} - {{address.city.name}}</p>
                        <p class="col-12 col-md-4">آدرس: {{ address.address }}</p>
                        <p class="col-12 col-md-4">کد پستی: {{ address.postal_code }}</p>
                        <p class="col-12 col-md-4">نام گیرنده: {{ address.receiver }}</p>
                        <p class="col-12 col-md-4">تلفن همراه: {{ address.mobile }}</p>
                        <p v-if="address.telephone" class="col-12 col-md-4">تلفن ثابت: {{ address.telephone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3 panel">
            <h1 class="col-12 border-bottom text-muted">
                سفارشات
            </h1>
            <div class="col-12 mt-2">
                <el-table :data="users.orders">

                </el-table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ShowUser",
    data: () => ({
        user: store.state.user,
        loading: false,
    }),


    methods: {
        // async submitDelete() {
        //     this.loading = true;
        //     try {
        //         await axios.delete(`/api/admin/users/${this.user.id}`);
        //         this.$router.push({
        //             name: 'users'
        //         })
        //     } catch (e) {
        //         this.loading = false;
        //         this.$swal({
        //             title: 'مشکلی پیش آمد',
        //             icon: 'erorr'
        //         });
        //     }
        //
        // },
        async submitUpdate() {
            this.loading = true;

            let postData = {};
            if (this.user.first_name) {
                postData.first_name = this.user.first_name;
            }
            if (this.user.last_name) {
                postData.last_name = this.user.last_name;
            }
            if (this.user.phone_number) {
                postData.phone_number = this.user.phone_number;
            }

            try {
                let result = await axios.patch(`/api/admin/users/${this.user.id}`, postData);
                this.user = result.data;
                this.loading = false;

                this.$swal({
                    title: 'اطلاعات کاربر با موفقیت بروزرسانی شد',
                    icon: 'success'
                });

            } catch (e) {
                this.loading = false;

                this.$swal({
                    title: 'مشکلی پیش آمد',
                    icon: 'error'
                });
            }
        },
    },

    async beforeRouteEnter(to, from, next) {
        let id = to.params.id;
        try {
            let result = await axios.get(`/api/admin/users/${id}`);
            store.state.user = result.data;
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
.address-wrapper {
    padding: 15px;
    border: 1px solid grey;
    border-radius: 10px;
}

.address-default {
    border: 3px solid green;
}
</style>
