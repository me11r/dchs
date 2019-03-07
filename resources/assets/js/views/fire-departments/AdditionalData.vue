<template>
    <div>
        <table v-if="departmentsArrived.length" class="table is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>ПЧ</th>
                    <th>Отделение</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in departmentsArrived">
                    <td>{{ item.department.title }}</td>
                    <td>{{ item.tech.department }}</td>
                    <td>
                        <button @click.prevent="retreat(item)" class="button is-warning">Отбой</button>
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
import {globalBus} from '../../scripts/global-bus'
import axios from 'axios';
export default {
    data () {
        return {
            currentTabIndex: 0,
            ticket: window.ticket101fd.ticket,
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
    methods: {
        setTab(tabIndex) {
            this.currentTabIndex = tabIndex;
        },
        retreat(result) {

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
                    this.departmentsArrived = this.departmentsArrived.filter((item) => {
                        return item.id !== result.id;
                    });

                    globalBus.$emit('retreated-from-roadtrip', {item: response.data.fd_chronology_item});


                }).catch((e) => {
                    console.dir(e);
                });
        },
    }
};
</script>
