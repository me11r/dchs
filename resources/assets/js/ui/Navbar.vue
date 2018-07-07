<template>
    <nav class="navbar app-navbar">
        <div class="navbar-brand">
            <a class="navbar-item" href="/"><i class="fas fa-tty fa-fw fa-2x"></i></a>
            <a role="button" class="navbar-burger" @click="toggleOpen"><span></span><span></span><span></span></a>
        </div>
        <div class="navbar-menu" :class="menuClass">

        </div>
    </nav>
</template>

<script>
    export default {
        name: "Navbar",
        data: function () {
            return {
                opened: false,
            }
        },
        computed: {
            menuClass: function () {
                return this.opened ? 'is-active' : '';
            }
        },
        methods: {
            toggleOpen: function () {
                this.opened = !this.opened
            }
        }
    }
</script>

<style>

</style>