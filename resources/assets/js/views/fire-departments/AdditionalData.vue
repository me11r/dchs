<template>
    <div>
        <table v-if="results.length" class="table is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>ПЧ</th>
                    <th>Отделение</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in results">
                    <td>{{ item.department.title }}</td>
                    <td>{{ item.tech.department }}</td>
                    <td>
                        <a
                                @click.prevent="markDeptArrived(item)"
                                v-if="item.dispatched === 1 && item.arrive_time === null && item.retreat_time === null"
                                class="button is-warning is-outlined"
                                href=""><i class="fas fa-retweet"></i>&nbsp;Отметить прибытие: отделение -  {{ item.tech.department ? item.tech.department : item.promoted_department }}</a>
                        <a
                                @click.prevent="markDeptReturned(item)"
                                v-else-if="(item.arrive_time !== null || item.retreat_time !== null) && item.ret_time === null"
                                class="button is-success is-outlined"
                                href=""><i class="fas fa-retweet"></i>&nbsp;Отметить возвращение: отделение -  {{ item.tech.department ? item.tech.department : item.promoted_department }}</a>
                        <a
                                v-else-if="item.ret_time !== null"
                                class="button is-disabled"
                                href="#"><i class="fas fa-retweet"></i>&nbsp;Отделение вернулось: {{ item.tech.department ? item.tech.department : item.promoted_department }}</a>
                        <button
                                v-if="item.retreat_time === null && item.arrive_time !== null && item.ret_time === null"
                                @click.prevent="retreat(item)"
                                class="button is-warning"
                        >Отбой</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <form action="">
            <input
                type="hidden"
                name="_token"
                :value="csrf">
            <div class="tabs buttab is-boxed">
                <ul>
                    <li :class="{'is-active': currentTabIndex === 0}">
                        <a @click="setTab(0)"><i class="fas fa-aviato"></i>&nbsp;Хронология
                    </a></li>
                    <li :class="{'is-active': currentTabIndex === 1}">
                        <a
                            @click="setTab(1)">
                            <i class="fas fa-check-double"></i>&nbsp;Итоги выезда
                    </a></li>
                </ul>
            </div>
            <div class="panels">
                <div :style="{'display': currentTabIndex === 0? 'block': 'none'}">
                    <ticket101-chronology-from-fd
                        :records="ticket.chronologies_from_fd"
                        :departments="departmentsOnWay"
                        :card="ticket"
                        :event-info="eventInfos"
                        :event-info-arrived="eventInfosArrived"
                        :fire_department_id="fire_department_id"
                        :trunk-types="trunkTypes"
                    />
                </div>
                <div :style="{'display': currentTabIndex === 1? 'block': 'none'}">
                    <ticket101-summary-from-fd
                        :ticket="ticketInfo"
                        :burn_object="burn_object"
                        :living_sector_types="living_sector_types"
                        :trip_result="trip_result"
                        :liquidation_methods="liquidation_methods"
                        :fire_levels="fire_levels"
                        :original-ticket="ticket"
                        :water_sources="water_sources"/>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import {globalBus} from '../../scripts/global-bus';
import axios from 'axios';
import rights from '../../scripts/rights';
export default {
    data () {
        return {
            currentTabIndex: 0,
            ticket: window.ticket101fd.ticket,
            results: window.ticket101fd.results,
            trip: window.ticket101fd.trip,
            csrf: window.csrf_token,
            eventInfosArrived: window.ticket101fd.eventInfosArrived,
            eventInfos: window.ticket101fd.eventInfos,
            departmentsOnWay: window.ticket101fd.departmentsOnWay,
            burn_object: window.ticket101fd.burn_object,
            living_sector_types: window.ticket101fd.living_sector_types,
            trip_result: window.ticket101fd.trip_result,
            liquidation_methods: window.ticket101fd.liquidation_methods,
            fire_levels: window.ticket101fd.fire_levels,
            water_sources: window.ticket101fd.water_sources,
            ticketInfo: window.ticket101fd.ticketInfo,
            fire_department_id: window.ticket101fd.fire_department_id,
            departmentsArrived: window.ticket101fd.departmentsArrived,
            trunkTypes: window.ticket101fd.trunk_types
        };
    },
    computed: {
        hasEditRight() {
            if (window.user_id === 1) {
                return true;
            }
            return rights.hasAnyRight(['CAN_CHANGE_TRIP_PLAN']);
        }
    },
    methods: {
        openNoEditRightsToast() {
            this.$toast.open({
                duration: 5000,
                message: `Нет прав на редактирование`,
                position: 'is-bottom',
                type: 'is-danger'
            });
        },
        setTab(tabIndex) {
            this.currentTabIndex = tabIndex;
        },
        retreat(result) {
            if (!this.hasEditRight) {
                this.openNoEditRightsToast();
                return;
            }
            //признак того, что мы отзываем отделение из вкладки "Высылка"
            //время прибытия обнуляется
            let props = {
                force: false
            };

            let ticketId = result.ticket101_id;
            let fire_department_id = result.fire_department_id;
            let dept_number = result.tech.id;

            axios.post('/roadtrip/retreat/' + fire_department_id + '/' + ticketId + '/' + dept_number, props)
                .then((response) => {
                    alert(`Отбой произведен`);
                    result.retreat_time = new Date();
                    this.departmentsArrived = this.departmentsArrived.filter((item) => {
                        return item.id !== result.id;
                    });

                    globalBus.$emit('retreated-from-roadtrip', {item: response.data.fd_chronology_item});


                }).catch((e) => {
                    console.dir(e);
                });
        },

        dispatchDept() {
            if (!this.hasEditRight) {
                this.openNoEditRightsToast();
                return;
            }
            let self = this;
            axios.post('/roadtrip/dispatch', {
                dept_id: self.dep_.id
            }).then((resp) => {
                self.is_dispatched_ = 1;
            });
        },
        markDeptArrived(dept) {
            if (!this.hasEditRight) {
                this.openNoEditRightsToast();
                return;
            }
            axios.post('/roadtrip/arrived', {
                dept_id: dept.id
            }).then((resp) => {
                dept.arrive_time = new Date();
            });
        },
        markDeptReturned(dept) {
            if (!this.hasEditRight) {
                this.openNoEditRightsToast();
                return;
            }
            axios.post('/roadtrip/return', {
                dept_id: dept.id
            }).then((resp) => {
                dept.ret_time = new Date ();
            });
        }
    }
};
</script>
