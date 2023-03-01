<template>
    <div class="container">
        <div class="row border-bottom">
            <div class="col-6">
                <h2>برند ها</h2>
            </div>
            <!--            <div class="col-6 text-end">-->
            <!--                <router-link :to="{name: 'new-brand'}"-->
            <!--                             tag="button"-->
            <!--                             class="btn btn-success">-->
            <!--                    <em class="fa fa-plus"></em>-->
            <!--                </router-link>-->
            <!--            </div>-->
        </div>
        <div v-loading="loading" class="mt-2 panel">
            <h6 class="border-bottom mb-3 pb-1">افزودن برند جدید</h6>
            <form class="border p-2 rounded col-12 row m-1" @submit.prevent="addBrand">
                <div class="col-12 col-lg-3 mb-2">
                    <label class="form-label required">نام برند</label>
                    <el-input v-model="brand.name"
                              placeholder="نام فارسی مورد نمایش"
                              size="mini"></el-input>
                </div>
                <div class="col-12 col-lg-3 mb-2">
                    <label class="form-label required">نام انگلیسی برند</label>
                    <el-input v-model="brand.name_en"
                              placeholder="الزاما انگلیسی"
                              size="mini"></el-input>
                </div>
                <div class="col-12 col-lg-3 mb-2">
                    <label class="form-label required">لوگوی برند(عکس)</label>
                    <input ref="logo" class="form-control form-control-file form-control-sm" type="file">
                </div>
                <div class="col-12 col-lg-7 mb-2">
                    <label class="form-label">متن توضیحات راجب برند</label>
                    <el-input v-model="brand.description" type="textarea"></el-input>
                </div>
                <div class="col-12 col-lg-2 d-lg-flex align-items-lg-end mb-2">
                    <el-button native-type="submit" size="mini" type="primary">افزودن برند</el-button>
                </div>
            </form>
        </div>
        <div class="row mt-4 panel">
            <h6 class="mb-3 pb-1 border-bottom">لیست برند های ذخیره شده</h6>
            <div class="">
                <el-table :data="brands.data">
                    <el-table-column align="right" width="90">
                        <template slot-scope="{row}">
                            <img :src="row.thumb" alt="Brand Logo"
                                 class="w-100">
                        </template>
                    </el-table-column>
                    <el-table-column
                        align="right"
                        label="نام برند"
                        prop="name"></el-table-column>
                    <el-table-column
                        align="right"
                        label="نام انگلیسی"
                        prop="name_en"></el-table-column>
                    <el-table-column
                        align="left">
                        <template slot-scope="{row}">
                            <router-link :to="{name: 'edit-brand', params: {id: row.id}}">
                                <el-button size="mini" type="info">ویرایش
                                    <em class="fa fa-pen"></em>
                                </el-button>
                            </router-link>
                            <!--                            <el-button type="danger" size="mini" class="ms-2">
                                                            <em class="fa fa-trash"></em>
                                                        </el-button>-->
                        </template>
                    </el-table-column>

                </el-table>
                <!--                <div class="col-12 col-md-4 col-lg-2 border rounded p-2" v-for="brand in brands.data" :key="brand.id">-->
                <!--                    <div class="mb-2">-->
                <!--                        <div class="text-center d-flex align-items-center justify-content-center">-->
                <!--                            <img :src="brand.thumb" alt="Brand logo" class="w-100 h-auto">-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                    <form @submit.prevent="updateBrand(brand.id)" class="row">-->
                <!--                        <div class="mb-2">-->
                <!--                            <label class="form-label required">نام برند</label>-->
                <!--                            <el-input v-model="brand.name"></el-input>-->
                <!--                        </div>-->
                <!--                        <div class="mb-2">-->
                <!--                            <label class="form-label required">نام انگلیسی برند</label>-->
                <!--                            <el-input v-model="brand.name_en"></el-input>-->
                <!--                        </div>-->
                <!--                    </form>-->
                <!--                </div>-->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListBrands",
    data: function () {
        return {
            brand: {
                name: undefined,
                name_en: undefined,
                description: undefined,
                status: true,

            },
            validations: {},
            loading: false,
            brands: store.state.brands
        }
    },

    methods: {
        updateBrand(brand) {

            this.$swal({
                title: 'بروزرسانی برند',
                message: 'از بروزرسانی برند اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                cancelButtonText: 'انصراف',
                confirmButtonText: 'تایید',
            })
                .then((confirm) => {
                    if (confirm.isConfirmed) {
                        axios.patch('/api/admin/brands/' + brand.id)
                            .then((result) => {

                            })
                            .catch((reason) => {

                            })
                    }
                })

        },
        addBrand() {
            this.loading = true;
            this.validations = {};
            let form = new FormData();


            if (this.$refs.logo.files.length > 0) {
                form.set('logo', this.$refs.logo.files[0]);
            }
            form.set('name', this.brand.name);
            form.set('name_en', this.brand.name_en);
            form.set('description', this.brand.description);
            form.set('status', this.brand.status ? 1 : 0);


            axios.post('/api/admin/brands/', form)
                .then(result => {
                    this.loading = false;
                    this.brands.data.unshift(result.data);
                    this.$notify({
                        title: 'افزودن برند',
                        type: 'success',
                        message: 'برند با موفقیت اضافه شد',
                    });
                })
                .catch((reason) => {
                    this.loading = false;
                    if (reason.response.status == 422) {
                        this.validations = reason.reaponse.data.errors;
                    }
                    this.$notify({
                        title: 'افزودن برند',
                        type: 'error',
                        message: 'مشکلی در افزودن برند به وجود آمد',
                    });
                });
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/brands');
            store.state.brands = result.data;
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
