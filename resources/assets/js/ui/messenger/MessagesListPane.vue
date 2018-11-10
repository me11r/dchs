<template>
    <div class="messages-list">
        eqeqe
        qweqeqqe
        qeqeqe
    </div>
</template>

<script>
export default {
    name: 'MessagesListPane'
};
</script>

<style lang="scss" scoped>
    @import "../../../sass/variables";
    .messages-list {
        max-height: 400px;
        min-height: 400px;
        border-bottom: 1px solid cadetblue;
        background-color: $blueish;
        overflow-y: scroll;
    }
</style>
