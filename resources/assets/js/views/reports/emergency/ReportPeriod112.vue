<template>
    <div class="">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px; min-height:1000px;">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">Отчет по карточке 112 за период</h4>
                    </div>
                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary"
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;Печать
                        </button>
                    </div>
                    <div class="level-right has-text-right">
                        <a
                            :href="getHref"
                            class="button is-primary"
                        ><i class="fas fa-print"></i>&nbsp;Сохранить в XLS
                        </a>
                    </div>
                </div>
                <br>
                <form>
                    <div class="field">
                        <label for="reason">Происшествие</label>
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
                        <label for="reason">Район города</label>
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
                        <label for="reason">Название ЧС</label>
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
                        <button @click.prevent="selectPeriod" class="button is-success">Поиск</button>
                    </div>

                    <div class="field" style="overflow: scroll">
                        <table class="formation-record-table">
                            <thead>
                            <tr>
                                <td class="is-narrow">Происшествие</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(record, title) in report_summary">
                                <td class="is-narrow">{{ title }}</td>
                                <td>
                                    <table class="formation-record-table">
                                        <thead>
                                        <tr>
                                            <td>Район города</td>
                                            <td>Кол-во происшествий(ЧС)</td>
                                            <td>Пострадавших людей/детей</td>
                                            <td>Погибших людей/детей</td>
                                            <td>Эвакуированных людей/детей</td>
                                            <td>Госпитализированных людей/детей</td>
                                            <td>Травмированных людей/детей</td>
                                            <td>Отравление людей/детей</td>
                                            <td>Спасено людей/детей</td>
                                            <td>Спасено животных</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(cityArea, citAreaTitle) in record">
                                            <td>{{ citAreaTitle }}</td>
                                            <td>{{ cityArea.total }}</td>
                                            <td>{{ cityArea.injured }}</td>
                                            <td>{{ cityArea.dead }}</td>
                                            <td>{{ cityArea.evacuated }}</td>
                                            <td>{{ cityArea.hospitalized }}</td>
                                            <td>{{ cityArea.injured_hard }}</td>
                                            <td>{{ cityArea.poisoned }}</td>
                                            <td>{{ cityArea.saved }}</td>
                                            <td>{{ cityArea.saved_animals }}</td>
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
