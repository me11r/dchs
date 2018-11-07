<template>
    <div>
        <table class="table is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>ПЧ</th>
                    <th>Отделение</th>
                    <th>Хронология</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="dept in departments_"
                    :key="dept.id">
                    <td>{{ dept.department.title }}</td>
                    <td>{{ dept.tech.department }}</td>
                    <td>
                        <div class="add_button">
                            <button
                                class="button is-small is-outlined is-success"
                                type="button"
                                @click.prevent="createNewItem(dept)">
                                <i class="fa fa-plus"></i>&nbsp;Добавить
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div
            class="field is-grouped"
            v-for="item in records_"
            :key="item.id">

            <div class="control is-narrow">
                <label :for="'on_way['+item.id+'][event_info_id]'">Событие</label>
                <div class="select">
                    <select
                        required
                        title="Ситуация"
                        :name="'on_way['+item.id+'][event_info_id]'"
                        :id="'on_way['+item.id+'][event_info_id]'"
                        v-model="item.event_info_id">
                        <option
                            v-for="e in eventData_"
                            :key="'event_' + e.id"
                            :value="e.id">{{ e.name }}
                        </option>
                    </select>
                </div>
            </div>

            <input
                type="hidden"
                v-model="item.id"
                :name="'on_way['+item.id+'][id]'">
            <div class="control">
                <label>Количество</label>
                <input
                    class="input"
                    type="number"
                    v-model="item.quantity">
            </div>

            <input
                type="hidden"
                v-model="item.id"
                :name="'on_way['+item.id+'][id]'">
            <div class="control">
                <label>Время работы</label>
                <input
                    class="input"
                    type="number"
                    v-model="item.working_time">
            </div>

            <div class="field is-narrow">
                <label>Информация</label>
                <textarea
                    v-model="item.information"
                    :name="'on_way['+item.id+'][time]'"
                    :id="'on_way['+item.id+'][information]'"
                    class="textarea"
                    cols="30"
                    rows="3"></textarea>
            </div>

            <div class="field">

                <div class="control is-narrow">
                    <button
                        class="button is-small is-outlined is-success square-button-36"
                        @click.prevent="addToTable(item)"
                        type="button"
                        title="Добавить">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <br>
                <div class="control is-narrow">
                    <button
                        class="button is-small is-outlined is-danger square-button-36"
                        @click.prevent="removeItem(item.id)"
                        type="button"
                        title="Удалить">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>

            </div>

        </div>
        <div class="field">
            <table class="table is-fullwidth is-hoverable">
                <thead>
                    <tr>
                        <th>ПЧ</th>
                        <th>Отделение</th>
                        <th>Событие</th>
                        <th>Количество</th>
                        <th>Время работы</th>
                        <th>Информация</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="record in tableRecords">
                        <td>{{ record.fire_department_result.department.title }}</td>
                        <td>{{ record.fire_department_result.tech.department }}</td>
                        <td>{{ record.event_info.name }}</td>
                        <td>{{ record.working_time }}</td>
                        <td>{{ record.quantity }}</td>
                        <td>{{ record.information }}</td>
                        <td>
                            <div class="control is-narrow">
                                <label>Удалить</label>

                                <button
                                    class="button is-small is-outlined is-danger square-button-36"
                                    @click.prevent="removeItemFromTable(record.id)"
                                    type="button"
                                    title="Удалить">
                                    <i class="fa fa-trash"></i>
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
import moment from 'moment';
import {_} from 'vue-underscore';
import axios from 'axios';

export default {
    name: 'ArrivedInfo',
    props: {
        departments: {
            type: Array,
            default: () => {
                return [];
            }
        },
        eventData: {
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
        }
    },
    data() {
        return {
            records_: [],
            departments_: this.departments,
            time: '',
            tableRecords: this.records,
            eventData_: this.eventData
        };
    },
    mounted() {
    },
    methods: {
        createNewItem(dept) {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
            let card_data = window.ticket101add;

            this.records_.push({
                id: moment().valueOf(),
                working_time: 0,
                information: '',
                event_info_id: 1,
                quantity: 1,
                fire_department_result: dept,
                event_info: {
                    name: 'ствол'
                }
            });
        },
        getTime(value) {
            this.time = value;
        },
        addToTable(item) {
            this.postItem(item);
            this.records_ = this.records_.filter(function (i) {
                return i.id !== item.id;
            });
        },
        postItem(record) {
            let card_data = window.ticket101add;

            record.time = this.time;

            axios.post('/api/101card/save-arrived', {
                ticket_id: card_data.ticketId,
                record: record
            }).then((resp) => {
                this.tableRecords.push(resp.data);
            });
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
        removeItemFromTable(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                axios.post('/api/101card/delete-arrived', {id: id});
                this.tableRecords = this.tableRecords.filter(function (item) {
                    return item.id !== id;
                });
            }
        },
        setCurrentTimeForItem(id) {
            _.where(this.records_, {id: id})[0]['time'] = moment().toDate();
            this.closeTimePickerByRefName('onway_time_picker_' + id);
        }
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
