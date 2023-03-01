<template>
    <div class="container" v-loading="loading">
        <div class="row border-bottom">
            <div class="col-6">
                <h2 class="text-muted">ویرایش برند</h2>
            </div>
            <!--            <div class="col-6 text-end">-->
            <!--&lt;!&ndash;                <button class="btn btn-danger" @click="deleteBrand">&ndash;&gt;-->
            <!--&lt;!&ndash;                    حذف برند <em class="fa fa-trash"></em>&ndash;&gt;-->
            <!--&lt;!&ndash;                </button>&ndash;&gt;-->
            <!--            </div>-->
        </div>
        <div class="row mt-4">
            <form class="col-12 panel" @submit.prevent="update">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <label class="form-label required">نام فارسی</label>
                        <el-input v-model="brand.name"
                                  placeholder="نام فارسی برند"
                                  size="mini"></el-input>
                        <span v-if="validations.name"
                              class="text-danger">
                            {{ validations.name[0] }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="form-label required">نام انگلیسی</label>
                        <el-input v-model="brand.name_en" placeholder="نام انگلیسی برند"
                                  size="mini"></el-input>
                        <span v-if="validations.name_en"
                              class="text-danger">
                            {{ validations.name_en[0] }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="form-label required">لوگو</label>
                        <input ref="logo"
                               class="form-control form-control-file form-control-sm"
                               type="file">
                        <div v-if="validations.logo"
                             class="text-danger">
                            {{ validations.logo[0] }}
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label class="form-label">توضیحات</label>
                        <el-input v-model="brand.description"
                                  placeholder="توضیحات"
                                  type="textarea"></el-input>
                        <span v-if="validations.description" class="text-danger">
                            {{ validations.description[0] }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-3 d-flex align-items-end">
                        <label class="form-check-label">
                            قابل نمایش
                            <el-checkbox v-model="brand.status" :checked="!!brand.status"></el-checkbox>
                        </label>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <el-button native-type="submit" size="mini" type="primary">ویرایش</el-button>
                    </div>
                </div>
            </form>
            <div class="col-12 panel border">
                <div class="row">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <h6 class="text-muted border-bottom pb-1 mb-3">فایل لوگوی فعلی</h6>
                            <img :src="brand.logo" alt="brand logo" class="border" height="500" width="500">
                        </div>
                        <div class="col-12 col-lg-6 text-center">
                            <h6 class="text-muted border-bottom pb-1 mb-3 text-start">فایل کوچک شده لوگو</h6>
                            <img :src="brand.thumb" alt="brand logo" class="border">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "EditBrand",
    data: function () {
        return {
            brand: store.state.brand,
            loading: false,
            validations: {}
        }
    },
    methods: {
        async update() {
            this.validations = {};
            this.loading = true;
            let form = new FormData();
            form.set('name', this.brand.name);
            form.set('name_en', this.brand.name_en);

            if (this.$refs.logo.files.length > 0) {
                form.set('logo', this.$refs.logo.files[0]);
            }

            if (this.brand.description) {
                form.set('description', this.brand.description);
            }
            form.set('status', this.brand.status);


            form.set('_method', 'patch');
            try {
                let result = await axios.post('/api/admin/brands/' + this.brand.id, form);
                this.brand = result.data;
                this.$swal({
                    title: 'بروزرسانی انجام شد',
                    icon: 'success',
                });
                this.loading = false;
            } catch (e) {
                if (e.response.status == 422) {
                    this.validations = e.response.data.errors;
                } else {
                    this.$swal({
                        title: 'مشکلی پیش آمد'
                    });
                }
                this.loading = false;
            }

        },
        deleteBrand() {
        },
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/brands/' + to.params.id);
            store.state.brand = result.data;
            next();

        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمد'
                });
            });
        }
    }
}
</script>

<style scoped>

</style>
