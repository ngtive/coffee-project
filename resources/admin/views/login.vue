<template>
    <div class="d-flex align-items-center justify-content-center">
        <form class="login-form-box text-center " id="loginForm" v-loading="loading">
            <div class="row border-bottom">
                <h4 class="text-muted">ورود</h4>
            </div>
            <div class="row g-0 mt-3">
                <div class="col-12 mt-1 text-start">
                    <label class="form-label" for="username">ایمیل</label>
                </div>
                <div class="col-12 mt-1">
                    <input class="form-control"
                           :class="{'is-invalid': validations.email}"
                           v-model="email"
                           type="text"
                           id="username"
                           placeholder="ایمیل">
                    <div class="invalid-feedback text-start" v-if="validations.email">
                        {{ validations.email[0] }}
                    </div>
                </div>
            </div>
            <div class="row g-0 mt-3">
                <div class="col-12 mt-1 text-start">
                    <label class="form-label" for="password">رمز عبور</label>
                </div>
                <div class="col-12 mt-1">
                    <input class="form-control"
                           :class="{'is-invalid': validations.password}"
                           id="password" v-model="password"
                           type="password"
                           placeholder="رمز عبور">
                    <div class="invalid-feedback text-start" v-if="validations.password">
                        {{ validations.password[0] }}
                    </div>
                </div>
            </div>
            <div class="row mt-4">
            </div>
            <div class="row mt-3 text-start ms-1">
                <div class="form-check col-12">
                    <input class="form-check-input" v-model="remember" type="checkbox" id="remember_me">
                    <label class="form-check-label" for="remember_me">به خاطر سپردن</label>
                </div>
            </div>
            <div class="row mt-4">
<!--                <VueRecaptcha :sitekey="'6Le-0RQaAAAAAIWP7t2At7cb9CHj0b0TBoOkfHcX'" @verify="verifyRecaptcha">-->
<!--                </VueRecaptcha>-->
                <button type="submit" class="btn btn-warning col-12" @click="login">ورود</button>



            </div>

        </form>
    </div>
</template>

<script>

export default {
    name: "login",
    data: function () {
        return {
            email: null,
            password: null,
            remember: true,
            loading: false,
            validations: {}
        }
    },
    methods: {
        login() {
            this.loading = true;
            this.validations = {};

            axios.post('/api/auth/admin/login', {
                email: this.email,
                password: this.password,
                remember: this.remember
            }).then((result) => {
                this.loading = false;

                if (result.data.ok) {
                    const token = result.data.token;
                    const user = result.data.user;
                    this.$store.commit('SET_AUTHENTICATED', true);
                    this.$store.commit('SET_TOKEN', token);
                    this.$store.commit('SET_USER', user);
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                    this.$router.push({
                        name: 'dashboard'
                    });
                }


            }).catch((reason) => {
                const response = reason.response;
                if (response.status == 422) {
                    this.validations = response.data.errors;
                } else {
                    this.validations = {};
                    this.$swal({
                        title: 'مشکلی پیش آمد'
                    });
                }
                this.loading = false;

            })
        }
    }
}
</script>

<style scoped>

</style>
