<template>
    <div class="container">
        <div class="border-bottom mb-3 row">
            <h2 class="text-muted col-6">مقالات</h2>
            <div class="col-6 text-end">
                <el-button size="medium" type="success" @click="$router.push({name: 'new-article'})">
                    <em class="fa fa-plus me-1"></em>
                    افزودن مقاله
                </el-button>
            </div>
        </div>

        <div class="panel">
            <div class="row">
                <div v-for="article in articles.data" class="col-12 col-lg-4">

                </div>
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
            search: undefined,
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
            let articles = await axios.get('/api/admin/articles', {params: {paginate: true}});
            store.state.articles = articles.data;
            next();

        } catch (e) {
            store.state.articles = {data: []};
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
