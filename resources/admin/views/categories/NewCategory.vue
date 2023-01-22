<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-12 text-muted">افزودن دسته بندی</h2>
        </div>
        <div class="row mt-2">
            <form class="col-12 col-md-8 panel" @submit.prevent="submitCategory">
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
                            <option value=""></option>
                            <option v-for="c in category.categories" :key="c.id" :value="c.id">
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
                        <button type="submit" class="btn btn-primary">
                            ساختن دسته بندی
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "NewCategory",
    data: function () {
        return {
            category: {
                name: null,
                name_en: null,
                parent_id: null,
                categories: store.state.categories,
                status: true,
            },
            validations: {},
        }
    },

    methods: {
        async submitCategory() {
            let form = new FormData();
            form.set('name', this.category.name);
            form.set('name_en', this.category.name_en);
            form.set('status', this.category.status);

            if (this.category.parent_id) {
                form.set('parent_id', this.category.parent_id);
            }

            if (this.$refs.cover.files.length >= 1) {
                form.set('cover', this.$refs.cover.files[0]);
            }


            try {

                let result = await axios.post('/api/admin/categories', form);
                this.$router.push({
                    name: 'categories',
                });

            } catch (e) {
                if (e.response && e.response.status == 422) {
                    let response = e.response.data;
                    this.validations = response.errors;
                }
            }
        }
    },


    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/categories');
            store.state.categories = result.data;

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
