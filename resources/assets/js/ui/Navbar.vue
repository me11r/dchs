<template>
    <nav class="navbar app-navbar">
        <div class="navbar-brand">
            <a class="navbar-item" href="/"><i class="fas fa-tty fa-fw fa-2x"></i></a>
            <a role="button" class="navbar-burger" @click="toggleOpen"><span></span><span></span><span></span></a>
        </div>
        <div class="navbar-menu" :class="menuClass">
            <div class="navbar-start">

            </div>
            <div class="navbar-end">
                <div class="navbar-item">
                <form action="/logout" method="post"><input type="hidden" name="_token" :value="csrf"><button class="button is-outlined"><i class="fas fa-door-open"></i>&nbsp;Выход</button></form>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "Navbar",
        data: function () {
            return {
                opened: false,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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