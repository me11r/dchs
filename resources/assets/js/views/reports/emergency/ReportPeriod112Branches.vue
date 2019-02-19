<template>
    <div class="">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px; min-height:1000px;">
                <h4 v-if="incidentTypes.length === 2" class="title">Отчет (падение веток и деревьев, подтопления)</h4>
                <h4 v-else class="title">Отчет 112 по происшествиям</h4>
                <br>
                <form>
                    <div class="field">
                        <label for="reason">Происшествие</label>
                        <select
                            v-model="incident_type_id"
                            class="select"
                            name=""
                            id="reason">
                            <option value=""></option>
                            <option
                                v-for="item in incidentTypes"
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
                        <button @click.prevent="search" class="button is-success">Поиск</button>
                    </div>
                    <div
                        class="buttons has-text-right is-grouped is-right"
                        style="">
                        <a
                            :href="getHref"
                            download
                            class="button is-success">
                            <i class="fas fa-check"></i>&nbsp;Скачать
                        </a>
                    </div>
                </form>
                <div class="section" v-for="(areaArray, cityArea) in response" :key="`table_${cityArea}`">
                    <h3>{{ cityArea }}</h3>
                    <table class="formation-record-table">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Район</th>
                            <th>Адрес</th>
                            <th>Дата происшествия</th>
                            <th>Место происшествия</th>
                            <th>Причина</th>
                            <th>Пострадавшие / погибшие</th>
                            <th>Принятые меры</th>
                            <th>Количество задействованных сил и средств</th>
                            <th>Начало и завершение работ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, key) in areaArray" :key="`row_`+item['Адрес']">
                            <th>{{ ++key }}</th>
                            <th>{{ cityArea }}</th>
                            <th>{{ item['Адрес'] }}</th>
                            <th>{{ item['Дата происшествия'] }}</th>
                            <th>{{ item['Место происшествия'] }}</th>
                            <th>{{ item['Причина'] }}</th>
                            <th>{{ item['Пострадавшие / погибшие'] }}</th>
                            <th>{{ item['Принятые меры'] }}</th>
                            <th>{{ item['Количество задействованных сил и средств'] }}</th>
                            <th>{{ item['Начало и завершение работ'] }}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';

export default {
    name: 'ReportPeriod112Branches',
    props: {
        incidentTypes: {
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
    computed: {
        getHref() {
            return '/reports/112/branches_export' +
                '?date_start=' + moment(this.date_begin_).format('YYYY-MM-DD') +
                '&date_end=' + moment(this.date_end_).format('YYYY-MM-DD') +
                '&incident_type_id=' + this.incident_type_id +
                '&csrf-token=' + this.csrf;
        }
    },
    data: function () {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            date_begin_: new Date("01/01/2019"),
            date_end_: new Date,
            incident_type_id: '',
            emergencyNameId: null,
            cityAreaId: null,
            response: []
        };
    },
    methods: {
        search() {
            axios.post('/reports/112/branches_export', {
                'date_start': moment(this.date_begin_).format('YYYY-MM-DD'),
                'date_end': moment(this.date_end_).format('YYYY-MM-DD'),
                'incident_type_id': this.incident_type_id,
                'emergency_name_id': this.emergencyNameId,
                'city_area_id': this.cityAreaId,
            }).then((q) => {
                this.response = q.data.data;
            });
        }
    }
};
</script>

<style scoped>

</style>
