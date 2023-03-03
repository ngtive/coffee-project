<template>
    <nav id="sidebar" class="collapse sidebar d-md-block col-md-2" dir="rtl">
        <ul class="nav flex-column text-right p-sm-0 p-2">
            <Link :href="$route('admin.home')" as="li" class="nav-item">
                <a class="nav-link active" href="#">
                    <em class="fa fa-tachometer-alt me-1"></em>
                    داشبورد
                </a>
            </Link>
            <Link :to="{name: 'orders'}" as="li" href="#">
                <a class="nav-link" href="#">
                    <em class="fa fa-box-open me-1"></em>
                    سفارشات
                </a>
            </Link>
            <Link :href="$route('products.index')" active-class="active" as="li" class="nav-item">
                <a class="nav-link" href="#">
                    <em class="fa fa-store-alt me-1"></em>
                    محصولات
                </a>
            </Link>
            <Link :href="$route('categories.index')"
                  active-class="active" as="li" class="nav-item">
                <a class="nav-link" href="#">
                    <em class="fa fa-list me-1"></em>
                    دسته بندی
                </a>
            </Link>
            <Link active-class="active" as="li" class="nav-item"
                  :href="$route('attributes.index')">
                <a class="nav-link" href="#">
                    <em class="fa fa-project-diagram me-1"></em>
                    تنوع ها
                </a>
            </Link>
            <Link :href="$route('brands.index')" active-class="active" as="li" class="nav-item">
                <a class="nav-link" href="#">
                    <em class="fa fa-sticky-note me-1"></em>
                    برند ها
                </a>
            </Link>

            <Link active-class="active" as="li" class="nav-item" href="#">
                <a class="nav-link" href="#">
                    <em class="fa fa-user me-1"></em>
                    کاربران
                </a>
            </Link>
            <Link :href="$route('discounts.index')" active-class="active" as="li" class="nav-item" type="li">
                <a class="nav-link" href="#">
                    <em class="fa fa-as me-1"></em>
                    تخفیف ها
                </a>
            </Link>
            <Link active-class="active" as="li" class="nav-item" href="#">
                <a class="nav-link" href="#">
                    <em class="fa fa-newspaper me-1"></em>
                    مقالات
                </a>
            </Link>
            <!--            <Link as="li" :to="{name: 'addresses'}" class="nav-item"
                                     active-class="active">
                            <a class="nav-link" href="#">
                                <em class="fa fa-address-book me-1"></em>
                                آدرس ها
                            </a>
                        </Link>-->
        </ul>
    </nav>
</template>

<script>
import {Link} from "@inertiajs/vue2";

export default {
    name: "Sidebar",
    components: {Link}

}
</script>

<style scoped>
</style>
