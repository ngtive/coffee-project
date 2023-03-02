<template>
    <div>
        <ul class="nav flex-column ms-4 mt-2">
            <li v-for="category in categories" :key="category.id" class="border rounded p-4 mb-3 mt-5">
                <div class="row">
                    <div class="d-lg-flex justify-content-between">
                        <label class="form-check-label col-6 col-lg-3">
                            <el-checkbox v-model="category.selected"
                                         class="me-1"
                                         size="mini"
                                         @change="change(category)"></el-checkbox>
                            <span>{{ category.name }}</span>
                        </label>
                        <div>
                            <el-button :class="{'me-1': category.children.length == 0}"
                                       class="text-end"
                                       size="mini"
                                       type="info" @click="onClickEdit(category)">
                                ویرایش
                            </el-button>
                            <el-button v-if="category.children.length == 0"
                                       class="text-end"
                                       size="mini"
                                       type="danger"
                                       @click="onClickDelete(category)">
                                <em class="fa fa-times" style="font-size: .6rem"></em>
                            </el-button>
                        </div>
                    </div>

                    <category-menu v-if="category.children.length > 0" :categories="category.children"
                                   :on-change="change"
                                   :on-click-delete="onClickDelete"
                                   :on-click-edit="onClickEdit"></category-menu>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "CategoryMenu",

    props: {
        categories: [],
        onChange: {
            type: Function,
        },
        onClickEdit: {
            type: Function
        },
        onClickDelete: {
            type: Function,
        }
    },

    methods: {
        change(category) {
            this.onChange(category);
        },
        clickEdit(category) {
            this.onClickEdit(category);
        },
        clickDelete(category) {
            this.onClickDelete(category);
        }
    }
}
</script>

<style scoped>
</style>
