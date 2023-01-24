<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-12 text-muted">افزودن تخفیف محصول</h2>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-8 col-lg-7 panel">
                <form @submit.prevent="storeDiscount">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="discount">درصد تخفیف</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input id="discount"
                                   v-model="discount.discount"
                                   :class="{'is-invalid': validation.discount}"
                                   class="form-control"
                                   placeholder="درصد تخفیف به عدد"
                                   type="number"></input>
                            <div v-if="validation.discount" class="invalid-feedback">
                                {{ validation.discount[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="expire">انقضا (ساعت)</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input id="expire"
                                   v-model="discount.expire"
                                   :class="{'is-invalid': validation.expire}"
                                   class="form-control"
                                   placeholder="انقضا به عدد"
                                   type="number"></input>
                            <div v-if="validation.expire" class="invalid-feedback">
                                {{ validation.expire[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="product">محصول</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <el-select id="product"
                                       v-model="discount.product_id">
                                <el-option v-for="product in products"
                                           :key="product.id"
                                           :label="product.title"
                                           :value="product.id"></el-option>
                            </el-select>
                            <div v-if="validation.product_id" class="text-danger">
                                {{ validation.product_id[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button class="btn btn-success" type="submit">افزودن تخفیف</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "NewDiscount",
    data: function () {
        return {
            products: store.state.products,
            discount: {
                product_id: null,
                discount: null,
                expire: null,
            },
            loading: false,
            validation: {},
        }
    },
    methods: {
        storeDiscount() {
            this.loading = true;
            this.validations = {};
            axios.post('/api/admin/discounts/' + this.discount.product_id, {
                discount: this.discount.discount,
                expire: this.discount.expire,
            })
                .then((result) => {
                    this.loading = false;
                    this.$swal({
                        title: 'تخفیف اضافه شد',
                        icon: 'success'
                    })
                        .then((ok) => {
                            this.$router.push({name: 'discounts'});
                        });
                })
                .catch((reason) => {
                    if (reason.response.status == 422) {
                        this.validations = reason.response.data.errors;
                    } else {
                        this.$swal({
                            title: 'مشکلی پیش آمد',
                            icon: 'error'
                        });
                    }
                });
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let products = await axios.get('/api/admin/products');
            store.state.products = products.data;
            next();

        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمده',
                    icon: 'error'
                });
            });
        }
    }
}
</script>

<style scoped>

</style>
