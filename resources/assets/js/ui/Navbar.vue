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
                    <a class="navbar-link is-small"><i class="fas fa-address-book fa-fw"></i>&nbsp;101</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight(2)"
                            href="/card/add101"
                            class="dropdown-item is-small"><i class="fas fa-address-card fa-fw"></i>&nbsp;
                            Путевка 101
                        </a>
                        <a
                            v-if="hasRight('CARD101_ACCESS_OTHERS_RIDES')"
                            href="/card101-other-rides/create"
                            class="dropdown-item is-small"><i class="fas fa-address-card fa-fw"></i>&nbsp;
                            Прочие выезда
                        </a>
                        <a
                            v-if="hasRight('CARD101_ACCESS_DRILL_RIDES')"
                            href="/card/add101/0/drill"
                            class="dropdown-item is-small"><i class="fas fa-address-card fa-fw"></i>&nbsp;
                            Учения
                        </a>
                        <a
                            v-if="hasRight('CAN_ACCESS_NORMS_PSP')"
                            href="/norms-psp"
                            class="dropdown-item is-small"><i
                            class="fas fa-address-card fa-fw"></i>&nbsp;
                            Нормативы ПСП
                        </a>
                        <a
                            v-if="hasRight(2)"
                            href="/card/101"
                            class="dropdown-item is-small"><i
                            class="fas fa-address-card fa-fw"></i>&nbsp;
                            Карточки 101
                        </a>
                    </div>
                </div>
                <div class="navbar-item has-dropdown is-hoverable is-small">
                    <a class="navbar-link is-small"><i class="fas fa-address-book fa-fw"></i>&nbsp;112</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight(33)"
                            href="/card112/create"
                            class="dropdown-item is-small"><i class="fas fa-address-card fa-fw"></i>&nbsp;
                            Путевка 112
                        </a>
                        <a
                            v-if="hasRight(33)"
                            href="/card112"
                            class="dropdown-item is-small"><i
                            class="fas fa-address-card fa-fw"></i>&nbsp;
                            Карточки 112
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
                    v-if="hasAnyRight('CAN_ACCESS_MANUAL_INPUT_CHRONO','CAN_ACCESS_HYDRANT','CAN_ACCESS_TECH','CAN_ACCESS_PERSONS',29,30,'CAN_ACCESS_HYDRANT')"
                    class="navbar-item has-dropdown is-hoverable is-small">
                    <a class="navbar-link is-small"><i class="fas fa-inbox fa-fw"></i>&nbsp;Ввод данных</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight('CAN_ACCESS_MANUAL_INPUT_CHRONO')"
                            href="/chats"
                            class="dropdown-item is-small"><i class="fas fa-address-book fa-fw"></i>&nbsp;
                            Ручной ввод хронометража</a>
                        <a
                            v-if="hasRight('CAN_ACCESS_TECH')"
                            href="/vehicles"
                            class="dropdown-item is-small"><i class="fas fa-car fa-fw"></i>&nbsp;
                            Транспортные средства</a>
                        <a
                            v-if="hasRight('CAN_ACCESS_PERSONS')"
                            href="/staff"
                            class="dropdown-item is-small"><i class="fas fa-child fa-fw"></i>&nbsp;
                            Личный состав</a>
                        <a
                            v-if="hasRight('CAN_ACCESS_FIRE_DEPTS')"
                            href="/schedules"
                            class="dropdown-item is-small"><i class="fas fa-fire fa-fw"></i>&nbsp;
                            Пожарные части</a>
                        <a
                            href="/fire-department-checks"
                            class="dropdown-item is-small"><i class="fas fa-check fa-fw"></i>&nbsp;
                            Проверка пожарных частей</a>
                        <a
                            v-if="hasRight('CAN_ACCESS_HYDRANT')"
                            href="/hydrants"
                            target="_blank"
                            class="dropdown-item is-small"><i class="fas fa-eye-dropper fa-fw"></i>&nbsp;
                            Карта гидрантов</a>
                        <a
                            v-if="hasRight('CAN_ACCESS_SALVAGE')"
                            href="/salvage"
                            class="dropdown-item is-small"><i class="fas fa-address-book fa-fw"></i>&nbsp;
                            Сумма спасенного имущества</a>
                    </div>
                </div>
                <div
                    class="navbar-item has-dropdown is-hoverable is-small"
                    v-if="hasAnyRight('CAN_ACCESS_REPORTS_TAB')">
                    <a class="navbar-link is-small"><i class="fas fa-receipt fa-fw"></i>&nbsp;Отчетность</a>
                    <div class="navbar-dropdown report-dropdown">
                        <div
                            v-if="hasRight('CAN_SEE_DAILY_REPORT')"
                            class="dropdown-item">
                            <div class="level is-mobile">
                                <div class="level-left">
                                    <a
                                        class="button is-bordered"
                                        href="/reports/daily-reports/101">
                                        <i class="fas fa-receipt fa-fw"></i>&nbsp;Суточный отчет
                                    </a>
                                </div>

                                <div class="level-right">
                                    <div class="buttons is-right">
                                        <!--<a
                                            href="/pdf/dailyReport"
                                            target="_blank"
                                            class=" button is-small">
                                            <i class="fas fa-file-pdf"></i>.pdf
                                        </a>-->
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
                            v-if="hasRight('CAN_SEE_DAILY_REPORT')"
                            class="dropdown-item">
                            <div class="level is-mobile">
                                <div class="level-left">
                                    <a
                                        class="button is-bordered"
                                        href="/reports/daily-reports/112">
                                        <i class="fas fa-receipt fa-fw"></i>&nbsp;Суточный отчет 112
                                    </a>
                                </div>

                                <div class="level-right">
                                    <div class="buttons is-right">
                                        <!--<a-->
                                            <!--href="/pdf/operational-report"-->
                                            <!--target="_blank"-->
                                            <!--class=" button is-small">-->
                                            <!--<i class="fas fa-file-pdf"></i>.pdf-->
                                        <!--</a>-->
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
                            v-if="hasAnyRight('CAN_ACCESS_REPORT_101_EMERGENCY_PERIOD',
                                              'CAN_ACCESS_REPORT_EMERGENCY_RESCUE_GU',
                                              'CAN_ACCESS_REPORT_FORCES_RESOURCES',
                                              'CAN_ACCESS_REPORT_DRILL_RIDES',
                                              'CAN_ACCESS_REPORT_OTHER_RIDES',
                                              'CAN_ACCESS_REPORT_OBJECT_CLASSIFICATION',
                                              'WATER_CONSUMPTION_REPORT_SHOW'
                            )"
                            href="/reports/analytics-spiasr"
                            class="dropdown-item is-small"><i class="fas fa-database"></i>
                            &nbsp; Аналитика СПиАСР
                        </a>

                        <a
                            v-if="hasAnyRight('ANALYTICS101_SHOW',
                                              'CAN_ACCESS_REPORT_112_EMERGENCY_REPORT',
                                              'CAN_ACCESS_REPORT_OTHER_RIDES',
                                              'CAN_ACCESS_REPORT_DRILL_RIDES',
                                              'CAN_ACCESS_REPORT_FORCES_RESOURCES',
                                              'CAN_ACCESS_REPORT_OBJECT_CLASSIFICATION',
                                              'CAN_ACCESS_REPORT_EMERGENCY_RESCUE_GU',
                                              'CAN_SEE_DAILY_REPORT',
                                              'CAN_ACCESS_REPORT_OBJECT_CLASSIFICATION'
                            )"
                            href="/reports/analytics101/"
                            class="dropdown-item is-small"><i class="fas fa-database"></i>
                            &nbsp; Суточные отчеты
                        </a>
                        <!--<a class="dropdown-item is-small"-->
                        <!--v-if="hasRight('CAN_SEE_DAILY_REPORT')"-->
                        <!--href="/reports/daily-reports/101">-->
                        <!--<i class="fas fa-database"></i>&nbsp; Суточный отчет 101</a>-->
                        <!--<a class="dropdown-item is-small"-->
                        <!--v-if="hasRight('CAN_SEE_DAILY_REPORT')"-->
                        <!--href="/reports/daily-reports/112">-->
                        <!--<i class="fas fa-database"></i>&nbsp; Суточный отчет 112</a>-->

                        <a
                            class="dropdown-item is-small"
                            v-if="hasRight('ANALYTICS112_SHOW')"
                            href="/reports/112">
                        <i class="fas fa-database"></i>&nbsp; Аналитика ДЧС</a>

                        <!--<div-->
                        <!--class="dropdown-item"-->
                        <!--v-if="hasRight('CAN_SEE_DAILY_REPORT')">-->
                        <!--<div class="level is-mobile">-->
                        <!--<div class="level-left">-->
                        <!--<i class="fas fa-receipt fa-fw"></i>&nbsp;-->
                        <!--Суточный отчет 112-->
                        <!--</div>-->
                        <!--<div class="level-right">-->
                        <!--<div class="buttons">-->
                        <!--<a-->
                        <!--href="/pdf/operational-report"-->
                        <!--target="_blank"-->
                        <!--class="button is-small"><i class="fas fa-file-pdf"></i>.pdf-->
                        <!--</a>-->
                        <!--<a-->
                        <!--href="/reports/daily112/word"-->
                        <!--target="_blank"-->
                        <!--class=" button is-small">-->
                        <!--<i class="fas fa-file-word"></i>.doc-->
                        <!--</a>-->
                        <!--</div>-->
                        <!--</div>-->
                        <!--</div>-->

                        <!--</div>-->
                        <!--<a
                            v-if="hasRight(21)"
                            href="/information"
                            class="dropdown-item is-small"><i class="fas fa-address-book fa-fw"></i>&nbsp;
                            Информация</a>
                        <a
                            v-if="hasRight(22)"
                            href="/emergency-situation"
                            class="dropdown-item is-small"><i class="fas fa-hand-spock fa-fw"></i>&nbsp;
                            Оперативная информация</a>-->
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
                            v-if="hasRight('SIREN_SPEECH_TECH_SHOW')"
                            href="/reports/siren-speeches/"
                            class="dropdown-item is-small"><i class="fas fa-sad-cry"></i>
                            &nbsp; Данные по СРУ
                        </a>
                        <a
                            v-if="hasRight('CALL_INFO_SHOW')"
                            href="/reports/call-infos/"
                            class="dropdown-item is-small"><i class="fas fa-amazon-pay"></i>
                            &nbsp; Информация по звонкам
                        </a>
                    </div>
                </div>
                <div
                    class="navbar-item has-dropdown is-hoverable is-small"
                    v-if="hasAnyRight(21,22)">
                    <a class="navbar-link is-small"><i class="fas fa-info fa-fw"></i>&nbsp;Информация</a>
                    <div class="navbar-dropdown">
                        <a
                            v-if="hasRight(21)"
                            href="/information"
                            class="dropdown-item is-small"><i class="fas fa-address-book fa-fw"></i>&nbsp;
                            Информация от служб взаимодействия</a>
                        <a
                            v-if="hasRight(22)"
                            href="/emergency-situation"
                            class="dropdown-item is-small"><i class="fas fa-hand-spock fa-fw"></i>&nbsp;
                            Оперативная информация</a>
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
                            Путевые листы ПЧ
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
                            href="/admin/messenger-permissions"
                            class="dropdown-item"><i class="fas fa-magic fa-fw"></i>&nbsp;Разрешения мессенджера</a>
                        <a
                            v-if="hasRight(7)"
                            href="/admin/roles"
                            class="dropdown-item"><i class="fas fa-balance-scale fa-fw"></i>&nbsp;Роли</a>
                        <a
                            v-if="hasRight(7)"
                            href="/admin/polygons"
                            class="dropdown-item"><i class="fas fa-magic fa-fw"></i>&nbsp;Границы микроучастков</a>
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

        <!--Карточка 101: прочие выезда-->
        <div
            class="modal"
            :class="opened_modal101">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">101 карточка</p>
                    <button
                        class="delete"
                        aria-label="close"
                        @click.prevent="toggleOpenModal101"></button>
                </header>
                <section class="modal-card-body">
                    <div class="level">
                        <div class="level-left">
                            <a
                                class="is-inline-block-widescreen is-block button is-primary"
                                href="/card/add101-other-rides/">Прочие выезда</a>
                        </div>
                        <div class="level-right">
                            <!--<a class="is-inline-block-widescreen is-block button is-primary" href="/card/add101/0/drill/">Учения</a>-->
                            <a
                                class="is-inline-block-widescreen is-block button is-primary"
                                href="/card/add101-drill-rides/">Учения</a>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <!--<button class="button is-success">Save changes</button>-->
                    <!--<button class="button">Cancel</button>-->
                </footer>
            </div>
        </div>
    </nav>
</template>

<script>
import axios from 'axios';
import rights from '../scripts/rights';
function getLocalRights() {
    return rights.rightsList();

    // todo: depricated: moved to rights.js
    /* let rights = window.localStorage.getItem('preloaded_rights');
    if (rights !== undefined) {
        rights = JSON.parse(rights);
    } else {
        rights = [];
    }
    return rights; */
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
        },
        opened_modal101: function () {
            return this.opened_modal101_ ? 'is-active' : '';
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
        // тащим права из базы
        let rightsPromise = rights.getRights();
        rightsPromise.then((list) => {
            this.rights = list;
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

    .reports-block {
        left: 248px !important;
        top: 3% !important;
        background-color: #2A394F !important;
    }

    .reports-block > div {
        background-color: #2A394F !important;
    }

    .report-dropdown {
        width: 350px;
    }

</style>
