<template>
    <nav class="collapse sidebar d-md-block col-md-2" dir="rtl" id="sidebar">
        <ul class="nav flex-column text-right p-sm-0 p-2">
            <router-link tag="li" :to="{name: 'dashboard'}" class="nav-item" active-class="active" exact>
                <a class="nav-link active" href="#">
                    <em class="fa fa-tachometer-alt me-1"></em>
                    داشبورد
                </a>
            </router-link>
            <router-link tag="li" :to="{name: 'orders'}" class="nav-item" active-class="active">
                <a class="nav-link" href="#">
                    <em class="fa fa-box-open me-1"></em>
                    سفارشات
                </a>
            </router-link>
            <router-link :to="{name: 'products'}" class="nav-item" active-class="active" tag="li">
                <a class="nav-link" href="#">
                    <em class="fa fa-store-alt me-1"></em>
                    محصولات
                </a>
            </router-link>
            <router-link :to="{name: 'categories'}" class="nav-item" active-class="active" tag="li">
                <a href="#" class="nav-link">
                    <em class="fa fa-list me-1"></em>
                    دسته بندی
                </a>
            </router-link>
            <router-link :to="{name: 'attributes'}" class="nav-item" active-class="active"
                         tag="li">
                <a href="#" class="nav-link">
                    <em class="fa fa-project-diagram me-1"></em>
                    تنوع ها
                </a>
            </router-link>
            <router-link :to="{name: 'brands'}" class="nav-item" active-class="active" tag="li">
                <a href="#" class="nav-link">
                    <em class="fa fa-sticky-note me-1"></em>
                    برند ها
                </a>
            </router-link>

            <router-link tag="li" :to="{name: 'users'}" class="nav-item" active-class="active">
                <a class="nav-link" href="#">
                    <em class="fa fa-user me-1"></em>
                    کاربران
                </a>
            </router-link>
            <router-link :to="{name: 'discounts'}" active-class="active" class="nav-item" tag="li">
                <a class="nav-link" href="#">
                    <em class="fa fa-tag me-1"></em>
                    تخفیف ها
                </a>
            </router-link>
            <router-link :to="{name: 'articles'}" active-class="active" class="nav-item" tag="li">
                <a class="nav-link" href="#">
                    <em class="fa fa-newspaper me-1"></em>
                    مقالات
                </a>
            </router-link>
            <!--            <router-link tag="li" :to="{name: 'addresses'}" class="nav-item"
                                     active-class="active">
                            <a class="nav-link" href="#">
                                <em class="fa fa-address-book me-1"></em>
                                آدرس ها
                            </a>
                        </router-link>-->
        </ul>
    </nav>
</template>

<script>
export default {
    name: "Sidebar"
}
</script>

<style scoped>
</style>
