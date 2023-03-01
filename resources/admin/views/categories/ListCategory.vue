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
                    </div>
                    <div class="col-12 col-lg-3 mb-3">
                        <label class="form-label required">نام انگلیسی</label>
                        <el-input v-model="category.name_en" placeholder="نام انگلیسی دسته بندی" size="mini"
                                  type="text"></el-input>
                    </div>
                    <div class="col-12 col-lg-3 mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label d-block">دسته بندی پدر</label>
                            </div>
                            <div v-if="category.parent_id" class="col-6 d-flex justify-content-end">
                                <el-button size="mini" type="text" @click="category.parent = undefined">
                                    پاک کردن
                                </el-button>
                            </div>
                        </div>
                        <el-select v-model="category.parent" class="w-100"
                                   placeholder="انتخاب دسته بندی پدر"
                                   size="mini">
                            <el-option v-for="category in allCategories"
                                       :key="category.id"
                                       :label="category.name"
                                       :value="category.id"></el-option>
                        </el-select>
                    </div>
                    <div class="col-12 col-lg-3 mb-3">
                        <label class="form-label">عکس کاور دسته بندی</label>
                        <input ref="cover" class="form-control form-control-file form-control-sm" type="file">
                    </div>
                    <div class="col-12 col-lg-2 mb-3 d-lg-flex justify-content-lg-end flex-lg-column">
                        <label class="form-check-label mb-3">
                            <span class="mb-2">فعال(قابل نمایش)</span>
                            <el-checkbox v-model="category.status" size="mini"></el-checkbox>
                        </label>
                        <div>
                            <el-button native-type="submit"
                                       size="mini"
                                       type="info">افزودن
                            </el-button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 panel mb-3">
                <category-menu :categories="categories" :on-change="handleSelect" :on-click-delete="clickDelete"
                               :on-click-edit="clickEdit"></category-menu>

            </div>
        </div>
    </div>
</template>

<script>
import CategoryMenu from "../../components/CategoryMenu.vue";

export default {
    name: "ListCategory",
    components: {CategoryMenu},

    data: function () {
        return {
            categories: store.state.categories,
            allCategories: store.state.allCategories,
            category: {
                name: undefined,
                name_en: undefined,
                parent_id: undefined,
                status: true,
            },
            validation: {},
            loading: false,
        }
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
            this.unSelectAll(this.categories, selectedCategory);
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
                        this.loading = true;

                        let category = this.category;
                        let form = new FormData();
                        if (this.$refs.cover.files.length > 0) {
                            form.set('cover', this.$refs.cover.files[0]);
                        }

                        form.set('name', category.name);
                        form.set('name_en', category.name_en);

                        if (category.parent_id) {
                            form.set('parent_id', category.parent_id);
                        }
                        form.set('status', category.status);

                        form.set('root', 1);
                        form.set('withChildren', 1);
                        form.set('orderByChildren', 1);

                        axios.post('/api/admin/categories', form)
                            .then((result) => {
                                this.categories = result.data;
                                this.loading = false;
                            })
                            .catch((reason) => {
                                if (reason.response.status == 422) {
                                    this.validation = reason.response.data.errors;
                                }
                                this.$notify({
                                    title: 'افزودن تنوع',
                                    message: 'مشکلی در افزودن تنوع پیش آمد',
                                    type: 'error'
                                });
                                this.loading = false;
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
                        this.loading = true;
                        axios.delete('/api/admin/categories/' + category.id, {
                            orderByChildren: 1,
                            root: 1,
                            withChildren: 1
                        })
                            .then((result) => {
                                this.loading = false;
                                this.categories = result.data;
                            })
                            .catch((reason) => {
                                this.loading = false;
                                this.$notify({
                                    title: 'مشکلی پیش آمد',
                                    type: 'error',
                                    message: 'کد خطا ' + reason.response.status,
                                });
                            });
                    }
                });
        },
        clickEdit(category) {
            this.$router.push({
                name: 'edit-category',
                params: {
                    id: category.id
                }
            });
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/categories', {
                params: {
                    orderByChildren: 1,
                    root: 1,
                    withChildren: 1
                }
            });
            let allCategories = await axios.get('/api/admin/categories', {
                params: {
                    withChildren: 1,
                }
            });
            store.state.allCategories = allCategories.data.map(item => ({...item, selected: false}));
            store.state.categories = result.data.map(item => ({...item, selected: false}));
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
