<template>
    <div class="container" v-loading="loading">
        <div class="row border-bottom">
            <h2 class="col-12 text-muted">افزودن محصول</h2>
        </div>

        <div class="row mt-2 justify-content-around">
            <form class="col-12 panel" @submit.prevent="formSubmit">
                <div class="row">
                    <h6 class="border-bottom pb-1 mb-3">مشخصات کلی محصول</h6>
                    <div class="col-12 col-lg-2 mb-lg-5">
                        <label class="form-label required" for="title">نام فارسی محصول</label>
                    </div>
                    <div class="col-12 col-lg-2 mb-lg-5">
                        <el-input id="title" v-model="product.title"
                                  placeholder="نام محصول (فارسی)" size="mini"></el-input>
                        <div v-if="validations.title" class="text-danger">
                            {{ validations.title[0] }}
                        </div>
                    </div>
                    <div class="col-12 col-lg-2 mb-lg-5">
                        <label class="form-label required" for="title_en">نام انگلیسی محصول</label>
                    </div>
                    <div class="col-12 col-lg-2 mb-lg-5">
                        <el-input id="title_en" v-model="product.title_en"
                                  placeholder="نام محصول(انگلیسی)"
                                  size="mini"></el-input>
                        <div v-if="validations.title_en" class="text-danger">
                            {{ validations.title_en[0] }}
                        </div>
                    </div>
                    <!--                    <div class="col-12 col-lg-2 mb-lg-2">-->
                    <!--                        <label class="form-label" for="weight">وزن (گرم)</label>-->
                    <!--                    </div>-->
                    <!--                    <div class="col-12 col-lg-2 mb-lg-2">-->
                    <!--                        <el-input class="" :class="{'is-invalid': !!validations.weight}"-->
                    <!--                                  id="weight" placeholder="وزن"-->
                    <!--                                  size="mini"-->
                    <!--                                  type="number" v-model="product.weight"></el-input>-->
                    <!--                        <div class="text-danger" v-if="validations.weight">-->
                    <!--                            {{ validations.weight[0] }}-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <div class="col-12 col-lg-3 mb-lg-5 drag-area row">
                        <label class="form-label col-12 col-lg-6 required" for="cover">کاور</label>
                        <div class="col-12 col-lg-6"
                             v-on:click="onClickCover"
                             v-on:drop.prevent="onDrop"
                             v-on:dragover.prevent="onDrag"
                             v-on:dragleave.prevent="onDragleave">
                            <div :class="{
                                 'p-1': cover.preview,
                                 'p-5 px-2': !cover.preview,
                            }"
                                 class="border rounded d-flex justify-content-center align-items-center"
                                 style="border-style: dashed !important; cursor: pointer">
                                <span v-if="!cover.preview" class="mx-2" v-html="dragText"></span>
                                <img v-if="cover.preview" :src="cover.preview" :style="{opacity: cover.uploading ? 0.3 : 1}"
                                     alt="Cover preview"
                                     style="width: 100%; height: 100%">
                            </div>
                            <input ref="cover"
                                   hidden
                                   type="file"
                                   v-on:change="onDrop">
                        </div>
                    </div>
                    <div class="col-12 col-lg-2 mb-lg-3">
                        <label class="form-label required">دسته بندی<br>
                            <router-link class="border-bottom text-primary" tag="span" to="/categories/new">(ایجاد دسته
                                بندی جدید)
                            </router-link>
                        </label>
                    </div>
                    <div class="col-12 col-lg-2 mb-lg-3">
                        <el-select v-model="product.category" size="mini"></el-select>
                    </div>
                    <div class="col-12 col-lg-2 mb-lg-3">
                        <label class="form-label">برند
                            <br>
                            <router-link class="text-primary border-bottom" tag="span" to="/brands/new">(ایجاد برند
                                جدید)
                            </router-link>
                        </label>
                    </div>
                    <div class="col-12 col-lg-2 mb-lg-3">
                        <el-select v-model="product.brand" size="mini"></el-select>
                    </div>
                    <!--                    <div class="col-12 col-lg-2 mb-lg-3">-->
                    <!--                        <label class="form-label">-->
                    <!--                            <span>قیمت (درصورت بدون تنوع بودن)</span>-->
                    <!--                        </label>-->
                    <!--                    </div>-->
                    <!--                    <div class="col-12 col-lg-2 mb-lg-3">-->
                    <!--                        <el-input-number v-model="product.price" size="mini" :step="10000" :min="10000"-->
                    <!--                                         placeholder="تومان"></el-input-number>-->
                    <!--                    </div>-->
                    <h6 class="border-bottom pb-1">ایجاد تنوع</h6>
                    <span class="text-muted mb-5">درصورتی که محصول بدون تنوع و از نظر مشخصات تک به فروش می رسد این قسمت را تکمیل نکنید</span>

                    <!--                    <div class=""-->
                    <router-link class="text-primary border-bottom text-decoration-none mb-3" tag="a" to="/attributes">
                        (درخواست ایجاد مشخصه تنوع جدید)
                    </router-link>
                    <form class="col-12 col-lg-7" @submit.prevent="addProductAttribute(product.product_attribute)">

                        <div v-loading="product.product_attribute.loading" class="border  rounded p-2 mt-2"
                             style="border-style: dashed !important;">
                            <div class="row">
                                <div v-for="attribute in attributes" :key="attribute.id" class="col-12 mb-3 col-lg-4">
                                    <label class="form-label">{{ attribute.name }}

                                        <span v-if="attribute.selected_value && attribute.is_color"
                                              :style="{backgroundColor: attribute.values.filter(i => i.id == attribute.selected_value)[0].value}"
                                              class="d-inline px-2 mx-2">
                                        </span>
                                    </label>
                                    <el-select v-model="attribute.selected_value" :placeholder="'انتخاب ' + attribute.name"
                                               size="mini">
                                        <el-option :value="undefined"></el-option>
                                        <el-option v-for="value in attribute.values"
                                                   :key="value.id"
                                                   :label="value.value"
                                                   :value="value.id">
                                            <span v-if="attribute.is_color"
                                                  :style="{backgroundColor: value.value}"
                                                  class="border p-2 mt-2"
                                                  style="float: right"></span>
                                            <span v-if="attribute.is_color"
                                                  style="float: left">{{ value.value }}</span>
                                        </el-option>
                                    </el-select>
                                </div>
                                <div class="col-12 mb-3 col-lg-4 row">
                                    <label class="form-label required">وزن بسته بندی تنوع</label>
                                    <el-input-number v-model="product.product_attribute.weight"
                                                     :controls="false"

                                                     placeholder="وزن بسته بندی به گرم"
                                                     size="mini"
                                                     style="width: 100%"></el-input-number>
                                    <span v-if="product.product_attribute.validation.weight" class="text-danger">
                                {{ product.product_attribute.validation.weight }}
                            </span>
                                </div>
                                <div class="col-12 mb-3 col-lg-6 row">
                                    <label class="form-label required">قیمت تنوع</label>
                                    <el-input-number v-model="product.product_attribute.amount"
                                                     :controls="false"
                                                     placeholder="قیمت به تومان"
                                                     size="mini"
                                                     style="width: 100%"></el-input-number>
                                    <span v-if="product.product_attribute.validation.amount" class="text-danger">
                                {{ product.product_attribute.validation.amount }}
                            </span>
                                </div>
                                <div class="col12 mb-3 col-lg-3 row">
                                    <label class="form-label required">موجودی انبار</label>
                                    <el-input-number
                                        v-model="product.product_attribute.quantity"
                                        :controls="false"
                                        placeholder="تعداد"
                                        size="mini"
                                        style="width: 100%"></el-input-number>
                                </div>
                                <div class="col-12 mt-3 d-flex justify-content-center align-items-center">
                                    <el-button class="w-100" native-type="submit" size="mini" type="info">
                                        <el-icon name="plus"></el-icon>
                                        افزودن تنوع
                                    </el-button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="col-12 col-lg-5">
                        <div v-if="product.product_attributes.length > 0" class="border rounded px-3 py-2">
                            <div class="row">
                                <div class="col-12 text-center border-bottom mb-2">
                                    <span>تنوع های اضافه شده</span>
                                </div>
                                <div v-for="productAttribute in product.product_attributes" :key="productAttribute.id"
                                     class="col-12 col-lg-6 mb-2">
                                    <div class="border rounded p-2 d-flex flex-column">
                                        <div class="row">
                                            <div class="col-12 col-lg-7 mb-2">
                                                <div v-for="value in productAttribute.attribute_values">
                                                    <label class="form-label mb-0 p-0 ir-sns" style="font-size: 0.6rem">{{
                                                            value.attribute.name
                                                        }}</label>
                                                    <div class="row">
                                                        <span v-if="value.attribute.is_color"
                                                              class="col-6 ">{{ value.value }}</span>
                                                        <span v-if="value.attribute.is_color"
                                                              :style="{backgroundColor: value.value, marginRight: '.2rem', width: '10px', height: '10px'}"
                                                              class="col-6 d-inline-block p-0 m-0 mx-1"></span>
                                                        <span v-else>
                                                        {{ value.value }}
                                                    </span>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-5 mb-2 text-end">
                                                <el-button class="p-2"
                                                           size="mini"
                                                           type="danger"
                                                           @click="deleteProductAttribute(productAttribute)"><em class="fa fa-times"></em></el-button>
                                            </div>

                                            <div class="col-12">
                                                <form @submit.prevent="updateProductAttribute(productAttribute)">
                                                    <div class="col-12">
                                                        <label class="form-label required mb-0">وزن بسته بندی</label>
                                                        <el-input-number v-model="productAttribute.weight"
                                                                         controls-position="right"
                                                                         size="mini"></el-input-number>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label required mb-0">قیمت تنوع</label>
                                                        <el-input-number v-model="productAttribute.amount"
                                                                         controls-position="right"
                                                                         size="mini"></el-input-number>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label required mb-0">موجودی انبار</label>
                                                        <el-input-number v-model="productAttribute.quantity"
                                                                         controls-position="right"
                                                                         size="mini"></el-input-number>
                                                    </div>
                                                    <div class="col-12 mt-2">
                                                        <el-button native-type="submit" size="mini" type="info">
                                                            بروزرسانی
                                                        </el-button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <el-button native-type="submit" type="primary">ثبت</el-button>
                    </div>
                </div>
            </form>
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
            price: undefined,
            description: null,
            status: false,
            weight: null,
            quantity: null,
            category: null,
            brand: null,
            product_attributes: [],
            product_attribute: {
                weight: undefined,
                amount: undefined,
                quantity: undefined,
                values: [],
                validation: {},
                loading: false,
            },
        },
        cover: {
            preview: null,
            file: null,
            dropped: false,
            uploading: false,
            uploadedResult: null,
        },
        attributes: store.state.attributes,
        categories: store.state.categories,
        brands: store.state.brands,


        attribute: {},


        validations: {},
        loading: false,

        dragText: '<em class="fa fa-plus text-muted"></em>',
        dragging: false,
    }),

    methods: {
        onClickCover(event) {
            this.$refs.cover.click();
        },
        async onDrop(event) {
            let files;
            if (event.type == 'change' && event.target) {
                files = event.target.files;
            } else if (event.dataTransfer) {
                files = event.dataTransfer.files;
            }
            if (files) {
                this.dragText = '<em class="fa fa-plus text-muted"></em>';
                this.dragging == false;
                if (files.length > 1) {
                    this.$notify({
                        message: 'برای عکس کاور محصول فقط می توان از یک عکس استفاده کرد ',
                        type: 'error'
                    });
                    return;
                }
                this.cover.dropped = true;
                this.cover.file = files[0];

                const fileReader = new FileReader();
                fileReader.readAsDataURL(this.cover.file);
                fileReader.addEventListener('load', function () {
                    this.cover.preview = fileReader.result;
                }.bind(this));
                this.cover.uploading = true;

                try {
                    let form = new FormData();
                    form.set('cover', this.cover.file);
                    let result = await axios.post('/api/admin/products/cover', form);
                    this.cover.uploadedResult = result.data.cover;
                    this.$notify({
                        message: 'آپلود فایل کاور به اتمام رسید',
                        type: "success",
                    });
                    this.cover.uploading = false;
                } catch (e) {
                    this.$notify({
                        title: 'مشکلی پیش آمد',
                        message: 'در آپلود فایل کاور مشکلی پیش آمد دوباره اپلود کنید',
                        type: 'error'
                    });
                }
            }
        },
        onDrag(event) {
            if (!this.dragging) {
                this.dragText = '<span class="text-muted">رها کنید</span>';
            }
            this.dragging = true;
        },
        onDragleave(event) {
            this.dragText = '<em class="fa fa-plus text-muted"></em>';
            this.dragging = false;
        },


        coverChange() {
            let cover = this.$refs.cover;
            let file = cover.files[0];
            if (file) {
                this.cover.preview = URL.createObjectURL(file);
            }
        },
        async formSubmit() {
            this.loading = true;
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
                this.loading = false;

                this.$router.push({
                    name: 'products'
                });
            } catch (e) {

                if (e.response.status == 422) {
                    this.validations = e.response.data.errors;
                } else {
                    this.$swal({
                        title: 'مشکلی پیش آمد',
                        icon: 'error'
                    });
                }

                this.loading = false;
            }
        },

        addProductAttribute(product_attribute) {
            let attributesToObject = {};
            product_attribute.values = [];

            this.attributes.forEach(i => {
                if (i.selected_value) {
                    product_attribute.values.push(i.selected_value);
                    console.log(i.selected_value);
                }
            });
            if (!product_attribute.weight || !product_attribute.amount || !product_attribute.quantity) {
                this.$notify({
                    message: 'مقادیر قیمت و وزن بسته بندی و موجودی انبار برای ایجاد تنوع جدید الزامی است',
                    type: 'warning'
                });
                this.product.product_attribute.validation.weight = 'وارد کردن این فیلد الزامی است';
                this.product.product_attribute.validation.amount = 'وارد کردن این فیلد الزامی است';
                return;
            }

            this.product.product_attribute.validation = {};
            this.product.product_attribute.loading = true;

            axios.post('/api/admin/product_attributes', product_attribute)
                .then((result) => {
                    this.product.product_attributes.push(result.data);
                    this.product.product_attribute.loading = false;
                })
                .catch((reason) => {
                    this.$notify({
                        title: 'مشکلی پیش آمده',
                        type: 'error'
                    });
                    this.product.product_attribute.loading = false;
                });
        },
        updateProductAttribute(product_attribute) {
            this.$swal({
                title: 'بروزرسانی',
                message: 'آیا از بروزرسانی اطمینان دارید ؟',
                icon: 'info',
                showCancelButton: true,
                cancelButtonText: 'انصراف',
                showConfirmButton: true,
                confirmButtonText: 'بله'
            }).then((confirm) => {
                if (confirm) {
                    axios.patch('/api/admin/productAttributes/' + product_attribute.id, product_attribute)
                        .then((result) => {
                            this.$notify({
                                title: 'بروزرسانی',
                                type: 'success',
                                message: 'بروزرسانی با موفقیت انجام شد',
                            });

                        }).catch((reason) => {
                        console.error(reason);
                        this.$notify({
                            title: 'مشکل در بروزرسانی تنوع',
                            type: 'error',
                            message: 'کد خطا ' + reason.response.status,
                        });
                    });
                }
            });
        },
        deleteProductAttribute(product_attribute) {
            this.$swal({
                title: 'آیا از حذف تنوع اطمینان دارید',
                icon: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                cancelButtonText: 'انصراف',
                confirmButtonText: 'تایید'
            })
                .then((isConfirmed) => {
                    axios.delete('/api/admin/productAttributes/' + product_attribute.id)
                        .then((result) => {
                            this.$notify({
                                title: 'حذف تنوع',
                                message: 'تنوع با موفقیت حذف شد',
                                type: 'success'
                            });
                            this.product.product_attributes =
                                this.product.product_attributes.filter(i => i.id != product_attribute.id)
                        })
                        .catch((reason) => {
                            this.$notify({
                                title: 'حذف تنوع',
                                message: 'مشکلی در حذف تنوع پیش آمد، کد خطا ' + reason.response.status
                            });
                        })
                })
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/attributes');
            let categories = await axios.get('/api/admin/categories');
            let brands = await axios.get('/api/admin/brands');
            store.state.attributes = result.data.map(i => ({...i, selected_value: undefined}));
            store.state.categories = categories.data.map(i => ({...i, selected: false}));
            store.state.brands = brands.data;
            next();
        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمده',
                });
            });
        }
    }
}
</script>

<style>
</style>
