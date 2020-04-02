<template>
    <div class="container">
        <div class="panel">
            <div
                    class="box"
                    style="margin-top: 20px">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">Отчет по {{ reportInfo.name }}</h4>
                    </div>
                    <div class="level-right has-text-right">
                        <button
                                class="button is-primary"
                                @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;Печать</button>
                    </div>
                    <div class="level-left">
                        <a
                                :href="getDownloadLink('docx')"
                                target="_blank"
                                download
                                class="button is-primary"
                                type="submit">
                            <i class="fas fa-print"></i>&nbsp;{{ 'download_word'|trans }}
                        </a>
                    </div>
                    <div class="level-left">
                        <a
                                :href="getDownloadLink('xlsx')"
                                target="_blank"
                                download
                                class="button is-primary"
                                type="submit">
                            <i class="fas fa-print"></i>&nbsp;{{ 'download_excel'|trans }}
                        </a>
                    </div>
                </div>
                <br>
                <form>
                    <table class="table is-narrow is-hoverable is-fullwidth is-striped is-small formation-record-table">
                        <thead>
                            <tr>
                                <td>ПЧ</td>
                                <td>Статус</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dept in []">
                            <td>{{ dept.department.title }}</td>
                            <td>
                                <div >
                                    <table class="table is-narrow is-hoverable is-fullwidth is-striped is-small is-bordered">
                                        <thead>
                                        <tr>
                                            <td>Отделение</td>
                                            <td>Кол-во выездов за сегодня</td>
                                            <td>Выезда по тревоге</td>
                                            <td>Учения</td>
                                            <td>Прочие</td>
                                            <td>Статус</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="department in dept.items">
                                            <td>{{ department.department || department.reserve + ' резерв' }}</td>
                                            <td>{{ department.departures_count }}</td>
                                            <td>{{ department.real_departures_count }}</td>
                                            <td>{{ department.drill_departures_count }}</td>
                                            <td>{{ department.other_departures_count }}</td>
                                            <td>
                                                <table
                                                        v-if="department.status"
                                                        class="table is-narrow is-hoverable is-fullwidth is-striped is-small is-bordered">
                                                    <thead>
                                                    <tr>
                                                        <td>Адрес</td>
                                                        <td>Ранг пожара</td>
                                                        <td>Время выезда</td>
                                                        <td>Время прибытия</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <span
                                                                    class="ymaps-geolink"
                                                                    data-type="biz">
                                                                Алматы, {{ department.address }}
                                                            </span>
                                                        </td>
                                                        <td>{{ department.fire_rank }}</td>
                                                        <td>{{ department.out_time }}</td>
                                                        <td>{{ department.arrive_time }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <p v-else>в ПЧ</p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import moment from 'moment'
    export default {
        name: "ReportServices",
        props: {
            records: {
                type: Array,
                default: () => []
            },
            reportInfo: {
                type: Object,
                default: () => {}
            },
            rideTypes: {
                type: Array,
                default: () => []
            },
            cityAreas: {
                type: Array,
                default: () => []
            },
            incidentTypes: {
                type: Array,
                default: () => []
            },
        },
        data() {
            return {
                form: {
                    emergencyTypeId: 0,
                    cityAreaId: 0,
                    rideTypeId: 0,
                    dateFrom: new Date(),
                    dateTo: new Date(),
                }
            }
        },
        computed: {
        },
        methods: {
            print() {
                window.print();
            },
            getDownloadLink(documentType) {
                let url = `/reports/analytics-services/${this.reportInfo.slug}?`;
                _.forEach(this.form, (item, key) => {
                    if (key === 'dateFrom' || key === 'dateTo') {
                        let date = moment(item).format('DD.MM.YYYY');
                        url += `${key}=${date}&`
                    } else {
                        url += `${key}=${item}&`
                    }
                });
                url += `download=${documentType}`
                return url;
                // switch (this.reportInfo.slug) {
                //     case 'roso':
                //         return `link?${url}`;
                //     default:
                //         return ''
                // }
            }
        }
    }
</script>

<style scoped>

</style>