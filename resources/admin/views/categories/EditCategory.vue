<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-12 text-muted">ویرایش دسته بندی</h2>
        </div>
        <div class="row mt-2">
            <form class="col-12 col-md-8 panel" @submit.prevent="submit">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="name">نام دسته بندی (فارسی)</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-control"
                               :class="{'is-invalid': !!validations.name}"
                               v-model="category.name">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="name_en">نام دسته بندی (انگلیسی)</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-control"
                               v-model="category.name_en"
                               :class="{'is-invalid': !!validations.name_en}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="cover">کاور دسته بندی</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-control"
                               type="file"
                               ref="cover"
                               :class="{'is-invalid': !!validations.cover}">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="parent">زیر مجموعه</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <select v-model="category.parent_id" class="form-control">
                            <option value="" :selected="!category.parent_id"></option>
                            <option v-for="c in categories" :key="c.id" :value="c.id" :selected="category.parent_id == c.id" :disabled="category.id == c.id">
                                {{ c.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label">وضعیت نمایش</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-check"
                               type="checkbox"
                               v-model="category.status">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-3 offset-md-3">
                        <button type="submit" class="btn btn-primary">ویرایش</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt-2">
            <div><img :src="category.cover" alt="Cover" width="500" height="500"></div>
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
            let result = await axios.get('/api/admin/categories/' + to.params.id);
            store.state.category = result.data;

            let categories = await axios.get('/api/admin/categories');
            store.state.categories = categories.data;

            next();
        } catch (e) {
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
