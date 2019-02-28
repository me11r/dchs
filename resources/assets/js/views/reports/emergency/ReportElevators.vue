<template>
    <div style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">Информация происшествия на лифтах
            с {{ dateFromFormatted }} по {{ dateToFormatted }}
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
                        <a :href="`/reports/112/elevators?dateFrom=${dateFromFormattedSearch}&dateTo=${dateToFormattedSearch}&elevatorEmergencyTypeId=${elevatorEmergencyTypeId}`"
                           class="button is-info">Сохранить в .DOCX</a>
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <div class="control">
                        <p>Тип происшествия на лифтах</p>
                        <select class="control" id="elevatorEmergencyTypeId" v-model="elevatorEmergencyTypeId">
                            <option value=""></option>
                            <option v-for="type in elevatorEmergencyTypes" :value="type.id">{{ type.name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <div class="control">
                        <p>Район города</p>
                        <select class="control" id="cityAreaId" v-model="cityAreaId">
                            <option value=""></option>
                            <option v-for="area in cityAreas" :value="area.id">{{ area.name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <button class="button is-success" @click.prevent="changeDate">Поиск</button>
            </div>
        </div>

        <div class="panel">
            <table v-for="(records_, title) in recordsByType" class="formation-record-table">
                <thead>
                    <tr>
                        <th colspan="5">{{ title }} - {{ records_.length }}</th>
                    </tr>
                    <tr>
                        <th>№ п/п</th>
                        <th>Дата/Время</th>
                        <th>Адрес</th>
                        <th>Район города</th>
                        <th>Характеристика происшествия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, index) in records_">
                        <td>{{ ++index }}</td>
                        <td>{{ record.created_at|dateFilter('DD/MM/YYYY HH:mm') }}</td>
                        <td>{{ record.detailed_address }}</td>
                        <td>{{ record.city_area ? record.city_area.name : '' }}</td>
                        <td>{{ record.emergency_feature }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p>Общее количество происшествия на лифтах - {{ records.length }}</p>

        <br>
    </div>
</template>

<script>
    import moment from 'moment';
    import axios from 'axios';
    export default {
        name: "ReportElevators",
        props: {
            elevatorEmergencyTypes: {
                type: Array,
                default: () => { return []; }
            },
            cityAreas: {
                type: Array,
                default: () => { return []; }
            },
        },
        data: function () {
            return {
                records: [],
                dateFrom: new Date,
                dateTo: new Date,
                elevatorEmergencyTypeId: 1,
                cityAreaId: 1,
            }
        },
        computed: {
            dateToFormatted() {
                return moment(this.dateTo).format('DD-MM-YYYY');
            },
            dateFromFormatted() {
                return moment(this.dateFrom).format('DD-MM-YYYY');
            },
            dateToFormattedSearch() {
                return moment(this.dateTo).format('YYYY-MM-DD');
            },
            dateFromFormattedSearch() {
                return moment(this.dateFrom).format('YYYY-MM-DD');
            },
            elevatorEmergencyTypeName() {
                return _.first(this.elevatorEmergencyTypes,{id: this.elevatorEmergencyTypeId}) ? _.first(this.elevatorEmergencyTypes,{id: this.elevatorEmergencyTypeId}).name : null;
            },
            recordsByType() {
                return _.groupBy(this.records, (item) => {
                    return item.elevator_emergency_type.name;
                });
            }
        },
        methods: {
            changeDate() {
                let form = document.getElementById('vue');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get('/reports/112/elevators', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                        elevatorEmergencyTypeId: this.elevatorEmergencyTypeId,
                        cityAreaId: this.cityAreaId,
                    }
                }).then((r) => {
                    this.records = r.data.records;
                    loadingComponent.close();
                });
            }
        },
    }
</script>

<style scoped>

</style>