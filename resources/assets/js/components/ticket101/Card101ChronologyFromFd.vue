<template>
    <div>
        <b-tabs>
            <b-tab-item
                label="В пути"
                icon="fa fa-truck-moving">
                <table class="table is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th>ПЧ</th>
                            <th>Отделение</th>
                            <th>Количество привлеченного л/с</th>
                            <th>Хронология</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="dept in departments_"
                            :key="`dept_${dept.id}`">
                            <td>{{ dept.department.title }}</td>
                            <td>{{ dept.tech.department }}</td>
                            <td>
                                <input type="number"
                                       v-model="dept.staff_count"
                                       @change="changeStaffCount(dept)"
                                       class="input">
                            </td>
                            <td>
                                <div class="add_button">
                                    <button
                                        class="button is-small is-outlined is-success"
                                        type="button"
                                        @click.prevent="createNewItemOnWay(dept)">
                                        <i class="fa fa-plus"></i>&nbsp;Добавить
                                    </button>
                                </div>

                            </td>
                            <td>
                                <div
                                    class="columns"
                                    v-for="item in records_onway"
                                    v-if="item.fire_department_result.id === dept.id"
                                    :key="item.id">
                                    <div class="column">
                                        <input
                                            type="hidden"
                                            v-model="item.id"
                                        >
                                    </div>

                                    <div class="column">
                                        <label>ПЧ, отделение</label>
                                        <input
                                            disabled
                                            class="input"
                                            type="text"
                                            :value="`${item.fire_department_result.department.title}: ${item.fire_department_result.tech.department}`"
                                        >
                                    </div>
                                    <div class="column">
                                        <label>Время</label>
                                        <timepicker
                                            :inputdate="item.time"
                                            v-model="item.time"
                                            @timeChanged="item.time = $event"
                                            :value="item.time"
                                        />
                                    <!--<timepicker-input
                                            v-model="item.time"
                                            @timeChanged="item.time = $event"
                                            :value-data="item.time"/>-->
                                    </div>

                                    <div class="column">
                                        <label>Ситуация</label>
                                        <div class="select">
                                            <select
                                                required
                                                title="Ситуация"
                                                v-model="item.event_info_id">
                                                <option
                                                    v-for="e in eventInfo_"
                                                    :key="'event_' + e.id"
                                                    :value="e.id">{{ e.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="column">
                                        <label>Информация</label>
                                        <textarea
                                            v-model="item.information"
                                            :id="'on_way['+item.id+'][information]'"
                                            class="textarea"
                                            cols="1"
                                            rows="1"></textarea>
                                    </div>

                                    <div class="column">

                                        <div class="control is-narrow">
                                            <button
                                                class="button is-small is-outlined is-success square-button-36"
                                                @click.prevent="addToTableOnWay(item)"
                                                type="button"
                                                title="Добавить">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <br>
                                        <div class="control is-narrow">
                                            <button
                                                class="button is-small is-outlined is-danger square-button-36"
                                                @click.prevent="removeItemOnWay(item.id)"
                                                type="button"
                                                title="Удалить">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </b-tab-item>
            <b-tab-item
                label="На месте"
                icon="fa fa-truck">
                <table class="table is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th>ПЧ</th>
                            <th>Отделение</th>
                            <th>Расстояние до места</th>
                            <th>Стволы</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="dept in departments_"
                            :key="dept.id">
                            <td>{{ dept.department.title }}</td>
                            <td>{{ dept.tech.department }}</td>
                            <td>
                                <input type="number"
                                       v-model="dept.distance"
                                       @change="changeDistanceCount(dept)"
                                       class="input">
                            </td>
                            <td>
                                <div class="add_button">
                                    <button
                                        class="button is-small is-outlined is-success"
                                        type="button"
                                        @click.prevent="createNewItemArrived(dept)">
                                        <i class="fa fa-plus"></i>&nbsp;Добавить
                                    </button>
                                    <button
                                            class="button is-small is-outlined is-success"
                                            type="button"
                                            @click.prevent="createNewItemArrivedWaterDelivery(dept)">
                                        <i class="fa fa-plus"></i>&nbsp;Подвоз воды
                                    </button>
                                    <button
                                            class="button is-small is-outlined is-success"
                                            type="button"
                                            @click.prevent="createNewItemArrived(dept, true)">
                                        <i class="fa fa-plus"></i>&nbsp;ГДЗС
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div
                                    class="columns"
                                    v-for="item in records_arrived"
                                    v-if="item.fire_department_result.id === dept.id"
                                    :key="item.id">

                                    <div class="column" v-if="!item.is_gdzs && !item.is_water_delivery">
                                        <label>Тип ствола</label>
                                        <div class="select">
                                            <select
                                                    required
                                                    title="Тип ствола"
                                                    v-model="item.event_info.trunk_type_id">
                                                <option :value="null"></option>
                                                <option
                                                        v-for="e in trunkTypes"
                                                        :key="'event_' + e.id"
                                                        :value="e.id">{{ e.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="column" v-if="!item.is_water_delivery">
                                        <label v-if="!item.is_gdzs" :for="'on_way['+item.id+'][event_info_id]'">Стволы</label>
                                        <label v-if="item.is_gdzs" :for="'on_way['+item.id+'][event_info_id]'">ГДЗС</label>
                                        <div v-if="item.is_gdzs" class="control">
                                            <input type="text" disabled class="input" value="ГДЗС">
                                            <input type="hidden" class="input" v-model="item.event_info_arrived_id">
                                        </div>
                                        <div v-if="!item.is_gdzs" class="select">
                                            <select
                                                    required
                                                    title="Ситуация"
                                                    v-model="item.event_info_arrived_id">
                                                <option
                                                        v-for="e in eventInfoArrivedFiltered(item.event_info.trunk_type_id)"
                                                        v-if="e.name !== 'ГДЗС'"
                                                        :key="'event_' + e.id"
                                                        :value="e.id">{{ e.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="item.is_water_delivery" class="column">
                                        <label>Расстояние подвоза</label>
                                        <input
                                                class="input"
                                                type="number"
                                                v-model="item.water_delivery_distance">
                                    </div>

                                    <div class="column">
                                        <label>Количество</label>
                                        <input
                                            class="input"
                                            type="number"
                                            v-model="item.quantity">
                                    </div>

                                    <div class="column">
                                        <label>Время</label>
                                        <timepicker
                                            :inputdate="item.time"
                                            v-model="item.time"
                                            @timeChanged="item.time = $event"
                                            :value="item.time"
                                        />
                                    </div>
                                    <div class="column">
                                        <label>Время работы</label>
                                        <input
                                            class="input"
                                            type="number"
                                            v-model="item.working_time">
                                    </div>

                                    <div class="column">
                                        <label>Информация</label>
                                        <textarea
                                            v-model="item.information"
                                            :name="'on_way['+item.id+'][time]'"
                                            :id="'on_way['+item.id+'][information]'"
                                            class="textarea"
                                            cols="30"
                                            rows="3"></textarea>
                                    </div>

                                    <div class="column">

                                        <div class="control is-narrow">
                                            <button
                                                class="button is-small is-outlined is-success square-button-36"
                                                @click.prevent="addToTableArrived(item)"
                                                type="button"
                                                title="Добавить">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <br>
                                        <div class="control is-narrow">
                                            <button
                                                class="button is-small is-outlined is-danger square-button-36"
                                                @click.prevent="removeItemArrived(item.id)"
                                                type="button"
                                                title="Удалить">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </b-tab-item>
            <br>
            <br>
            <br>
        </b-tabs>

        <!--<div class="field">-->
        <!--<a-->
        <!--class="button is-small is-info"-->
        <!--:href="'/xls/card101/chronology/'+card_.id">Скачать в Excel</a>-->
        <!--</div>-->

        <div class="field">
            <table class="table is-fullwidth is-hoverable">
                <thead>
                    <tr>
                        <th>ПЧ</th>
                        <th>Отделение</th>
                        <th>Расстояние подвоза</th>
                        <th>Время</th>
                        <th>Количество</th>
                        <th>Время работы</th>
                        <th>Ситуация</th>
                        <th>Информация</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="record in tableRecords"
                        :key="record.id">
                        <td>{{ record.fire_department_result.department.title }}</td>
                        <td>{{ record.fire_department_result.tech.department }}</td>
                        <td><input
                                v-if="canEdit(record.id) === true"
                                class="input"
                                type="number"
                                v-model="record.water_delivery_distance">
                            <span v-else>{{ record.water_delivery_distance }}</span></td>
                        <td>
                            <timepicker
                                v-if="canEdit(record.id) === true"
                                :inputdate="record.time"
                                v-model="record.time"
                                @timeChanged="record.time = parsedTime($event)"
                            />
                            <span v-else>{{ record.time }}</span>
                        </td>
                        <td>
                            <input
                                v-if="canEdit(record.id) === true"
                                class="input"
                                type="number"
                                v-model="record.quantity">
                            <span v-else>{{ record.quantity }}</span>
                        </td>
                        <td>
                            <input
                                v-if="canEdit(record.id) === true"
                                class="input"
                                type="number"
                                v-model="record.working_time">
                            <span v-else>{{ record.working_time }}</span>

                        </td>
                        <td v-if="record.event_info !== null">
                            <div
                                v-if="canEdit(record.id) === true"
                                class="select">
                                <select
                                    required
                                    title="Ситуация"
                                    v-model="record.event_info_id">
                                    <option
                                        v-for="e in eventInfo_"
                                        :key="'event_' + e.id"
                                        :value="e.id">{{ e.name }}
                                    </option>
                                </select>
                            </div>
                            <span v-else>{{ record.event_info ? record.event_info.name : '' }}</span>

                        </td>
                        <td v-else >
                            <div
                                v-if="canEdit(record.id) === true"
                                class="select">
                                <select
                                    required
                                    title="Ситуация"
                                    v-model="record.event_info_arrived_id">
                                    <option
                                        v-for="e in eventInfoArrived_"
                                        :key="'event_' + e.id"
                                        :value="e.id">{{ e.name }}
                                    </option>
                                </select>
                            </div>
                            <span v-else>{{ record.event_info_arrived ? record.event_info_arrived.name : '' }}</span>
                        </td>
                        <td>
                            <textarea
                                v-if="canEdit(record.id) === true"
                                v-model="record.information"
                                class="textarea"
                                cols="30"
                                rows="3"></textarea>
                            <p v-else>{{ record.information }}</p>
                        </td>
                        <td>
                            <div class="control is-narrow">
                                <button
                                    class="button is-small is-outlined is-danger square-button-36"
                                    @click.prevent="removeItemFromTable(record.id)"
                                    type="button"
                                    title="Удалить">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <div class="control is-narrow">
                                <button
                                    class="button is-small is-outlined is-info square-button-36"
                                    @click.prevent="editData(record.id)"
                                    type="button"
                                    title="Удалить">
                                    <i class="fa fa-pen"></i>
                                </button>
                            </div>
                            <div
                                v-if="canEdit(record.id) === true"
                                class="control is-narrow">
                                <button
                                    class="button is-small is-outlined is-success square-button-36"
                                    @click.prevent="updateItem(record)"
                                    type="button"
                                    title="Обновить">
                                    <i class="fa fa-anchor"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import Timepicker from '../../components/Timepicker';
import _ from 'lodash';
import {globalBus} from '../../scripts/global-bus'
import rights from '../../scripts/rights';
export default {
    name: 'Card101Chronology',
    components: {
        Timepicker
    },
    props: {
        departments: {
            type: Array,
            default: () => {
                return [];
            }
        },
        trunkTypes: {
            type: Array,
            default: () => {
                return [];
            }
        },
        eventInfo: {
            type: Array,
            default: () => {
                return [];
            }
        },
        eventInfoArrived: {
            type: Array,
            default: () => {
                return [];
            }
        },
        records: {
            type: Array,
            default: () => {
                return [];
            }
        },
        card: {
            type: Object,
            default: () => {
                return {};
            }
        },
        fire_department_id: {
            type: Number,
            default: null
        }
    },
    data() {
        return {
            records_arrived: [],
            records_onway: [],
            departments_: this.departments,
            time: '',
            tableRecords: this.records.filter(i => i.fire_department_result.fire_department_id === this.fire_department_id),
            eventInfo_: this.eventInfo,
            eventInfoArrived_: this.eventInfoArrived,
            card_: this.card,
            tableEdits: []
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
        parsedTime(timestamp) {
            if (moment(timestamp).isValid()) {
                return moment(timestamp).format('HH:mm');
            } else {
                return timestamp;
            }
        },
        changeStaffCount(dept) {
            if (!this.hasEditRight) {
                this.openNoEditRightsToast();
                return;
            }
            axios.post('/api/101card/update-fire-department-result', {
                id: dept.id,
                staff_count: dept.staff_count,
            });
        },
        changeDistanceCount(dept) {
            if (!this.hasEditRight) {
                this.openNoEditRightsToast();
                return;
            }
            axios.post('/api/101card/update-fire-department-result-distance', {
                id: dept.id,
                distance: dept.distance,
                fd: true,
            });
        },
        editData(id) {
            if (_.find(this.tableEdits, {id: id})) {
                _.find(this.tableEdits, {id: id}).edit = !_.find(this.tableEdits, {id: id}).edit;
            }
        },
        canEdit(id) {
            if (_.find(this.tableEdits, {id: id})) {
                return _.find(this.tableEdits, {id: id}).edit;
            } else {
                return false;
            }
        },
        createNewItemOnWay(dept) {

            this.records_onway.push({
                id: moment().valueOf(),
                time: '00:00',
                information: '',
                event_info_id: 1,
                fire_department_result: dept,
                editable: false,
                event_info: {
                    name: 'светофор'
                }
            });
        },

        createNewItemArrived(dept) {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
            // let card_data = window.ticket101add;

            this.records_arrived.push({
                id: moment().valueOf(),
                working_time: 0,
                time: '00:00',
                is_water_delivery: false,
                water_delivery_distance: 0,
                information: '',
                event_info_arrived_id: 1,
                quantity: 1,
                fire_department_result: dept,
                event_info: {
                    name: 'ствол',
                    trunk_type_id: 1,
                }
            });
        },
        createNewItemArrivedWaterDelivery(dept) {

            this.records_arrived.push({
                id: moment().valueOf(),
                working_time: 0,
                is_gdzs: false,
                time: '00:00',
                information: '',
                event_info_arrived_id: 1,
                water_delivery_distance: 0,
                is_water_delivery: true,
                quantity: 1,
                fire_department_result: dept,
                event_info: {
                    name: 'ствол',
                    trunk_type_id: 1,
                }
            });
        },
        eventInfoArrivedFiltered(typeId) {
            if(typeId === null) {
                return this.eventInfoArrived_;
            }
            return _.filter(this.eventInfoArrived_, (item) => {
                return item.trunk_type_id === typeId;
            });
        },
        getTime(value) {
            this.time = value;
        },
        addToTableOnWay(item) {
            this.postItem(item);
            this.records_onway = this.records_onway.filter(function (i) {
                return i.id !== item.id;
            });
        },
        addToTableArrived(item) {
            this.postItem(item);
            this.records_arrived = this.records_arrived.filter(function (i) {
                return i.id !== item.id;
            });
        },
        postItem(record) {
            if (!this.hasEditRight) {
                this.openNoEditRightsToast();
                return;
            }
            let card_data = window.ticket101fd;

            axios.post('/api/101card/save-chronology-from-fd', {
                ticket_id: card_data.ticketId,
                record: record
            }).then((resp) => {
                let data = resp.data;
                this.tableEdits.push({
                    id: data.id,
                    edit: false
                });
                this.tableRecords.push(data);
                this.tableRecords = this.sortByTime();
            });
        },
        updateItem(record) {
            if (!this.hasEditRight) {
                this.openNoEditRightsToast();
                return;
            }
            let card_data = window.ticket101fd;

            axios.post('/api/101card/save-chronology-from-fd', {
                ticket_id: card_data.ticketId,
                record: record
            });

            this.editData(record.id);
        },
        addEmptyItem() {
            this.addItem(this.getEmptyItem());
        },
        addItem(item) {
            this.records_.push(item);
        },
        getEmptyItem() {
            return {
                id: moment().valueOf(),
                time: '00:00',
                information: '',
                event_info_id: 1,
                event_info: {
                    name: 'светофор'
                }
            };
        },
        closeTimePickerByRefName(refName) {
            if (this.$refs[refName].close) {
                this.$refs[refName].close();
            } else {
                this.$refs[refName][0].close();
            }
        },
        removeItem(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                this.records_ = this.records_.filter(function (item) {
                    return item.id !== id;
                });
            }
        },
        removeItemOnWay(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                this.records_onway = this.records_onway.filter(function (item) {
                    return item.id !== id;
                });
            }
        },
        removeItemArrived(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                this.records_arrived = this.records_arrived.filter(function (item) {
                    return item.id !== id;
                });
            }
        },
        removeItemFromTable(id) {
            if (!this.hasEditRight) {
                this.openNoEditRightsToast();
                return;
            }
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                axios.post('/api/101card/delete-chronology-from-fd', {id: id});
                this.tableRecords = this.tableRecords.filter(function (item) {
                    return item.id !== id;
                });

                this.tableEdits = this.tableEdits.filter(function (item) {
                    return item.id !== id;
                });
            }
        },
        setCurrentTimeForItem(id) {
            _.where(this.records_, {id: id})[0]['time'] = moment().toDate();
            this.closeTimePickerByRefName('onway_time_picker_' + id);
        },
        sortByTime() {
            this.tableRecords = _.sortBy(this.tableRecords, ['time']);

            return this.tableRecords;
        }
    },

    created() {
        this.tableRecords.forEach((item) => {
            this.tableEdits.push({
                id: item.id,
                edit: false
            });
        });

        this.sortByTime();

        globalBus.$on('retreated-from-roadtrip', (eventData) => {
            this.tableRecords.push(eventData.item);
            this.tableRecords = this.sortByTime();
        });
    }
};
</script>

<style scoped>
    .small-time-picker {
        max-width: 6rem;
    }

    .select, .select > select {
        width: 100%
    }

    .is-narrow {
        width: 20%;
    }

    .square-button-36 {
        display: block;
        height: 36px;
        width: 36px;
    }

    .add_button {
        padding: 0 0 20px 0;
    }
</style>
