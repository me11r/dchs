<template>
    <div
        id="other-rides-form"
        style="margin-top: 20px; min-height:1000px;">
        <h4
            class="title"
            style="padding: 3px 15px">{{ '/reports/analytics-spiasr.object_classification.title'|trans({year:year}) }}
            <!--Классификация объектов за {{ year }} г.-->
        </h4>

        <div class="panel">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div class="field is-grouped">
                            <div class="control">
                                <label for="">Год</label>
                                <select
                                    v-model="year"
                                    name="year"
                                    id="year">
                                    <option>-</option>
                                    <option
                                        v-for="x in getYears()"
                                        :value="x">{{ x }}</option>
                                </select>
                            </div>
                            <div class="control">
                                <label for="">{{ '/reports/analytics-spiasr.object_classification.drill_type'|trans }}</label><!--Тип учения-->
                                <select
                                    v-model="drillType"
                                    name=""
                                    id="">
                                    <option value="">-</option>
                                    <option value="ПТЗ">ПТЗ</option>
                                    <option value="ПТУ">ПТУ</option>
                                </select>
                            </div>
                        </div>


                    </div>

                </div>
                <div class="level-right">
                    <div class="level-item">
                        <a
                            href="/reports/object-classifications/export/xlsx"
                            class="button is-info">{{ 'download_excel'|trans }}</a><!--Сохранить в .XLSX-->
                    </div>
                </div>
            </div>
        </div>

        <div class="field">
            <button @click.prevent="changeDate" class="button is-info">{{ 'search'|trans }}</button><!--Поиск-->
        </div>

        <div
            class="panel"
            v-for="type in drillTypes">
            <h2 class="title has-text-centered">{{ '/reports/analytics-spiasr.object_classification.sub_title'|trans({type: type, year: year}) }}</h2>
            <table
                class="formation-record-table"
                v-if="type === drillType || drillType === ''">
                <tr>
                    <td>{{ '/reports/analytics-spiasr.object_classification.table_title'|trans }}</td><!--классификация объектов-->
                    <td v-for="month in months">{{ month }}</td>
                    <td>{{ 'total'|trans }}</td><!--Итого-->
                </tr>
                <tr v-for="c in object_classes">
                    <td>{{ c.name }}</td>
                    <td v-for="(month, key) in months">{{ records_[type][c.name][++key] }}</td>
                    <td>{{ counts_[type]['per_object'][c.name] }}</td>
                </tr>
                <tr v-if="object_classes.length">
                    <td>{{ 'total'|trans }}</td><!--Итого-->
                    <td v-for="totalMonth in counts_[type]['per_month']">{{ totalMonth }}</td>
                    <td></td>
                </tr>
            </table>
        </div>
        <br>
    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import BField from 'buefy/src/components/field/Field';
export default {
    name: 'ReportTicket101ObjectClassification',
    components: {BField},
    props: {
        records: {
            type: Array | Object,
            default: () => { return []; }
        },
        counts: {
            type: Object,
            default: () => { return {}; }
        },
        object_classes: {
            type: Array,
            default: () => { return []; }
        }
    },
    data: function () {
        return {
            records_: this.records,
            counts_: this.counts,
            date: new Date(),
            year: (new Date()).getFullYear(),
            drillType: '',
            drillTypes: [
                'ПТЗ',
                'ПТУ'
            ],
            months: [
                window.trans.get('months.january'), //Январь
                window.trans.get('months.february'), //Февраль
                window.trans.get('months.march'), //Март
                window.trans.get('months.april'), //Апрель
                window.trans.get('months.may'), //Май
                window.trans.get('months.june'), //Июнь
                window.trans.get('months.july'), //Июль
                window.trans.get('months.august'), //Август
                window.trans.get('months.september'), //Сентябрь
                window.trans.get('months.october'), //Октябрь
                window.trans.get('months.november'), //Ноябрь
                window.trans.get('months.december')  //Декабрь
            ]
        };
    },
    computed: {
        dateToFormatted() {
            return moment(this.date).format('YYYY');
        }

    },
    methods: {
        changeDate() {
            let form = document.getElementById('other-rides-form');
            let loadingComponent = this.$loading.open({
                container: form
            });
            axios.get('/reports/object-classifications', {
                params: {
                    year: this.year
                }
            }).then((r) => {
                this.records_ = r.data.records;
                this.counts_ = r.data.counts;
                loadingComponent.close();
            });
        },
        getYears() {
            let date = new Date();
            return [
                date.getFullYear() - 1,
                date.getFullYear(),
                date.getFullYear() + 1,
            ];
        }
    },
    watch: {
    }
};
</script>

<style scoped>

</style>
