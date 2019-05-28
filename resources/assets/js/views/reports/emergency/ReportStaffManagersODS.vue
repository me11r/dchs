<template>
    <div class="">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px; min-height:1000px;">
                <h4 class="title">{{ '/reports/analytics101.tabs.staff.title'|trans }}</h4><!--Отчет по личному составу ДЧС-->
                <div class="level">
                    <div class="level-right has-text-right">
                        <button
                                class="button is-info "
                                @click.prevent="post_data()"><i class="fas fa-search"></i>&nbsp;{{ 'search'|trans }}</button><!--Поиск-->
                    </div>

                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary "
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;{{ 'print'|trans }}</button><!--Печать-->
                    </div>

                </div>
                <br>
                <form>
                    <div class="field">
                        <label for="staff">{{ 'name_abbreviation'|trans }}</label><!--Ф.И.О.-->
                        <select
                            class="select"
                            v-model="person_id"
                            id="staff">
                            <option
                                v-for="person, key in orderedStaff"
                                :value="person.id"
                                :key="`staff_${person.name}_${key}`">{{ person.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="staff">{{ 'ods'|trans }}</label><!--ОДС-->
                        <select
                            class="select"
                            v-model="ogId"
                            id="og">
                            <option value=""></option>
                            <option
                                v-for="num in og"
                                :value="num.id"
                                :key="`num_${num.id}`">{{ num.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field is-grouped">
                        <v-datepicker-search
                            label="Начало периода"
                            :date="date_begin_"
                            v-model="date_begin_"
                            @dateChanged="date_begin_ = $event"
                        ></v-datepicker-search>

                        <v-datepicker-search
                            label="Конец периода"
                            :date="date_end_"
                            v-model="date_end_"
                            @dateChanged="date_end_ = $event"
                        ></v-datepicker-search>

                    </div>

                    <table v-if="report_summary !== null" class="formation-record-table">
                        <thead>
                            <tr>
                                <td>{{ '/reports/analytics101.tabs.staff.shifts'|trans }}</td><!--Выход на дежурство-->
                                <td>{{ '/reports/analytics101.tabs.staff.absence'|trans }}</td><!--Отсутствие-->
                                <td>{{ '/reports/analytics101.tabs.staff.absence_vacation'|trans }}</td><!--Отсутствие: Отпуск-->
                                <td>{{ '/reports/analytics101.tabs.staff.absence_maternity'|trans }}</td><!--Отсутствие: Декрет-->
                                <td>{{ '/reports/analytics101.tabs.staff.absence_sick'|trans }}</td><!--Отсутствие: Больничный-->
                                <td>{{ '/reports/analytics101.tabs.staff.absence_business_trip'|trans }}</td><!--Отсутствие: Командировка-->
                                <td>{{ 'ods'|trans }}</td><!--ОДС-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ report_summary.active.length }}</td>
                                <td>{{ report_summary.inactive.length }}</td>
                                <td>
                                    {{ report_summary.vacation.length }}
                                    <p v-for="(item, key) in report_summary.vacation">{{ ++key }}. {{ item.date_from|dateFilter('DD.MM.YYYY') }} - {{ item.date_to|dateFilter('DD.MM.YYYY') }}, {{ item.comment }}</p>
                                </td>
                                <td>
                                    {{ report_summary.maternity.length }}
                                    <p v-for="(item, key) in report_summary.maternity">{{ ++key }}. {{ item.date_from|dateFilter('DD.MM.YYYY') }} - {{ item.date_to|dateFilter('DD.MM.YYYY') }}, {{ item.comment }}</p>
                                </td>
                                <td>
                                    {{ report_summary.sick_leave.length }}
                                    <p v-for="(item, key) in report_summary.sick_leave">{{ ++key }}. {{ item.date_from|dateFilter('DD.MM.YYYY') }} - {{ item.date_to|dateFilter('DD.MM.YYYY') }}, {{ item.comment }}</p>
                                </td>
                                <td>
                                    {{ report_summary.business_trip.length }}
                                    <p v-for="(item, key) in report_summary.business_trip">{{ ++key }}. {{ item.date_from|dateFilter('DD.MM.YYYY') }} - {{ item.date_to|dateFilter('DD.MM.YYYY') }}, {{ item.comment }}</p>
                                </td>
                                <td>
                                    {{ report_summary ? report_summary.ogCount : null }}
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
    name: 'Report101Staff',
    props: {
        staff: {
            type: Array,
            default: () => []
        },
        og: {
            type: Array,
            default: () => []
        },
    },
    data: function () {
        return {
            staff_: this.staff,
            person_id: '',
            date_begin_: new Date,
            ogId: null,
            date_end_: new Date,
            report_summary: null
        };
    },
    methods: {
        print() {
            window.print();
        },
        post_data() {
            axios.post('/reports/101/staff-managers-ods', {
                date_begin: this.dates.from,
                date_end: this.dates.to,
                ogId: this.ogId,
                staff_id: this.person_id
            }).then((resp) => {
                this.report_summary = resp.data;
            });
        },
    },
    computed: {
        dates() {
            return {
                from: this.date_begin_ ? moment(this.date_begin_).format('YYYY-MM-DD') : null,
                to: this.date_end_ ? moment(this.date_end_).format('YYYY-MM-DD') : null,
            };
        },
        orderedStaff() {
            return _.sortBy(this.staff_, 'name');
        }
    }
};
</script>

<style scoped>

</style>
