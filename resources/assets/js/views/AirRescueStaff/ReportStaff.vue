<template>
    <div class="container">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px">
                <h4 class="title">Отчет по строевой записке ЛС</h4>
                <div class="level">
                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary "
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;Печать</button>
                    </div>
                </div>
                <br>
                <form>
                    <div class="field">
                        <label for="staff">Ф.И.О.</label>
                        <select
                            @change="selectName"
                            class="select"
                            name=""
                            id="staff">
                            <option
                                v-model="person_id"
                                v-for="person in staff_"
                                :value="person.id"
                                :key="person.id">{{ person.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="">Начало периода</label>
                        <input
                            @blur="selectPeriod"
                            v-model="date_begin_"
                            type="date">
                    </div>
                    <div class="field">
                        <label for="">Конец периода</label>
                        <input
                            @blur="selectPeriod"
                            v-model="date_end_"
                            class="date"
                            type="date">
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Выход на дежурство</td>
                                <td>Отсутствие</td>
                                <td>Отсутствие: Отпуск</td>
                                <td>Отсутствие: Учебный</td>
                                <td>Отсутствие: Декрет</td>
                                <td>Отсутствие: Больничный</td>
                                <td>Отсутствие: Командировка</td>
                                <td>Отсутствие: Другое</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ report_summary.active_count }}</td>
                                <td>{{ report_summary.inactive_count }}</td>
                                <td>{{ report_summary.vacation_count }}</td>
                                <td>{{ report_summary.study_count }}</td>
                                <td>{{ report_summary.maternity_count }}</td>
                                <td>{{ report_summary.sick_count }}</td>
                                <td>{{ report_summary.business_trip_count }}</td>
                                <td>{{ report_summary.other_count }}</td>
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
    name: 'Report101Staff',
    props: {
        staff: {
            type: Array,
            default: []
        },
        date_begin: {
            type: String,
            default: ''
        },
        date_end: {
            type: String,
            default: ''
        }
    },
    data: function () {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            staff_: this.staff,
            person_id: '',
            date_begin_: this.date_begin,
            date_end_: this.date_end,
            report_summary: {}
        };
    },
    methods: {
        selectName(event) {
            this.person_id = event.target.value;

            console.dir(this.person_id);

            this.post_data();
        },
        print() {
            window.print();
        },
        post_data() {
            let self = this;
            axios.post('/reports/101/staff', {
                date_begin: self.date_begin_,
                date_end: self.date_end_,
                staff_id: self.person_id
            }).then((resp) => {
                self.report_summary = resp.data;
            });
        },

        selectPeriod() {
            this.post_data();
        }
    },

    created () {
        const token = document.head.querySelector('meta[name="csrf-token"]');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
    }
};
</script>

<style scoped>

</style>
