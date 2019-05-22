<template>
    <div id="other-rides-form" style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">
            {{ '/reports/analytics101.tabs.quakes.title'|trans({date_from: dateFromFormatted, date_to:dateToFormatted}) }}
            <!--Случаи землетрясения в г. Алматы
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
                        <a :href="`/reports/quakes?dateFrom=${dateFromFormattedSearch}&dateTo=${dateToFormattedSearch}`" class="button is-info">{{ 'download_word'|trans }}</a><!--Сохранить в .DOCX-->
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
                        <th>{{ '/reports/analytics101.tabs.diseases.description'|trans }}</th><!--Краткая характеристика происшествия-->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, index) in records">
                        <td>{{ ++index }}</td>
                        <td>{{ record.date_almaty|dateFilter('DD/MM/YYYY') }}</td>
                        <td>{{ record.total_info }}</td>
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
        name: "ReportQuakes",
        data: function () {
            return {
                records: [],
                dateFrom: new Date,
                dateTo: new Date,
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
        },
        methods: {
            changeDate() {
                let form = document.getElementById('vue');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get('/reports/quakes', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
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