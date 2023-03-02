<template>
    <div v-loading="loading" class="container">
        <div class="row border-bottom">
            <h2 class="text-muted col-6 mt-3 mt-md-0">مدیریت سفارشات</h2>
            <div class="col-6 mb-2" dir="ltr">
                <a class="btn btn-primary" href="/admin/orders/change-paid-to-printed" target="_blank">
                    <em class="fa fa-print"></em>
                    پرینت سفارشات پرداخت شده
                </a>
            </div>
        </div>

        <div class="row mt-3 panel">
            <div class="col-12 border-bottom row">
                <div class="col-12">
                    <h4 class="text-muted">مدیریت سفارشات</h4>
                </div>
                <div class="col-12 row p-0 mb-3" dir="ltr">
                    <div class="col-12 col-md-2">
                        <button :disabled="!anythingChecked" class="btn btn-secondary" @click="clickSendSelected">
                            <em class="fa fa-truck"></em>
                            ارسال
                        </button>
                    </div>
                    <div class="col-12 col-md-2 mt-2 mt-md-0">
                        <button :disabled="!anythingChecked" class="btn btn-primary" @click="clickPrintSelected">
                            <em class="fa fa-print"></em>
                            پرینت
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="paid_orders.length <= 0" class="col-12 mt-3 text-center">
                <h4>سفارش پرینت شده ای وجود ندارد </h4>
            </div>
            <div v-else class="col-12 mt-2">
                <div class="table-responsive">
                    <table class="table table-light table-bordered">
                        <thead>
                        <tr>
                            <th>
                                <input v-model="checkAll" type="checkbox">
                            </th>
                            <th scope="row">شماره سفارش</th>
                            <th>نام</th>
                            <th>استان - شهر</th>
                            <th>کد پستی</th>
                            <th>شماره همراه</th>
                            <th>تاریخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="order in paid_orders" v-if="order.address">
                            <td>
                                <input v-model="order.checked"
                                       type="checkbox">
                            </td>
                            <td>{{ order.id }}</td>
                            <td>{{ order.address.first_name }}&nbsp;{{ order.address.last_name }}</td>
                            <td>{{ order.address.city.province.name }} - {{ order.address.city.name }}</td>
                            <td>{{ order.address.postal_code }}</td>
                            <td>{{ order.address.phone }}</td>
                            <td dir="ltr">{{ order.accepted_at }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PrintPaidOrders",

    data: () => ({
        paid_orders: store.state.orders,
        checkAll: false,
        loading: false,
    }),

    methods: {
        async clickSendSelected() {
            this.loading = true;
            if (this.selectedOrders.length > 0) {

                try {
                    await axios.post('/orders/send-orders-by-id', {orders: this.selectedOrders});
                    this.$swal({
                        title: 'ارسال',
                        text: 'سفارشات انتخاب شده به حالت ارسال تغییر کرد و به سفارش دهنده از طریق پیامک اطلاع رسانی شد',
                        icon: 'success',
                        showConfirmButton: true,
                        showCancelButton: false,
                        confirmButtonText: 'متوجه شدم!'
                    });
                    this.selectedOrders.forEach((selectedOrderId, index) => {
                        let paidOrdersFind = this.paid_orders.findIndex((item) => {
                            return item.id == selectedOrderId;
                        });
                        if (paidOrdersFind >= 0) {
                            this.paid_orders.splice(paidOrdersFind, 1);
                        }
                    });
                } catch (e) {

                }

            } else {
                this.$swal({
                    title: 'مشکلی پیش آمد',
                    icon: 'error',
                    text: 'هیچ سفارشی انتخاب نشده است'
                });
            }
            this.loading = false;
        },
        async clickPrintSelected() {
            this.loading = true;
            if (this.selectedOrders.length > 0) {
                try {
                    let result = await axios.post('/orders/print-orders-by-id', {orders: this.selectedOrders}, {
                        responseType: 'arraybuffer',
                    });

                    let data = new Blob([result.data], {type: 'application/pdf'});
                    let urlData = URL.createObjectURL(data);
                    window.open(urlData);

                } catch (e) {

                }
            } else {
                this.$swal({
                    title: 'مشکلی پیش آمد',
                    icon: 'error',
                    text: 'هیچ سفارشی انتخاب نشده است'
                });
            }
            this.loading = false;
        }
    },

    computed: {
        anythingChecked() {
            let condition = false;
            this.paid_orders.forEach((order) => {
                if (order.checked) {
                    condition = true;
                    return;
                }
            });
            return condition;
        },

        selectedOrders() {
            return this.paid_orders.filter((order) => {
                return order.checked;
            }).map((order) => {
                return order.id;
            });
        }
    },

    watch: {
        checkAll(checked) {
            this.paid_orders.forEach((order) => {
                order.checked = checked;
            });
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/orders/printed-paid-orders');
            store.state.orders = result.data.map((item) => ({...item, checked: false}));
        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمد',
                    icon: 'error',
                });
            });
        }
        next();
    }
}
</script>

<style scoped>

</style>
