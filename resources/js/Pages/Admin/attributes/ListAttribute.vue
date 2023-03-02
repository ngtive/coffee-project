<template>
    <div v-loading="loading" class="container">
        <div class="row border-bottom">
            <div class="col-6">
                <h2 class="text-muted">تنوع ها</h2>
            </div>
            <!--            <div class="col-6 text-end">
                            <button class="btn btn-success" @click="addAttribute">
                                <em class="fa fa-plus"></em>
                            </button>
                        </div>-->
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="panel">
                    <form ref="form" class="row border py-2 px-1 rounded" @submit.prevent="addAttribute">
                        <h6 class="border-bottom pb-1 mb-3">تنوع جدید</h6>

                        <div class="col-6 col-lg-4">
                            <label class="form-label">نام تنوع</label>
                            <el-input v-model="attribute.name"
                                      placeholder="نام تنوع"
                                      size="mini"></el-input>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label class="form-check-inline mt-2"> تنوع رنگ است ؟<br>(در صورتی که نام تنوع رنگ است)
                                <el-checkbox v-model="attribute.is_color" class="mx-2"></el-checkbox>
                            </label>
                        </div>

                        <div class="col-12 col-lg-3">
                            <label class="form-check-label mt-2">
                                تنوع وزن است ؟<br>
                                (جهت محاسبه هزینه پستی)
                                <el-checkbox v-model="attribute.is_weight" class="mx-2"></el-checkbox>
                            </label>
                        </div>
                        <div class="col-12">

                        </div>
                        <div class="col-12 mt-lg-4">
                            <el-button native-type="submit" size="mini" type="primary">
                                <span>ایجاد تنوع</span>
                            </el-button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12">
                <div class="panel">
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
                                <el-button size="mini" type="info"
                                           @click="$router.push({name: 'attributes-values', params: {id:row.id}})">
                                    ویرایش
                                    <em class="fa fa-pen"></em>
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>

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
            attribute: {
                name: undefined,
                is_color: false,
                is_weight: false,
                values: [],
            },
            loading: false,
        }
    },

    methods: {
        addAttribute() {

            this.loading = true;

            axios.post('/api/admin/attributes', {
                name: this.attribute.name,
                is_color: this.attribute.is_color,
                is_weight: this.attribute.is_weight,
            }).then((result) => {
                this.attributes.unshift(result.data);
                this.loading = false;

                this.$router.push({
                    name: 'attributes-values',
                    params: {
                        id: result.data.id
                    }
                });

            }).catch((reason) => {
                this.loading = false;
                this.$notify({
                    title: 'مشکلی پیش آمده',
                    message: 'مشکلی در افزودن تنوع جدید ایجاد شد کد ارور: ' + reason.response.status,
                    type: 'error'
                });
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
