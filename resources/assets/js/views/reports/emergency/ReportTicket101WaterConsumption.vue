<template>
    <div id="other-rides-form" style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">{{ '/reports/analytics-spiasr.water_consumption.title'|trans({date_from: dateFromFormatted, date_to: dateToFormatted}) }}
            <!--Расход воды
            за {{ dateFromFormatted }} по {{ dateToFormatted }}-->
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
                        <a href="/reports/water-consumption/export/xlsx" class="button is-info">{{ 'download_excel'|trans }}</a><!--Сохранить в .XLSX-->
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>{{ '/reports/analytics-spiasr.water_consumption.headers.card_number'|trans }}</th><!--№ карточки-->
                        <th>{{ '/reports/analytics-spiasr.water_consumption.headers.date'|trans }}</th><!--Дата-->
                        <th>{{ '/reports/analytics-spiasr.water_consumption.headers.liquidation_method_id_1'|trans }}</th><!--Первым стволом (стволами от емкости автоцистерны)-->
                        <th>{{ '/reports/analytics-spiasr.water_consumption.headers.liquidation_method_id_2'|trans }}</th><!--С установкой пож.автомобилей на водоисточники, ПГ-->
                        <th>{{ '/reports/analytics-spiasr.water_consumption.headers.liquidation_method_id_3'|trans }}</th><!--От емкости нескольких автоцистерн (подвозом воды)-->
                        <th>{{ '/reports/analytics-spiasr.water_consumption.headers.liquidation_method_id_9'|trans }}</th><!--Пенные стволы-->
                        <th>{{ '/reports/analytics-spiasr.water_consumption.headers.liquidation_method_id_4'|trans }}</th><!--Подручными средствами-->
                        <th>{{ '/reports/analytics-spiasr.water_consumption.headers.liquidation_method_id_5'|trans }}</th><!--До прибытия-->
                        <th>{{ '/reports/analytics-spiasr.water_consumption.headers.time'|trans }}</th><!--Время тушения-->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, key) in records_" :key="`records_${key}`">
                        <td>{{ ++key }}</td>
                        <td><a target="_blank" :href="`/card/add101/${record.id}#return=0`">{{ record.id }}</a></td>
                        <td>{{ record.date }}</td>
                        <td>{{ record['liquidation_method_id'][1] }}</td>
                        <td>{{ record['liquidation_method_id'][2] }}</td>
                        <td>{{ record['liquidation_method_id'][3] }}</td>
                        <td>{{ record['liquidation_method_id'][9] }}</td>
                        <td>{{ record['liquidation_method_id'][4] }}</td>
                        <td>{{ record['liquidation_method_id'][5] }}</td>
                        <td>{{ record.time }}</td>
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
        name: "ReportTicket101WaterConsumption",
        components: {BField},
        props: {
            records: {
                type: Array,
                default: () => { return []; }
            },
        },
        data: function () {
            return {
                records_: this.records,
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
                let form = document.getElementById('vue');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get('/reports/water-consumption', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                    }
                }).then((r) => {
                    this.records_ = r.data.records;
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