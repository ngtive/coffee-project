<template>
    <div v-loading="loading" class="container">
        <div class="row border-bottom">
            <div class="col-6">
                <h2 class="text-muted">مقادیر تنوع {{ attribute.name }}</h2>
            </div>
        </div>

        <div class="row mt-4">
            <div class="panel">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <form class="border rounded p-3" style="border-style: dashed !important;"
                              @submit.prevent="addValue">

                            <div class="row">
                                <h6 class="border-bottom pb-1 mb-5">
                                    افزودن مقدار به {{ attribute.name }}
                                </h6>
                                <div class="col-12 mb-3">
                                    <label class="form-label">مقدار {{ attribute.name }}</label>
                                </div>
                                <div :class="{'col-lg-2': attribute.is_color}"
                                     class="col-12 mb-3">
                                    <input v-if="attribute.is_color" v-model="value" class="form-control" dir="ltr"
                                           type="color">
                                    <el-input-number v-else-if="attribute.is_weight"
                                                     v-model="value"
                                                     :controls="false"
                                                     placeholder="وزن به گرم"
                                                     size="mini"></el-input-number>
                                    <el-input v-else
                                              v-model="value"
                                              class="w-100"
                                              placeholder="مقدار"
                                              size="medium"></el-input>

                                </div>
                                <div v-if="attribute.is_color" class="col-12 col-lg-3 mb-2">
                                    <span v-if="attribute.is_color">{{ value }}</span>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <el-button native-type="submit"
                                               size="mini"
                                               type="info">
                                        افزودن
                                        <em class="fa fa-plus"></em>
                                    </el-button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="col-12 col-lg-6">
                        <el-table :data="attribute.values" border>
                            <el-table-column
                                align="right"
                                label="مقدار">
                                <template slot-scope="{row}">
                                    <div v-if="attribute.is_color" :style="{backgroundColor: row.value}"
                                         class="border p-3">
                                        <span class="text-black text-center">{{ row.value }}</span>
                                    </div>
                                    <span v-else>
                                        {{ row.value }}
                                    </span>
                                </template>
                            </el-table-column>
                            <el-table-column
                                align="right"
                                label="تاریخ ایجاد"
                                prop="created_at"></el-table-column>
                        </el-table>
                    </div>
                </div>
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
            value: undefined,
            isColor: store.state.attribute.is_color,
            isWeight: store.state.attribute.is_weight,
            loading: false,
        }
    },

    methods: {
        addValue() {

            // this.$swal({
            //     title: 'مقدار را وارد کنید',
            //     input: 'text',
            //     inputLabel: 'مقدار',
            //     showCancelButton: true,
            //     cancelButtonText: 'انصرف',
            //     confirmButtonText: 'ذخیره',
            //     confirmButtonColor: 'green'
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         let value = result.value;
            //         this.loading = true;
            //
            //         axios.post('/api/admin/attributes/' + this.attribute.id + '/values', {
            //             value: value,
            //         }).then((result) => {
            //             this.loading = false;
            //             this.attribute.values.unshift(result.data);
            //
            //         }).catch((reason) => {
            //             this.loading = false;
            //             this.$swal({
            //                 title: 'مشکلی پیش آمده',
            //             });
            //         });
            //     }
            // });

            if (this.isWeight) {
                this.value = this.value + ' گرمی';
            }

            axios.post('/api/admin/attributes/' + this.attribute.id + '/values', {
                value: this.value,
                is_color: this.isColor,
                is_weight: this.isWeight,
            })
                .then((result) => {
                    this.attribute.values.unshift(result.data);
                })
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
