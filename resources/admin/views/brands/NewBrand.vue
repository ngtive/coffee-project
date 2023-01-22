<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-12 text-muted">افزودن برند</h2>
        </div>

        <div class="row mt-4">
            <form class="col-12 col-md-10 col-lg-9 mt-4 panel" @submit.prevent="submit">
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
                    <div class="col-12 offset-lg-3">
                        <button class="btn btn-primary" type="submit">ثبت</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</template>

<script>
export default {
    name: "NewBrand",
    data: function () {
        return {
            brand: {
                name: null,
                name_en: null,
                status: true,
                description: null,
            },
            validations: {},
        }
    },
    methods: {
        async submit() {
            let form = new FormData();
            form.set('name', this.brand.name);
            form.set('name_en', this.brand.name_en);
            form.set('status', this.brand.status);
            form.set('description', this.brand.description);

            if (this.$refs.logo.files.length > 0) {
                form.set('logo', this.$refs.logo.files[0]);
            }

            try {
                let result = await axios.post('/api/admin/brands', form);
                this.$router.push({
                    name: 'brands'
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
}
</script>

<style scoped>

</style>
