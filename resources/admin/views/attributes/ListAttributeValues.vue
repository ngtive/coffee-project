<template>
    <div class="container" v-loading="loading">
        <div class="row border-bottom">
            <div class="col-6">
                <h2 class="text-muted">مقادیر تنوع {{ attribute.name }}</h2>
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-success" @click="addValue">
                    <em class="fa fa-plus"></em>
                </button>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 col-md-8 col-lg-7 panel">
                <el-table :data="attribute.values">
                    <el-table-column
                        align="right"
                        prop="value"
                        label="مقدار"></el-table-column>
                    <el-table-column
                        align="right"
                        label="تاریخ ایجاد"
                        prop="created_at"></el-table-column>
                </el-table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListAttributeValues",

    data: function () {
        return {
            attribute: store.state.attribute,
            loading: false,
        }
    },

    methods: {
        addValue() {

            this.$swal({
                title: 'مقدار را وارد کنید',
                input: 'text',
                inputLabel: 'مقدار',
                showCancelButton: true,
                cancelButtonText: 'انصرف',
                confirmButtonText: 'ذخیره',
                confirmButtonColor: 'green'
            }).then((result) => {
                if (result.isConfirmed) {
                    let value = result.value;
                    this.loading = true;

                    axios.post('/api/admin/attributes/' + this.attribute.id + '/values', {
                        value: value,
                    }).then((result) => {
                        this.loading = false;
                        this.attribute.values.unshift(result.data);

                    }).catch((reason) => {
                        this.loading = false;
                        this.$swal({
                            title: 'مشکلی پیش آمده',
                        });
                    });
                }
            });
        }
    },

    async beforeRouteEnter(to, from, next) {
        let id = to.params.id;
        try {
            let result = await axios.get('/api/admin/attributes/' + id)
            store.state.attribute = result.data;
            next();
        } catch (e) {
            next(vm => {
                vm.$swal({
                    title: 'مشکلی پیش آمد',
                });
            });
        }
    }
}
</script>

<style scoped>

</style>
