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
            ref="table_div"
            class="tbl"
            :class="parentDivClasses"
            style="overflow-x: auto;">
            <table
                class="formation-record-table content"
                ref="table_content">
                <thead>
                    <tr>
                        <td>Дата выезда</td>
                        <td>Время</td>
                        <td>ФИО</td>
                        <td>Телефон</td>
                        <td>Район города</td>
                        <td>Адрес</td>
                        <td>Наименование объектов</td>
                        <td>Классификация объектов</td><!--new-->
                        <td>Ранг пожара</td><!--new-->
<!--                        <td>Участники тушения</td> &lt;!&ndash;Из "хронологии"&ndash;&gt;-->
                        <td>Ликвидировано стволами</td> <!--Из "хронологии"-->
                        <td>Время следования</td> <!--время прибытия - время выезда-->
                        <td>Время тушения</td> <!--время от прибытия до локализации-->
                        <td>Локализация</td> <!--фактическое время Локализации (хронология)-->
                        <td>Ликвидация</td> <!--фактическое время Ликвидации (хронология)-->
                        <td>Пенные стволы</td> <!--Пенные стволы-->
                        <td>Время работы стволов</td>
                        <td>Звенья ГДЗС</td> <!--Кол-во ГДЗС (хронология)-->
                        <td>Время работы ГДЗС</td> <!--из хронологии-->
                        <td>Спасено людей</td> <!--Итоги выезда-->
                        <td>Эвакуировано людей</td> <!--Итоги выезда-->
                        <td>Травмы</td> <!--Итоги выезда-->
                        <td>Гибель</td> <!--Итоги выезда-->
                        <td>Результат выезда</td> <!--Справочник Результат выезда-->
<!--                        <td>Затраченное время на локализацию</td> &lt;!&ndash;время локализации - время прибытия&ndash;&gt;-->
<!--                        <td>Затраченное время на ликвидацию</td> &lt;!&ndash;время ликвидации - время прибытия&ndash;&gt;-->
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
                        <td>{{ item.object_classification_name }}</td><!--Классификация объектов-->
                        <td>{{ item.result_fire_level_name }}</td><!--Ранг пожара-->
<!--                        <td>{{ item.result_fire_level_name }}</td> &lt;!&ndash;УЧАСТНИКИ ТУШЕНИЯ&ndash;&gt;-->
                        <td>{{ item.liquidation_method_name }}</td> <!--Ликвидировано стволами-->
                        <td>{{ item.on_way_time }}</td>
                        <td>{{ item.loc_time_total }}</td>
                        <td>{{ item.loc_time }}</td>
                        <td>{{ item.liqv_time }}</td>
                        <td>{{ item.trunks_event_info_arrived_names }}</td><!--Пенные стволы-->
                        <td>{{ item.trunks_chronology_working_time }}</td><!--Время работы стволов-->
                        <td>{{ item.gdzs_count }}</td> <!--Звенья ГДЗС-->
                        <td>{{ item.event_info_arrived_names }}</td> <!--Время работы ГДЗС-->
                        <td>{{ item.rescued_count }}</td>
                        <td>{{ item.evac_count }}</td>
                        <td>{{ item.gpt_burns_count }}</td>
                        <td>{{ item.total_death_count }}</td>
                        <td>{{ item.trip_result_name }}</td>
<!--                        <td>{{ item.loc_time_total }}</td> &lt;!&ndash;Затраченное время на локализацию&ndash;&gt; &lt;!&ndash;время локализации - время прибытия&ndash;&gt;-->
<!--                        <td>{{ item.liqv_time_total }}</td> &lt;!&ndash;Затраченное время на ликвидацию&ndash;&gt; &lt;!&ndash;время ликвидации - время прибытия&ndash;&gt;-->
                        <td>{{ item.max_square }}</td>
                        <td>{{ item.storey_count }}</td>
                    </tr>
                </tbody>
            </table>
<!--            <p>Общее количество выездов: {{ report_summary['items'].length }}</p>-->
        </div>
    </div>
</template>

<script>
import {ReportViewMixin} from '../report-view-mixin';
import {FlippedScrollMixin} from '../flipped-scroll-mixin';

export default {
    name: 'VAnalyticsSpiasr',
    mixins: [ReportViewMixin, FlippedScrollMixin],
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
    .flipped-scroll, .flipped-scroll .content
    {
        transform:rotateX(180deg);
        -ms-transform:rotateX(180deg); /* IE 9 */
        -webkit-transform:rotateX(180deg); /* Safari and Chrome */
    }
</style>
