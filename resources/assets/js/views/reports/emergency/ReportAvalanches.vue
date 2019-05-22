<template>
    <div id="other-rides-form" style="margin-top: 20px; min-height:1000px;">
        <h4
                class="title"
                style="padding: 3px 15px">{{ '/reports/analytics101.tabs.avalanches.title'|trans({date_from: dateFromFormatted, date_to:dateToFormatted}) }}
            <!--Профилактический и самопроизвольный сход лавин
            с {{ dateFromFormatted }} по {{ dateToFormatted }}-->
        </h4>

        <div class="panel">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div class="field is-grouped">
                            <v-datepicker-search
                                    v-model="dateFrom"
                                    :date="dateFrom"
                                    class="control"
                                    @dateChanged="dateFrom = $event"
                                    label="С">
                            </v-datepicker-search>
                            <v-datepicker-search
                                    v-model="dateTo"
                                    :date="dateTo"
                                    @dateChanged="dateTo = $event"
                                    class="control"
                                    label="По">
                            </v-datepicker-search>

                        </div>
                    </div>

                </div>
                <div class="level-right">
                    <div class="level-item">
                        <a :href="`/reports/112/avalanches?dateFrom=${dateFromFormattedSearch}&dateTo=${dateToFormattedSearch}&avalancheTypeId=${avalancheTypeId}`"
                           class="button is-info">{{ 'download_word'|trans }}</a><!--Сохранить в .DOCX-->
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <div class="control">
                        <p>{{ '/reports/analytics101.tabs.avalanches.type'|trans }}</p><!--Тип схода снежных лавин-->
                        <select class="control" id="avalancheTypeId" v-model="avalancheTypeId">
                            <option value=""></option>
                            <option v-for="type in avalancheTypes" :value="type.id">{{ type.name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <button class="button is-success" @click.prevent="changeDate">{{ 'search'|trans }}</button><!--Поиск-->
            </div>
        </div>

        <div class="panel">
            <table class="formation-record-table">
                <thead>
                    <tr>
                        <th>{{ 'number'|trans }}</th><!--№ п/п-->
                        <th>{{ 'date'|trans }}</th><!--Дата-->
                        <th>{{ 'address'|trans }}</th><!--Адрес-->
                        <th>{{ '/reports/analytics101.tabs.avalanches.type'|trans }}</th><!--Тип схода лавин-->
                        <th>{{ 'description'|trans }}</th><!--Описание происшествия-->
                        <th>Куб/м</th><!--Куб/м-->
                        <th>{{ 'note'|trans }}</th><!--Примечание-->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, index) in records">
                        <td>{{ ++index }}</td>
                        <td>{{ record.custom_created_at|dateFilter('DD/MM/YYYY') }}</td>
                        <td>{{ record.detailed_address ? record.detailed_address : record.location }}</td>
                        <td>{{ record.avalanche_type.name }}</td>
                        <td>{{ record.emergency_feature }}</td>
                        <td>{{ record.avalanche_volume }}</td>
                        <td>{{ record.avalanche_note }}</td>
                    </tr>
                </tbody>
            </table>
            <p v-if="records.length > 0">{{ 'total'|trans }}: {{ records.length }}</p>
        </div>
        <br>
    </div>
</template>

<script>
    import moment from 'moment';
    import axios from 'axios';
    export default {
        name: "ReportAvalanches",
        props: {
            avalancheTypes: {
                type: Array,
                default: () => { return []; }
            }
        },
        data: function () {
            return {
                records: [],
                dateFrom: new Date,
                dateTo: new Date,
                avalancheTypeId: 1,
            }
        },
        computed: {
            dateToFormatted() {
                return moment(this.dateTo).format('DD-MM-YYYY');
            },
            dateFromFormatted() {
                return moment(this.dateFrom).format('DD-MM-YYYY');
            },
            dateToFormattedSearch() {
                return moment(this.dateTo).format('YYYY-MM-DD');
            },
            dateFromFormattedSearch() {
                return moment(this.dateFrom).format('YYYY-MM-DD');
            },
            avalancheTypeName() {
                return _.first(this.avalancheTypes,{id: this.avalancheTypeId}) ? _.first(this.avalancheTypes,{id: this.avalancheTypeId}).name : null;
            },
        },
        methods: {
            changeDate() {
                let form = document.getElementById('vue');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get('/reports/112/avalanches', {
                    params: {
                        dateFrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateTo: moment(this.dateTo).format('YYYY-MM-DD'),
                        avalancheTypeId: this.avalancheTypeId,
                    }
                }).then((r) => {
                    this.records = r.data.records;
                    loadingComponent.close();
                });
            }
        },
    }
</script>

<style scoped>

</style>