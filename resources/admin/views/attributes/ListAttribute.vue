<template>
    <div class="container" v-loading="loading">
        <div class="row border-bottom">
            <div class="col-6">
                <h2 class="text-muted">تنوع ها</h2>
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-success" @click="addAttribute">
                    <em class="fa fa-plus"></em>
                </button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-8 col-lg-7 panel">
                <el-table :data="attributes">
                    <el-table-column
                        align="right"
                        label="نام تنوع"
                        prop="name"></el-table-column>
                    <el-table-column
                        align="right"
                        label="تاریخ ایجاد"
                        prop="created_at"></el-table-column>
                    <el-table-column align="right">
                        <template slot-scope="{row}">
                            <el-button size="mini" type="primary" @click="$router.push({name: 'attributes-values', params: {id:row.id}})">مشاهده</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListAttribute",
    data: function () {
        return {
            attributes: store.state.attributes,
            loading: false,
        }
    },

    methods: {
        addAttribute() {
            this.$swal({
                title: 'نام تنوع را وارد کنید',
                input: 'text',
                inputLabel: 'نام تنوع',
                showCancelButton: true,
                cancelButtonText: 'انصرف',
                confirmButtonText: 'ذخیره',
                confirmButtonColor: 'green'

            }).then((result) => {
                if (result.isConfirmed) {
                    let name = result.value;
                    this.loading = true;
                    axios.post('/api/admin/attributes', { name: name }).then((result) => {
                        this.attributes.unshift(result.data);
                        this.loading = false;
                    }).catch((reason) => {
                        this.loading = false;
                        this.$swal({
                            title: 'مشکلی پیش آمده'
                        });
                    });
                }
            });
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/attributes');
            store.state.attributes = result.data;
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

<style scoped>

</style>
