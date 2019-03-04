<template>
    <div class="container" id="forces-resources-form">
        <h4
                class="title"
                style="padding: 3px 15px">Информация по звонкам
            за {{ dateFromFormatted }} по {{ dateToFormatted }}
        </h4>
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px">
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
                    <div class="level-left">
                        <a
                            href="/reports/call-infos/download/docx"
                            target="_blank"
                            download
                            class="button is-primary"
                            type="submit">
                            <i class="fas fa-print"></i>&nbsp;Сохранить в DOCX
                        </a>
                    </div>
                </div>
                <br>
                <form>
                    <h3 class="title">Сведения
                        о количестве звонков поступивших на номер «112» с 07.00 {{ this.dateFromFormatted }} до 07.00  {{ this.dateToFormatted }} года
                        УЕДДС ДЧС г. Алматы</h3>
                    <table class="table is-narrow is-hoverable is-fullwidth is-striped is-small formation-record-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Общее количество принятых сообщения (звонков)</th>
                                <th colspan="7">Из них</th>
                            </tr>
                            <tr>
                                <th>По основной деятельности «112»</th>
                                <th>По линии «101»</th>
                                <th>По линии «102»</th>
                                <th>По линии «103»</th>
                                <th>Информационно – справочного характера</th>
                                <th>Прочее (проверка сотовых телефонов, шалость детей и т.д.)</th>
                                <th>Примечание</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ report.report112.total112 }}</td>
                                <td>{{ report.report112.count_112 }}</td>
                                <td>{{ report.report112.count_101 }}</td>
                                <td>{{ report.report112.count_102 }}</td>
                                <td>{{ report.report112.count_103 }}</td>
                                <td>{{ report.report112.count_info }}</td>
                                <td>{{ report.report112.count_other }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="title">Сведения
                        о количестве звонков поступивших на номер «101» с 07.00 {{ this.dateFromFormatted }} до 07.00 {{ this.dateToFormatted }} года
                        ГУ «СПиАСР» ДЧС г.Алматы</h3>
                    <table class="table is-narrow is-hoverable is-fullwidth is-striped is-small formation-record-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Общее количество принятых сообщений</th>
                                <th colspan="6">Из них</th>
                            </tr>
                            <tr>
                                <th>По линии «101»</th>
                                <!--<th>По вопросам реагирования на ЧС природного и техногенного характера</th>-->
                                <th>По линии «102»</th>
                                <th>По линии «103»</th>
                                <th>Информационно - справочного характера</th>
                                <th>Прочие (проверка сотовых телефонов, шалость детей ит.д.)</th>
                                <th>Примечание</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ report.report101.total_101 }}</td>
                                <td>{{ report.report101.count_101 }}</td>
                                <!--<td>{{ report.report101.count_emergency }}</td>-->
                                <td>{{ report.report101.count_102 }}</td>
                                <td>{{ report.report101.count_103 }}</td>
                                <td>{{ report.report101.count_info }}</td>
                                <td>{{ report.report101.count_other }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';
import moment from 'moment';

export default {
    name: 'ReportCallInfos',
    props: {
        reports: {
            type: Array,
            default: () => []
        },
    },
    data: function () {
        return {
            dateFrom: new Date,
            dateTo: new Date,
            reports_: this.reports
        };
    },
    computed: {
        dateToFormatted() {
            return moment(this.dateTo).format('DD-MM-YYYY');
        },
        dateFromFormatted() {
            return moment(this.dateFrom).format('DD-MM-YYYY');
        },
        report() {
            return {
                report112: {
                    count_112: this.sumByField('count_112'),
                    count_101: this.sumByField('count_101'),
                    count_102: this.sumByField('count_102'),
                    count_103: this.sumByField('count_103'),
                    count_info: this.sumByField('count_info'),
                    count_other: this.sumByField('count_other'),
                    total112: this.sumByField('total_112'),
                },
                report101: {
                    count_101: this.sumByField('count_101'),
                    // count_emergency: this.sumByField('count_emergency'),
                    count_102: this.sumByField('count_102'),
                    count_103: this.sumByField('count_103'),
                    count_info: this.sumByField('count_info'),
                    count_other: this.sumByField('count_other'),
                    total_101: this.sumByField('total_101'),
                },

            };
        }
    },
    methods: {
        sumByField(field) {
            return _.sumBy(this.reports_, (item) => {
                return parseInt(item[field]);
            });
        },
        changeDate() {
            let form = document.getElementById('forces-resources-form');
            let loadingComponent = this.$loading.open({
                container: form
            });
            axios.get('/reports/call-infos/show', {
                params: {
                    dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                    dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                    fireDepartmentId: this.fireDepartmentId
                }
            }).then((r) => {
                this.reports_ = r.data.reports;
                loadingComponent.close();
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
    }

};
</script>

<style scoped>

</style>
