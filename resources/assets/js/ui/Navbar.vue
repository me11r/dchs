<template>
    <nav class="navbar app-navbar">
        <div class="navbar-brand">
            <a class="navbar-item" href="/"><i class="fas fa-tty fa-fw fa-2x"></i></a>
            <a role="button" class="navbar-burger" @click="toggleOpen"><span></span><span></span><span></span></a>
        </div>
        <div class="navbar-menu" :class="menuClass">
            <div class="navbar-start">
                <div class="navbar-item"><a href="/card/101" class="button is-inline-block-widescreen is-block"><i class="fas fa-address-card"></i>&nbsp; Карточка 101</a></div>
<!--
                <div class="navbar-item"><a href="/card/112" class="button is-inline-block-widescreen is-block"><i class="fas fa-address-card"></i>&nbsp; Карточка 112</a></div>
-->
                <div class="navbar-item"><a href="/formation/101" class="button is-inline-block-widescreen is-block"><i class="fas fa-address-book"></i>&nbsp; Строевая записка 101</a>
                </div>
                <div class="navbar-item"><a href="/roadplan/" class="button is-inline-block-widescreen is-block"><i class="fas fa-truck"></i>&nbsp; Путевой лист</a></div>
                <div class="navbar-item"><a href="/pdf/daily_report.pdf" class="button is-inline-block-widescreen is-block"><i class="fas fa-address-book"></i>&nbsp; Суточный отчет</a>
                </div>

            </div>
            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="navbar-item"><a href="/dictionaries" class="button is-inline-block-widescreen is-block"><i class="far fa-list-alt"></i>&nbsp;Справочники</a></div>
                </div>
                <div class="navbar-item">
                    <form action="/logout" id="logout" method="post"><input type="hidden" name="_token" :value="csrf"><a @click="logout"
                                                                                                                         class="  is-inline-block-widescreen is-block button is-primary is-outlined"><i
                            class="fas fa-door-open"></i>&nbsp;Выход</a></form>
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
            },
            logout: function () {
                document.getElementById('logout').submit();
            }
        }
    }
</script>

<style>

</style>