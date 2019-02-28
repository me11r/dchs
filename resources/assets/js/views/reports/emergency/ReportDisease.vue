<template>
    <div style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">Зафиксированные случаи инфекционного заболевания в г.Алматы
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
                        <a :href="`/reports/112/disease?dateFrom=${dateFromFormattedSearch}&dateTo=${dateToFormattedSearch}&diseaseTypeId=${diseaseTypeId}`"
                           class="button is-info">Сохранить в .DOCX</a>
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <div class="control">
                        <p>Типы заболеваний</p>
                        <select class="control" id="diseaseTypeId" v-model="diseaseTypeId">
                            <option value=""></option>
                            <option v-for="type in diseaseTypes" :value="type.id">{{ type.name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <button class="button is-success" @click.prevent="changeDate">Поиск</button>
            </div>
        </div>

        <div class="panel">
            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>№ п/п ЧС</th>
                        <th>Дата и время происшествия</th>
                        <th>Тип инфекционного заболевания</th>
                        <th>Ф.И.О</th>
                        <th>Краткая характеристика происшествия</th>
                        <th>Кол-во погибших</th>
                        <th>Кол-во пострадавших</th>
                        <th>Вид ЧС</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, index) in records">
                        <td>{{ ++index }}</td>
                        <td>{{ record.created_at|dateFilter('DD/MM/YYYY HH:mm') }}</td>
                        <td>{{ record.disease_type.name }}</td>
                        <td>{{ record.name_disease }}</td>
                        <td>{{ record.emergency_feature }}</td>
                        <td>{{ record.dead }}</td>
                        <td>{{ record.injured }}</td>
                        <td>{{ record.emergency_name ? record.emergency_name.name : '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import axios from 'axios';
    export default {
        name: "ReportDisease",
        props: {
            diseaseTypes: {
                type: Array,
                default: () => { return []; }
            },
        },
        data: function () {
            return {
                records: [],
                dateFrom: new Date,
                dateTo: new Date,
                diseaseTypeId: 1,
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
            diseaseTypeName() {
                return _.first(this.diseaseTypes,{id: this.diseaseTypeId}) ? _.first(this.diseaseTypes,{id: this.diseaseTypeId}).name : null;
            },
        },
        methods: {
            changeDate() {
                let form = document.getElementById('vue');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get('/reports/112/disease', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                        diseaseTypeId: this.diseaseTypeId,
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