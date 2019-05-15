<template>
    <div id="other-rides-form" style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">
            <!--Учет аварийно спасательных работ, проведенных
            ГУ «Служба пожаротушения и аварийно-спасательных работ» ДЧС г. Алматы
            за {{ dateFromFormatted }} по {{ dateToFormatted }}-->
            {{ '/reports/analytics-spiasr.emergency_form_051.title'|trans({date_from: dateFromFormatted,date_to: dateToFormatted}) }}
        </h4>

        <div class="panel">
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
                <div class="level-right">
                    <div class="level-item">
                        <a href="/reports/emergency-rescue-gu/export/xlsx" class="button is-info">Сохранить в .XLSX</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th rowspan="2">Дата</th>
                        <th rowspan="2">{{ '/reports/analytics-spiasr.emergency_form_051.real_rides'|trans }}</th><!--Кол-во выездов по тревоге-->
                        <th rowspan="2">{{ '/reports/analytics-spiasr.emergency_form_051.asr'|trans }}</th><!--Из них на АСР-->
                        <th rowspan="2">{{ '/reports/analytics-spiasr.emergency_form_051.false_calls'|trans }}</th><!--Кол-во ложных  выездов-->
                        <th colspan="11">{{ '/reports/analytics-spiasr.emergency_form_051.within_asr'|trans }}</th><!--В ходе аварийно-спасательных работ-->
                    </tr>
                    <tr>
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.staff_count'|trans }}</th><!--Численность привлеченного  л/c (человек)-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.tech_count'|trans }}</th><!--Кол-во привлеченной техники (единиц)-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.vehicles_saved'|trans }}</th><!--Кол-во освобожденных а/м призаносах-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.people_saved'|trans }}</th><!--Кол-во спасенных человек-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.children_saved'|trans }}</th><!--В том числе детей-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.bodies_excavated'|trans }}</th><!--Извлечено тел-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.children_bodies_excavated'|trans }}</th><!--В том числе детей-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.medical_help_applied'|trans }}</th><!--Оказана мед. помощь-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.medical_help_applied_children'|trans }}</th><!--В том числе детям-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.evacuated'|trans }}</th><!--Кол-во эвакуированных-->
                        <th>{{ '/reports/analytics-spiasr.emergency_form_051.evacuated_children'|trans }}</th><!--В том числе детей-->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ dateFromFormatted }} - {{ dateToFormatted }}</td>
                        <td>{{ record_.rides_count }}</td>
                        <td>{{ record_.rides_asr_count }}</td>
                        <td>{{ record_.rides_false_count }}</td>
                        <td>{{ record_.total_staff_count }}</td>
                        <td>{{ record_.total_tech_count }}</td>
                        <td>{{ record_.saved_vehicles }}</td>
                        <td>{{ record_.rescued_count }}</td>
                        <td>{{ record_.saved_children }}</td>
                        <td>{{ record_.bodies_extracted }}</td>
                        <td>{{ record_.children_bodies_extracted }}</td>
                        <td>{{ record_.medical_care_provided }}</td>
                        <td>{{ record_.children_medical_care_provided }}</td>
                        <td>{{ record_.evac_count }}</td>
                        <td>{{ record_.children_evacuated }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
    </div>
</template>

<script>
    import moment from 'moment';
    import axios from 'axios';
    import BField from "buefy/src/components/field/Field";
    export default {
        name: "ReportTicket101EmergencyRescueGu",
        components: {BField},
        props: {
            record: {
                type: Object,
                default: () => { return {}; }
            },
        },
        data: function () {
            return {
                record_: this.record,
                dateFrom: new Date,
                dateTo: new Date,
            }
        },
        computed: {
            dateToFormatted() {
                return moment(this.dateTo).format('DD-MM-YYYY');
            },
            dateFromFormatted() {
                return moment(this.dateFrom).format('DD-MM-YYYY');
            },
        },
        methods: {
            changeDate() {
                let form = document.getElementById('other-rides-form');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get('/reports/emergency-rescue-gu', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                    }
                }).then((r) => {
                    this.record_ = r.data.record;
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
        }
    }
</script>

<style scoped>

</style>