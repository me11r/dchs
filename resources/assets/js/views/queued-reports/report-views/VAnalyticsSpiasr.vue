<template>
    <div class="panel">
        <div
            class="field">
            <h4 class="title">Общее количество выездов: {{ report_summary['items'].length }}</h4>
        </div>
        <hr>
        <div class="field">
            <h4 class="title">Время следования</h4>

            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>до 5 минут</th>
                        <th>до 10 минут</th>
                        <th>более 10 минут</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ report_summary['totals']['on_way_totals']['less_5'] }}</td>
                        <td>{{ report_summary['totals']['on_way_totals']['less_10'] }}</td>
                        <td>{{ report_summary['totals']['on_way_totals']['more_10'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="field">
            <h4 class="title">Время ликвидации</h4>

            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>до 15 минут</th>
                        <th>до 30 минут</th>
                        <th>до 1 часа</th>
                        <th>до 2 часов</th>
                        <th>более 2 часов</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ report_summary['totals']['liqv_totals']['less_15'] }}</td>
                        <td>{{ report_summary['totals']['liqv_totals']['less_30'] }}</td>
                        <td>{{ report_summary['totals']['liqv_totals']['less_60'] }}</td>
                        <td>{{ report_summary['totals']['liqv_totals']['less_120'] }}</td>
                        <td>{{ report_summary['totals']['liqv_totals']['more_120'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="field">
            <h4 class="title">Звенья ГДЗС</h4>

            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>одним звеном</th>
                        <th>двумя и более</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ report_summary['totals']['elements_of_gdzs']['one'] }}</td>
                        <td>{{ report_summary['totals']['elements_of_gdzs']['many'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>
        <div
            class="tbl"
            style="overflow-x: scroll;">
            <table class="formation-record-table" >
                <thead>
                    <tr>
                        <td>Дата выезда</td>
                        <td>Время</td>
                        <td>ФИО</td>
                        <td>Телефон</td>
                        <td>Район города</td>
                        <td>Адрес</td>
                        <td>Объект пожара</td>
                        <td>Участники тушения</td> <!--Из "хронологии"-->
                        <td>Ликвидировано стволами</td> <!--Из "хронологии"-->
                        <td>Время в пути</td> <!--время прибытия - время выезда-->
                        <td>Локализация</td> <!--фактическое время Локализации (хронология)-->
                        <td>Ликвидация</td> <!--фактическое время Ликвидации (хронология)-->
                        <td>Время тушения</td> <!--время от прибытия до локализации-->
                        <td>Звенья ГДЗС</td> <!--Кол-во ГДЗС (хронология)-->
                        <td>Время работы ГДЗС</td> <!--из хронологии-->
                        <td>Спасено людей</td> <!--Итоги выезда-->
                        <td>Эвакуировано людей</td> <!--Итоги выезда-->
                        <td>Травмы</td> <!--Итоги выезда-->
                        <td>Гибель</td> <!--Итоги выезда-->
                        <td>Затраченное время на локализацию</td> <!--время локализации - время прибытия-->
                        <td>Затраченное время на ликвидацию</td> <!--время ликвидации - время прибытия-->
                        <td>Результат выезда</td> <!--Справочник Результат выезда-->
                        <td>Площадь горения</td> <!--Итоги выезда-->
                        <td>Этажность</td> <!--Путевка-->
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item, idx) in report_summary['items']"
                        :key="`rpt_smm_${idx}`">

                        <td>{{ item.custom_created_at_date }}</td>
                        <td>{{ item.custom_created_at_hours }}</td>
                        <td>{{ item.caller_name }}</td>
                        <td>{{ item.caller_phone }}</td>
                        <td>{{ item.city_area_name }}</td>
                        <td>{{ item.location }}</td>
                        <td>{{ item.object_name }}</td>
                        <td>{{ item.result_fire_level_name }}</td> <!--УЧАСТНИКИ ТУШЕНИЯ-->
                        <td>{{ item.liquidation_method_name }}</td> <!--Ликвидировано стволами-->
                        <td>{{ item.on_way_time }}</td>
                        <td>{{ item.loc_time }}</td>
                        <td>{{ item.liqv_time }}</td>
                        <td>{{ item.loc_time_total }}</td>
                        <td>{{ item.gdzs_count }}</td> <!--Звенья ГДЗС-->
                        <td>{{ item.event_info_arrived_names }}</td> <!--Время работы ГДЗС-->
                        <td>{{ item.rescued_count }}</td>
                        <td>{{ item.evac_count }}</td>
                        <td>{{ item.gpt_burns_count }}</td>
                        <td>{{ item.total_death_count }}</td>
                        <td>{{ item.loc_time_total }}</td> <!--Затраченное время на локализацию--> <!--время локализации - время прибытия-->
                        <td>{{ item.liqv_time_total }}</td> <!--Затраченное время на ликвидацию--> <!--время ликвидации - время прибытия-->
                        <td>{{ item.trip_result_name }}</td>
                        <td>{{ item.max_square }}</td>
                        <td>{{ item.storey_count }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import {ReportViewMixin} from '../report-view-mixin';

// @TODO при необходимости переносить дополнительный функционал со старой страницы отчета
export default {
    name: 'VAnalyticsSpiasr',
    mixins: [ReportViewMixin],
    data() {
        return {
            report_summary: {
                items: [],
                totals: {
                    on_way_totals: {},
                    liqv_totals: {},
                    elements_of_gdzs: {}
                }
            }
        };
    },
    computed: {
        summaryFiltered() {
            return this.report_summary;
        }
    },
    methods: {
        prepareDataToShow() {
            this.report_summary = this.reportData;
        }
    },
    mounted() {
        this.prepareDataToShow();
    }
};
</script>

<style scoped>

</style>
