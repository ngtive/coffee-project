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
        <div class="mt-2 panel">
            <h6 class="border-bottom mb-3 pb-1">افزودن برند جدید</h6>
            <form class="border p-2 rounded col-12 row m-1" @submit.prevent="addBrand">
                <div class="col-12 col-lg-3 mb-2">
                    <label class="form-label required">نام برند</label>
                    <el-input v-model="brand.name"
                              placeholder="نام فارسی مورد نمایش"
                              size="mini"></el-input>
                    <span v-if="brand.errors.name"
                          class="text-danger ms-1 mt-1 d-block">{{ brand.errors.name }}</span>
                </div>
                <div class="col-12 col-lg-3 mb-2">
                    <label class="form-label required">نام انگلیسی برند</label>
                    <el-input v-model="brand.name_en"
                              placeholder="الزاما انگلیسی"
                              size="mini"></el-input>
                    <span v-if="brand.errors.name_en"
                          class="text-danger ms-1 mt-1 d-block">{{ brand.errors.name_en }}</span>
                </div>
                <div class="col-12 col-lg-3 mb-2">
                    <label class="form-label required">لوگوی برند(عکس)</label>
                    <input class="form-control form-control-file form-control-sm" type="file"
                           @input="brand.logo = $event.target.files[0]">
                    <span v-if="brand.errors.logo"
                          class="text-danger ms-1 mt-1 d-block">{{
                            brand.errors.logo
                        }}</span>
                </div>
                <div class="col-12 col-lg-3 mb-2">
                    <label class="form-label mt-3">
                        نمایش فعال
                        <el-checkbox
                            v-model="brand.status"
                            class="mx-1"
                            size="mini"></el-checkbox>
                    </label>
                </div>
                <div class="col-12 col-lg-7 mb-2">
                    <label class="form-label">متن توضیحات راجب برند</label>
                    <el-input v-model="brand.description" type="textarea"></el-input>
                    <span v-if="brand.errors.description"
                          class="text-danger ms-1 mt-1 d-block">{{ brand.errors.description }}</span>
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
                        align="right"
                        label="وضعیت">
                        <template slot-scope="{row}">
                            <span :class="{'text-success': row.status, 'text-danger': !row.status}">
                                {{ row.status ? 'فعال' : 'غیر فعال' }}
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column
                        align="left">
                        <template slot-scope="{row}">
                            <Link :href="$route('brands.show', {id: row.id})">
                                <el-button size="mini" type="info">ویرایش
                                    <em class="fa fa-pen"></em>
                                </el-button>
                            </Link>
                        </template>
                    </el-table-column>

                </el-table>
            </div>
        </div>
    </div>
</template>

<script>
import {Link, useForm} from "@inertiajs/vue2";

export default {
    name: "ListBrands",
    components: {Link},
    data: function () {
        return {
            brand: useForm({
                name: null,
                name_en: null,
                description: null,
                status: true,
                logo: null,
            })
        }
    },
    props: {
        brands: Object,
    },

    methods: {
        addBrand() {
            this.brand
                .transform(i => ({...i, status: i.status ? '1' : '0'}))
                .post('/admin/brands', {
                    onSuccess: params => {
                        console.log(params);
                    },
                    onError: () => {
                        this.$notify({
                            title: 'افزودن برند',
                            type: 'error',
                            message: 'افزودن برند با خطا مواجه شد',
                        });
                    }
                })
        }
    },
}
</script>

<style scoped>

</style>
