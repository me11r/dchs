<template>
    <div class="container" id="forces-resources-form" style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">Учет сил и средств
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
                            href="/reports/forces-resources/export/xlsx"
                            target="_blank"
                            download
                            class="button is-primary"
                            type="submit">
                            <i class="fas fa-print"></i>&nbsp;Сохранить в XLSX
                        </a>
                    </div>
                </div>
                <div class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <div class="control">
                                <label>ПЧ</label><br>
                                <select v-model="fireDepartmentId"
                                        class="control">
                                    <option value="">-</option>
                                    <option v-for="i in fireDepartments"
                                            :key="`fire_department_id_${i.id}`"
                                            :value="i.id">{{ i.title }}
                                    </option>
                                </select>
                            </div>
                        </div>
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
                            <tr v-for="dept in fireDepartments" v-if="uniqueDepartments(dept.id).length">
                                <td>{{ dept.title }}</td>
                                <td>
                                    <div >
                                        <table class="table is-narrow is-hoverable is-fullwidth is-striped is-small is-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Отделение</td>
                                                    <td>Кол-во выездов за период</td>
                                                    <td>Выезда по тревоге</td>
                                                    <td>Учения</td>
                                                    <td>Прочие выезда</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="ride in uniqueDepartments(dept.id)">
                                                    <td>{{ ride.tech.department || ride.tech.reserve + ' резерв' }}</td>
                                                    <td>{{ amountRides(dept.id, ride.tech.department).length }}</td>
                                                    <td>{{ amountRidesReal(dept.id, ride.tech.department).length }}</td>
                                                    <td>{{ amountRidesDrill(dept.id, ride.tech.department).length }}</td>
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
            fireDepartmentId: 0,
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
                return q.ticket && q.ticket.form_type_drill === null;
            });
        },
        amountRidesOther(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket_other;
            });
        },
        amountRidesDrill(deptId, department) {
            return _.filter(this.amountRides(deptId, department), function (q) {
                return q.ticket && q.ticket.form_type_drill !== null;
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
                console.dir(data);

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
                console.log(this.formActive);
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
        'fireDepartmentId'() {
            this.changeDate();
        }
    }

};
</script>

<style scoped>

</style>
