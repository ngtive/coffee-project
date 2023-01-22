<template>
    <div class="container">
        <div class="row border-bottom">
            <h1 class="text-muted yk-b">داشبورد</h1>
        </div>

        <div class="row mt-3">
        </div>
    </div>
</template>

<script>
export default {
    name: "index",
    data: () => ({}),

    mounted() {

    },

    async beforeRouteEnter(to, from, next) {
        next();
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
