<template>
    <div class="directive-loading-wrapper">
        <div class="d-flex justify-content-center h-100 position-relative">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DirectiveLoading"
}
</script>

<style scoped>
.directive-loading-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: white;
    opacity: 0.8;
}
</style>
