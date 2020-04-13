<template>
    <div class="container">
        <div class="panel">
            <div
                    class="box"
                    style="margin-top: 20px">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">Отчет по {{ reportInfo.name }}</h4>
                    </div>
                    <div class="level-right has-text-right">
                        <button
                                class="button is-primary"
                                @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;Печать</button>
                    </div>
                    <div class="level-left">
                        <a
                                :href="getDownloadLink('docx')"
                                target="_blank"
                                download
                                class="button is-primary"
                                type="submit">
                            <i class="fas fa-print"></i>&nbsp;{{ 'download_word'|trans }}
                        </a>
                    </div>
                    <div class="level-left">
                        <a
                                :href="getDownloadLink('xlsx')"
                                target="_blank"
                                download
                                class="button is-primary"
                                type="submit">
                            <i class="fas fa-print"></i>&nbsp;{{ 'download_excel'|trans }}
                        </a>
                    </div>
                </div>
                <br>
                <form>
                    <div class="field">
                        <label for="reason">{{ 'emergency'|trans }}</label><!--Происшествие-->
                        <select
                                class="select"
                                name=""
                                v-model="form.emergencyTypeId"
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
                        <label for="reason">{{ 'city_area'|trans }}</label><!--Район города-->
                        <select
                                class="select"
                                name="city_area_id"
                                v-model="form.cityAreaId"
                                id="city_area_id">
                            <option value=""></option>
                            <option
                                    v-for="item in cityAreas"
                                    :value="item.name"
                                    :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="reason">Классификация выездов</label><!--Классификация выездов-->
                        <select
                                class="select"
                                name="emergencyNameId"
                                v-model="form.rideTypeId"
                        >
                            <option value=""></option>
                            <option
                                    v-for="item in rideTypes"
                                    :value="item.id"
                                    :key="item.id">{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="reason">Категория</label><!--Категория-->
                        <select
                                class="select"
                                v-model="form.category"
                        >
                            <option value=""></option>
                            <option
                                    v-for="item in categories"
                                    :value="item.category"
                                    :key="`category_${item.category}`">{{ item.category }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="reason">Подкатегория</label><!--Подкатегория-->
                        <select
                                class="select"
                                v-model="form.subcategory"
                        >
                            <option value=""></option>
                            <option
                                    v-for="item in subcategories"
                                    :value="item"
                                    :key="`subcategory_${item}`">{{ item }}
                            </option>
                        </select>
                    </div>
                    <div class="field is-grouped">
                        <v-datepicker-search
                                v-model="form.dateFrom"
                                :date="form.dateFrom"
                                class="control"
                                @dateChanged="form.dateFrom = $event"
                                label="С">
                        </v-datepicker-search>
                        <v-datepicker-search
                                v-model="form.dateTo"
                                :date="form.dateTo"
                                @dateChanged="form.dateTo = $event"
                                class="control"
                                label="По">
                        </v-datepicker-search>
                    </div>
                    <div class="field">
                        <button @click.prevent="selectPeriod" class="button is-success">{{ 'search'|trans }}</button><!--Поиск-->
                    </div>
                    <div v-if="tableData.length" class="field">
                        <p>Всего за период: <b>{{ tableData.length }}</b></p>
                        <p>Отфильтровано: <b>{{ filteredTableData.length }}</b></p>
                    </div>
                    <div class="field" style="overflow: scroll">
                        <table class="formation-record-table">
                            <thead>
                                <tr>
                                    <td class="is-narrow">Дата</td>
                                    <td>Дата закрытия</td>
                                    <td>Текст сообщения</td>
                                    <td>Текст рапорта</td>
                                    <td>Категория</td>
                                    <td>Подкатегория</td>
                                    <td>Статус</td>
                                    <td>Исполнитель</td>
                                    <td>Район</td>
                                    <td>Улица</td>
                                    <td>Дом</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(record, key) in filteredTableData" :key="`${reportInfo.slug}_${key}`">
                                    <td class="is-narrow">{{ record.date }}</td>
                                    <td>{{ record.date_end }}</td>
                                    <td>{{ record.first_msg }}</td>
                                    <td>{{ record.desc }}</td>
                                    <td>{{ record.cat }}</td>
                                    <td>{{ record.subcat }}</td>
                                    <td>{{ record.state }}</td>
                                    <td>{{ record.ispoln }}</td>
                                    <td>{{ record.district }}</td>
                                    <td>{{ record.street }} {{ record.streetx ? 'x ' + record.streetx : '' }}</td>
                                    <td>{{ record.house }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import moment from 'moment'
    import axios from 'axios'
    export default {
        name: "ReportServices",
        props: {
            records: {
                type: Array,
                default: () => []
            },
            apiDictionaries: {
                type: [Array, Object],
                default: () => []
            },
            reportInfo: {
                type: Object,
                default: () => {}
            },
            rideTypes: {
                type: Array,
                default: () => []
            },
            cityAreas: {
                type: Array,
                default: () => []
            },
            incidentTypes: {
                type: Array,
                default: () => []
            },
        },
        data() {
            return {
                form: {
                    emergencyTypeId: 0,
                    cityAreaId: 0,
                    rideTypeId: 0,
                    category: '',
                    subcategory: '',
                    dateFrom: new Date(),
                    dateTo: new Date(),
                },
                tableData: []
            }
        },
        computed: {
            categories() {
                return this.apiDictionaries;
            },
            subcategories() {
                const category = this.form.category ? this.apiDictionaries.find((item) => {
                    return item.category === this.form.category;
                }) : {};

                return category ? category.subcategories : [];
            },
            filteredTableData() {
                let records = this.form.cityAreaId
                    ? this.tableData.filter((item) => item.district.toLowerCase() === this.form.cityAreaId.toLowerCase())
                    : this.tableData;

                return records.filter((item) => {
                    if (this.form.category && this.form.subcategory) {
                        return item.cat === this.form.category && item.subcat === this.form.subcategory;
                    } else if (this.form.category) {
                        return item.cat === this.form.category;
                    } else {
                        return true;
                    }
                });
            }
        },
        methods: {
            print() {
                window.print();
            },
            getDownloadLink(documentType = null) {
                let url = `/reports/analytics-services/search?`;
                _.forEach(this.form, (item, key) => {
                    if (key === 'dateFrom' || key === 'dateTo') {
                        let date = moment(item).format('DD.MM.YYYY');
                        url += `${key}=${date}&`
                    } else {
                        url += `${key}=${item}&`
                    }
                });
                if (documentType) {
                    url += `download=${documentType}&`;
                }
                url += `slug=${this.reportInfo.slug}`;
                return url;
            },
            selectPeriod() {
                const url = this.getDownloadLink();
                let form = document.getElementById('vue');
                let loadingComponent = this.$loading.open({
                    container: form
                });
                axios.get(url).then((response) => {
                    this.tableData = response.data;
                    if (!Array.isArray(response.data.records)) {
                        this.$snackbar.open({
                            message: `Ошибка: ${response.data.records.description}`,
                            indefinite: true,
                            type: 'is-danger',
                            position: 'is-top'
                        });
                    } else {
                        this.tableData = response.data.records;
                    }
                }).finally(() => {
                    loadingComponent.close();
                })
            }
        }
    }
</script>

<style scoped>

</style>