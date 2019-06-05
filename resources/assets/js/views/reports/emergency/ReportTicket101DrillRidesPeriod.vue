<template>
    <div id="other-rides-form" style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">{{ '/reports/analytics-spiasr.drill_rides_report.title'|trans({date_from: dateFromFormatted, date_to: dateToFormatted}) }}
            <!--Общий свод по учениям и занятиям
            за {{ dateFromFormatted }} по {{ dateToFormatted }}-->
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
                        <a href="/reports/drill-rides-report/export/xlsx" class="button is-info">{{ 'download_excel'|trans }}<!--Сохранить в XLSX-->
                        </a>
                    </div>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <label>{{ 'name'|trans }}</label><br><!--Наименование-->
                    <select v-model="normTypeId"
                            class="control">
                        <option value="">-</option>
                        <option v-for="i in normTypes"
                                :key="`norm_type_id_${i}`"
                                :value="i">{{ i }}</option>
                    </select>
                </div>
                <div class="control">
                    <label>{{ 'fd'|trans }}</label><br><!--ПЧ-->
                    <select v-model="fireDepartmentId"
                            class="control">
                        <option value="">-</option>
                        <option v-for="i in fireDepartments"
                                :key="`fire_department_id_${i.id}`"
                                :value="i.id">{{ i.title }}</option>
                    </select>
                </div>

            </div>
            <div class="field">
                <button class="button is-info"
                        @click.prevent="changeDate">{{ 'search'|trans }}</button><!--Поиск-->
            </div>
        </div>

        <div class="panel" style="overflow-x: scroll">
            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>{{ 'date'|trans }}</th><!--Дата-->
                        <th>{{ 'fd'|trans }}</th><!--Подразделение-->
                        <th>{{ 'type'|trans }}</th><!--Тип-->
                        <th>{{ 'department'|trans }}</th><!--Отделение-->
                        <th>{{ 'name'|trans }}</th><!--Наименование-->
                        <th>Адрес</th><!--Адрес-->
                        <th>{{ 'time_begin'|trans }}</th><!--Время начала-->
                        <th>{{ 'time_end'|trans }}</th><!--Время окончания-->
                        <th>{{ 'name_abbreviation'|trans }}</th><!--ФИО-->
                        <th>{{ '/reports/analytics-spiasr.drill_rides_report.checked_pg'|trans }}</th><!--ПровПГ-->
                        <th>{{ '/reports/analytics-spiasr.drill_rides_report.damaged_pg'|trans }}</th><!--НеиспПГ-->
                        <th>{{ '/reports/analytics-spiasr.drill_rides_report.checked_pv'|trans }}</th><!--ПровПВ-->
                        <th>{{ '/reports/analytics-spiasr.drill_rides_report.damaged_pv'|trans }}</th><!--НеиспПВ-->
                        <th>ОП</th><!--ОП-->
                        <th>ОК</th><!--ОК-->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, key) in records_" :key="`records_${key}`">
                        <td>{{ ++key }}</td>
                        <td><a target="_blank" :href="record.href">{{ record.date }}</a></td>
                        <td>
                            <p v-for="result in record.fire_departments">{{ result.name }}</p>
                        </td>
                        <td>{{ record.type }}</td>
                        <td>
                            <p v-for="result in record.departments">{{ result.name }}</p>
                        </td>
                        <td>{{ record.name }}</td>
                        <td>{{ record.location }}</td>
                        <td>{{ record.time_begin }}</td>
                        <td>{{ record.time_end }}</td>
                        <td>{{ record.responsible_person }}</td>
                        <td>{{ record.checked_pg_total }}</td>
                        <td>{{ record.out_pg_total }}</td>
                        <td>{{ record.checked_pv_total }}</td>
                        <td>{{ record.out_pv_total }}</td>
                        <td>{{ record.corrected_op_total }}</td>
                        <td>{{ record.corrected_ok_total }}</td>
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
        name: "ReportTicket101DrillRidesPeriod",
        components: {BField},
        props: {
            records: {
                type: Array,
                default: () => { return []; }
            },
            normTypes: {
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
                normTypeId: 0,
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
                axios.get('/reports/drill-rides-report', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                        type: this.normTypeId,
                        fireDepartmentId: this.fireDepartmentId,
                        location: this.direction,
                    }
                }).then((r) => {
                    this.records_ = r.data.records;
                    loadingComponent.close();
                });
            }
        },
    }
</script>

<style scoped>

</style>