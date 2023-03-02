<template>
    <div v-loading="loading" class="container">

        <div class="row border-bottom">
            <h2 class="col-12 text-muted">ویرایش محصول</h2>
        </div>

        <div class="row mt-2">
            <form v-loading="loading" class="col-12 col-md-8 col-lg-6 panel" @submit.prevent="submitForm">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <label class="form-label" for="title">نام فارسی محصول</label>
                    </div>
                    <div class="col-12 col-lg-9">
                        <input id="title" v-model="product.title" :class="{'is-invalid': !!validation.title}"
                               class="form-control" placeholder="نام محصول">
                        <div v-if="validation.title" class="invalid-feedback">
                            {{ validation.title[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-lg-3">
                        <label class="form-label" for="title_en">نام انگلیسی محصول</label>
                    </div>
                    <div class="col-12 col-lg-9">
                        <input id="title_en" v-model="product.title_en"
                               :class="{'is-invalid': !!validation.title_en}"
                               class="form-control" placeholder="نام انگلیسی محصول"></input>
                        <div v-if="validation.title_en" class="invalid-feedback">
                            {{ validation.title_en[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="weight">وزن(گرم)</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input id="weight" v-model="product.weight" :class="{'is-invalid': !!validation.weight}"
                               class="form-control" placeholder="وزن" type="number">
                        <div v-if="validation.weight" class="invalid-feedback">
                            {{ validation.weight[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="cover">کاور</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input id="cover" ref="cover" :class="{'is-invalid': !!validation.cover}"
                               class="form-control" placeholder="کاور" type="file">
                        <div v-if="validation.cover" class="invalid-feedback">
                            {{ validation.cover[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="description">توضیحات</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <textarea id="description" v-model="product.description"
                                  :class="{'is-invalid': !!validation.description}" class="form-control"></textarea>
                        <div v-if="validation.description" class="invalid-feedback">
                            {{ validation.description[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="amount">قیمت(تومان)</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input id="amount" v-model="product.amount" :class="{'is-invalid': !!validation.amount}"
                               class="form-control" placeholder="قیمت" type="number">
                        <div v-if="validation.amount" class="invalid-feedback">
                            {{ validation.amount[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="quantity">موجودی</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input id="quantity" v-model="product.quantity" :class="{'is-invalid': !!validation.quantity}"
                               class="form-control" placeholder="موجودی" type="number">
                        <div v-if="validation.quantity" class="invalid-feedback">
                            {{ validation.quantity[0] }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <label class="form-label">
                            فعال
                            <input id="active" v-model="product.status" class="form-check-input ms-2" type="checkbox">
                        </label>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="brand">برند</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <el-select v-model="product.brand_id">
                            <el-option :value="null" label="بدون برند"></el-option>
                            <el-option v-for="brand in brands" :key="brand.id" :label="brand.name"
                                       :value="brand.id"></el-option>
                        </el-select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 offset-lg-3">
                        <button class="btn btn-success" type="submit">بروز رسانی</button>
                        <button class="btn btn-danger" type="button" @click="submitDelete">حذف</button>

                    </div>
                </div>
            </form>
            <img v-if="product.cover" :src="product.cover" class="col-12 col-md-4 col-lg-6 h-75 mt-4 mt-md-0">

        </div>
        <div class="row mt-3">
            <div class="col-12 col-md-8 col-lg-6 panel">
                <div class="border-bottom">
                    <h5 class="text-muted">دسته بندی محصول</h5>
                </div>

                <div class="row mt-4">
                    <div class="col-12 col-lg-3">
                        <label>دسته بندی ها</label>
                    </div>
                    <div class="col-12 col-lg-9">
                        <el-select v-model="product.categories"
                                   dir="ltr"
                                   filterable
                                   multiple>
                            <el-option v-for="category in categories"
                                       :key="category.id"
                                       :label="category.name"
                                       :value="category.id"></el-option>
                        </el-select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <el-button size="mini"
                                   type="success" @click="attachCategories">بروزرسانی
                        </el-button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="panel">
                    <div class="border-bottom">
                        <h5 class="text-muted">تنوع ها</h5>
                    </div>

                    <div class="d-flex flex-column gap-3 mt-2">
                        <div v-for="productAttribute in product.product_attributes" :key="productAttribute.id"
                             class="border p-3 rounded row">
                            <div v-for="value in productAttribute.attribute_values" :key="value.id" class="col-12">
                                <span>{{ value.attribute.name }} : {{ value.value }}</span>
                            </div>
                            <div class="row mt-2 border-top">
                                <div class="col-12 col-lg-6 mt-2">
                                    <label class="form-label">موجودی</label>
                                    <input v-model="productAttribute.quantity" class="form-control"
                                           placeholder="موجودی"></input>
                                </div>
                                <div class="col-12 col-lg-6 mt-2">
                                    <label class="form-label">قیمت</label>
                                    <input v-model="productAttribute.amount" class="form-control"
                                           placeholder="قیمت به تومان"></input>
                                </div>
                                <div class="col-12 col-lg-6 mt-2">
                                    <label class="form-label">وزن(گرم)</label>
                                    <input v-model="productAttribute.weight" class="form-control"
                                           placeholder="گرم"></input>
                                </div>
                                <div class="col-12 text-end mt-2">
                                    <el-button size="mini" type="success"
                                               @click="updateProductAttribute(productAttribute)">بروزرسانی
                                    </el-button>
                                    <el-button size="mini" type="danger"
                                               @click="deleteProductAttribute(productAttribute)">حذف
                                    </el-button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label" for="selectable_type">افزودن تنوع</label>
                        </div>
                        <div class="col-12 row">
                            <div v-for="attribute in attributes" :key="attribute.id" class="col-6 col-lg-4">
                                <el-select v-model="attribute.selected_attribute_value" :placeholder="attribute.name">
                                    <el-option v-for="value in attribute.values"
                                               :key="value.id"
                                               :label="value.value"
                                               :value="value.id"></el-option>
                                </el-select>
                            </div>
                            <div class="col-12 mt-2">
                                <label class="form-label">موجودی</label>
                                <input v-model="product_attribute.quantity" class="form-control"
                                       placeholder="تعداد را به عدد وارد کنید"
                                       type="number"></input>
                            </div>
                            <div class="col-12 mt-2">
                                <label class="form-label">قیمت</label>
                                <input v-model="product_attribute.amount" class="form-control"
                                       placeholder="رقم به تومان"
                                       type="number"></input>
                            </div>
                            <div class="col-12 mt-2">
                                <label class="form-label">وزن (گرم)</label>
                                <input v-model="product_attribute.weight" class="form-control"
                                       placeholder="گرم"
                                       type="number"></input>
                            </div>
                            <div class="col-12 mt-2">
                                <el-button type="success" @click="submitProductAttribute">افزودن</el-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "EditProduct",
    data: () => ({
        product: store.state.product,
        attributes: store.state.attributes,
        categories: store.state.categories,
        brands: store.state.brands,
        product_attribute: {
            quantity: null,
            amount: null,
            weight: null,
        },
        validation: {},
        cover: {
            preview: null,
        },
        loading: false,
    }),

    methods: {
        async submitDelete() {
            this.loading = true;

            this.$swal({
                title: 'آیا از حذف این محصول مطمئن هستید',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'انصراف',
                confirmButtonText: 'حذف',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        await axios.delete(`/api/admin/products/${this.product.id}`);
                        this.loading = false;
                        this.$router.push({
                            name: 'products',
                        });
                    } catch (e) {

                    }
                    this.loading = false;
                } else {
                    this.loading = false;
                }
            }).catch((reason) => {
                this.loading = false;
            })
        },
        async submitForm() {
            this.loading = true;

            const product_id = this.product.id;
            let formData = new FormData();

            if (this.$refs.cover.files && this.$refs.cover.files.length > 0) {
                formData.append('cover', this.$refs.cover.files[0]);
            }

            formData.append('title', this.product.title);
            formData.append('title_en', this.product.title_en);
            formData.append('weight', this.product.weight);
            if (this.product.description) {
                formData.append('description', this.product.description);
            }
            formData.append('amount', this.product.amount);

            formData.append('quantity', this.product.quantity);
            formData.append('status', this.product.status);

            if (this.product.brand_id) {
                formData.append('brand_id', this.product.brand_id);
            }
            try {
                let result = await axios.post(`/api/admin/products/${product_id}`, formData, {
                    params: {
                        _method: 'patch'
                    }
                });
                if (typeof result.data.active == 'string') {
                    if (result.data.active == '0') {
                        result.data.active = 0;
                    } else {
                        result.data.active = 1;
                    }
                }
                this.product = result.data;

                this.$swal({
                    title: 'بروزرسانی محصول',
                    text: 'محصول بروزرسانی شد',
                    icon: 'success',
                });
            } catch (e) {

            }
            this.loading = false;
        },
        async submitProductAttribute() {
            this.loading = true;
            let selectedValues = this.attributes.filter((item) => item.selected_attribute_value != null).map(i => i.selected_attribute_value);

            try {
                let result = await axios.post('/api/admin/products/' + this.product.id + '/product_attribute', {
                    quantity: this.product_attribute.quantity,
                    amount: this.product_attribute.amount,
                    weight: this.product_attribute.weight,
                    values: selectedValues,
                });
                this.$swal({
                    title: 'اضافه شد',
                    icon: 'success',
                });

                this.product.product_attributes.unshift(result.data);

            } catch (e) {
                if (e.response.status == 422) {
                } else {
                    this.$swal({
                        title: 'مشکلی پیش آمد'
                    });
                }
            }
            this.loading = false;

        },
        async deleteProductAttribute(productAttribute) {
            this.$swal({
                title: 'آیا از حذف این تنوع مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                cancelButtonText: 'انصراف',
                confirmButtonText: 'تایید',

            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/admin/productAttributes/' + productAttribute.id)
                        .then((result) => {
                            this.$swal({
                                title: 'حذف شد',
                                icon: 'success',
                            });
                            this.product.product_attributes = this.product.product_attributes.filter((item) => {
                                return item.id != productAttribute.id;
                            });
                        })
                        .catch((reason) => {
                            this.$swal({
                                title: 'مشکلی پیش آمد'
                            });
                        });
                }
            })
        },
        async updateProductAttribute(productAttribute) {
            this.$swal({
                title: 'آیا از بروزرسانی این تنوع اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                cancelButtonText: 'انصراف',
                confirmButtonText: 'تایید',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.patch('/api/admin/productAttributes/' + productAttribute.id, {
                        quantity: productAttribute.quantity,
                        amount: productAttribute.amount,
                        weight: productAttribute.weight
                    })
                        .then((result) => {
                            this.$swal({
                                title: 'بروزرسانی انجام شد',
                                icon: 'success'
                            });
                        })
                        .catch((reason) => {
                            this.$swal({
                                title: 'مشکلی پیش آمد',
                            });
                        });
                }
            })
        },
        async attachCategories() {
            this.$swal({
                title: 'آیا از بروزرسانی دسته بندی محصول اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                cancelButtonText: 'انصراف',
                confirmButtonText: 'تایید',

            }).then((answer) => {
                if (answer.isConfirmed) {
                    axios.post('/api/admin/products/' + this.product.id + '/categories', {
                        ids: this.product.categories.map(i => {
                            return (typeof i == 'object' ? i.id : i)
                        })
                    })
                        .then((result) => {
                            this.$swal({
                                title: 'بروزرسانی انجام شد',
                                icon: 'success',
                            });
                        }).catch((reason) => {

                        this.$swal({
                            title: 'مشکلی پیش آمد',
                        });
                    });
                }
            })
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get(`/api/admin/products/${to.params.id}`, {
                params: {
                    withCategories: 1,
                }
            });
            let attributes = await axios.get('/api/admin/attributes');
            let categories = await axios.get('/api/admin/categories');
            let brands = await axios.get('/api/brands');
            store.state.brands = brands.data;
            store.state.attributes = attributes.data.map(item => ({...item, selected_attribute_value: null}));
            let products = result.data;
            products.categories = products.categories.map(i => i.id);
            store.state.product = products;
            store.state.categories = categories.data;

            next();
        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمد',
                });
            });
        }
    }
}
</script>

<style scoped>

</style>
