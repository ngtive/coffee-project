<template>
    <div v-loading="loading" class="container">
        <div class="row border-bottom">
            <h1 class="col-6 text-muted mt-1">سفارش {{ order.id }}</h1>
            <div class="row col-6 mb-1" dir="ltr">
                <button class="col-12 col-md-2 btn btn-danger" @click="deleteOrder">
                    <em class="fa fa-trash-alt"></em>
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div id="receiptDescription" class="col-12 col-md-6 panel">
                <div class="row">
                    <div class="border-bottom row g-0">
                        <h6 class="col-6 text-muted mt-1">وضعیت سفارش</h6>
                        <em class="col-6 text-muted fa fa-info-circle" dir="ltr" style="font-size: 25px"></em>
                    </div>
                    <div class="col-12 mt-3">
                        <p>
                            {{ order.status.fa }}&nbsp;
                            <span v-if="order.status.en == 'unpaid'"
                                  class="fa fa-times text-danger"
                                  style="font-size: 20px;">
                            </span>
                            <span v-else
                                  class="text-success fa fa-check"
                                  style="font-size: 20px;"></span>
                        </p>
                    </div>
                    <div class="border-bottom row g-0 mt-3">
                        <h6 class="col-6 text-muted mt-1">مشخصات خریدار</h6>
                        <em class="col-6 fa fa-address-card text-muted" dir="ltr" style="font-size: 25px"></em>
                    </div>
                    <div class="col-12 mt-3">
                        <router-link :to="{name: 'user', params: {id: order.user_id}}" class="user-anchor" tag="a">
                            {{ order.address.first_name }} {{ order.address.last_name }}
                        </router-link>
                    </div>
                    <div class="col-12 mt-1">
                        <p>{{ order.address.city.province.name }} - {{ order.address.city.name }}</p>
                    </div>
                    <div class="col-12 mt-1">
                        <p>کد پستی: {{ order.address.postal_code }}</p>
                    </div>
                    <div class="col-12 mt-1">
                        <p>آدرس: {{ order.address.address }}</p>
                    </div>
                    <div class="col-12 mt-1">
                        <p>تلفن: {{ order.address.phone }}</p>
                    </div>

                    <div class="col-12 mt-3 border-bottom g-0 row">
                        <h6 class="col-6 text-muted mt-1">مشخصات پرداخت</h6>
                        <em class="col-6 fa fa-receipt text-muted" dir="ltr" style="font-size: 25px;"></em>
                    </div>
                    <div class="col-12 mt-3">
                        <p>شناسه: <b class="text-muted" style="font-size: 14px">{{ order.transaction_id }}</b></p>
                    </div>
                    <div class="col-12 mt-1">
                        <p>مبلغ سفارش: <b class="fs-6">{{ order.total_paid.toLocaleString() }} تومان</b></p>
                    </div>
                    <div class="col-12 mt-1">
                        <p>شماره مرجع:
                            <span>{{ order.reference_number ? order.reference_number : 'شماره مرجع وجود ندارد' }}</span>
                        </p>
                    </div>
                    <div class="col-12 mt-1">
                        <p>تاریخ پرداخت: <span
                            dir="ltr">{{ order.accepted_at ? order.accepted_at : 'تاریخ پرداخت وجود ندارد' }}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-5 panel ms-md-1 mt-md-0 mt-1">
                <div class="row">
                    <div class="border-bottom row g-0">
                        <h6 class="col-6 text-muted mt-1">محصولات</h6>
                        <em class="col-6 fa fa-cube text-muted" dir="ltr" style="font-size: 25px;"></em>
                    </div>

                    <div v-for="item in order.items" v-if="order.items.length > 0" class="col-12 mt-1">
                        <div class="row border p-1 border-info rounded">
                            <p class="col-6">{{ item.product.title }}</p>
                            <p class="col-5 ms-1" dir="ltr">{{ item.quantity }}</p>
                        </div>
                    </div>
                    <div v-else class="col-12 mt-1">
                        <h1>آیتمی در سفارش وجود ندارد</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ShowOrder",
    data: () => ({
        order: store.state.order,
        loading: false,
    }),


    methods: {
        async deleteOrder() {
            this.loading = true;

            this.$swal({
                title: 'تایید حذف',
                icon: 'info',
                text: 'از حذف فاکتور مطمئن هستید',
                showConfirmButton: true,
                showCancelButton: true,
                cancelButtonText: 'انصراف',
                confirmButtonText: 'تایید'
            })
                .then(async (condition) => {

                    if (condition.isConfirmed) {
                        try {
                            let id = this.order.id;
                            await axios.delete(`/orders/${id}`);
                            this.$router.push({name: 'orders'});
                        } catch (e) {

                        }
                    }
                    this.loading = false;
                });
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let id = to.params.id;
            let result = await axios.get(`/orders/${id}`);
            store.state.order = result.data;
            next();

        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'پیدا نشد',
                    text: 'سفارش شماره ' + to.params.id + ' پیدا نشد',
                    icon: 'error',
                    showConfirmButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'متوجه شدم!'
                })
                    .then(() => {
                        vm.$router.push({
                            name: 'orders'
                        });
                    })
            })
        }
    }
}
</script>

<style scoped>
p {
    padding: 0;
    margin: 0;
}

a.user-anchor {
    color: black;
    text-decoration: underline;
}
</style>
