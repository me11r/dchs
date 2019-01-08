<template>
    <div class="container">
        <h4
            class="title"
            style="padding: 3px 15px">Сведения
            о чрезвычайных ситуациях природного и техногенного характера произошедших
            на территории  ДЧС города Алматы
            по форме ЧС-1
        </h4>
        <!--<div class="panel">
            <label for="month">Месяц</label>
            <select class="select" name="month" id="month">
                <option value="">август</option>
            </select>
        </div>

        2018 года-->
        <div class="panel">
            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th rowspan="2">Наименование ЧС</th>
                        <th colspan="3">Количество ЧС</th>
                        <th colspan="3">Пострадало всего, чел.</th>
                        <th colspan="3">из них:
                        погибло, чел</th>

                    </tr>
                    <tr>
                        <th>{{ previous_year }}г.</th>
                        <th>{{ current_year }}г.</th>
                        <th>% (+,-)</th>
                        <th>{{ previous_year }}г.</th>
                        <th>{{ current_year }}г.</th>
                        <th>% (+,-)</th>
                        <th>{{ previous_year }}г.</th>
                        <th>{{ current_year }}г.</th>
                        <th>% (+,-)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in card101_results_">
                        <td>{{ item.title }}</td>
                        <td>{{ item.emergency_count_previous }}</td>
                        <td>{{ item.emergency_count_current }}</td>
                        <td>{{ item.emergency_different }}</td>
                        <td>{{ item.hurt_previous }}</td>
                        <td>{{ item.hurt_current }}</td>
                        <td>{{ item.hurt_different }}</td>
                        <td>{{ item.died_previous }}</td>
                        <td>{{ item.died_current }}</td>
                        <td>{{ item.died_different }}</td>
                    </tr>
                    <tr v-for="item in card112_results_">
                        <td>{{ item.title }}</td>
                        <td>{{ item.emergency_count_previous }}</td>
                        <td>{{ item.emergency_count_current }}</td>
                        <td>{{ item.emergency_different }}</td>
                        <td>{{ item.hurt_previous }}</td>
                        <td>{{ item.hurt_current }}</td>
                        <td>{{ item.hurt_different }}</td>
                        <td>{{ item.died_previous }}</td>
                        <td>{{ item.died_current }}</td>
                        <td>{{ item.died_different }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'MainPageReport',
    props: {
        date_begin: {
            type: String,
            default: ''
        },
        date_end: {
            type: String,
            default: ''
        },
        card101_results: {
            type: Array,
            default: () => {}
        },
        current_year: {
            type: Number,
            default: 2018
        },
        previous_year: {
            type: Number,
            default: 2017
        },
        card112_results: {
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
            card112_results_: this.card112_results,
            time: 1000 * 3,
            card101_results_: this.card101_results
        };
    },
    methods: {

        get_data() {
            let self = this;
            axios.get('/').then((resp) => {
                self.results = resp.data.results;
                self.card112_results_ = resp.data.card112_results;

                setTimeout(this.get_data,this.time)
            });
        }

    },

    created () {
        const token = document.head.querySelector('meta[name="csrf-token"]');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';

        setTimeout(this.get_data, this.time);
    }
};
</script>

<style scoped>

</style>
