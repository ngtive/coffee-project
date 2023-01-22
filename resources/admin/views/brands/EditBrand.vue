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
            <form class="col-12 col-md-10 col-lg-9 panel" @submit.prevent="update">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <label for="name" class="form-label">نام فارسی برند</label>
                    </div>
                    <div class="col-12 col-lg-9">
                        <input class="form-control"
                               id="name"
                               :class="{'is-invalid': validations.name}"
                               type="text"
                               v-model="brand.name">
                        <div class="invalid-feedback" v-if="validations.name">
                            {{ validations.name[0] }}
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-lg-3">
                        <label for="name_en" class="form-label">نام انگلیسی برند</label>
                    </div>

                    <div class="col-12 col-lg-9">
                        <input class="form-control"
                               id="name_en"
                               :class="{'is-invalid': validations.name_en}"
                               type="text"
                               v-model="brand.name_en">
                        <div class="invalid-feedback" v-if="validations.name_en">
                            {{ validations.name_en[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-lg-3">
                        <label for="logo" class="form-label">لگوی برند</label>
                    </div>
                    <div class="col-12 col-lg-9">
                        <input class="form-control"
                               id="logo"
                               :class="{'is-invalid': validations.logo}"
                               type="file"
                               ref="logo">
                        <div class="invalid-feedback" v-if="validations.logo">
                            {{ validations.logo[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-lg-3">
                        <label for="description">
                            توضیحات مورد نمایش
                        </label>
                    </div>
                    <div class="col-12 col-lg-9">
                        <textarea class="form-control"
                                  id="description"
                                  :class="{'is-invalid': validations.description}"
                                  v-model="brand.description"></textarea>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 offset-lg-3">
                        <label class="form-label" for="status">وضعیت نمایش
                            <input class="form-check-inline ms-2"
                                   type="checkbox"
                                   id="status"
                                   v-model="brand.status">
                        </label>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">ویرایش</button>
                    </div>
                </div>
            </form>
            <div class="col-12">
                <img :src="brand.logo" alt="brand logo" width="500" height="500">
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

            form.set('status', this.brand.status);
            form.set('description', this.brand.description);


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
        deleteBrand() {},
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/brands/' + to.params.id);
            store.state.brand = result.data;
            next();

        } catch (e) {
            next( vm => {
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
