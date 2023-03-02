<template>
    <div class="container">
        <div class="row border-bottom pb-1">
            <h1 class="col-6 text-muted">سفارشات</h1>
            <div class="col-6" dir="ltr">
                <router-link :to="{name: 'print-paid-orders'}" class="col-11 col-md-6 btn btn-primary"><em
                    class="fa fa-truck-loading"></em>&nbsp; مدیریت سفارشات
                </router-link>
            </div>
        </div>

        <div class="row mt-1 panel p-0 pt-4">
            <div class="col-12 col-md-3 mt-1">
                <select v-model="filters.select" class="form-select" @change="submitFilter">
                    <option selected value="test">همه سفارشات</option>
                    <option value="paid">پرداخت شده</option>
                    <option value="unpaid">پرداخت نشده</option>
                    <option value="sent">ارسال شده</option>
                    <option value="printed">پرینت شده</option>
                </select>
            </div>
            <div class="col-12 mt-4 mt-md-4">
                <form @submit.prevent="searchOrder">
                    <div class="row">
                        <div class="col-12 col-md-3 mt-1">
                            <input v-model="search.order" class="form-control text-start" placeholder="شماره سفارش   "
                                   type="number">
                        </div>
                        <div class="col-12 col-md-3 mt-1">
                            <input v-model="search.first_name"
                                   class="form-control text-start"
                                   placeholder="نام"
                                   type="text">
                        </div>
                        <div class="col-12 col-md-3 mt-1">
                            <input v-model="search.last_name" class="form-control text-start"
                                   placeholder="نام خانوادگی">
                        </div>
                        <div class="col-12 col-md-3 mt-1">
                            <input v-model="search.mobile" class="form-control text-start" placeholder="شماره تلفن">
                        </div>
                        <div class="col-12 mt-1">
                            <button class="btn btn-secondary" type="submit">
                                <em class="fa fa-search"></em>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 mt-3 table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="row">شماره سفارش</th>
                        <th>وضعیت</th>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>تلفن</th>
                        <th>مبلغ سفارش</th>
                        <th>تاریخ ایجاد</th>
                    </tr>
                    </thead>
                    <tbody>
                    <router-link v-for="order in orders.data"
                                 :key="order.id"
                                 :to="{name: 'orders-show', params: {id: order.id}}"
                                 tag="tr">
                        <td>{{ order.id }}</td>
                        <td>{{ order.status ? order.status.fa : null }}</td>
                        <td>{{ order.address.first_name }}</td>
                        <td>{{ order.address.last_name }}</td>
                        <td>{{ order.address.phone }}</td>
                        <td>{{ order.total_paid.toLocaleString() }} تومان</td>
                        <td dir="ltr">{{ order.created_at }}</td>
                    </router-link>
                    </tbody>
                </table>
            </div>
            <div class="col-12 mt-4">
                <pagination :current-page="orders.current_page"
                            :per-page="orders.per_page"
                            :total="orders.total"
                            dir="ltr"
                            @change="changePage"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "OrdersList",
    data: () => ({
        orders: store.state.orders,
        filters: store.state.filter,
        search: {
            order: null,
            first_name: null,
            last_name: null,
            mobile: null,
        }
    }),

    methods: {
        async searchOrder() {
            this.startLoading();
            if (this.search.order) {
                this.$router.push({
                    name: 'orders-show',
                    params: {
                        id: this.search.order
                    }
                });
                return;
            }

            let postData = {};
            if (this.search.first_name) {
                postData.first_name = this.search.first_name;
            }
            if (this.search.last_name) {
                postData.last_name = this.search.last_name;
            }
            if (this.search.mobile) {
                postData.mobile = this.search.mobile;
            }

            try {
                let result = await axios.get('/orders', {
                    params: {
                        filters: this.filters.select,
                        ...postData
                    }
                });
                this.orders = result.data;

            } catch (e) {

            }

            this.stopLoading();

        },
        async changePage(page) {
            let params = {};
            if (this.filters.select) {
                params.filter = this.filters.select;
            }
            params.page = page;

            try {
                let result = await axios.get('/orders', {params: params});
                this.orders = result.data;
            } catch (e) {

            }
        },
        async submitFilter() {
            try {
                let result = await axios.get('/orders', {
                    params: {
                        filter: this.filters.select,
                    }
                });
                this.orders = result.data;
            } catch (e) {

            }
        }
    },

    async beforeRouteEnter(to, from, next) {
        let filter = 'test';
        if (to.params.filter) {
            filter = to.params.filter
        }
        try {
            let result = await axios.get('/orders', {
                params: {
                    filter: filter
                }
            });
            store.state.orders = result.data;
            store.state.filter = {select: filter};
        } catch (e) {

        }
        next();
    }
}
</script>

<style scoped>
table tbody tr {
    cursor: pointer;
}

</style>
