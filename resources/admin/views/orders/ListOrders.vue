<template>
    <div class="container">
        <div class="row border-bottom">
            <div class="col-12">
                <h2 class="text-muted">سفارشات</h2>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 col-md-10 panel">
                <el-table :data="orders.data">
                    <el-table-column
                        align="right"
                        label="شماره سفارش"
                        prop="id"></el-table-column>
                </el-table>
                <div class="mt-4">
                    <el-pagination
                        :current-page="orders.current_page"
                        :page-size="orders.per_page"
                        :total="orders.total"
                        layout="next, pager, prev"></el-pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListOrders",

    data: function () {
        return {
            orders: store.state.orders,
            filter: null,
            filters: [
                {label: 'نمایش همه', key: 'all'},
                {label: 'پرینت نشده ها', key: 'not-printed'},
                {label: 'پرینت شده ها', key: 'printed'},
                {label: 'ارسال شده ها', key: 'sent'},
                {label: 'در انتظار پرداخت', key: 'payment-pending'},
            ]
        }
    },

    methods: {
        changePage() {
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let orders = await axios.get('/api/admin/orders');
            store.state.orders = orders;
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
