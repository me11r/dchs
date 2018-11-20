<template>
    <div class="container">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">Отчет по карточке 101 за период</h4>
                    </div>
                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary"
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;Печать</button>
                    </div>
                    <div class="level-right has-text-right">
                        <a
                                href="/xls/report101/emergency"
                                class="button is-primary"
                                ><i class="fas fa-print"></i>&nbsp;Сохранить в XLS
                        </a>
                    </div>
                </div>
                <br>
                <form>
                    <div class="field">
                        <label for="reason">Результат выезда</label>
                        <select
                            @change="selectResult"
                            class="select"
                            name=""
                            id="reason">
                            <option value=""></option>
                            <option
                                v-for="item in results_"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="">Начало периода</label>
                        <input
                            @blur="selectPeriod"
                            v-model="date_begin_"
                            type="date"
                            class="date">
                    </div>
                    <div class="field">
                        <label for="">Конец периода</label>
                        <input
                            @blur="selectPeriod"
                            v-model="date_end_"
                            class="date"
                            type="date">
                    </div>
                    <table class="formation-record-table">
                        <thead>
                            <tr>
                                <td>Результат выезда</td>
                                <td>Объект горения</td>
                                <td>Район города</td>
                                <td>Адрес</td>
                                <td>Спасено людей</td>
                                <td>Эвакуировано людей</td>
                                <td>Получили отравление угарным газом</td>
                                <td>Получили отравление природным газом</td>
                                <td>Получили ожоги</td>
                                <td>Гибель людей</td>
                                <td>Гибель детей</td>
                                <td>Госпитализировано</td>
                                <td>Время первым прибывшего отделения</td>
                                <td>Время в пути</td>
                                <td>Ликвидация</td>
                                <td>Локализация</td>
                                <td>Использованные стволы</td>
                                <td>Время работы стволов</td>
                                <td>Затраченное время на локализацию</td>
                                <td>Затраченное время на ликвидацию</td>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in report_summary">
                            <td>{{ item.trip_result ? item.trip_result.name : '' }}</td>
                            <td>{{ item.burn_object ? item.burn_object.name : '' }}</td>
                            <td>{{ item.city_area ? item.city_area.name : '' }}</td>
                            <td>{{ item.location }}</td>
                            <td>{{ item.rescued_count }}</td>
                            <td>{{ item.evac_count }}</td>
                            <td>{{ item.co2_poisoned_count }}</td>
                            <td>{{ item.ch4_poisoned_count }}</td>
                            <td>{{ item.gpt_burns_count }}</td>
                            <td>{{ item.people_death_count }}</td>
                            <td>{{ item.children_death_count }}</td>
                            <td>{{ item.hospitalized_count }}</td>

                            <td>{{ item.first_arrived_time }}</td>
                            <td>{{ item.on_way_time }}</td> <!--из вкладки "Высылка" по формуле "время прибытия" - "время выезда"-->
                            <td>{{ item.liqv_time }}</td>
                            <td>{{ item.loc_time }}</td>
                            <td>
                                <p v-for="chronology in item.chronology">
                                    Количество: {{ chronology.quantity }}<br>
                                </p>
                            </td>
                            <td>
                                <p v-for="chronology in item.chronology">
                                    Количество: {{ chronology.working_time }}<br>
                                </p>
                            </td>
                            <td>{{ item.loc_time_total }}</td> <!--по формуле "время локализации" - "время прибытия" -->
                            <td>{{ item.liqv_time_total }}</td> <!--по формуле "время ликвидации" - "время прибытия" -->
                        </tr>
                        </tbody>
                    </table>
                    <!--<div class="buttons has-text-right is-grouped is-right" style="">-->
                    <!--<button type="submit" class="button is-success"><i class="fas fa-check"></i>&nbsp;Сохранить</button>-->
                    <!--</div>-->
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'ReportPeriod101',
    props: {
        date_begin: {
            type: String,
            default: ''
        },
        date_end: {
            type: String,
            default: ''
        },
        results: {
            type: Array,
            default: () => {}
        }
    },
    data: function () {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            date_begin_: this.date_begin,
            date_end_: this.date_end,
            report_summary: {},
            result_id: null,
            results_: this.results
        };
    },
    methods: {
        selectResult(event) {
            this.result_id = event.target.value;

            window.history.pushState('page2', 'Title', '/reports/101/emergency?reason=' + this.result_id);

            // console.dir(this.reason_id);

            this.post_data();
        },
        print() {
            window.print();
        },
        post_data() {
            let self = this;
            axios.post('/reports/101/emergency', {
                date_begin: self.date_begin_,
                date_end: self.date_end_,
                result_id: self.result_id
            }).then((resp) => {
                self.report_summary = resp.data;

                console.dir(self.report_summary);
            });
        },

        selectPeriod() {
            this.post_data();
        }
    },

    created () {
        // const token = document.head.querySelector('meta[name="csrf-token"]');
        // axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        // axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
    }
};
</script>

<style scoped>

</style>
