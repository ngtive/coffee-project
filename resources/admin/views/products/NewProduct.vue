<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-12 text-muted">افزودن محصول</h2>
        </div>

        <div class="row mt-2">
            <form class="col-12 col-md-8 col-lg-6 panel" @submit.prevent="formSubmit">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="title">نام فارسی محصول</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-control" :class="{'is-invalid': !!validations.title}" id="title"
                               placeholder="نام محصول (فارسی)" v-model="product.title">
                        <div class="invalid-feedback" v-if="validations.title">
                            {{ validations.title[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="title_en">نام انگلیسی محصول</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-control" :class="{'is-invalid': !!validations.title_en}" id="title_en"
                               placeholder="نام محصول(انگلیسی)"
                               v-model="product.title_en">
                        <div class="invalid-feedback" v-if="validations.title_en">
                            {{ validations.title_en[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="weight">وزن (گرم)</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-control" :class="{'is-invalid': !!validations.weight}"
                               id="weight" placeholder="وزن"
                               type="number" v-model="product.weight">
                        <div class="invalid-feedback" v-if="validations.weight">
                            {{ validations.weight[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="cover">کاور</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-control" :class="{'is-invalid': !!validations.cover}"
                               id="cover" placeholder="کاور"
                               type="file" ref="cover"
                               @change="coverChange">
                        <div class="invalid-feedback" v-if="validations.cover">
                            {{ validations.cover[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="description">توضیحات</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <textarea class="form-control" :class="{'is-invalid': !!validations.description}"
                                  id="description" v-model="product.description"></textarea>
                        <div class="invalid-feedback" v-if="validations.description">
                            {{ validations.description[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="price">قیمت (تومان)</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-control" :class="{'is-invalid': !!validations.price}"
                               type="number" id="price"
                               v-model="product.price" placeholder="قیمت">
                        <div class="invalid-feedback" v-if="validations.price">
                            {{ validations.price[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="quantity">موجودی</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input class="form-control" :class="{'is-invalid': !!validations.quantity}" type="number"
                               id="quantity" v-model="product.quantity" placeholder="موجودی">
                        <div class="invalid-feedback" v-if="validations.quantity">
                            {{ validations.quantity[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3 offset-md-3 form-check">
                        <input class="form-check-input" id="active" type="checkbox" v-model="product.status">
                        <label class="form-check-label" for="active">فعال</label>
                    </div>
                </div>
                <div class="row mt-3">
                    <button class="col-12 col-md-3 offset-md-3 btn btn-success" type="submit">ثبت</button>
                </div>
            </form>
            <img class="col-12 col-md-4 col-lg-6" v-if="cover.preview" :src="cover.preview">

        </div>
    </div>
</template>

<script>
export default {
    name: "NewProduct",
    data: () => ({
        product: {
            title: null,
            title_en: null,
            price: null,
            description: null,
            status: false,
            weight: null,
            quantity: null,
        },
        cover: {
            preview: null,
        },
        validations: {},
    }),

    methods: {
        coverChange() {
            let cover = this.$refs.cover;
            let file = cover.files[0];
            if (file) {
                this.cover.preview = URL.createObjectURL(file);
            }
        },

        async formSubmit() {
            let formData = new FormData();
            if (this.$refs.cover.files.length > 0) {
                formData.append('cover', this.$refs.cover.files[0]);
            }
            if (this.product.title) {
                formData.append('title', this.product.title);
            }
            if (this.product.title_en) {
                formData.append('title_en', this.product.title_en);
            }
            if (this.product.description) {
                formData.append('description', this.product.description);
            }
            if (this.product.weight) {
                formData.append('weight', this.product.weight);
            }
            if (this.product.price) {
                formData.append('amount', this.product.price);
            }
            if (this.product.quantity) {
                formData.append('quantity', this.product.quantity);
            }

            formData.append('status', this.product.status);

            try {
                let result = await axios.post('/api/admin/products', formData);

                this.$router.push({
                    name: 'products'
                });
            } catch (e) {

                if (e.response.status == 422) {
                    this.validations = e.response.data.errors;
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
