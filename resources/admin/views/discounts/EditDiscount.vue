<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-6 text-muted">تخفیف برای محصول {{ discount.product.title }}</h2>
            <div class="col-6 text-end">
                <button class="btn btn-danger btn-lg mb-2" @click="deleteDiscount">حذف تخفیف</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-8 col-lg-7 panel">
                <form @submit.prevent="updateDiscount">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="discount">
                                درصد تخفیف (به عدد)
                            </label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input id="discount"
                                   v-model="discount.discount"
                                   :class="{'is-invalid': validations.discount}"
                                   class="form-control form-control-lg"
                                   placeholder="درصد به تخفیف (عدد)"
                                   type="number"></input>
                            <div v-if="validations.discount" class="invalid-feedback">
                                {{ validations.discount[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="expire">انقضا تا چند ساعت</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input id="expire"
                                   v-model="discount.expire"
                                   :class="{'is-invalid': validations.expire}"
                                   class="form-control form-control-lg"
                                   placeholder="انقضا به ساعت (چند ساعت دیگر)"
                                   type="number"></input>
                            <div v-if="validations.expire" class="invalid-feedback">
                                {{ validations.expire[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-3 offset-md-3">
                            <span v-if="discount.is_expired" class="badge bg-danger">
                                منقضی شده است
                            </span>
                            <span v-else class="badge bg-success">
                                دارای اعتبار
                            </span>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button class="btn btn-success" type="submit">بروزرسانی</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "EditDiscount",
    data: function () {
        return {
            discount: store.state.discount,
            validations: {},
            loading: false,
        }
    },

    methods: {
        updateDiscount() {
            let params = {};
            params.discount = this.discount.discount;
            if (this.discount.expire) {
                params.expire = this.discount.expire;
            }

            axios.patch('/api/admin/discounts/' + this.discount.id, params)
                .then((result) => {
                    this.$swal({
                        title: 'تخفیف بروزرسانی شد',
                        icon: 'success'
                    });
                    this.discount = result.data;
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

                    this.loading = false;
                })
        },

        deleteDiscount() {
            this.$swal({
                title: 'آیا از حذف اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'تایید',
                cancelButtonText: 'انصراف'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete('/api/admin/discounts/' + this.discount.id)
                        .then(result => {
                            this.$swal({
                                title: 'حذف شد',
                                icon: 'success'
                            }).then(result => {
                                this.$router.push({
                                    name: 'discounts'
                                });
                            });
                        }).catch(reason => {
                        this.$swal({
                            title: 'مشکلی پیش آمد',
                            icon: 'error'
                        });
                    });
                }
            });
        }
    },


    async beforeRouteEnter(to, from, next) {
        try {
            let discount = await axios.get('/api/admin/discounts/' + to.params.id);
            store.state.discount = discount.data;
            next();
        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمد',
                    icon: 'error'
                });
            });
        }
    }
}
</script>

<style scoped>

</style>
