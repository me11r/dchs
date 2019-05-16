<template>
    <div>
        <h4
                class="title"
                style="padding: 3px 15px">{{ '/reports/analytics101.tabs.emergency_situations.title'|trans({date_from: dateFromFormatted, date_to: dateToFormatted}) }}
            <!--Чрезвычайные ситуации природного и техногенного характера
            зарегистрированные по г. Алматы
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
                        </div>

                    </div>

                </div>
                <div class="level-right">
                    <div class="level-item">
                        <a href="/reports/112-emergency-report/export/docx" class="button is-info">{{ 'download_word'|trans }}</a><!--Сохранить в .DOCX-->
                    </div>
                    <div class="level-item">
                        <a href="/reports/112-emergency-report/export/xlsx" class="button is-info">{{ 'download_excel'|trans }}</a><!--Сохранить в .XLSX-->
                    </div>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <label>{{ '/reports/analytics101.tabs.emergency_situations.emergency_type112'|trans }}</label><br><!--Вид ЧС (112 карточка)-->
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
                    <label for="reason">{{ '/reports/analytics101.tabs.emergency_situations.emergency_name'|trans }}</label><!--Название ЧС-->
                    <select
                            class="control"
                            name="emergencyNameId"
                            v-model="emergencyNameId"
                    >
                        <option value=""></option>
                        <option
                                v-for="item in emergencyNames"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <label>{{ '/reports/analytics101.tabs.emergency_situations.result'|trans }}</label><br><!--Результат выезда (101 карточка)-->
                    <select v-model="tripResultId"
                            class="control">
                        <option value="">-</option>
                        <option v-for="i in tripResults"
                                :key="`trip_result_id_${i.id}`"
                                :value="i.id">{{ i.name }}</option>
                    </select>
                </div>

            </div>
            <div class="field">
                <label for="city_area_id">{{ 'city_area'|trans }}</label><!--Район города-->
                <select
                        class="select"
                        name="city_area_id"
                        v-model="cityAreaId"
                        id="city_area_id">
                    <option value=""></option>
                    <option
                            v-for="item in cityAreas"
                            :value="item.id"
                            :key="item.id">{{ item.name }}
                    </option>
                </select>
            </div>
            <div class="field">
                <p>Адрес</p>
                <input type="text" v-model="addressSearch" class="input">
            </div>
            <div class="field">
                <button @click.prevent="changeDate" class="button is-success">{{ 'search'|trans }}</button><!--Поиск-->
            </div>
        </div>

        <div class="panel">
            <table class="formation-record-table">
                <thead>
                <tr>
                    <th>№ п/п</th>
                    <th>{{ 'datetime'|trans }}</th><!--Дата и время происшествия-->
                    <th>Адрес</th>
                    <th>{{ '/reports/analytics101.tabs.emergency_situations.description'|trans }}</th><!--Краткая характеристика происшествия-->
                    <th>{{ '/reports/analytics101.tabs.emergency_situations.dead'|trans }}</th><!--Кол-во погибших-->
                    <th>{{ '/reports/analytics101.tabs.emergency_situations.injured'|trans }}</th><!--Кол-во пострадавших-->
                    <th>{{ '/reports/analytics101.tabs.emergency_situations.emergency_type'|trans }}</th><!--Вид ЧС-->
                    <th>{{ 'note'|trans }}</th><!--Примечание-->
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
        <div class="section">
            <div class="level">
                <div class="level-left">
                </div>
                <div class="level-right">
                    <h4>{{ '/reports/analytics101.tabs.branches_fall.dead'|trans }} - {{ totalInjured }}</h4><!--Пострадавшие/погибшие-->
                </div>
            </div>
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
            emergencyNames: {
                type: Array,
                default: () => {}
            },
            cityAreas: {
                type: Array,
                default: () => {}
            },
        },
        data: function () {
            return {
                records_: this.records,
                dateFrom: new Date("01/01/2019"),
                dateTo: new Date,
                incidentTypeId: 0,
                tripResultId: 0,
                emergencyNameId: null,
                cityAreaId: null,
                addressSearch: null,
                totalInjured: '0',
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
                        emergency_name_id: this.emergencyNameId,
                        city_area_id: this.cityAreaId,
                        addressSearch: this.addressSearch,
                    }
                }).then((r) => {
                    this.records_ = r.data.records;
                    this.totalInjured = r.data.deadInjured;
                });
            }
        },
        watch: {
        }
    }
</script>

<style scoped>

</style>