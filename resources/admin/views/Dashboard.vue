<template>
    <div class="container">
        <div class="row border-bottom">
            <h1 class="text-muted yk-b">داشبورد</h1>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="panel">
                    <div class="row text-center">
                        <span class="text-muted">سفارشات امروز</span>
                        <span class="fs-4">{{ (stats.today).toLocaleString() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="panel">
                    <div class="row text-center">
                        <span class="text-muted">سفارشات ماه جاری</span>
                        <span class="fs-4">{{ (stats.month).toLocaleString() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="panel">
                    <div class="row text-center">
                        <span class="text-muted">سفارشات هفته جاری</span>
                        <span class="fs-4">{{ (stats.week).toLocaleString() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "index",
    data: () => ({
        stats: {
            today: store.state.stats.today,
            month: store.state.stats.month,
            week: store.state.stats.week,
        },
    }),

    mounted() {

    },

    async beforeRouteEnter(to, from, next) {
        try {
            let stats = await axios.get('/api/admin/orders/stats');
            store.state.stats = stats.data;
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
.chart-wrapper {
    padding: 20px;
    background: linear-gradient(#526897, #10192d);
    border-radius: 10px;
}

.chart-wrapper1 {
    padding: 20px;
    background-color: #2E4057;
    border-radius: 10px;
}
</style>
