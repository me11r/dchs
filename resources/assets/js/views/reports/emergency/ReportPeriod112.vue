<template>
    <div class="container">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">Отчет по карточке 112 за период</h4>
                    </div>
                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary"
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;Печать
                        </button>
                    </div>
                    <div class="level-right has-text-right">
                        <a
                            href="/xls/report112/emergency"
                            class="button is-primary"
                        ><i class="fas fa-print"></i>&nbsp;Сохранить в XLS
                        </a>
                    </div>
                </div>
                <br>
                <form>
                    <div class="field">
                        <label for="reason">Происшествие</label>
                        <select
                            @change="selectReason"
                            class="select"
                            name=""
                            id="reason">
                            <option value=""></option>
                            <option
                                v-model="reason_id"
                                v-for="item in reasons_"
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
                                <td class="is-narrow">Происшествие</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(record, title) in report_summary">
                                <td class="is-narrow">{{ title }}</td>
                                <td>
                                    <table class="formation-record-table">
                                        <thead>
                                        <tr>
                                            <td>Район города</td>
                                            <td>Кол-во происшествий(ЧС)</td>
                                            <td>Пострадавших людей/детей</td>
                                            <td>Погибших людей/детей</td>
                                            <td>Эвакуированных людей/детей</td>
                                            <td>Госпитализированных людей/детей</td>
                                            <td>Травмированных людей/детей</td>
                                            <td>Отравление людей/детей</td>
                                            <td>Спасено людей/детей</td>
                                            <td>Спасено животных</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(cityArea, citAreaTitle) in record">
                                            <td>{{ citAreaTitle }}</td>
                                            <td>{{ cityArea.total }}</td>
                                            <td>{{ cityArea.injured }}</td>
                                            <td>{{ cityArea.dead }}</td>
                                            <td>{{ cityArea.evacuated }}</td>
                                            <td>{{ cityArea.hospitalized }}</td>
                                            <td>{{ cityArea.injured_hard }}</td>
                                            <td>{{ cityArea.poisoned }}</td>
                                            <td>{{ cityArea.saved }}</td>
                                            <td>{{ cityArea.saved_animals }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>

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
        reasons: {
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
            reason_id: null,
            reasons_: this.reasons
        };
    },
    methods: {
        selectReason(event) {
            this.reason_id = event.target.value;

            window.history.pushState('page2', 'Title', '/reports/112/emergency?reason=' + this.reason_id);

            this.post_data(this.reason_id);
        },
        print() {
            window.print();
        },
        post_data() {
            let self = this;
            axios.post('/reports/112/emergency', {
                date_begin: self.date_begin_,
                date_end: self.date_end_,
                reason_id: self.reason_id
            }).then((resp) => {
                self.report_summary = resp.data;
                console.dir(self.report_summary);
            });
        },

        selectPeriod() {
            this.post_data();
            console.dir(this.report_summary);
        }
    },

    created () {
        // const token = document.head.querySelector('meta[name="csrf-token"]');
        // axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        // axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
        // this.post_data();
    }
};
</script>

<style scoped>

</style>
