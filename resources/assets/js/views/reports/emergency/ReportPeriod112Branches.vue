<template>
    <div class="container">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px">
                <h4 class="title">Отчет (падение веток и деревьев, подтопления)</h4>
                <br>
                <form>
                    <div class="field">
                        <label for="reason">Происшествие</label>
                        <select
                            v-model="incident_type_id"
                            class="select"
                            name=""
                            id="reason">
                            <option value=""></option>
                            <option
                                v-for="item in incidentTypes"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="date_begin_">Начало периода</label>
                        <input
                            v-model="date_begin_"
                            id="date_begin_"
                            type="date"
                            class="date">
                    </div>
                    <div class="field">
                        <label for="date_end_">Конец периода</label>
                        <input
                            v-model="date_end_"
                            id="date_end_"
                            name="date_end_"
                            class="date"
                            type="date">
                    </div>
                    <div
                        class="buttons has-text-right is-grouped is-right"
                        style="">
                        <a
                            :href="getHref"
                            download
                            class="button is-success">
                            <i class="fas fa-check"></i>&nbsp;Скачать
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';

export default {
    name: 'ReportPeriod112Branches',
    props: {
        incidentTypes: {
            type: Array,
            default: () => {}
        }
    },
    computed: {
        getHref() {
            return '/reports/112/branches_export' +
                '?date_start=' + moment(this.date_begin_).format('YYYY-MM-DD') +
                '&date_end=' + moment(this.date_end_).format('YYYY-MM-DD') +
                '&incident_type_id=' + this.incident_type_id +
                '&csrf-token=' + this.csrf;
        }
    },
    data: function () {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            date_begin_: '',
            date_end_: '',
            incident_type_id: ''
        };
    }
};
</script>

<style scoped>

</style>
