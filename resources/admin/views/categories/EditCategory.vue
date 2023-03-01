<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-12 text-muted">ویرایش دسته بندی</h2>
        </div>
        <div class="row mt-2">
            <form class="col-12 panel" @submit.prevent="submit">
                <!--                <div class="mb-5">-->
                <!--                    <h6 class="text-muted border-bottom mb-3 pb-2">-->
                <!--                        تعداد محصولات وصل شده به دسته بندی: {{ category.products_count }}-->
                <!--                    </h6>-->
                <!--                    <div class="border-bottom pb-2">-->
                <!--                        <div class="row">-->
                <!--                            <div class="col-6 col-lg-2">-->
                <!--                                دسته بندی های زیر مجموعه-->
                <!--                            </div>-->
                <!--                            <div class="col-6 col-lg-3 text-start">-->
                <!--                                <el-tag size="mini" type="info" v-for="child in category.children" :key="child.id" @click="$router.push({name: 'edit-category', params: {id: child.id}})">-->
                <!--                                    {{ child.name }}-->
                <!--                                </el-tag>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                    <div class="border-bottom pb-2 ">-->
                <!--                        <h6 class="text-muted">دسته بندی پدر</h6>-->
                <!--                    </div>-->
                <!--                </div>-->
                <div class="row">
                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label" for="name">نام فارسی</label>
                        <el-input v-model="category.name"
                                  placeholder="نام فارسی"
                                  size="mini" type="text"></el-input>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label" for="name_en">نام دسته بندی (انگلیسی)</label>
                        <el-input v-model="category.name_en"
                                  placeholder="نام انگلیسی"
                                  size="mini"
                                  type="text"></el-input>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label" for="cover">کاور دسته بندی</label>
                        <input ref="cover"
                               type="file"
                               class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label d-block">زیر مجموعه</label>
                        <el-select v-model="category.parent_id"
                                   placeholder="زیر مجموعه"
                                   size="mini">
                            <el-option
                                v-for="c in categories"
                                :key="c.id"
                                :disabled="c.id == category.id"
                                :label="c.name"
                                :value="c.id"></el-option>
                        </el-select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label">وضعیت نمایش
                            <el-checkbox
                                v-model="category.status"></el-checkbox>
                        </label>
                    </div>
                    <div class="col-12 col-md-9">

                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary">ویرایش</button>
                    </div>
                    <div v-if="category.cover" class="col-6">
                        <h6 class="text-center border-bottom pb-1">کاور دسته بندی</h6>
                        <div class="border p-1 rounded">
                            <img :src="category.cover" alt="Cover" class="w-100">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "EditCategory",
    data: function () {
        return {
            category: store.state.category,
            categories: store.state.categories,
            validations: {}
        }
    },

    methods: {
        async submit() {
            let form = new FormData();
            form.set('name', this.category.name);
            form.set('name_en', this.category.name_en);
            if (this.$refs.cover.files.length > 0) {
                form.set('cover', this.$refs.cover.files[0]);
            }

            form.set('parent_id', this.category.parent_id);
            form.set('status', (this.category.status == 1 || this.category.status == 'true'));

            form.set('_method', 'patch');
            try {

                let result = await axios.post('/api/admin/categories/' + this.category.id, form);

                this.$swal({
                    title: 'تغییرات با موفقیت ذخیره شد',
                    icon: 'success',
                });
            } catch (e) {
                if (e.response.status == 422) {
                    this.validations = e.response.data.errors;
                } else {
                    this.$swal({
                        title: 'مشکلی پیش آمده',
                    });
                }
            }
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let category = await axios.get('/api/admin/categories/' + to.params.id);
            category = category.data;
            category.status = (category.status == 1 || category.status == true);
            store.state.category = category;

            let categories = await axios.get('/api/admin/categories');
            categories = categories.data;

            function recursiveAdd(category) {
                category.label = category.name;
                category.value = category.id;

                if (category.children) {
                    category.children.forEach(i => recursiveAdd(i));
                    return;
                }
            }

            categories.forEach(i => recursiveAdd(i));
            store.state.categories = categories;

            next();
        } catch (e) {
            console.error(e);
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمد'
                });
            })
        }
    }
}
</script>

<style scoped>

</style>
