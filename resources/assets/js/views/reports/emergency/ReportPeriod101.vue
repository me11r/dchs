<template>
    <div class="container" id="other-rides-form">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px; min-height:1000px;">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">Отчет-1</h4>
                    </div>
                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary"
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;Печать</button>
                    </div>
                    <div class="level-right has-text-right">
                        <button
                            @click.prevent="exportXls"
                            class="button is-primary"
                        >
                            <i class="fas fa-print"></i>&nbsp;Сохранить в XLS
                        </button>
                    </div>
                </div>
                <br>
                <form>
                    <div class="field">
                        <label for="reason">Результат выезда:</label>
                        <select
                            @change="selectResult"
                            class="select"
                            name=""
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
                            @change="post_data"
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
                            @change="post_data"
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
                        <h4 class="title">Время следования</h4>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>до 5 минут</th>
                                    <th>до 10 минут</th>
                                    <th>более 10 минут</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td :key="`time_on_way_summary_${item.type}`" v-for="item in time_onway_summary">{{ item.value }}</td>
                                </tr>
                            </tbody>
                        </table>
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
                        <h4 class="title">Время ликвидации</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>до 15 минут</th>
                                    <th>до 30 минут</th>
                                    <th>до 1 часа</th>
                                    <th>до 2 часов</th>
                                    <th>более 2 часов</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td :key="`time_liqv_summary${item.type}`" v-for="item in time_liqv_summary">{{ item.value }}</td>
                                </tr>
                            </tbody>
                        </table>
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
                    <div class="field">
                        <label for="startPeriod">Начало периода:</label>
                        <input
                            @blur="selectPeriod"
                            v-model="date_begin_"
                            type="date"
                            class="date"
                            id="startPeriod">
                    </div>
                    <div class="field">
                        <label for="endPeriod">Конец периода:</label>
                        <input
                            @blur="selectPeriod"
                            v-model="date_end_"
                            class="date"
                            type="date"
                            id="endPeriod">
                    </div>
                    <div class="field">
                        <h4 class="title">Звенья ГДЗС</h4>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>одним звеном</th>
                                <th>двумя и более</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td :key="`time_gdzs_${item.type}`" v-for="item in time_gdzs_summary">{{ item.value }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="field">
                        <h3 class="title">Общее количество выездов: {{ summaryFiltered.length }}</h3>
                    </div>
                    <div class="tbl" style="overflow-x: scroll;">
                        <table class="formation-record-table" >
                            <thead>
                            <tr>
                                <td>Дата выезда</td>
                                <td>Время</td>
                                <td>ФИО</td>
                                <td>Телефон</td>
                                <td>Район города</td>
                                <td>Адрес</td>
                                <td>Объект пожара</td>
                                <td>Участники тушения</td> <!--Из "хронологии"-->
                                <td>Ликвидировано стволами</td> <!--Из "хронологии"-->
                                <td>Время в пути</td> <!--время прибытия - время выезда-->
                                <td>Локализация</td> <!--фактическое время Локализации (хронология)-->
                                <td>Ликвидация</td> <!--фактическое время Ликвидации (хронология)-->
                                <td>Время тушения</td> <!--время от прибытия до локализации-->
                                <td>Стволы</td> <!--из хронологии-->
                                <td>Звенья ГДЗС</td> <!--Кол-во ГДЗС (хронология)-->
                                <td>Время работы стволов</td> <!--из хронологии-->
                                <td>Время работы ГДЗС</td> <!--из хронологии-->
                                <td>Спасено людей</td> <!--Итоги выезда-->
                                <td>Эвакуировано людей</td> <!--Итоги выезда-->
                                <td>Травмы</td> <!--Итоги выезда-->
                                <td>Гибель</td> <!--Итоги выезда-->
                                <td>Затраченное время на локализацию</td> <!--время локализации - время прибытия-->
                                <td>Затраченное время на ликвидацию</td> <!--время ликвидации - время прибытия-->
                                <td>Результат выезда</td> <!--Справочник Результат выезда-->
                                <td>Площадь горения</td> <!--Итоги выезда-->
                                <td>Этажность</td> <!--Путевка-->
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                    v-for="(item, idx) in summaryFiltered"
                                    :key="`rpt_smm_${idx}`">

                                <td>{{ item.created_at|dateFilter('DD.MM.YYYY') }}</td>
                                <td>{{ item.created_at|dateFilter('HH:mm') }}</td>
                                <td>{{ item.caller_name }}</td>
                                <td>{{ item.caller_phone }}</td>
                                <td>{{ item.city_area ? item.city_area.name : '' }}</td>
                                <td>{{ item.location }}</td>
                                <td>{{ item.object_name }}</td>
                                <td>{{ item.detailed_staff_count }}</td> <!--УЧАСТНИКИ ТУШЕНИЯ-->
                                <td>{{ item.trucks_count }}</td> <!--Ликвидировано стволами-->
                                <td>{{ item.on_way_time }}</td>
                                <td>{{ item.loc_time }}</td>
                                <td>{{ item.liqv_time }}</td>
                                <td>{{ item.loc_time_total }}</td>
                                <td>
                                    <p
                                        v-for="(chronology, idx) in item.chronologies"
                                        v-if="chronology.event_info_arrived_id !== null && chronology.event_info_arrived.name !== 'ГДЗС'"
                                        :key="`chr_qnt_${idx}`">
                                        Тип {{ chronology.event_info_arrived.name }}
                                        Количество: {{ chronology.quantity }}<br>
                                    </p>
                                </td> <!--СТВОЛЫ-->
                                <td>{{ item.gdzs_count }}</td> <!--Звенья ГДЗС-->
                                <td>
                                    <p
                                            v-for="(chronology, idx) in item.chronologies"
                                            v-if="chronology.event_info_arrived_id !== null && chronology.event_info_arrived.name !== 'ГДЗС'"
                                            :key="`chr_woring_time_${idx}`">
                                        Тип {{ chronology.event_info_arrived.name }}
                                        Количество: {{ chronology.working_time }}<br>
                                    </p>
                                </td> <!--Время работы стволов-->
                                <td>
                                    <p
                                            v-for="(chronology, idx) in item.chronologies"
                                            v-if="chronology.event_info_arrived_id !== null && chronology.event_info_arrived.name === 'ГДЗС'"
                                            :key="`chr_woring_time_gdzs_${idx}`">
                                        Тип {{ chronology.event_info_arrived.name }}
                                        Количество: {{ chronology.working_time }}<br>
                                    </p>
                                </td> <!--Время работы ГДЗС-->
                                <td>{{ item.rescued_count }}</td>
                                <td>{{ item.evac_count }}</td>
                                <td>{{ item.gpt_burns_count }}</td>
                                <td>{{ item.people_death_count + item.children_death_count }}</td>
                                <td>{{ item.loc_time_total }}</td> <!--Затраченное время на локализацию--> <!--время локализации - время прибытия-->
                                <td>{{ item.liqv_time_total }}</td> <!--Затраченное время на ликвидацию--> <!--время ликвидации - время прибытия-->
                                <td>{{ item.trip_result ? item.trip_result.name : '' }}</td>
                                <td>{{ item.max_square }}</td>
                                <td>{{ item.storey_count }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import _ from 'lodash';
import { serialize } from '../../../utils';
import { clone } from '../../../utils';

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

            time_onway_summary: [
                {type: 'less_5', value: ''},
                {type: 'less_10', value: ''},
                {type: 'more_10', value: ''},
            ],
            time_onway_options: [
                {title: 'до 5 минут', name: 'less_5'},
                {title: 'до 10 минут', name: 'less_10'},
                {title: 'более 10 минут', name: 'more_10'},
            ],
            time_onway: null,

            time_liqv: null,
            time_liqv_options: [
                {title: 'до 15 минут', name: 'less_15'},
                {title: 'до 30 минут', name: 'less_30'},
                {title: 'до 1 часа', name: 'less_60'},
                {title: 'до 2 часов', name: 'less_120'},
                {title: 'более 2 часов', name: 'more_120'},
            ],
            time_liqv_summary: [
                {type: 'less_15', value: ''},
                {type: 'less_30', value: ''},
                {type: 'less_60', value: ''},
                {type: 'less_120', value: ''},
                {type: 'more_120', value: ''},
            ],

            time_gdzs_summary: [
                {type: 'one', value: ''},
                {type: 'many', value: ''},
            ],
        };
    },
    methods: {
        selectResult(event) {
            this.result_id = event.target.value;

            window.history.pushState('page2', 'Title', '/reports/101/emergency?reason=' + this.result_id);

            this.post_data();
        },
        print() {
            window.print();
        },
        post_data() {
            let self = this;
            let form = document.getElementById('other-rides-form');
            let loadingComponent = this.$loading.open({
                container: form
            });
            axios.post('/reports/101/emergency', {
                date_begin: self.date_begin_,
                date_end: self.date_end_,
                result_id: self.result_id,
                burnt_id: self.burnt_id,
                time_liqv: self.time_liqv,
                time_onway: self.time_onway,
                city_area_id: self.city_area_id
            }).then((resp) => {
                self.report_summary = resp.data;
                loadingComponent.close();

                _.find(this.time_onway_summary, {type: 'less_5'}).value = _.filter(this.report_summary, {on_way_category: 'less_5'}).length;
                _.find(this.time_onway_summary, {type: 'less_10'}).value = _.filter(this.report_summary, {on_way_category: 'less_10'}).length;
                _.find(this.time_onway_summary, {type: 'more_10'}).value = _.filter(this.report_summary, {on_way_category: 'more_10'}).length;

                _.find(this.time_liqv_summary, {type: 'less_15'}).value = _.filter(this.report_summary, {liqv_category: 'less_15'}).length;
                _.find(this.time_liqv_summary, {type: 'less_30'}).value = _.filter(this.report_summary, {liqv_category: 'less_30'}).length;
                _.find(this.time_liqv_summary, {type: 'less_60'}).value = _.filter(this.report_summary, {liqv_category: 'less_60'}).length;
                _.find(this.time_liqv_summary, {type: 'less_120'}).value = _.filter(this.report_summary, {liqv_category: 'less_120'}).length;
                _.find(this.time_liqv_summary, {type: 'more_120'}).value = _.filter(this.report_summary, {liqv_category: 'more_120'}).length;

                _.find(this.time_gdzs_summary, {type: 'one'}).value = _.sumBy(_.filter(this.report_summary, {gdzs_count_type: 'one'}), (o) => {
                    return parseInt(o.gdzs_count_time);
                });

                _.find(this.time_gdzs_summary, {type: 'many'}).value = _.sumBy(_.filter(this.report_summary, {gdzs_count_type: 'many'}), (o) => {
                    return parseInt(o.gdzs_count_time);
                });

            });
        },

        selectPeriod() {
            this.post_data();
        },

        exportXls() {
            let self = this;
            let queries = {
                date_begin: self.date_begin_,
                date_end: self.date_end_,
                result_id: self.result_id,
                burnt_id: self.burnt_id,
                city_area_id: self.city_area_id
            };

            window.location.href = `/xls/report101/emergency?${serialize(queries)}`;
        }
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
