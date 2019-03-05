<template>
    <div class="container">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">Учет сил и средств</h4>
                    </div>
                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary"
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;Печать</button>
                    </div>
                    <div class="level-left">
                        <a
                            href="/xls/report101/forces"
                            target="_blank"
                            download
                            class="button is-primary"
                            type="submit">
                            <i class="fas fa-print"></i>&nbsp;Сохранить в XLSX
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
                            <tr v-for="dept in reports_">
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
import axios from 'axios';
export default {
    name: 'ReportForces',
    props: {
        reports: {
            type: Array,
            default: () => {}
        }
    },
    data: function () {
        return {
            reports_: this.reports
        };
    },
    methods: {
        print() {
            window.print();
        },
        sync() {
            setTimeout(() => {
                this.getReports();
            }, 1000 * 2 * 60);
        },
        getReports() {
            axios.get('/reports/101/forces-resources').then((response) => {
                this.reports_ = response.data.reports;
                this.sync();
            });
        },
        isNull(data, property) {
            console.dir(data);

            try {
                if (data[property] === undefined || data[property] === null) {
                    return null;
                }

                return data[property];
            } catch (e) {
                return null;
            }
        },
        status(data) {
            if (data === null) {
                return null;
            }

            try {
                console.dir(data);

                return data.address + ', ранг пожара: ' +
                        this.isNull(data.status, 'fire_level').name + ', время выезда: ' + data.out_time + ', время прибытия: ' + data.arrive_time;
            } catch (e) {
                return null;
            }
        }
    },
    mounted() {
        this.getReports();
    }

};
</script>

<style scoped>

</style>
