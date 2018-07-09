<template>
</template>

<script>
    export default {
        name: "Card101",
        data: function () {
            return {
                activeTab: 0,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
    }
</script>

<style scoped>

</style>