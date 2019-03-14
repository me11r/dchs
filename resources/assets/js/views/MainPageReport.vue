<template>
    <div class="container">
        <div class="box">
            <div class="section">
                <button class="button is-success" @click.prevent="get_data">Обновить</button>
            </div>
            <div class="section">
                <h4
                        class="title"
                        style="padding: 3px 15px">Информация по звонкам
                </h4>
                <div class="panel">
                    <table class="formation-record-table">
                        <thead>
                        <tr>
                            <th>101</th>
                            <th>112</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ callsInfos_.count_101 }}</td>
                            <td>{{ callsInfos_.count_112 }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <div class="section">
                <h4
                        class="title"
                        style="padding: 3px 15px">Информация по карточкам
                </h4>
                <div class="panel">
                    <table class="formation-record-table">
                        <thead>
                        <tr>
                            <th>101 Боевые</th>
                            <th>101 Учебные</th>
                            <th>101 Нормативы ПСП</th>
                            <th>101 Прочие выезда</th>
                            <th>112</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ cardsInfos_.count_101_real }}</td>
                            <td>{{ cardsInfos_.count_101_drill }}</td>
                            <td>{{ cardsInfos_.count_101_norm_psp }}</td>
                            <td>{{ cardsInfos_.count_101_other_rides }}</td>
                            <td>{{ cardsInfos_.count_112 }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <div class="section">
                <h4
                        class="title"
                        style="padding: 3px 15px">Информация от служб
                </h4>
                <div class="panel" style="overflow-x: scroll;">
                    <table class="formation-record-table">
                        <tr>
                            <td><b>СОМЭ</b></td>
                            <td class="text-top">{{ servicesInfos_.SOME }}</td>
                        </tr>
                        <tr>
                            <td><b>Казгидромет</b></td>
                            <td class="text-top">{{ servicesInfos_.weather }}</td>
                        </tr>
                        <tr>
                            <td class="text-top"><b>Казселезащита</b></td>
                            <td>
                                <table v-for="river in rivers" :key="`rivers_${river.id}`" class="table is-narrow is-hoverable is-fullwidth is-striped is-small">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Река</th>
                                        <th>Информация</th>
                                        <th>Наименование гидропостов и их отметка</th>
                                        <th>Расход воды</th>
                                        <th>Критический расход воды</th>
                                        <th>Мутность воды</th>
                                        <th>Максимальная мутность воды</th>
                                        <th>Температура воздуха</th>
                                        <th>Температура воды</th>
                                        <th>Осадки</th>
                                        <th>Высота снега</th>
                                        <th>Погода</th>
                                        <th>Комментарий</th>
                                        <th class="has-text-right">Дата и время</th>
                                    </tr>
                                    </thead>
                                    <tr v-for="(gaugingStation, index) in river.gauging_stations" :key="`gaugingStation_${gaugingStation.id}`" v-if="servicesInfos.mudflow[gaugingStation.id]">
                                        <td class="has-text-right">{{ index }}</td>
                                        <td><b>{{ river.name }}</b></td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].information }}</td>
                                        <td>{{ gaugingStation.name }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].water_flow_rate }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].critical_water_flow_rate }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].turbidity_of_water }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].max_turbidity_of_water }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].air_temperature }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].water_temperature }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].precipitation }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].height_of_snow }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].weather }}</td>
                                        <td>{{ servicesInfos.mudflow[gaugingStation.id].comment }}</td>
                                        <td class="has-text-right">{{ servicesInfos.mudflow[gaugingStation.id].updated_at }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'MainPageReport',
    props: {
        callsInfos: {
            type: Object,
            default: () => {
                return {
                    count_101: 0,
                    count_112: 0,
                };
            }
        },
        cardsInfos: {
            type: Object,
            default: () => {
                return {
                    count_101_real: 0,
                    count_101_drill: 0,
                    count_101_norm_psp: 0,
                    count_101_other_rides: 0,
                    count_112: 0,
                };
            }
        },
        servicesInfos: {
            type: Object,
            default: () => {
                return {
                    SOME: '',
                    weather: '',
                    mudflow: '',
                };
            }
        },
        rivers: {
            type: Array,
            default: () => {
                return [];
            }
        },
    },
    data: function () {
        return {
            time: 1000 * 60,
            callsInfos_: this.callsInfos,
            cardsInfos_: this.cardsInfos,
            servicesInfos_: this.servicesInfos,
        };
    },
    methods: {

        get_data() {
            let form = document.getElementById('vue');
            let loadingComponent = this.$loading.open({
                container: form
            });

            axios.get('/').then((resp) => {
                loadingComponent.close();

                let results = resp.data.data;
                this.callsInfos_ = results.call_infos;
                this.cardsInfos_ = results.card_infos;
                this.servicesInfos_ = results.services_infos;

                // setTimeout(this.get_data,this.time)
            });
        }

    },

    created () {
        // setTimeout(this.get_data, this.time);
    }
};
</script>

<style scoped>
    .text-top {
        vertical-align: top !important;
    }

</style>
