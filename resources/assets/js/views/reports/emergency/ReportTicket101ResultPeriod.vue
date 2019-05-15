<template>
    <div id="other-rides-form" style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">Сводный отчет
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
                        <a :href="exportUrl" class="button is-info">Сохранить в .DOCX</a>
                    </div>
                </div>
            </div>
            <div class="field">
                <button class="button is-info"
                        @click.prevent="changeDate">Поиск</button>
            </div>
        </div>

        <div class="panel">
            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Количество</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, key) in records" :key="`records_${key}`">
                        <td>{{ record.title }}</td>
                        <td>{{ record.count }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p v-if="total">Итого: {{ total }}</p>
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
        },
        data: function () {
            return {
                records: [],
                dateFrom: moment('01/01/2019').toDate(),
                dateTo: new Date,
                total: 0,
            }
        },
        computed: {
            dateToFormatted() {
                return moment(this.dateTo).format('DD-MM-YYYY');
            },
            dateFromFormatted() {
                return moment(this.dateFrom).format('DD-MM-YYYY');
            },
            exportUrl() {
                let dateFrom = moment(this.dateFrom).format('YYYY-MM-DD');
                let dateTo = moment(this.dateTo).format('YYYY-MM-DD');
                return `/reports/result-period?dateFrom=${dateFrom}&$dateTo=${dateTo}`;
            }
        },
        methods: {
            changeDate() {
                let form = document.getElementById('vue');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get('/reports/result-period', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                    }
                }).then((r) => {
                    this.records = r.data.records;
                    this.total = r.data.total;
                    loadingComponent.close();
                });
            }
        },
    }
</script>

<style scoped>

</style>