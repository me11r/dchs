<template>
    <div>
        <h4
                class="title"
                style="padding: 3px 15px">Чрезвычайные ситуации природного и техногенного характера
            зарегистрированные по г. Алматы
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
                        </div>

                    </div>

                </div>
                <div class="level-right">
                    <div class="level-item">
                        <a href="/reports/112-emergency-report/export/docx" class="button is-info">Сохранить в .DOCX</a>
                    </div>
                    <div class="level-item">
                        <a href="/reports/112-emergency-report/export/xlsx" class="button is-info">Сохранить в .XLSX</a>
                    </div>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <label>Вид ЧС (112 карточка)</label><br>
                    <select v-model="incidentTypeId"
                            class="control">
                        <option value="">-</option>
                        <option v-for="i in incidentTypes"
                                :key="`incident_type_id_${i.id}`"
                                :value="i.id">{{ i.name }}</option>
                    </select>
                </div>

            </div>
            <div class="field is-grouped">
                <div class="control">
                    <label>Результат выезда (101 карточка)</label><br>
                    <select v-model="tripResultId"
                            class="control">
                        <option value="">-</option>
                        <option v-for="i in tripResults"
                                :key="`trip_result_id_${i.id}`"
                                :value="i.id">{{ i.name }}</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="panel">
            <table class="formation-record-table">
                <thead>
                <tr>
                    <th>№ п/п ЧС</th>
                    <th>Дата и время происшествия</th>
                    <th>Адрес</th>
                    <th>Краткая характеристика происшествия</th>
                    <th>Кол-во погибших</th>
                    <th>Кол-во пострадавших</th>
                    <th>Вид ЧС</th>
                    <th>Примечание</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(record, key) in records_">
                    <td>{{ ++key }}</td>
                    <td>{{ record.created_at }}</td>
                    <td>{{ record.detailed_address }}</td>
                    <td>{{ record.emergency_feature }}</td>
                    <td>{{ record.dead }}</td>
                    <td>{{ record.injured }}</td>
                    <td>{{ record.additional_incident }}</td>
                    <td></td>
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
    export default {
        name: "ReportEmergencyTypePeriod",
        props: {
            records: {
                type: Array,
                default: () => { return []; }
            },
            incidentTypes: {
                type: Array,
                default: () => { return []; }
            },
            tripResults: {
                type: Array,
                default: () => { return []; }
            },
        },
        data: function () {
            return {
                records_: this.records,
                dateFrom: new Date("01/01/2019"),
                dateTo: new Date,
                incidentTypeId: 0,
                tripResultId: 0,
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
                axios.get('/reports/112-emergency-report', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                        incidentTypeId: this.incidentTypeId,
                        tripResultId: this.tripResultId,
                    }
                }).then((r) => {
                    this.records_ = r.data.records;
                });
            }
        },
        watch: {
            'dateTo'() {
                this.changeDate();
            },
            'dateFrom'() {
                this.changeDate();
            },
            'incidentTypeId'() {
                this.changeDate();
            },
            'tripResultId'() {
                this.changeDate();
            },
        }
    }
</script>

<style scoped>

</style>