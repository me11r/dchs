<template>
    <div class="container" id="forces-resources-form" style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">{{ '/reports/analytics-spiasr.forces_resources.title'|trans({date_from: dateFromFormatted, date_to: dateToFormatted}) }}
            <!--Учет выездов подразделений
            за {{ dateFromFormatted }} по {{ dateToFormatted }}-->
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
                            href="/reports/forces-resources/export/xlsx"
                            target="_blank"
                            download
                            class="button is-primary"
                            type="submit">
                            <i class="fas fa-print"></i>&nbsp;{{ 'download_excel'|trans }}<!--Сохранить в XLSX-->
                        </a>
                    </div>
                </div>
                <div class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <div class="control">
                                <label>{{ 'fd'|trans }}</label><br>
                                <select v-model="fireDepartmentId"
                                        class="control">
                                    <!--<option value="">-</option>-->
                                    <option v-for="i in fireDepartments"
                                            :key="`fire_department_id_${i.id}`"
                                            :value="i.id">{{ i.title }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <button class="button is-info"
                            @click.prevent="changeDate">{{ 'search'|trans }}</button><!--Поиск-->
                </div>
                <br>
                <form>
                    <table class="table is-narrow is-hoverable is-fullwidth is-striped is-small formation-record-table">
                        <thead>
                            <tr>
                                <td>{{ 'fd'|trans }}</td><!--ПЧ-->
                                <td>{{ 'status'|trans }}</td><!--Статус-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dept in fireDepartments" v-if="uniqueDepartments(dept.id).length">
                                <td>{{ dept.title }}</td>
                                <td>
                                    <div>
                                        <table class="table is-narrow is-hoverable is-fullwidth is-striped is-small is-bordered">
                                            <thead>
                                            <tr>
                                                <td rowspan="2">{{ 'department'|trans }}</td><!--Отделение-->
                                                <td colspan="7">{{ '/reports/analytics-spiasr.forces_resources.real_rides'|trans }}</td><!--Количество выездов по тревоге-->
                                                <td rowspan="2">{{ '/reports/analytics-spiasr.forces_resources.practical_rides'|trans }}</td><!--Практические выезда (ПТЗ,ПТУ,ТСУ,РКШУ,Учения, ТДК)-->
                                                <td rowspan="2">{{ '/reports/analytics-spiasr.forces_resources.corrections'|trans }}</td><!--Корректировки-->
                                                <td rowspan="2">{{ '/reports/analytics-spiasr.forces_resources.false_nonreal'|trans }}</td><!--Прочие Выезда-->
                                            </tr>
                                            <tr>
                                                <td>{{ '/reports/analytics-spiasr.forces_resources.real_rides2'|trans }}</td><!--Общее количество выездов по тревоге-->
                                                <td>{{ '/reports/analytics-spiasr.forces_resources.fires'|trans }}</td><!--Пожары-->
                                                <td>{{ '/reports/analytics-spiasr.forces_resources.asr'|trans }}</td><!--Проведение аварийно-спасательных работ-->
                                                <td>{{ '/reports/analytics-spiasr.forces_resources.false_nonreal'|trans }}</td><!--Ложные/Бдительность населения-->
                                                <td>{{ '/reports/analytics-spiasr.forces_resources.siren'|trans }}</td><!--Срабатывание сигнализации-->
                                                <td>{{ '/reports/analytics-spiasr.forces_resources.area'|trans }}</td><!--Область-->
                                                <td>{{ '/reports/analytics-spiasr.forces_resources.non_fires'|trans }}</td><!--Случаи горения, не подлежащие учету как пожары-->
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="ride in uniqueDepartments(dept.id)">
                                                    <td>{{ ride.tech.department || ride.tech.reserve + ' резерв' }}</td>
                                                    <td>{{ amountRidesReal(dept.id, ride.tech.department).length }}</td>
                                                    <td>{{ amountRidesFire(dept.id, ride.tech.department).length }}</td>
                                                    <td>{{ amountRidesASR(dept.id, ride.tech.department).length }}</td>
                                                    <td>{{ amountRidesFalse(dept.id, ride.tech.department).length }}</td>
                                                    <td>{{ amountRidesAlarm(dept.id, ride.tech.department).length }}</td>
                                                    <td>{{ amountRidesArea(dept.id, ride.tech.department).length }}</td>
                                                    <!--<td>{{ amountRidesNonFire(dept.id, ride.tech.department).length }}</td>-->
                                                    <td>{{ amountRidesReal(dept.id, ride.tech.department).length - (amountRidesFire(dept.id, ride.tech.department).length + amountRidesASR(dept.id, ride.tech.department).length + amountRidesFalse(dept.id, ride.tech.department).length + amountRidesAlarm(dept.id, ride.tech.department).length + amountRidesArea(dept.id, ride.tech.department).length) }}</td>
                                                    <td>{{ amountRidesDrill(dept.id, ride.tech.department).length }}</td>
                                                    <td>{{ amountRidesCorrection(dept.id, ride.tech.department).length }}</td>
                                                    <td>{{ amountRidesOther(dept.id, ride.tech.department).length }}</td>
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
import moment from 'moment';

export default {
    name: 'ReportForcesResources',
    props: {
        reports: {
            type: Array,
            default: () => {}
        },
        fireDepartments: {
            type: Array,
            default: () => { return []; }
        },
    },
    data: function () {
        return {
            reports_: this.reports,
            dateFrom: new Date,
            dateTo: new Date,
            fireDepartmentId: 1,
        };
    },
    computed: {
        dateToFormatted() {
            return moment(this.dateTo).format('DD-MM-YYYY');
        },
        dateFromFormatted() {
            return moment(this.dateFrom).format('DD-MM-YYYY');
        },
        formRides() {
            let data = [];
            this.fireDepartments.forEach((dept) => {
                data[dept.id] = _.filter(this.reports_, function (report) {
                    return report.fire_department_id === dept.id;
                });
            });

            return data;
        },
    },
    methods: {
        isNull(data, property) {
            try {
                if (data[property] === undefined || data[property] === null) {
                    return null;
                }

                return data[property];
            } catch (e) {
                return null;
            }
        },
        amountRidesReal(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket && q.ticket.drill_type_id === null;
            });
        },
        amountRidesOther(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket_other;
            });
        },
        amountRidesDrill(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket && q.ticket.drill_type_id !== null;
            });
        },
        amountRidesFire(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket && q.ticket.trip_result && q.ticket.trip_result.name === 'Пожар';
            });
        },
        amountRidesCorrection(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket && q.ticket.drill_type && q.ticket.drill_type.name === 'Корректировка';
            });
        },
        amountRidesNonFire(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket && q.ticket.trip_result
                    && q.ticket.trip_result.name !== 'Пожар'
                    && q.ticket.trip_result.name !== 'Ложный'
                    && q.ticket.trip_result.name !== 'Бдительность населения'
                    && q.ticket.trip_result.name !== 'Срабатывание сигнализации'
                    && q.ticket.trip_result.name !== 'Область'
                    && q.ticket.trip_result.name !== 'АСР';
            });
        },
        amountRidesASR(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket && q.ticket.trip_result && q.ticket.trip_result.name === 'АСР';
            });
        },
        amountRidesFalse(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return (q.ticket && q.ticket.trip_result) && (q.ticket.trip_result.name === 'Ложный' || q.ticket.trip_result.name === 'Бдительность населения');
            });
        },
        amountRidesAlarm(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket && q.ticket.trip_result && q.ticket.trip_result.name === 'Срабатывание сигнализации';
            });
        },
        amountRidesArea(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket && q.ticket.trip_result && q.ticket.trip_result.name === 'Область';
            });
        },
        amountRides(deptId, department) {
            return _.filter(this.formRides[deptId], function (q) {
                return q.tech.department === department;
            });
        },
        uniqueDepartments(deptId) {
            let rides = this.formRides[deptId];
            return _.uniqBy(rides, function (q) {
                return q.tech.department;
            });
        },
        status(data) {
            if (data === null) {
                return null;
            }

            try {
                return data.address + ', ранг пожара: ' +
                        this.isNull(data.status, 'fire_level').name + ', время выезда: ' + data.out_time + ', время прибытия: ' + data.arrive_time;
            } catch (e) {
                return null;
            }
        },
        changeDate() {
            let form = document.getElementById('forces-resources-form');
            let loadingComponent = this.$loading.open({
                container: form
            });
            axios.get('/reports/forces-resources', {
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

};
</script>

<style scoped>

</style>
