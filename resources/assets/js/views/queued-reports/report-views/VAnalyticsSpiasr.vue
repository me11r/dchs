<template>
    <div class="panel">
        <div
            class="field">
            <h3 class="title">Общее количество выездов: {{ summaryFiltered.length }}</h3>
        </div>
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
                        <td>Стволы</td> <!--из хронологии-->
                        <td>Звенья ГДЗС</td> <!--Кол-во ГДЗС (хронология)-->
                        <td>Время работы стволов</td> <!--из хронологии-->
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
                        v-for="(item, idx) in summaryFiltered"
                        :key="`rpt_smm_${idx}`">

                        <td>{{ item.custom_created_at|dateFilter('DD.MM.YYYY') }}</td>
                        <td>{{ item.custom_created_at|dateFilter('HH:mm') }}</td>
                        <td>{{ item.caller_name }}</td>
                        <td>{{ item.caller_phone }}</td>
                        <td>{{ item.city_area ? item.city_area.name : '' }}</td>
                        <td>{{ item.location }}</td>
                        <td>{{ item.object_name }}</td>
                        <td>{{ item.detailed_staff_count }}</td> <!--УЧАСТНИКИ ТУШЕНИЯ-->
                        <td>{{ item.trucks_count }}</td> <!--Ликвидировано стволами-->
                        <td>{{ item.on_way_time }}</td>
                        <td>{{ item.loc_time }}</td>
                        <td>{{ item.liqv_time }}</td>
                        <td>{{ item.loc_time_total }}</td>
                        <td>
                            <p
                                v-for="(chronology, idx) in item.chronologies"
                                v-if="chronology.event_info_arrived_id !== null && chronology.event_info_arrived.name !== 'ГДЗС'"
                                :key="`chr_qnt_${idx}`">
                                Тип {{ chronology.event_info_arrived.name }}
                                Количество: {{ chronology.quantity }}<br>
                            </p>
                        </td> <!--СТВОЛЫ-->
                        <td>{{ item.gdzs_count }}</td> <!--Звенья ГДЗС-->
                        <td>
                            <p
                                v-for="(chronology, idx) in item.chronologies"
                                v-if="chronology.event_info_arrived_id !== null && chronology.event_info_arrived.name !== 'ГДЗС'"
                                :key="`chr_woring_time_${idx}`">
                                Тип {{ chronology.event_info_arrived.name }}
                                Количество: {{ chronology.working_time }}<br>
                            </p>
                        </td> <!--Время работы стволов-->
                        <td>
                            <p
                                v-for="(chronology, idx) in item.chronologies"
                                v-if="chronology.event_info_arrived_id !== null && chronology.event_info_arrived.name === 'ГДЗС'"
                                :key="`chr_woring_time_gdzs_${idx}`">
                                Тип {{ chronology.event_info_arrived.name }}
                                Количество: {{ chronology.working_time }}<br>
                            </p>
                        </td> <!--Время работы ГДЗС-->
                        <td>{{ item.rescued_count }}</td>
                        <td>{{ item.evac_count }}</td>
                        <td>{{ item.gpt_burns_count }}</td>
                        <td>{{ item.people_death_count + item.children_death_count }}</td>
                        <td>{{ item.loc_time_total }}</td> <!--Затраченное время на локализацию--> <!--время локализации - время прибытия-->
                        <td>{{ item.liqv_time_total }}</td> <!--Затраченное время на ликвидацию--> <!--время ликвидации - время прибытия-->
                        <td>{{ item.trip_result ? item.trip_result.name : '' }}</td>
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
            report_summary: {}
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
