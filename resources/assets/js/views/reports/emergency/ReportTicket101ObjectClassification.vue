<template>
    <div id="other-rides-form">
        <h4
            class="title"
            style="padding: 3px 15px">Классификация объектов за {{ year }} г.
        </h4>

        <div class="panel">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div class="field is-grouped">
                            <div class="control">
                                <label for="">Год</label>
                                <select v-model="year" name="year" id="year">
                                    <option>-</option>
                                    <option v-for="x in [2018, 2019, 2020]" :value="x">{{ x }}</option>
                                </select>
                            </div>
                            <div class="control">
                                <label for="">Тип учения</label>
                                <select v-model="drillType" name="" id="">
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
                        <a href="/reports/object-classifications/export/xlsx" class="button is-info">Сохранить в .XLSX</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel"
             v-for="type in drillTypes">
            <table class="formation-record-table" v-if="type === drillType || drillType === ''">
                <tr>
                    <td>классификация объектов {{ type }}</td>
                    <td v-for="month in months">{{ month }}</td>
                    <td>Итого</td>
                </tr>
                <tr v-for="c in object_classes">
                    <td>{{ c.name }}</td>
                    <td v-for="(month, key) in months">{{ records_[type][c.name][++key] }}</td>
                    <td>{{ counts_[type]['per_object'][c.name] }}</td>
                </tr>
                <tr v-if="object_classes.length">
                    <td>Итого</td>
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
    import BField from "buefy/src/components/field/Field";
    export default {
        name: "ReportTicket101ObjectClassification",
        components: {BField},
        props: {
            records: {
                type: Array,
                default: () => { return []; }
            },
            counts: {
                type: Object,
                default: () => { return {};}
            },
            object_classes: {
                type: Array,
                default: () => { return []; }
            },
        },
        data: function () {
            return {
                records_: this.records,
                counts_: this.counts,
                date: new Date,
                year: (new Date).getFullYear(),
                drillType: '',
                drillTypes: [
                    'ПТЗ',
                    'ПТУ',
                ],
                months: ["Январь", "Февраль", "Март", "Апреть", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            }
        },
        computed: {
            dateToFormatted() {
                return moment(this.date).format('YYYY');
            },

        },
        methods: {
            changeDate() {
                let form = document.getElementById('other-rides-form');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get('/reports/object-classifications', {
                    params: {
                        year: this.year,
                    }
                }).then((r) => {
                    this.records_ = r.data.records;
                    this.counts_ = r.data.counts;
                    loadingComponent.close();
                });
            },
        },
        watch: {
            'year'() {
                this.changeDate();
            },
        }
    }
</script>

<style scoped>

</style>