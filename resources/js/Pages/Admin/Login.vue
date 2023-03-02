<template>
    <div class="d-flex align-items-center justify-content-center">
        <form id="loginForm" class="login-form-box text-center"
              @submit.prevent="login">
            <div class="row border-bottom">
                <h4 class="text-muted">ورود</h4>
            </div>
            <div class="row g-0 mt-3">
                <div class="col-12 mt-1 text-start">
                    <label class="form-label" for="username">ایمیل</label>
                </div>
                <div class="col-12 mt-1">
                    <input id="username"
                           v-model="loginForm.email"
                           :class="{'is-invalid': loginForm.errors.email}"
                           class="form-control"
                           placeholder="ایمیل"
                           type="text">
                    <div v-if="loginForm.errors.email" class="invalid-feedback text-start">
                        {{ loginForm.errors.email }}
                    </div>
                </div>
            </div>
            <div class="row g-0 mt-3">
                <div class="col-12 mt-1 text-start">
                    <label class="form-label" for="password">رمز عبور</label>
                </div>
                <div class="col-12 mt-1">
                    <input id="password"
                           v-model="loginForm.password"
                           :class="{'is-invalid': loginForm.errors.password}" class="form-control"
                           placeholder="رمز عبور"
                           type="password">
                    <div v-if="loginForm.errors.password" class="invalid-feedback text-start">
                        {{ loginForm.errors.password }}
                    </div>
                </div>
            </div>
            <div class="row mt-4">
            </div>
            <div class="row mt-3 text-start ms-1">
                <div class="form-check col-12">
                    <input id="remember_me" v-model="loginForm.remember" class="form-check-input" type="checkbox">
                    <label class="form-check-label" for="remember_me">به خاطر سپردن</label>
                </div>
            </div>
            <div class="row mt-4">
                <button class="btn btn-warning col-12" type="submit">ورود</button>
            </div>
        </form>
    </div>

</template>

<script>
import {useForm} from "@inertiajs/vue2";

export default {
    name: "Login",
    data: function () {

        return {
            loginForm: useForm({
                email: undefined,
                password: undefined,
                remember: true,
            }),
        }

    },
    methods: {
        login() {
            this.loginForm.post(this.$route('login'));
        },
    }
}
</script>

<style scoped>

</style>
