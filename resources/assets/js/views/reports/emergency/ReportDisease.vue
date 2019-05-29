<template>
    <div style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">{{ '/reports/analytics101.tabs.diseases.title'|trans({date_from:dateFromFormatted, date_to:dateToFormatted}) }}
            <!--Зафиксированные случаи инфекционного заболевания в г.Алматы
            с {{ dateFromFormatted }} по {{ dateToFormatted }}-->
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
                           class="button is-info">{{ 'download_word'|trans }}</a><!--Сохранить в .DOCX-->
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <div class="control">
                        <p>{{ '/reports/analytics101.tabs.diseases.types'|trans}}</p><!--Типы заболеваний-->
                        <select class="control" id="diseaseTypeId" v-model="diseaseTypeId">
                            <option value=""></option>
                            <option v-for="type in diseaseTypes" :value="type.id">{{ type.name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <button class="button is-success" @click.prevent="changeDate">{{ 'search'|trans }}</button><!--Поиск-->
            </div>
        </div>

        <div class="panel">
            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>{{ 'number'|trans }}</th><!--№ п/п ЧС-->
                        <th>{{ 'datetime'|trans }}</th><!--Дата и время происшествия-->
                        <th>{{ '/reports/analytics101.tabs.diseases.type'|trans }}</th><!--Тип инфекционного заболевания-->
                        <th>{{ 'name_abbreviation'|trans }}</th><!--Ф.И.О-->
                        <th>{{ '/reports/analytics101.tabs.diseases.description'|trans }}</th><!---->
                        <th>{{ 'dead'|trans }}</th><!---->
                        <th>{{ 'injured'|trans }}</th><!--Кол-во пострадавших-->
                        <th>{{ 'type'|trans }}</th><!--Вид ЧС-->
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