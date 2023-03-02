<template>
    <div class="container">
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
                        <el-input v-model="form.name"
                                  placeholder="نام فارسی برند"
                                  size="mini"></el-input>
                        <span v-if="form.errors.name"
                              class="text-danger">
                            {{ form.errors.name }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="form-label required">نام انگلیسی</label>
                        <el-input v-model="form.name_en" placeholder="نام انگلیسی برند"
                                  size="mini"></el-input>
                        <span v-if="form.errors.name_en"
                              class="text-danger">
                            {{ form.errors.name_en }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="form-label required">لوگو</label>
                        <input ref="logo"
                               class="form-control form-control-file form-control-sm"
                               type="file"
                               @input="form.logo = $event.target.files[0]">
                        <div v-if="form.errors.logo"
                             class="text-danger">
                            {{ form.errors.logo }}
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label class="form-label">توضیحات</label>
                        <el-input v-model="form.description"
                                  placeholder="توضیحات"
                                  type="textarea"></el-input>
                        <span v-if="form.errors.description" class="text-danger">
                            {{ form.errors.description }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-3 d-flex align-items-end">
                        <label class="form-check-label">
                            قابل نمایش
                            <el-checkbox v-model="form.status" :checked="!!form.status"></el-checkbox>
                        </label>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <el-button :disabled="form.processing" native-type="submit" size="mini" type="primary">ویرایش
                        </el-button>
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
import {useForm} from "@inertiajs/vue2";

export default {
    name: "EditBrand",
    data: function () {
        return {
            form: useForm({
                name: this.brand.name,
                name_en: this.brand.name_en,
                logo: undefined,
                status: this.brand.status,
                description: this.brand.description ?? undefined,
                _method: 'patch',
            }),
        }
    },

    props: {
        brand: Object,
    },
    methods: {
        update() {
            this.form
                .transform(data => ({...data, status: data.status ? '1' : '0'}))
                .post('/admin/brands/' + this.brand.id, {
                    onError: params => {
                        this.$notify({
                            title: 'بروزرسانی برند',
                            type: 'error',
                            message: 'بروزرسانی با خطا مواجه شد'
                        })
                    },
                    onSuccess: params => {
                        this.$notify({
                            title: 'بروزرسانی برند',
                            type: 'success',
                            message: 'بروزرسانی برند انجام شد'
                        })
                    }
                });
        },
        deleteBrand() {
        },
    }
}
</script>

<style scoped>

</style>
