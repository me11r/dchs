<template>
    <nav class="navbar app-navbar printing-invisible">
        <div class="navbar-brand">
            <a
                class="navbar-item navbar-logo"
                href="/">
                <img src="/assets/logo.png">
            </a>
            <a
                role="button"
                class="navbar-burger"
                @click="toggleOpen"
                :class="menuClass"
            >
                <span></span><span></span><span></span>
            </a>
        </div>
        <div
            class="navbar-menu"
            :class="menuClass">
            <div class="navbar-start">
                <div class="navbar-item has-dropdown is-hoverable is-small">
                    <a class="navbar-link is-small"><i class="fas fa-address-book fa-fw"></i>&nbsp;Карточки</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight(2)"
                            href="/card/101"
                            class="dropdown-item is-small"><i class="fas fa-address-card fa-fw"></i>&nbsp;
                            Карточка 101</a>
                        <a
                            v-if="hasRight(33)"
                            href="/card112"
                            class="dropdown-item is-small"><i
                            class="fas fa-address-card fa-fw"></i>&nbsp; Карточка 112
                        </a>
                    </div>
                </div>
                <div
                    class="navbar-item has-dropdown is-hoverable is-small"
                    v-if="hasAnyRight(12,13,14,15,16,17,18,19)">
                    <a
                        href="/formation"
                        class="navbar-link is-small">
                        <i class="fas fa-address-book fa-fw"></i>&nbsp;
                        Строевые записки
                    </a>
                    <div class="navbar-dropdown">
                        <a
                            href="/formation/101"
                            class="dropdown-item is-small"
                            v-if="hasRight(12)"
                        >
                            <i class="fas fa-truck fa-fw"></i>&nbsp;СП и АСР
                        </a>
                        <a
                            href="/formation-record/mudflow"
                            class="dropdown-item is-small"
                            v-if="hasRight(15)">
                            <i class="fas fa-truck fa-fw"></i>&nbsp;ГУ «Казселезащита»
                        </a>
                        <a
                            href="/formation/savers"
                            class="dropdown-item is-small"
                            v-if="hasRight(13)">
                            <i class="fas fa-truck fa-fw"></i>&nbsp;ГУ «РОСО КЧС МВД РК»
                        </a>
                        <a
                            href="/formation-record/medical"
                            class="dropdown-item is-small"
                            v-if="hasRight(14)">
                            <i class="fas fa-truck fa-fw"></i>&nbsp;ГУ «Центр медицины катастроф»
                        </a>
                        <a
                            href="/formation/air-rescue"
                            class="dropdown-item is-small"
                            v-if="hasRight(16)"
                        >
                            <i class="fas fa-truck fa-fw"></i>&nbsp;АО "Казавиаспас"
                        </a>
                        <a
                            href="/formation-record/ort_sert"
                            class="dropdown-item is-small"
                            v-if="hasRight(17)">
                            <i class="fas fa-truck fa-fw"></i>&nbsp;АО "Өртсөндіруші"
                        </a>
                        <a
                            href="/formation-record/emergency_almaty"
                            class="dropdown-item is-small"
                            v-if="hasRight(19)">
                            <i class="fas fa-truck fa-fw"></i>&nbsp;Служба спасения г.Алматы
                        </a>
                        <!--<a
                                href="/formation-record/district-managers"
                                class="dropdown-item is-small"
                                v-if="hasRight(18)">
                            <i class="fas fa-truck"></i>&nbsp;Ответственные по районным отделам г.Алматы
                        </a>-->
                        <a
                            href="/formation-record"
                            class="dropdown-item is-small"
                            v-if="hasRight(18)">
                            <i class="fas fa-truck fa-fw"></i>&nbsp;Журнал строевых записок ДЧС г.Алматы
                        </a>
                    </div>
                </div>
                <div
                    v-if="hasAnyRight(25,26,27,28,29,30)"
                    class="navbar-item has-dropdown is-hoverable is-small">
                    <a class="navbar-link is-small"><i class="fas fa-inbox fa-fw"></i>&nbsp;Ввод данных</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight(25)"
                            href="/chats"
                            class="dropdown-item is-small"><i class="fas fa-address-book fa-fw"></i>&nbsp;
                            Ручной ввод хронометража</a>
                        <a
                            v-if="hasRight(26)"
                            href="/hydrant"
                            class="dropdown-item is-small"><i class="fas fa-truck fa-fw"></i>&nbsp;
                            Расположение гидрантов</a>
                        <a
                            v-if="hasRight(27)"
                            href="/vehicles"
                            class="dropdown-item is-small"><i class="fas fa-car fa-fw"></i>&nbsp;
                            Транспортные средства</a>
                        <a
                            v-if="hasRight(28)"
                            href="/staff"
                            class="dropdown-item is-small"><i class="fas fa-child fa-fw"></i>&nbsp;
                            Личный состав</a>
                        <a
                            v-if="hasRight(29)"
                            href="/schedules"
                            class="dropdown-item is-small"><i class="fas fa-fire fa-fw"></i>&nbsp;
                            Пожарные части</a>
                        <a
                            v-if="hasRight(30)"
                            href="/morainic-lakes"
                            class="dropdown-item is-small"><i class="fas fa-allergies fa-fw"></i>&nbsp;
                            Моренные озера</a>
                    </div>
                </div>
                <div
                    class="navbar-item has-dropdown is-hoverable is-small"
                    v-if="hasAnyRight(11,21,22,23,24)">
                    <a class="navbar-link is-small"><i class="fas fa-receipt fa-fw"></i>&nbsp;Отчетность</a>
                    <div class="navbar-dropdown">
                        <div
                            v-if="hasRight(11)"
                            class="dropdown-item">
                            <div class="level is-mobile">
                                <div class="level-left">
                                    <i class="fas fa-receipt fa-fw"></i>&nbsp;Суточный отчет
                                </div>
                                <div class="level-right">
                                    <div class="buttons is-right">
                                        <a
                                            href="/pdf/dailyReport"
                                            target="_blank"
                                            class=" button is-small">
                                            <i class="fas fa-file-pdf"></i>.pdf
                                        </a>
                                        <a
                                            href="/reports/daily101/word"
                                            target="_blank"
                                            class=" button is-small">
                                            <i class="fas fa-file-word"></i>.doc
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="dropdown-item"
                            v-if="hasRight(11)">
                            <div class="level is-mobile">
                                <div class="level-left">
                                    <i class="fas fa-receipt fa-fw"></i>&nbsp;
                                    Суточный отчет 112
                                </div>
                                <div class="level-right">
                                    <div class="buttons">
                                        <a
                                            href="/pdf/operational-report"
                                            target="_blank"
                                            class="button is-small"><i class="fas fa-file-pdf"></i>.pdf
                                        </a>
                                        <a
                                            href="/reports/daily112/word"
                                            target="_blank"
                                            class=" button is-small">
                                            <i class="fas fa-file-word"></i>.doc
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <a
                            v-if="hasRight(21)"
                            href="/information"
                            class="dropdown-item is-small"><i class="fas fa-address-book fa-fw"></i>&nbsp;
                            Информация</a>
                        <a
                            v-if="hasRight(22)"
                            href="/emergency-situation"
                            class="dropdown-item is-small"><i class="fas fa-hand-spock fa-fw"></i>&nbsp;
                            Оперативная информация</a>
                        <a
                            v-if="hasRight(23)"
                            href="/reports/101/staff"
                            class="dropdown-item is-small"><i class="fas fa-hand-spock fa-fw"></i>&nbsp;
                            Отчет по ЛС</a>
                        <a
                            v-if="hasRight(24)"
                            href="/reports/101/vehicles"
                            class="dropdown-item is-small"><i class="fas fa-hand-spock fa-fw"></i>&nbsp;
                            Отчет по технике</a>
                        <a
                            v-if="hasRight(24)"
                            href="/reports/101/emergency"
                            class="dropdown-item is-small"><i class="fas fa-band-aid fa-fw"></i>&nbsp;
                            Отчет по карточке 101 за период</a>
                        <a
                            v-if="hasRight(24)"
                            href="/reports/112/emergency"
                            class="dropdown-item is-small"><i class="fas fa-bolt fa-fw"></i>&nbsp;
                            Отчет по карточке 112 за период</a>
                        <a
                            v-if="hasRight(24)"
                            href="/reports/112/branches"
                            class="dropdown-item is-small"><i class="fas fa-tree"></i>
                            &nbsp; Подтопления / Падение веток и деревьев</a>
                        <a
                            v-if="hasRight(24)"
                            href="/reports/101/forces-resources/"
                            class="dropdown-item is-small"><i class="fas fa-archive"></i>
                            &nbsp; Учет сил и средств</a>
                    </div>
                </div>
                <div class="navbar-item has-dropdown is-hoverable is-small">
                    <a class="navbar-link is-small"><i class="fas fa-fw fa-road"></i>&nbsp;Путевые листы</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight(9)"
                            href="/roadtrip/"
                            class="dropdown-item">
                            <i class="fas fa-truck fa-fw"></i>&nbsp;
                            Путевые листы ДЧС
                        </a>
                        <a
                            v-if="hasRight(32)"
                            href="/service-plans/"
                            class="dropdown-item">
                            <i class="fas fa-archway fa-fw"></i>&nbsp;
                            Путевые листы служб взаимодействия
                        </a>

                    </div>
                </div>
            </div>
            <div class="navbar-end">
                <div
                    class="navbar-item has-dropdown is-hoverable is-small"
                    v-if="hasAnyRight(7,8)">
                    <a
                        href="#"
                        class="navbar-link is-small"><i class="fas fa-cog fa-fw"></i>&nbsp;Управление</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight(8)"
                            href="/dictionaries"
                            class="dropdown-item"><i class="fas fa-list-alt fa-fw"></i>&nbsp;Справочники</a>
                        <a
                            v-if="hasRight(7)"
                            href="/admin/users"
                            class="dropdown-item"><i class="fas fa-user fa-fw"></i>&nbsp;Пользователи</a>
                        <a
                            v-if="hasRight(7)"
                            href="/admin/roles"
                            class="dropdown-item"><i class="fas fa-balance-scale fa-fw"></i>&nbsp;Роли</a>
                        <a
                            href="/import"
                            class="dropdown-item"><i class="fas fa-arrow-down fa-fw"></i>&nbsp;Импорт</a>
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
                                class="is-inline-block-widescreen is-block button is-primary logout-button">
                                <i class="fas fa-door-open fa-fw"></i>&nbsp;Выход
                            </a>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import axios from 'axios';
