<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="text-muted">دسته بندی ها</h2>
        </div>

        <div class="row">
            <div class="col-12 panel mb-3">
                <h6 class="text-muted border-bottom pb-1 mb-4">افزودن دسته بندی جدید</h6>
                <form class="row border rounded p-2 m-1" @submit.prevent="addCategory">
                    <div class="col-12 col-lg-3 mb-3">
                        <label class="form-label required">
                            نام فارسی
                        </label>
                        <el-input v-model="category.name"
                                  placeholder="نام دسته بندی"
                                  size="mini"
                                  type="text"></el-input>
                        <span v-if="category.errors.name"
                              class="text-danger d-block ms-1 mt-1">
                            {{ category.errors.name }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-3 mb-3">
                        <label class="form-label required">نام انگلیسی</label>
                        <el-input v-model="category.name_en" placeholder="نام انگلیسی دسته بندی" size="mini"
                                  type="text"></el-input>
                        <span v-if="category.errors.name_en" class="text-danger d-block ms-1 mt-1">
                            {{ category.errors.name_en }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-3 mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label d-block">دسته بندی پدر</label>
                            </div>
                            <div v-if="category.parent_id" class="col-6 d-flex justify-content-end">
                                <el-button size="mini" type="text" @click="category.parent_id = undefined">
                                    پاک کردن
                                </el-button>
                            </div>
                        </div>
                        <el-select v-model="category.parent_id" class="w-100"
                                   placeholder="انتخاب دسته بندی پدر"
                                   size="mini">
                            <el-option v-for="category in allCategories"
                                       :key="category.id"
                                       :label="category.name"
                                       :value="category.id"></el-option>
                        </el-select>
                        <span v-if="category.errors.parent_id" class="text-danger d-block ms-1 mt-1">
                            {{ category.errors.parent_id }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-3 mb-3">
                        <div class="d-flex">
                            <div class="col-6">
                                <label class="form-label">عکس کاور دسته بندی</label>
                            </div>
                            <!--                            <div class="col-6 text-end">
                                                            <el-button size="mini" type="text" @click="$refs.categoryCover.value = null">
                                                                پاک کردن
                                                            </el-button>
                                                        </div>-->
                        </div>
                        <input class="form-control form-control-file form-control-sm" type="file"
                               ref="categoryCover"
                               @input="category.cover = $event.target.files[0]">
                        <span v-if="category.errors.cover" class="text-danger d-block ms-1 mt-1">
                            {{ category.errors.cover }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-2 mb-3 d-lg-flex justify-content-lg-end flex-lg-column">
                        <label class="form-check-label mb-3">
                            <span class="mb-2">فعال(قابل نمایش)</span>
                            <el-checkbox v-model="category.status" size="mini"></el-checkbox>
                        </label>
                        <div>
                            <el-button native-type="submit"
                                       size="mini"
                                       type="primary">افزودن
                            </el-button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 panel mb-3">
                <category-menu :categories="rootCategories" :on-change="handleSelect" :on-click-delete="clickDelete"
                               :on-click-edit="clickEdit"></category-menu>
            </div>
        </div>
    </div>
</template>

<script>
import CategoryMenu from "../../../components/CategoryMenu.vue";
import {router, useForm} from "@inertiajs/vue2";

export default {
    name: "ListCategory",
    components: {CategoryMenu},

    data: function () {
        return {
            category: useForm({
                name: undefined,
                name_en: undefined,
                parent_id: undefined,
                cover: undefined,
                status: true,
            }),
        }
    },
    props: {
        rootCategories: Array,
        allCategories: Array,
    },

    methods: {
        unSelectAll(categories, selected) {
            categories.forEach(i => {
                if (i) {
                    if (i.id != selected.id) {
                        i.selected = false;
                    }
                    if (i.children.length > 0) {
                        this.unSelectAll(i.children, selected);
                    }
                }
            });
        },
        handleSelect(selectedCategory) {
            this.category.parent_id = selectedCategory.id;
            this.unSelectAll(this.rootCategories, selectedCategory);
        },
        addCategory() {
            this.$swal({
                title: 'افزودن دسته بندی',
                icon: 'warning',
                text: 'آیا از ثبت دسته بندی جدید اطمینان دارید؟',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'تایید',
                cancelButtonText: 'انصراف',
            })
                .then((confirm) => {
                    if (confirm.isConfirmed) {
                        this.category.transform(i => ({
                            ...i, status: i.status ? '1' : '0',
                            parent_id: i.parent_id ?? null,
                        }))
                            .post(this.$route('categories.index'), {
                                onSuccess: (params) => {
                                    this.$notify({
                                        title: 'افزودن دسته بندی',
                                        type: 'success',
                                        message: 'دسته بندی اضافه شد',
                                    });
                                    this.category.reset();
                                },
                            })
                    }
                })
        },
        clickDelete(category) {
            this.$swal({
                title: 'حذف دسته بندی',
                icon: 'warning',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'حذف',
                cancelButtonText: 'انصراف',
                text: 'آیا از حذف دسته بندی مطمئن هستید؟'
            })
                .then((confirm) => {
                    if (confirm.isConfirmed) {
                        router.delete(this.$route('categories.destroy', category.id), {
                            onSuccess: () => {
                                this.$notify({
                                    title: 'حذف دسته بندی',
                                    type: 'success',
                                    message: 'دسته بندی حذف شد',
                                });
                            }
                        });
                    }
                });
        },
        clickEdit(category) {
            router.visit(this.$route('categories.show', category.id));
        }
    }
}
</script>

<style scoped>

</style>
