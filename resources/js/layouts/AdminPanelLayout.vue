<template>
    <div>
        <Header v-if="user" :admin="user"></Header>
        <div class="container-fluid">
            <div class="row">
                <Sidebar v-if="user"></Sidebar>
                <div :class="{'col-12 col-md-10 ms-md-auto': user}">
                    <main class="ps-md-5 d-block" role="main">
                        <slot/>
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from "../components/Sidebar.vue";
import Header from "../components/Header.vue";
import 'sweetalert2/dist/sweetalert2.min.css';

export default {
    name: "AdminPanelLayout",
    components: {
        Header,
        Sidebar
    },

    computed: {
        user() {
            return this.$page.props.user;
        }
    }
}
</script>

<style scoped>

</style>