function getLocalRights() {
    let rights = window.localStorage.getItem('preloaded_rights');
    if (rights !== undefined) {
        rights = JSON.parse(rights);
    } else {
        rights = [];
    }
    return rights;
}
export default {
    name: 'Navbar',
    data: function () {
        return {
            opened: false,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            rights: getLocalRights()
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
            return this.rights.indexOf(id) !== -1;
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
            window.localStorage.setItem('preloaded_rights', JSON.stringify(this.rights));
        });
    }

};
</script>

<style lang="scss">
    @import "../../../assets/sass/variables";

    .app-navbar {
        min-height: 35px;
        transition: all 1s ease-out;
        border-bottom: 2px solid $grey-lighter;
        box-shadow: 0 2px 2px $white-ter;

        a.navbar-burger:hover {
            color: $white !important;
        }
        .navbar-item {
            padding: 0.5rem 0.75rem 0.5rem 0;
            &.navbar-logo {
                color: $grey-dark;
                padding: .50rem;
                min-width: 2.75rem;
                text-align: center;
            }
            > a:not(.navbar-link) {
                border: 1px solid #dbdbdb;
                border-radius: 4px;
                &:hover {
                    color: $primary;
                    background-color: $cyan;
                    .fas {
                        color: $white;
                    }
                }
            }
            &:hover > .navbar-link {
                background-color: $cyan;
                color: $primary;
                .fas {
                    color: $white;
                }
            }
        }

        .navbar-link, .logout-button, .button {
            font-size: .9rem;
            color: $white;
            height: 2.25rem;
            line-height: 1.5;
            border-radius: 3px;
            border: 1px solid $white;
            background-color: $primary;
            text-transform: uppercase;
            padding-top: .5rem;
            padding-bottom: .5rem;
            &:hover {
                background-color: $cyan;
                color: $white;
             .fas {
                 color: $white;
             }
            }
            .fas {
                color: $cyan;
            }
        }

        .navbar-dropdown {
            border-top-width: 3px;
            border-bottom: 3px solid $cyan;
            background-color: $primary;
            border-left: 1px solid $grey-light;
            .dropdown-item {
                i.fas {
                    transition: all 1s ease-out;
                }
                color: $white-bis;
            }
            .dropdown-item:hover {
                background-color: $cyan;
                color: $primary;
                i.fas {
                    color: $white;
                }
            }
        }
    }
</style>
