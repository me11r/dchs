<template>
    <nav class="navbar app-navbar">
        <div class="navbar-brand">
            <a
                class="navbar-item"
                href="/"><i class="fas fa-tty fa-fw fa-2x"></i></a>
            <a
                role="button"
                class="navbar-burger"
                @click="toggleOpen"><span></span><span></span><span></span></a>
        </div>
        <div
            class="navbar-menu"
            :class="menuClass">
            <div class="navbar-start">
                <div class="navbar-item has-dropdown is-hoverable is-small">
                    <a class="navbar-link is-small"><i class="fas fa-address-book"></i>&nbsp;Карточки</a>
                    <div class="navbar-dropdown">
                        <a
                            href="/card/101"
                            class="dropdown-item is-small"><i class="fas fa-address-card"></i>&nbsp;
                            Карточка 101</a>
                        <a
                            href="/card112"
                            class="dropdown-item is-small"><i
                            class="fas fa-address-card"></i>&nbsp; Карточка 112
                        </a>
                    </div>
                </div>
                <div
                    class="navbar-item has-dropdown is-hoverable is-small"
                    v-if="hasAnyRight(12,13,14,15,16,17)">
                    <a
                        href="/formation"
                        class="navbar-link is-small">
                        <i class="fas fa-address-book"></i>&nbsp;
                        Строевые записки
                    </a>
                    <div class="navbar-dropdown">
                        <a
                            href="/formation/101"
                            class="dropdown-item is-small"
                            v-if="hasRight(12)"
                        >
                            <i class="fas fa-truck"></i>&nbsp;СП и АСР
                        </a>
                        <a
                            href="/formation-record/mudflow"
                            class="dropdown-item is-small"
                            v-if="hasRight(15)">
                            <i class="fas fa-truck"></i>&nbsp;ГУ «Казселезащита»
                        </a>
                        <a
                            href="/formation/savers"
                            class="dropdown-item is-small"
                            v-if="hasRight(13)">
                            <i class="fas fa-truck"></i>&nbsp;ГУ «РОСО КЧС МВД РК»
                        </a>
                        <a
                            href="/formation-record/medical"
                            class="dropdown-item is-small"
                            v-if="hasRight(14)">
                            <i class="fas fa-truck"></i>&nbsp;ГУ «Центр медицины катастроф»
                        </a>
                        <a
                            href="/formation-record/air_rescue"
                            class="dropdown-item is-small"
                            v-if="hasRight(16)"
                        >
                            <i class="fas fa-truck"></i>&nbsp;АО "Казавиаспас"
                        </a>
                        <a
                            href="/formation-record/ort_sert"
                            class="dropdown-item is-small"
                            v-if="hasRight(17)">
                            <i class="fas fa-truck"></i>&nbsp;АО "Өртсөндіруші"
                        </a>
                        <a
                            href="/formation-record"
                            class="dropdown-item is-small"
                            v-if="hasRight(18)">
                            <i class="fas fa-truck"></i>&nbsp;ДЧС г.Алматы
                        </a>

                    </div>
                </div>
                <div class="navbar-item has-dropdown is-hoverable is-small">
                    <a class="navbar-link is-small"><i class="fas fa-inbox"></i>&nbsp;Ввод данных</a>
                    <div class="navbar-dropdown">
                        <a
                            href="/chats"
                            class="dropdown-item is-small"><i class="fas fa-address-book"></i>&nbsp;
                            Ручной ввод хронометража</a>
                        <a
                            v-if="hasRight(10)"
                            href="/hydrant"
                            class="dropdown-item is-small"><i class="fas fa-truck"></i>&nbsp;
                            Расположение гидрантов</a>
                        <a
                            v-if="hasRight(10)"
                            href="/vehicles"
                            class="dropdown-item is-small"><i class="fas fa-car"></i>&nbsp;
                            Транспортные средства</a>
                        <a
                            v-if="hasRight(10)"
                            href="/schedules"
                            class="dropdown-item is-small"><i class="fas fa-fire"></i>&nbsp;
                            Пожарные части</a>
                    </div>
                </div>
                <div
                    class="navbar-item has-dropdown is-hoverable is-small"
                    v-if="hasAnyRight(11)">
                    <a class="navbar-link is-small"><i class="fas fa-receipt"></i>&nbsp;Отчетность</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight(11)"
                            href="/pdf/dailyReport"
                            target="_blank"
                            class="dropdown-item is-small">
                            <i class="fas fa-address-card"></i>&nbsp;
                            Суточный отчет
                        </a>
                        <a
                            href="/information"
                            class="dropdown-item is-small"><i class="fas fa-address-book"></i>&nbsp;
                            Информация</a>
                        <a
                            href="/emergency-situation"
                            class="dropdown-item is-small"><i class="fas fa-hand-spock"></i>&nbsp;
                            Оперативная информация</a>
                    </div>
                </div>
                <div class="navbar-item">
                    <a
                        href="/roadtrip/"
                        class="button is-inline-block-widescreen is-block is-small">
                        <i class="fas fa-truck"></i>&nbsp;
                        Путевой лист
                    </a>
                </div>
            </div>
            <div class="navbar-end">
                <div
                    class="navbar-item has-dropdown is-hoverable is-small"
                    v-if="hasAnyRight(7,9)">
                    <a
                        href="#"
                        class="navbar-link is-small"><i class="fas fa-cog"></i>&nbsp;Управление</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight(9)"
                            href="/dictionaries"
                            class="navbar-item"><i class="far fa-list-alt"></i>&nbsp;Справочники</a>
                        <a
                            v-if="hasRight(7)"
                            href="/admin/users"
                            class="navbar-item"><i class="fas fa-user"></i>&nbsp;Пользователи</a>
                        <a
                            href="/import"
                            class="navbar-item"><i class="fas fa-arrow-down"></i>&nbsp;Импорт</a>
                    </div>
                </div>
                <div class="navbar-item">
                    <form
                        action="/logout"
                        id="logout"
                        method="post"><input
                            type="hidden"
                            name="_token"
                            :value="csrf"><a
                                @click="logout"
                                class="is-inline-block-widescreen is-block button is-primary is-outlined is-small">
                                <i class="fas fa-door-open"></i>&nbsp;Выход
                            </a>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Navbar',
    data: function () {
        return {
            opened: false,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            rights: []
        };
    },
    computed: {
        menuClass: function () {
            return this.opened ? 'is-active' : '';
        }
    },
    methods: {
        toggleOpen: function () {
            this.opened = !this.opened;
        },

        logout: function () {
            document.getElementById('logout').submit();
        },
        hasRight: function (id) {
            return this.rights[id] !== undefined;
        },
        hasAnyRight: function () {
            const ids = Array.from(arguments);
            let hasRight = false;
            ids.forEach((value, index) => {
                if (this.hasRight(value)) {
                    hasRight = true;
                }
            });
            return hasRight;
        }

    },
    mounted: function () {
        axios.get('/ajax/rights/list').then((response) => {
            this.rights = response.data;
        });
    }

};
</script>

<style lang="scss">
    @import "../../../assets/sass/variables";
    .navbar-link {
        font-size: .9rem;
    }
    .navbar-dropdown {
        border-top-width: 3px;
        border-bottom: 3px solid $primary;
        .dropdown-item {
            i.fas {
                transition: all 1s ease-out;
            }
        }
        .dropdown-item:hover {
            i.fas {
                color: tomato;
            }
        }
    }
    .navbar-item {
        padding: 0.5rem 0.75rem 0.5rem 0;
    }
</style>
