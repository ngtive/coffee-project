<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="text-muted">مقالات</h2>
        </div>

        <div class="row mt-4">
            <div class="col-12 panel">

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListArticle",
    data: function () {
        return {
            articles: store.state.articles,
            search: null,
            loading: false,
        }
    },

    methods: {
        changePage(page) {
            this.loading = true;

        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let articles = await axios.get('/api/admin/articles');
            store.state.articles = articles.data;
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
