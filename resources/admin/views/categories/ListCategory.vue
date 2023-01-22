<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="col-6 text-muted">دسته بندی ها</h2>
            <div class="col-6 text-end">
                <router-link tag="button" class="btn btn-success" :to="{name: 'new-category'}">
                    <em class="fa fa-plus"></em>
                </router-link>
            </div>
        </div>

        <div class="row mt-4 panel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th >نام</th>
                        <th>زیر مجموعه</th>
                        <th>وضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="category in categories">
                        <td>{{ category.name }}</td>
                        <td>
                            <span :class="{'border rounded p-1 text-muted': !category.parent}">
                                {{ category.parent ? category.parent.name : 'ندارد' }}
                            </span>
                        </td>
                        <td>{{ category.status ? 'فعال' : 'غیر فعال' }}</td>
                        <td class="text-end">
                            <router-link tag="button" :to="{name: 'edit-category', params: {id: category.id}}"
                                         class="btn btn-sm btn-dark">
                                ویرایش
                            </router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListCategory",

    data: function () {
        return {
            categories: store.state.categories,
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/categories', {params: {orderByChildren: 1}});
            store.state.categories = result.data.map(item => ({...item, selected: false}));
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
