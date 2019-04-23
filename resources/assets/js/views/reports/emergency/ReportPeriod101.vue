<template>
    <div
        class="container"
        id="other-rides-form">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px; min-height:1000px;">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">Отчет-1</h4>
                    </div>
                    <div class="level-right has-text-right">
                    </div>
                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary"
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;Печать</button>
                    </div>
                </div>
                <br>
                <form>
                    <div class="field">
                        <label for="reason">Результат выезда:</label>
                        <select
                            class="select"
                            name=""
                            v-model="result_id"
                            id="reason">
                            <option value=""></option>
                            <option
                                v-for="item in results_"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="burnt">Объект горения:</label>
                        <select
                            class="select"
                            v-model="burnt_id"
                            id="burnt">
                            <option value=""></option>
                            <option
                                v-for="item in burnt_objects"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="cityArea">Район города:</label>
                        <select
                            class="select"
                            v-model="city_area_id"
                            id="cityArea">
                            <option value=""></option>
                            <option
                                v-for="item in city_areas"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="time_onway">Время следования:</label>
                        <select
                            class="select"
                            v-model="time_onway"
                            id="time_onway">
                            <option value=""></option>
                            <option
                                v-for="item in time_onway_options"
                                :value="item.name"
                                :key="item.name">{{ item.title }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="time_liqv">Время ликвидации:</label>
                        <select
                            class="select"
                            v-model="time_liqv"
                            id="time_liqv">
                            <option value=""></option>
                            <option
                                v-for="item in time_liqv_options"
                                :value="item.name"
                                :key="item.name">{{ item.title }}
                            </option>
                        </select>
                    </div>
                    <v-datepicker-search
                        :date-string="date_begin_"
                        v-model="date_begin_"
                        @dateChanged="date_begin_ = $event"
                        label="Начало периода"
                    />
                    <v-datepicker-search
                        :date-string="date_end_"
                        @dateChanged="date_end_ = $event"
                        v-model="date_end_"
                        label="Конец периода"
                    />
                    <div class="field">
                        <button
                            style="margin-top:20px"
                            class="button is-info"
                            :disabled="inProgress"
                            @click.prevent="sendReportToQueue">
                            <template v-if="inProgress">
                                <i class="fas fa-spinner fa-pulse"></i> &nbsp; Отправка...
                            </template>
                            <template v-else>
                                Отправить в обработку
                            </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
// import axios from 'axios';
import _ from 'lodash';
import moment from 'moment';
import {
    // serialize,
    clone
} from '../../../utils';
import {QueuedReportService, ReportType} from '../../../services/queued-report-service';

export default {
    name: 'ReportPeriod101',
    props: {
        date_begin: {
            type: String,
            default: ''
        },
        date_end: {
            type: String,
            default: ''
        },
        results: {
            type: Array,
            default: () => {}
        },
        burnt_objects: {
            type: Array,
            default: () => []
        },
        city_areas: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            date_begin_: this.date_begin,
            date_end_: this.date_end,
            report_summary: {},
            result_id: null,
            results_: this.results,
            burnt_id: null,
            city_area_id: null,
            timerId: null,

            time_onway_summary: [
                {type: 'less_5', value: ''},
                {type: 'less_10', value: ''},
                {type: 'more_10', value: ''}
            ],
            time_onway_options: [
                {title: 'до 5 минут', name: 'less_5'},
                {title: 'до 10 минут', name: 'less_10'},
                {title: 'более 10 минут', name: 'more_10'}
            ],
            time_onway: null,

            time_liqv: null,
            time_liqv_options: [
                {title: 'до 15 минут', name: 'less_15'},
                {title: 'до 30 минут', name: 'less_30'},
                {title: 'до 1 часа', name: 'less_60'},
                {title: 'до 2 часов', name: 'less_120'},
                {title: 'более 2 часов', name: 'more_120'}
            ],
            time_liqv_summary: [
                {type: 'less_15', value: ''},
                {type: 'less_30', value: ''},
                {type: 'less_60', value: ''},
                {type: 'less_120', value: ''},
                {type: 'more_120', value: ''}
            ],

            time_gdzs_summary: [
                {type: 'one', value: ''},
                {type: 'many', value: ''}
            ],
            loadingComponent: null,
            inProgress: false
        };
    },
    methods: {
        selectResult(event) {
            this.result_id = event.target.value;
        },
        print() {
            window.print();
        },
        startCheck() {
            let form = document.getElementById('other-rides-form');

            if (this.loadingComponent === null) {
                this.loadingComponent = this.$loading.open({
                    container: form
                });
            }

            this.timerId = setTimeout(this.post_data, 30 * 1000);
        },
        stopCheck() {
            this.loadingComponent.close();
            this.loadingComponent = null;
            clearTimeout(this.timerId);
        },
        sendReportToQueue() {
            QueuedReportService
                .UserHasNotFinishedReport()
                .then(result => {
                    if (result === true) {
                        if (confirm('Для вашей учетной записи есть незавершенный отчет. Вы действительно хотите добавить ещё один?')) {
                            this.registerReportToQueue();
                        }
                    } else {
                        this.registerReportToQueue();
                    }
                });
        },
        registerReportToQueue() {
            const self = this;
            const dateStart = self.date_begin_ ? moment(self.date_begin_).format('YYYY-MM-DD') : '';
            const dateEnd = self.date_end_ ? moment(self.date_end_).format('YYYY-MM-DD') : '';

            this.inProgress = true;
            QueuedReportService
                .RegisterToQueue(
                    dateStart,
                    dateEnd,
                    ReportType.ANALYTICS_SPIASR,
                    {
                        date_begin: dateStart,
                        date_end: dateEnd,
                        result_id: self.result_id,
                        burnt_id: self.burnt_id,
                        time_liqv: self.time_liqv,
                        time_onway: self.time_onway,
                        city_area_id: self.city_area_id
                    }
                )
                .then((response) => {
                    this.inProgress = false;
                    const result = response.data.result;
                    if (result) {
                        this.$snackbar.open({
                            message: 'Успешно добавлено в очередь обработки',
                            type: 'is-info'
                        });
                        setTimeout(function() {
                            window.location = '/reports/queued-reports';
                        }, 1000);
                    } else {
                        this.$snackbar.open({
                            message: 'Во время постановки в очередь произошла ошибка',
                            type: 'is-danger'
                        });
                    }
                })
                .catch(() => {
                    this.inProgress = false;
                    this.$snackbar.open({
                        message: 'Во время постановки в очередь произошла ошибка',
                        type: 'is-danger'
                    });
                });
        }
        // post_data() {
        //     let self = this;
        //
        //     axios.post('/reports/101/emergency', {
        //
        //         date_begin: self.date_begin_,
        //         date_end: self.date_end_,
        //         result_id: self.result_id,
        //         burnt_id: self.burnt_id,
        //         time_liqv: self.time_liqv,
        //         time_onway: self.time_onway,
        //         city_area_id: self.city_area_id
        //
        //     }).then((resp) => {
        //         if (resp.data.result !== undefined) {
        //             self.report_summary = resp.data.result;
        //             // console.dir(resp.data.result)
        //             this.stopCheck();
        //
        //             _.find(this.time_onway_summary, {type: 'less_5'}).value = _.filter(this.report_summary, {on_way_category: 'less_5'}).length;
        //             _.find(this.time_onway_summary, {type: 'less_10'}).value = _.filter(this.report_summary, {on_way_category: 'less_10'}).length;
        //             _.find(this.time_onway_summary, {type: 'more_10'}).value = _.filter(this.report_summary, {on_way_category: 'more_10'}).length;
        //
        //             _.find(this.time_liqv_summary, {type: 'less_15'}).value = _.filter(this.report_summary, {liqv_category: 'less_15'}).length;
        //             _.find(this.time_liqv_summary, {type: 'less_30'}).value = _.filter(this.report_summary, {liqv_category: 'less_30'}).length;
        //             _.find(this.time_liqv_summary, {type: 'less_60'}).value = _.filter(this.report_summary, {liqv_category: 'less_60'}).length;
        //             _.find(this.time_liqv_summary, {type: 'less_120'}).value = _.filter(this.report_summary, {liqv_category: 'less_120'}).length;
        //             _.find(this.time_liqv_summary, {type: 'more_120'}).value = _.filter(this.report_summary, {liqv_category: 'more_120'}).length;
        //
        //             _.find(this.time_gdzs_summary, {type: 'one'}).value = _.sumBy(_.filter(this.report_summary, {gdzs_count_type: 'one'}), (o) => {
        //                 return parseInt(o.gdzs_count_time);
        //             });
        //
        //             _.find(this.time_gdzs_summary, {type: 'many'}).value = _.sumBy(_.filter(this.report_summary, {gdzs_count_type: 'many'}), (o) => {
        //                 return parseInt(o.gdzs_count_time);
        //             });
        //         } else {
        //             this.startCheck();
        //         }
        //     });
        // },

        // exportXls() {
        //     let self = this;
        //     let queries = {
        //         date_begin: self.date_begin_,
        //         date_end: self.date_end_,
        //         result_id: self.result_id,
        //         burnt_id: self.burnt_id,
        //         city_area_id: self.city_area_id
        //     };
        //
        //     window.location.href = `/xls/report101/emergency?${serialize(queries)}`;
        // }
    },
    computed: {
        summaryFiltered() {
            let result = clone(this.report_summary);

            if (this.time_onway) {
                result = _.filter(result, {on_way_category: this.time_onway});
            }

            if (this.time_liqv) {
                result = _.filter(result, {liqv_category: this.time_liqv});
            }

            return result;
        }
    },
    created () {
    }
};
</script>

<style scoped>

</style>
