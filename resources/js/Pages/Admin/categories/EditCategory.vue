<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-12 text-muted">ویرایش دسته بندی</h2>
        </div>
        <div class="row mt-2">
            <form class="col-12 panel" @submit.prevent="submit">
                <div class="row">
                    <div :class="{'col-lg-6': category.cover}" class="col-12">
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label required" for="name">نام فارسی</label>
                                <el-input v-model="form.name"
                                          placeholder="نام فارسی"
                                          size="mini" type="text"></el-input>
                                <span v-if="form.errors.name" class="text-danger d-block ms-1 mt-1">
                                    {{ form.errors.name }}
                                </span>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label required" for="name_en">نام دسته بندی (انگلیسی)</label>
                                <el-input v-model="form.name_en"
                                          placeholder="نام انگلیسی"
                                          size="mini"
                                          type="text"></el-input>
                                <span v-if="form.errors.name_en" class="text-danger d-block ms-1 mt-1">
                                    {{ form.errors.name_en }}
                                </span>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">کاور دسته بندی</label>
                                <input class="form-control form-control-sm"
                                       type="file"
                                       @input="form.cover = $event.target.files[0]">
                                <span v-if="form.errors.cover"
                                      class="ms-1 mt-1 d-block text-danger">
                                    {{ form.errors.cover }}
                                </span>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label d-block">زیر مجموعه</label>
                                <el-select v-model="form.parent_id"
                                           class="w-100"
                                           placeholder="زیر مجموعه"
                                           size="mini">
                                    <el-option
                                        v-for="c in categories"
                                        :key="c.id"
                                        :disabled="c.id == category.id"
                                        :label="c.name"
                                        :value="c.id"></el-option>
                                </el-select>
                                <span v-if="form.errors.parent_id" class="text-danger d-block ms-1 mt-1">
                                    {{ form.errors.parent_id }}
                                </span>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">وضعیت نمایش
                                    <el-checkbox
                                        v-model="form.status"></el-checkbox>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div :class="{'col-lg-6': category.cover}" class="col-12">
                        <div v-if="category.cover">
                            <h6 class="text-center border-bottom pb-1">کاور دسته بندی</h6>
                            <div class="border p-1 rounded">
                                <div class="text-end mb-2">
                                    <el-button size="mini"
                                               type="danger"
                                               @click="deleteCover">
                                        <el-icon name="delete"></el-icon>
                                        حذف کاور
                                    </el-button>
                                </div>
                                <img :src="category.cover" alt="Cover" class="w-100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border-top pt-2 mt-5">
                        <el-button :disabled="form.processing"
                                   native-type="submit"
                                   size="mini"
                                   type="primary">
                            <el-icon name="edit"></el-icon>
                            ویرایش
                        </el-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {router, useForm} from "@inertiajs/vue2";

export default {
    name: "EditCategory",

    data: function () {
        return {
            form: useForm({
                name: this.category.name,
                name_en: this.category.name_en,
                parent_id: this.category.parent_id ?? undefined,
                cover: undefined,
                status: this.category.status ? true : false,
                _method: 'put'
            }),
        }
    },

    props: {
        categories: Array,
        category: Object,
    },

    methods: {
        submit() {
            if (this.form.defaults(''))
                this.form
                    .transform(i => ({
                        ...i,
                        status: i.status ? '1' : '',
                        parent_id: i.parent_id ? i.parent_id : null,
                        cover: i.cover ? i.cover : null,
                    }))
                    .post(this.$route('categories.update', this.category.id), {
                        onSuccess: () => {
                            this.$notify({
                                title: 'بروزرسانی دسته بندی',
                                type: 'success',
                                message: 'بروزرسانی دسته بندی',
                            });
                        },
                        onError: () => {
                            this.$notify({
                                title: 'بروزرسانی دسته بندی',
                                type: 'error',
                                message: 'بروزرسانی دسته بندی با خطا مواجه شد'
                            });
                        }
                    });
        },
        deleteCover() {
            this.$swal({
                title: 'حذف کاور دسته بندی',
                text: 'آیا از حذف کاور دسته بندی اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                cancelButtonText: 'انصراف',
                confirmButtonText: 'تایید',
            }).then((ok) => {
                if (ok.isConfirmed) {
                    router.delete(this.$route('categories.delete-cover', this.category.id), {
                        onSuccess: () => {
                            this.$notify({
                                title: 'حذف کاور',
                                type: 'success',
                                message: 'کاور دسته بندی حذف شد',
                            });
                        },
                        onError: () => {
                            this.$notify({
                                title: 'حذف کاور',
                                type: 'error',
                                message: 'حذف کاور با خطا مواجه شد',
                            });
                        }
                    });
                }
            });
        },
    }
}
</script>

<style scoped>

</style>
