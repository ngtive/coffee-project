<template>
    <div class="container">
        <div class="row border-bottom">
            <div class="col-6 text-muted">
                <h2>برند ها</h2>
            </div>
            <div class="col-6 text-end">
                <router-link :to="{name: 'new-brand'}"
                             tag="button"
                             class="btn btn-success">
                    <em class="fa fa-plus"></em>
                </router-link>
            </div>
        </div>
        <div class="row mt-4 panel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>نام برند</th>
                        <th>وضعیت نمایش برند</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="brand in brands" :key="brand.ir">
                        <td>{{ brand.name }}</td>
                        <td>{{ brand.status ? 'فعال' : 'غیر فعال'}}</td>
                        <td class="text-end">
                            <router-link :to="{name: 'edit-brand', params: {id: brand.id}}"
                                         class="btn btn-sm btn-dark"
                                         tag="button">ویرایش</router-link>
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
    name: "ListBrands",
    data: function () {
        return {
            brands: store.state.brands
        }
    },

    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/api/admin/brands');
            store.state.brands = result.data;
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
