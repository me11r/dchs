<template>
    <div id="other-rides-form">
        <h4
                class="title"
                style="padding: 3px 15px">Общий свод по прочим выездам
            за {{ dateFromFormatted }} по {{ dateToFormatted }}
        </h4>

        <div class="panel">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div class="field is-grouped">
                            <v-datepicker-search
                                    v-model="dateFrom"
                                    :date="dateFrom"
                                    class="control"
                                    @dateChanged="dateFrom = $event"
                                    label="С">
                            </v-datepicker-search>
                            <v-datepicker-search
                                    v-model="dateTo"
                                    :date="dateTo"
                                    @dateChanged="dateTo = $event"
                                    class="control"
                                    label="По">
                            </v-datepicker-search>
                            <div class="control">
                                <b-field :label="'Адрес'">
                                    <input v-model="direction" class="input">
                                </b-field>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="level-right">
                    <div class="level-item">
                        <a href="/reports/other-rides-report/export/xlsx" class="button is-info">Сохранить в .XLSX</a>
                    </div>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <label>Куда</label><br>
                    <select v-model="rideTypeId"
                            class="control">
                        <option value="">-</option>
                        <option v-for="i in rideTypes"
                                :key="`ride_type_id_${i.id}`"
                                :value="i.id">{{ i.name }}</option>
                    </select>
                </div>
                <div class="control">
                    <label>ПЧ</label><br>
                    <select v-model="fireDepartmentId"
                            class="control">
                        <option value="">-</option>
                        <option v-for="i in fireDepartments"
                                :key="`fire_department_id_${i.id}`"
                                :value="i.id">{{ i.title }}</option>
                    </select>
                </div>


            </div>
        </div>

        <div class="panel">
            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Дата</th>
                        <th>Подразделение</th>
                        <th>Отделение</th>
                        <th>Куда</th>
                        <th>Адрес</th>
                        <th>Время начала</th>
                        <th>Время окончания</th>
                        <th>Ответственный</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, key) in records_" :key="`records_${record.id}`">
                        <td>{{ ++key }}</td>
                        <td>{{ record.created_at|dateFilter('DD.MM.YYYY H:m') }}</td>
                        <td>
                            <p v-for="result in record.results" v-if="result.dispatch_time !== null" :key="`results_dept_title_${result.id}`">{{ result.department.title }}</p>
                        </td>
                        <td>
                            <p v-for="result in record.results" v-if="result.dispatch_time !== null" :key="`results_dept_number_${result.id}`">{{ result.tech.department }}</p>
                        </td>
                        <td>{{ record.ride_type ? record.ride_type.name : '' }}</td>
                        <td>{{ record.direction }}</td>
                        <td>{{ record.time_begin }}</td>
                        <td>{{ record.time_end }}</td>
                        <td>{{ record.responsible_person }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
    </div>
</template>

<script>
    import moment from 'moment';
    import axios from 'axios';
    import BField from "buefy/src/components/field/Field";
    export default {
        name: "ReportTicket101OtherRidesPeriod",
        components: {BField},
        props: {
            records: {
                type: Array,
                default: () => { return []; }
            },
            rideTypes: {
                type: Array,
                default: () => { return []; }
            },
            fireDepartments: {
                type: Array,
                default: () => { return []; }
            },
        },
        data: function () {
            return {
                records_: this.records,
                dateFrom: new Date,
                dateTo: new Date,
                rideTypeId: 0,
                fireDepartmentId: 0,
                direction: '',
            }
        },
        computed: {
            dateToFormatted() {
                return moment(this.dateTo).format('DD-MM-YYYY');
            },
            dateFromFormatted() {
                return moment(this.dateFrom).format('DD-MM-YYYY');
            },
        },
        methods: {
            changeDate() {
                let form = document.getElementById('other-rides-form');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get('/reports/other-rides-report', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                        rideTypeId: this.rideTypeId,
                        fireDepartmentId: this.fireDepartmentId,
                        direction: this.direction,
                    }
                }).then((r) => {
                    this.records_ = r.data.records;
                    loadingComponent.close();
                });
            }
        },
        watch: {
            'direction'() {
                this.changeDate();
            },
            'dateTo'() {
                this.changeDate();
            },
            'dateFrom'() {
                this.changeDate();
            },
            'fireDepartmentId'() {
                this.changeDate();
            },
            'rideTypeId'() {
                this.changeDate();
            },
        }
    }
</script>

<style scoped>

</style>