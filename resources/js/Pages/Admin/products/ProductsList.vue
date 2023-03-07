<template>
    <div class="container">
        <div class="row border-bottom">
            <div class="col-6"><h1 class="text-muted">محصولات</h1></div>
            <div class="col-6 text-end">
                <el-button size="medium"
                           type="success"
                           @click="$inertia.get($route('products.show-store-form'))">
                    <em class="fa fa-plus me-1"></em>
                    افزودن محصول
                </el-button>
            </div>
        </div>
        <div class="row mt-4 panel">
            <div class="table-responsive">
                <el-table :data="products.data">
                    <el-table-column align="right">
                        <template slot-scope="scope">
                            <img :src="scope.row.thumbs['200']" alt="Product Thumb" height="100" width="100">
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
                                <el-input v-model="search" placeholder="جستجو محصول" type="text"
                                          @change="submitSearch"></el-input>
                                <em class="fa fa-search ms-1" @click="submitSearch"></em>
                            </div>
                        </template>
                        <template slot-scope="scope">
                            <el-button size="mini" type="info"
                                       @click="$inertia.get($route('products.show', scope.row.id))">
                                ویرایش
                                <em class="fa fa-pen"></em>
                            </el-button>

                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination
                    :current-page="products.current_page"
                    :page-size="products.per_page"
                    :total="products.total"
                    layout="next, pager, prev"
                    @current-change="(page) => $inertia.get(this.$route('products.index', {page: page, search: search}))"></el-pagination>
            </div>
        </div>
        <div class="row mt-2">
        </div>
    </div>
</template>

<script>
import {router} from "@inertiajs/vue2";

export default {
    name: "ProductsList",
    data: () => ({
        search: null,
    }),

    props: {
        products: Object,
    },

    // methods: {
    //     async changePage(page) {
    //         console.log(page);
    //     },
    //     async clickRow({id}) {
    //         this.$router.push({
    //             name: 'products-edit',
    //             params: {
    //                 id: id,
    //             }
    //         })
    //     },
    //     searchChange(e) {
    //         this.loading = true;
    //         let params = {};
    //         if (this.search) {
    //             params.search = this.search;
    //         }
    //
    //         axios.get('/api/admin/products', {
    //             params: params})
    //             .then((result) => {
    //                 this.loading = false;
    //                 this.products = result.data;
    //             })
    //             .catch((reason) => {})
    //     }
    // },

    methods: {
        submitSearch() {
            router.get(this.$route('products.index', {search: this.search}))
        },
    }
}
</script>
