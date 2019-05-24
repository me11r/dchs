<template>
    <div class="">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px; min-height:1000px;">
                <h4 v-if="incidentTypes.length === 2" class="title">{{ '/reports/analytics101.tabs.branches_fall.title'|trans }}</h4><!--Отчет (падение веток и деревьев, подтопления)-->
                <h4 v-else class="title">{{ '/reports/analytics101.tabs.report112_emergency.title'|trans }}</h4><!--Отчет 112 по происшествиям-->
                <br>
                <form>
                    <div class="field">
                        <label for="reason">{{ 'emergency'|trans }}</label><!--Происшествие-->
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
                        <label for="reason">{{ 'incident_place'|trans }}</label><!--Место происшествия-->
                        <select
                            v-model="incident_place_id"
                            class="select"
                            name="incident_place_id"
                            id="incident_place_id">
                            <option value=""></option>
                            <option
                                v-for="item in incidentPlaces"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="emergencyNameId">{{ '/reports/analytics101.tabs.emergency_situations.emergency_name'|trans }}</label><!--Название ЧС-->
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
                        <label for="city_area_id">{{ 'city_area'|trans }}</label><!--Район города-->
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
                    <!--Причина подтопления-->
                    <div class="field" v-if="incident_type_id === 36">
                        <label for="reasonFloodingId">{{ '/reports/analytics101.tabs.branches_fall.reason'|trans }}</label><!--Причина-->
                        <select
                                class="select"
                                name="reasonFloodingId"
                                v-model="reasonFloodingId"
                                id="reasonFloodingId">
                            <option value=""></option>
                            <option
                                    v-for="item in reasonsFlooding"
                                    :value="item.id"
                                    :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>

                    <!--Место подтопления-->
                    <div class="field" v-if="incident_type_id === 36">
                        <label for="placeFloodingId">{{ '/reports/analytics101.tabs.branches_fall.flooding_place'|trans }}</label><!--Место-->
                        <select
                                class="select"
                                name="placeFloodingId"
                                v-model="placeFloodingId"
                                id="placeFloodingId">
                            <option value=""></option>
                            <option
                                    v-for="item in placesFlooding"
                                    :value="item.id"
                                    :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>

                    <!--Причина падения веток/деревьев-->
                    <div class="field" v-if="incident_type_id === 37">
                        <label for="reasonBranchesId">{{ '/reports/analytics101.tabs.branches_fall.reason'|trans }}</label><!--Причина-->
                        <select
                                class="select"
                                name="reasonBranchesId"
                                v-model="reasonBranchesId"
                                id="reasonBranchesId">
                            <option value=""></option>
                            <option
                                    v-for="item in reasonsBranches"
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
                        <p>Адрес</p>
                        <input type="text" v-model="addressSearch" class="input">
                    </div>
                    <div class="field">
                        <button @click.prevent="search" class="button is-success">{{ 'search'|trans }}</button><!--Поиск-->
                    </div>
                    <div
                        class="buttons has-text-right is-grouped is-right"
                        style="">
                        <a
                            :href="getHref('xlsx')"
                            download
                            class="button is-success">
                            <i class="fas fa-check"></i>&nbsp;{{ 'download_excel'|trans }}<!--Скачать в XLSX-->
                        </a>
                    </div>
                    <div
                        class="buttons has-text-right is-grouped is-right"
                        style="">
                        <a
                            :href="getHref('docx')"
                            download
                            class="button is-success">
                            <i class="fas fa-check"></i>&nbsp;{{ 'download_word'|trans }}<!--Скачать в DOCX-->
                        </a>
                    </div>
                </form>
                <div class="section" v-for="(areaArray, cityArea) in response" :key="`table_${cityArea}`">
                    <h3>{{ cityArea }} - {{ areaArray.length }}</h3>
                    <table class="formation-record-table">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th></th><!--Район-->
                            <th>Адрес</th>
                            <th v-if="incident_type_id === 36">{{ '/reports/analytics101.tabs.branches_fall.people_live'|trans }}</th><!--Количество проживающих-->
                            <th>{{ 'date'|trans }}</th><!--Дата происшествия-->
                            <th>{{ 'emergency'|trans }}</th><!--Происшествие-->
                            <th>{{ '/reports/analytics101.tabs.branches_fall.place'|trans }}</th><!--Место происшествия-->
                            <th>{{ '/reports/analytics101.tabs.branches_fall.reason'|trans }}</th><!--Причина-->
                            <th>{{ '/reports/analytics101.tabs.branches_fall.dead'|trans }}</th><!--Пострадавшие / погибшие-->
                            <th>{{ '/reports/analytics101.tabs.branches_fall.measures'|trans }}</th><!--Принятые меры-->
                            <th>{{ '/reports/analytics101.tabs.branches_fall.resources'|trans }}</th><!--Количество задействованных сил и средств-->
                            <th>{{ '/reports/analytics101.tabs.branches_fall.begin_end'|trans }}</th><!--Начало и завершение работ-->
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, key) in areaArray" :key="`row_`+item['Адрес']+item['Дата происшествия']+key">
                            <th>{{ ++key }}</th>
                            <th>{{ cityArea }}</th>
                            <th>{{ item['Адрес'] }}</th>
                            <th v-if="incident_type_id === 36">{{ item['Кол-во проживающих'] }}</th>
                            <th>{{ item['Дата происшествия'] }}</th>
                            <th>{{ item['Происшествие'] }}</th>
                            <th>{{ item['Место происшествия'] || item['Место подтопления'] }}</th>
                            <th>{{ item['Причина'] || item['Причина подтопления'] }}</th>
                            <th>{{ item['Пострадавшие / погибшие'] }}</th>
                            <th>{{ item['Принятые меры'] }}</th>
                            <th>{{ item['Количество задействованных сил и средств'] }}</th>
                            <th>{{ item['Начало и завершение работ'] }}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="section">
                    <div class="level">
                        <div class="level-left">
                            <h4>{{ '/reports/analytics101.tabs.branches_fall.total'|trans }} - {{ total }}</h4><!--Общее количество происшествий-->
                        </div>
                        <div class="level-right">
                            <h4>{{ '/reports/analytics101.tabs.branches_fall.dead'|trans }} - {{ totalInjured }}</h4><!--Пострадавшие/погибшие-->
                        </div>
                    </div>
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
        incidentPlaces: {
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
        reasonsFlooding: {
            type: Array,
            default: () => {}
        },
        placesFlooding: {
            type: Array,
            default: () => {}
        },
        reasonsBranches: {
            type: Array,
            default: () => {}
        },
    },
    computed: {
        getReportType() {
            return this.incidentTypes.length === 2 ? 'branches' : 'common';
        },
        getReasonFloodingId() {
            return this.incident_type_id === 36 ? this.reasonFloodingId : null;
        },
        getPlaceFloodingId() {
            return this.incident_type_id === 36 ? this.placeFloodingId : null;
        },
        getReasonBranchesId() {
            return this.incident_type_id === 37 ? this.reasonBranchesId : null;
        }
    },
    data: function () {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            date_begin_: new Date("01/01/2019"),
            date_end_: new Date,
            incident_type_id: '',
            incident_place_id: '',
            emergencyNameId: null,
            cityAreaId: null,
            reasonFloodingId: null,
            placeFloodingId: null,
            reasonBranchesId: null,
            addressSearch: null,
            total: 0,
            totalInjured: '0',
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
                'incident_place_id': this.incident_place_id,
                'city_area_id': this.cityAreaId,
                'addressSearch': this.addressSearch,
                'report_type': this.getReportType,
                'reasonBranchesId': this.getReasonBranchesId,
                'reasonFloodingId': this.getReasonFloodingId,
                'placeFloodingId': this.getPlaceFloodingId,
            }).then((q) => {
                this.response = q.data.data;
                this.total = q.data.total;
                this.totalInjured = q.data.totalInjured;
            });
        },
        getHref(download) {
            return '/reports/112/branches_export' +
                '?date_start=' + moment(this.date_begin_).format('YYYY-MM-DD') +
                '&date_end=' + moment(this.date_end_).format('YYYY-MM-DD') +
                '&incident_type_id=' + this.incident_type_id +
                '&incident_place_id=' + this.incident_place_id +
                '&city_area_id=' + this.cityAreaId +
                '&addressSearch=' + this.addressSearch +
                '&report_type=' + this.getReportType +
                '&reasonBranchesId=' + this.getReasonBranchesId +
                '&reasonFloodingId=' + this.getReasonFloodingId +
                '&placeFloodingId=' + this.getPlaceFloodingId +
                '&download=' + download +
                '&csrf-token=' + this.csrf;
        },
    },
};
</script>

<style scoped>

</style>
