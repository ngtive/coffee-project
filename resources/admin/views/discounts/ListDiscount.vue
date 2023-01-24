<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-6 text-muted">تخفیف ها</h2>
            <div class="col-6 text-end">
                <router-link :to="{name: 'new-discount'}" class="btn btn-success"
                             tag="button">
                    <em class="fa fa-plus"></em>
                </router-link>
            </div>
        </div>

        <div class="row mt-4 panel">
            <el-table :data="discounts.data" v-on:cell-click="(row) => cellClick(row)">
                <el-table-column
                    align="right">
                    <template slot-scope="{row}">
                        <img :src="row.product.thumbs['200']" alt="Product Cover" height="100" width="100">
                    </template>
                </el-table-column>
                <el-table-column
                    align="right"
                    label="محصول"
                    prop="product.title"></el-table-column>
                <el-table-column
                    align="right"
                    label="تخفیف"
                    prop="discount"></el-table-column>
                <el-table-column
                    align="right"
                    label="تاریخ انقضا"
                    prop="expire_at"></el-table-column>
            </el-table>
            <el-pagination
                :current-page="discounts.current_page"
                :page-size="discounts.per_page"
                :total="discounts.total"
                layout="next, pager, prev"
                v-on:current-change="changePage"></el-pagination>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListDiscount",

    data: function () {
        return {
            discounts: store.state.discounts,
            search: null,
            loading: false,
        }
    },

    methods: {

        cellClick(row) {
            this.$router.push({
                name: 'edit-discount',
                params: {
                    id: row.id,
                }
            });
        },

        searchDiscount() {
            this.loading = true;
            axios.get('/api/admin/discounts', {
                params: {
                    search: this.search,
                }
            })
                .then((result) => {
                    this.loading = false;
                    this.discounts = result.data;
                })
                .catch(reason => {
                    this.loading = false;
                    this.$swal({
                        title: 'مشکلی پیش آمد',
                        icon: 'error'
                    });
                });
        },

        changePage(page) {
            this.loading = true;
            let params = {};

            if (this.search) {
                params.search = this.search;
            }

            params.page = page;

            axios.get('/api/admin/discounts', {params: params})
                .then((result) => {
                    this.discounts = result.data;
                    this.loading = false;
                })
                .catch(reason => {
                    this.$swal({
                        title: 'مشکلی پیش آمد',
                        icon: 'error'
                    });
                    this.loading = false;
                });
        },

        deleteDiscount(discount) {
            this.loading = true;
            axios.delete('/api/admin/discounts/' + discount.id)
                .then((result) => {
                    this.loading = false;
                    this.discounts = this.discounts.filter(item => item.id == discount.id);
                    this.$swal({
                        title: 'تخفیف حذف شد',
                        icon: 'success'
                    });
                })
                .catch((reason) => {
                    this.loading = false;
                    this.$swal({
                        title: 'مشکلی پیش آمد',
                        icon: 'error'
                    });
                });
        },

    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/discounts');
            store.state.discounts = result.data;
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
