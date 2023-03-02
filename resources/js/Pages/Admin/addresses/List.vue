<template>
    <div class="container">
        <div class="d-grid gap-3">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3>آدرس ها</h3>
                    <button class="btn btn-primary" data-target="#modal" data-toggle="modal" type="button">
                        <em class="fa fa-plus"></em> افزودن
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div v-if="addresses.length <= 0" class="d-flex justify-content-center">
                            <p>آدرسی وجود ندارد</p>
                        </div>
                        <div v-for="address in addresses" :key="address.id" class="col-md-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <span> {{ address.key }}</span>
                                    <button class="btn btn-danger" @click="deleteAddress(address.id)">
                                        <em class="fa fa-times" style="font-size: .5rem"></em>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <p>{{ address.address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal" class="modal" role="dialog" tabindex="-1">
            <form @submit.prevent="add">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">افزودن آدرس جدید</h5>
                            <button aria-label="Close" class="btn" data-dismiss="modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    کلید یافتن آدرس توسط کاربران
                                </label>
                                <input v-model="new_address.key"
                                       :class="{'is-invalid': new_address.validations.key}"
                                       class="form-control "
                                       placeholder="کلید"
                                       type="text">
                                <span v-if="new_address.validations.key" class="invalid-feedback">
                                    {{ new_address.validations.key[0] }}
                                </span>

                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    آدرس
                                </label>
                                <textarea v-model="new_address.address"
                                          class="form-control"
                                          rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">ذخیره آدرس</button>
                            <button class="btn btn-secondary" data-dismiss="modal" type="button">انصراف</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "List",
    data: function () {
        return {
            addresses: store.state.addresses,
            new_address: {
                key: null,
                address: null,
                validations: {},
            }
        }
    },
    methods: {
        async add() {
            try {
                let result = await axios.post('/addresses', this.new_address);
                this.addresses.unshift(result.data);

                let modal = bootstrap.Modal.getInstance(document.querySelector('#modal'));
                $('#modal').hide();
                $('.modal-backdrop').hide();
            } catch (e) {
                this.new_address.validations = e.response.data.errors;
            }
        },
        async deleteAddress(id) {
            try {
                await axios.delete('/addresses/' + id);
                this.addresses.splice(this.addresses.findIndex(i => i.id == id), 1);
            } catch (e) {

            }
        }
    },
    async beforeRouteEnter(to, from, next) {
        try {
            let result = await axios.get('/addresses');
            store.state.addresses = result.data;
            next();
        } catch (e) {

        }
    }
}
</script>

<style scoped>

</style>
