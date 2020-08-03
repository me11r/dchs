<template>
    <div class="">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px; min-height:1000px;">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">{{ '/reports/analytics101.tabs.report_112_period.title'|trans }}</h4><!--Отчет по карточке 112 за период-->
                    </div>
                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary"
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;{{ 'print'|trans }} <!--Печать-->
                        </button>
                    </div>
                    <!-- Добавляем Ворд -->
                    <div class="level-right has-text-right">
                        <a
                            :href="getHreftoWord"
                            class="button is-primary"
                        ><i class="fas fa-print"></i>&nbsp;{{ 'download_word'|trans }} <!--Скачать в Word-->
                        </a>
                    </div>
                    <div class="level-right has-text-right">
                        <a
                            :href="getHref"
                            class="button is-primary"
                        ><i class="fas fa-print"></i>&nbsp;{{ 'download_excel'|trans }} <!--Скачать в XLS-->
                        </a>
                    </div>
                </div>
                <br>
                <form>
                    <div class="field">
                        <label for="reason">{{ 'emergency'|trans }}</label><!--Происшествие-->
                        <select
                            @change="selectReason"
                            class="select"
                            name=""
                            v-model="reason_id"
                            id="reason">
                            <option value=""></option>
                            <option
                                v-for="item in reasons_"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="reason">{{ 'city_area'|trans }}</label><!--Район города-->
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
                        <label for="reason">{{ '/reports/analytics101.tabs.emergency_situations.emergency_name'|trans }}</label><!--Название ЧС-->
                        <select
                            class="select"
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
                    <div class="field is-grouped">
                        <v-datepicker-search
                                v-model="date_begin_"
                                :date="date_begin_"
                                class="control"
                                @dateChanged="date_begin_ = $event"
                                label="С">
                        </v-datepicker-search>
                        <v-datepicker-search
                                v-model="date_end_"
                                :date="date_end_"
                                @dateChanged="date_end_ = $event"
                                class="control"
                                label="По">
                        </v-datepicker-search>
                    </div>
                    <div class="field">
                        <button @click.prevent="selectPeriod" class="button is-success">{{ 'search'|trans }}</button><!--Поиск-->
                    </div>

                    <div class="field" style="overflow: scroll">
                        <table class="formation-record-table">
                            <thead>
                            <tr>
                                <td class="is-narrow">{{ 'emergency'|trans }}</td><!--Происшествие-->
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(record, title) in reportSummaryFiltered">
                                <td class="is-narrow">{{ title }}</td>
                                <td>
                                    <table class="formation-record-table">
                                        <thead>
                                        <tr>
                                            <td>{{ 'city_area'|trans }}</td><!--Район города-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.emergency_count'|trans }}</td><!--Кол-во происшествий(ЧС)-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.injured'|trans }}</td><!--Пострадавших людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.dead'|trans }}</td><!--Погибших людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.evacuated'|trans }}</td><!--Эвакуированных людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.hospitalized'|trans }}</td><!--Госпитализированных людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.traumatized'|trans }}</td><!--Травмированных людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.poisoned'|trans }}</td><!--Отравление людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.saved'|trans }}</td><!--Спасено людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.saved_animals'|trans }}</td><!--Спасено животных-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(cityArea, citAreaTitle) in record">
                                            <td>{{ citAreaTitle }}</td>
                                            <td>{{ cityArea.total }}</td>
                                            <td>{{ cityArea.injured }} / {{ cityArea.injured_children }}</td>
                                            <td>{{ cityArea.dead }} / {{ cityArea.dead_children }}</td>
                                            <td>{{ cityArea.evacuated }} / {{ cityArea.evacuated_children }}</td>
                                            <td>{{ cityArea.hospitalized }} / {{ cityArea.hospitalized_children }}</td>
                                            <td>{{ cityArea.injured_hard }} / {{ cityArea.injured_hard_children }}</td>
                                            <td>{{ cityArea.poisoned }} / {{ cityArea.poisoned_children }}</td>
                                            <td>{{ cityArea.saved }} / {{ cityArea.saved_children }}</td>
                                            <td>{{ cityArea.saved_animals }}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr v-if="totalRow['Итог']">
                                <td class="is-narrow">Итог</td>
                                <td>
                                    <table class="formation-record-table">
                                        <thead>
                                        <tr>
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.emergency_count'|trans }}</td><!--Кол-во происшествий(ЧС)-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.injured'|trans }}</td><!--Пострадавших людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.dead'|trans }}</td><!--Погибших людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.evacuated'|trans }}</td><!--Эвакуированных людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.hospitalized'|trans }}</td><!--Госпитализированных людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.traumatized'|trans }}</td><!--Травмированных людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.poisoned'|trans }}</td><!--Отравление людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.saved'|trans }}</td><!--Спасено людей/детей-->
                                            <td>{{ '/reports/analytics101.tabs.report_112_period.saved_animals'|trans }}</td><!--Спасено животных-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{ totalRow['Итог'].total }}</td>
                                            <td>{{ totalRow['Итог'].injured }} / {{ totalRow['Итог'].injured_children }}</td>
                                            <td>{{ totalRow['Итог'].dead }} / {{ totalRow['Итог'].dead_children }}</td>
                                            <td>{{ totalRow['Итог'].evacuated }} / {{ totalRow['Итог'].evacuated_children }}</td>
                                            <td>{{ totalRow['Итог'].hospitalized }} / {{ totalRow['Итог'].hospitalized_children }}</td>
                                            <td>{{ totalRow['Итог'].injured_hard }} / {{ totalRow['Итог'].injured_hard_children }}</td>
                                            <td>{{ totalRow['Итог'].poisoned }} / {{ totalRow['Итог'].poisoned_children }}</td>
                                            <td>{{ totalRow['Итог'].saved }} / {{ totalRow['Итог'].saved_children }}</td>
                                            <td>{{ totalRow['Итог'].saved_animals }}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
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
import moment from 'moment';
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
        reasons: {
            type: Array,
            default: () => {}
        },
        cityAreas: {
            type: Array,
            default: () => {}
        },
        emergencyNames: {
            type: Array,
            default: () => {}
        },
    },
    data: function () {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            date_begin_: new Date("01/01/2019"),//this.date_begin,
            date_end_: new Date,//this.date_end,
            report_summary: {},
            reason_id: null,
            cityAreaId: null,
            emergencyNameId: null,
            reasons_: this.reasons
        };
    },
    methods: {
        selectReason(event) {
            this.reason_id = event.target.value;

            // this.post_data(this.reason_id);
        },
        print() {
            window.print();
        },
        post_data() {
            let self = this;

            let form = document.getElementById('vue');
            let loadingComponent = this.$loading.open({
                container: form
            });

            axios.post('/reports/112/emergency', {
                date_begin: moment(self.date_begin_).format('YYYY-MM-DD'),
                date_end: moment(self.date_end_).format('YYYY-MM-DD'),
                reason_id: self.reason_id,
                city_area_id: self.cityAreaId,
                emergency_name_id: self.emergencyNameId,
            }).then((resp) => {
                self.report_summary = resp.data;
                loadingComponent.close();
            });
        },

        selectPeriod() {
            this.post_data();
        }
    },
    computed: {
        getHref() {
            return '/xls/report112/emergency' +
                '?date_begin=' + moment(this.date_begin_).format('YYYY-MM-DD') +
                '&date_end=' + moment(this.date_end_).format('YYYY-MM-DD') +
                '&result_id=' + this.reason_id +
                '&city_area_id=' + this.cityAreaId;
        },
        getHreftoWord() {
            return '/word/report112/emergency' +
                '?date_begin=' + moment(this.date_begin_).format('YYYY-MM-DD') +
                '&date_end=' + moment(this.date_end_).format('YYYY-MM-DD') +
                '&result_id=' + this.reason_id +
                '&city_area_id=' + this.cityAreaId;
        },
        reportSummaryFiltered() {
            return window._.pickBy(this.report_summary, (item, key) => {
                return key !== 'Итог';
            });
        },
        totalRow() {
            return window._.pickBy(this.report_summary, (item, key) => {
                return key === 'Итог';
            });
        }
    },
    watch: {

    },

    created () {
    }
};
</script>

<style scoped>

</style>
