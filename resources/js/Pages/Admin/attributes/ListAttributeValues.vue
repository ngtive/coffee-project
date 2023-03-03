<template>
    <div class="container">
        <div class="row border-bottom">
            <div class="col-12">
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
                                <div :class="{'col-lg-4': attribute.is_color}"
                                     class="col-12 mb-3">
                                    <input v-if="attribute.is_color" v-model="form.value" class="form-control mb-1"
                                           dir="ltr"
                                           type="color">
                                    <el-input v-if="isColor"
                                              v-model="form.value"
                                              size="mini"></el-input>
                                    <el-input v-else-if="attribute.is_weight"
                                              v-model="form.value"
                                              class="w-auto"
                                              placeholder="وزن به گرم"
                                              size="mini"></el-input>
                                    <el-input v-else
                                              v-model="form.value"
                                              class="w-100"
                                              placeholder="مقدار"
                                              size="medium"></el-input>

                                    <span v-if="form.errors.value"
                                          class="text-danger d-block ms-1 mt-1">
                                        {{ form.errors.value }}
                                    </span>

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
import {useForm} from "@inertiajs/vue2";

export default {
    name: "ListAttributeValues",

    data: function () {
        return {
            form: useForm({
                value: undefined,
                is_color: this.attribute.is_color,
                is_weight: this.attribute.is_weight,
            })
        }
    },

    props: {
        attribute: Object,
    },

    computed: {
        isColor() {
            this.attribute.is_color;
        },
        isWeight() {
            this.attribute.is_weight;
        }
    },

    methods: {
        addValue() {
            this.form.post(this.$route('attribute.store.values', this.attribute.id), {
                onSuccess: () => {
                    this.$notify({
                        title: 'افزودن مقدار به تنوع',
                        type: 'success',
                        message: 'افزودن مقدار انجام شد'
                    });
                },
                onError: () => {
                    this.$notify({
                        title: 'افزودن تنوع',
                        type: 'error',
                        message: 'افزودن مقدار با خطا مواجه شد مجدد امتحان کنید'
                    });
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
