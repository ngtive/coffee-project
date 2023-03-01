<template>
    <div class="container">
        <div class="row border-bottom">
            <div class="col-6"><h1 class="text-muted">محصولات</h1></div>
            <div class="col-6 text-end">
                <el-button size="medium"
                           type="success"
                           @click="$router.push({name: 'new-product'})">
                    <em class="fa fa-plus me-1"></em>
                    افزودن محصول
                </el-button>
            </div>
        </div>
        <div class="row mt-4 panel" v-loading="loading">
            <div class="table-responsive">
                <el-table :data="products">
                    <el-table-column align="right">
                        <template slot-scope="scope">
                            <img :src="scope.row.thumbs['200']" width="100" height="100" alt="Product Thumb">
                        </template>
                    </el-table-column>
                    <el-table-column
                        align="right"
                        label="نام محصول">
                        <template slot-scope="scope">
                            {{ scope.row.title }}
                        </template>
                    </el-table-column>
                    <el-table-column align="right" label="قیمت">
                        <template slot-scope="{row}">
                            <span v-if="!row.has_product_attribute">
                                {{ row.amount.toLocaleString() }} تومان
                            </span>
                            <span v-else class="text-muted">
                                مشروط به نوع
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column align="right" label="موجودی">
                        <template slot-scope="{row}">
                            <span v-if="!row.has_product_attribute">
                                {{ row.quantity }}
                            </span>
                            <span v-else class="text-muted">
                                مشروط به نوع
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column align="right" label="وضعیت نمایش">
                        <template slot-scope="{row}">
                            <span>{{ row.status ? 'فعال' : 'غیر فعال' }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="right">
                        <template slot="header" slot-scope="scope">
                            <div class="d-flex align-items-center justify-content-center">
                                <el-input type="text" placeholder="جستجو محصول" v-model="search" @change="searchChange"></el-input>
                                <em class="fa fa-search ms-1" @click="searchChange(search)"></em>
                            </div>
                        </template>
                        <template slot-scope="scope">
                            <el-button size="mini" type="info" @click="$router.push({name: 'edit-product', params: {id: scope.row.id}})">
                                ویرایش
                                <em class="fa fa-pen"></em>
                            </el-button>

                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
        <div class="row mt-2">
        </div>
    </div>
</template>

<script>


export default {
    name: "ProductsList",
    components: {},
    data: () => ({
        products: store.state.products,
        search: null,
        loading: false,
    }),

    methods: {
        async changePage(page) {
            console.log(page);
        },
        async clickRow({id}) {
            this.$router.push({
                name: 'products-edit',
                params: {
                    id: id,
                }
            })
        },
        searchChange(e) {
            this.loading = true;
            let params = {};
            if (this.search) {
                params.search = this.search;
            }

            axios.get('/api/admin/products', {
                params: params})
                .then((result) => {
                    this.loading = false;
                    this.products = result.data;
                })
                .catch((reason) => {})
        }
    },

    mounted() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/products');
            store.state.products = result.data;
            next();
        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمد'
                });
            });
        }
    }
}
</script>
