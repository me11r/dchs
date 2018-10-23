<template>
    <section
        class="container"
        v-if="formDataExists">
        <h4
            class="title"
            style="padding: 3px 15px">{{ model.id ? 'Редактирование' : 'Добавление' }}: Карточка 112</h4>
        <form
            :action="this.formRoute"
            method="POST">
            <input
                type="hidden"
                name="_method"
                :value="method">
            <input
                type="hidden"
                name="_token"
                :value="csrf">
            <div class="tabs is-boxed">
                <ul>
                    <li :class="{'is-active': currentTabIndex === 0}">
                        <a @click="setTab(0)"><i class="fas fa-phone"></i>&nbsp;Звонок</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 1}">
                        <a
                            @click="setTab(1)">
                        <i class="fas fa-envelope"></i>&nbsp;Службы</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 2}">
                        <a
                            @click="setTab(2)">
                        <i class="fas fa-truck-moving"></i>&nbsp;Информация с места проишествия</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 3}">
                        <a
                            @click="setTab(3)">
                        <i class="fas fa-info-circle"></i>&nbsp;Хронология событий</a>
                    </li>
                </ul>
            </div>
            <div class="panels">
                <div class="panels">
                    <div :style="{'display': currentTabIndex === 0? 'block': 'none'}">
                        <h5 class="subtitle">Первоначальная информация заявителя:</h5>
                        <!--АДРЕС-->
                        <div class="field">
                            <label for="location">Адрес
                                <a
                                    :href="'/card/mapscreen'"
                                    target="_blank"
                                    class="button is-small is-light">
                                    <i class="far fa-map"></i>&nbsp;Открыть карту
                                </a>
                            </label>
                            <input
                                name="location"
                                id="location"
                                class="input"
                                v-model="model.location">
                        </div>

                        <!--<div class="control is-expanded">-->
                        <!--<p class="control">-->
                        <!--<label for="street_id">Адрес</label>-->
                        <!--</p>-->
                        <!--<buefy-common-select-->
                        <!--id="street_id"-->
                        <!--:options="streetsOptions"-->
                        <!--v-model="model.street_id"/>-->
                        <!--<input-->
                        <!--type="hidden"-->
                        <!--name="street_id"-->
                        <!--v-model="model.street_id">-->
                        <!--</div>-->
                        <!--РАЙОН-->
                        <div class="control is-expanded">
                            <p class="control">
                                <label for="city_area_id">Район</label>
                            </p>
                            <div class="select">
                                <select
                                    id="city_area_id"
                                    name="city_area_id"
                                    v-model="model.city_area_id">
                                    <option
                                        v-for="item in cityAreasOptions"
                                        :key="item.id"
                                        :value="item.id">{{ item.text }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="field is-grouped">
                            <!--ПЕРЕСЕЧЕНИЕ УЛИЦЫ 1-->
                            <div class="control is-expanded">
                                <p class="control">
                                    <label for="crossroad_1_id">Пересечение улицы</label>
                                </p>
                                <div class="autocomplete control">
                                    <buefy-common-select
                                        id="crossroad_1_id"
                                        :options="streetsOptions"
                                        v-model="model.crossroad_1_id"/>
                                </div>
                                <input
                                    type="hidden"
                                    name="crossroad_1_id"
                                    v-model="model.crossroad_1_id">
                            </div>
                            <!--ПЕРЕСЕЧЕНИЕ УЛИЦЫ 2-->
                            <div
                                class="control is-expanded">
                                <p class="control">
                                    <label for="crossroad_2_id">и улицы</label>
                                </p>
                                <div class="autocomplete control">
                                    <buefy-common-select
                                        id="crossroad_2_id"
                                        :options="streetsOptions"
                                        v-model="model.crossroad_2_id"/>
                                </div>
                                <input
                                    type="hidden"
                                    name="crossroad_2_id"
                                    v-model="model.crossroad_2_id">
                            </div>
                        </div>
                        <!--ТИП ПРОИСШЕСТВИЯ-->
                        <div class="field">
                            <p class="control">
                                <label for="incident_type_id">Происшествие</label>
                            </p>
                            <div class="select">
                                <select
                                    id="incident_type_id"
                                    name="incident_type_id"
                                    v-model="model.incident_type_id"
                                    required>
                                    <option
                                        v-for="incidentType in incidentTypes"
                                        :key="incidentType.id"
                                        :value="incidentType.id">{{ incidentType.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!--ОПИСАНИЕ ПРОИСШЕСТВИЯ-->
                        <div class="field">
                            <label for="description">Описание происшествия</label>
                            <textarea
                                name="description"
                                id="description"
                                class="textarea"
                                cols="30"
                                rows="3"
                                v-model="model.description"></textarea>
                        </div>
                        <div class="field is-grouped">
                            <!--ФИО ЗАЯВИТЕЛЯ-->
                            <p class="control is-expanded">
                                <label for="caller_name">ФИО Заявителя</label>
                                <input
                                    type="text"
                                    class="input"
                                    name="caller_name"
                                    id="caller_name"
                                    v-model="model.caller_name">
                            </p>
                            <!--ТЕЛЕФОН ЗАЯВИТЕЛЯ-->
                            <p class="control">
                                <label for="caller_phone">Телефон заявителя</label>
                                <input
                                    type="text"
                                    class="input"
                                    name="caller_phone"
                                    id="caller_phone"
                                    v-model="model.caller_phone">
                            </p>
                        </div>
                        <!--ВРЕМЯ ПОЛУЧЕНИЯ СООБЩЕНИЯ-->
                        <div class="field">
                            <p class="control">
                                <label>Время получения сообщения</label>
                            </p>
                            <b-timepicker
                                class="small-time-picker"
                                icon="clock"
                                icon-pack="far"
                                id="call_time"
                                name="call_time"
                                :ref="'call_time_picker'"
                                type="text"
                                v-model="model.call_time">
                                <div
                                    class="field is-grouped"
                                    style="justify-content: space-between">
                                    <p class="control">
                                        <a
                                            class="button is-primary is-small"
                                            @click="setCurrentCallTime()">
                                            <b-icon
                                                pack="far"
                                                icon="clock"/>
                                            <span>Сейчас</span>
                                        </a>
                                    </p>
                                    <p class="control">
                                        <a
                                            class="button is-outlined is-small"
                                            @click="$refs['call_time_picker'].close()">
                                            <i class="fas fa-check"></i><span>Принять</span>
                                        </a>
                                    </p>
                                </div>
                            </b-timepicker>
                        </div>
                    </div>
                    <div :style="{'display': currentTabIndex === 1 ? 'block': 'none'}">
                        <h5 class="subtitle">Службы:</h5>

                        <table class="table is-expanded is-striped is-narrow is-fullwidth">
                            <thead>
                                <tr>
                                    <th>Службы</th>
                                    <th>Время сообщения</th>
                                    <th>Фамилия<br/>принявшего сообщение</th>
                                    <th>Время прибытия</th>
                                    <th>Путевой лист отправлен</th>
                                    <th>Уведомление отправлено</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="service in serviceTypes">
                                    <td>{{ service.name }}</td>
                                    <td>
                                        <input type="text"
                                               readonly
                                               v-model="service.message_time"
                                               :id="service.id + + '_message_time'"
                                               class="input">
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="input"
                                               :id="service.id + + '_name'"
                                               v-model="service.name_accepted">
                                    </td>
                                    <td>
                                        <input type="text"
                                               v-model="service.arrive_time"
                                               readonly
                                               class="input">
                                    </td>
                                    <td>

                                        <input type="text"
                                            v-model="services[service.id].sent_at"
                                        >

                                    </td>
                                    <td>
                                        <label for="">
                                            <input type="checkbox"
                                                    @change="sendOneCheckService($event, model.id, service.id)"
                                                   value="1"

                                                   class="checkbox">
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div :style="{'display': currentTabIndex === 2? 'block': 'none'}">
                        <h5 class="subtitle">Информация с места происшествия:</h5>
                        <!--УТОЧНЕННЫЙ АДРЕС-->
                        <div class="field">
                            <p class="control">
                                <label for="additional_street_id">Уточненный адрес</label>
                            </p>
                            <div class="select">
                                <buefy-common-select
                                    id="additional_street_id"
                                    :options="streetsOptions"
                                    v-model="model.additional_street_id"/>
                                <input
                                    type="hidden"
                                    name="additional_street_id"
                                    v-model="model.additional_street_id">
                            </div>
                        </div>
                        <!--УТОЧНЕНИЕ ТИПА ПРОИСШЕСТВИЯ-->
                        <div class="field">
                            <p class="control">
                                <label for="additional_incident_type_id">Происшествие</label>
                            </p>
                            <div class="select">
                                <select
                                    id="additional_incident_type_id"
                                    name="additional_incident_type_id"
                                    v-model="model.additional_incident_type_id"
                                >
                                    <option
                                        v-for="incidentType in incidentTypes"
                                        :key="incidentType.id"
                                        :value="incidentType.id">{{ incidentType.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!--ПРИНЯТЫЕ МЕРЫ-->
                        <div class="field">
                            <label for="measures">Принятые меры</label>
                            <textarea
                                name="measures"
                                id="measures"
                                class="textarea"
                                cols="30"
                                rows="3"
                                v-model="model.measures"></textarea>
                        </div>
                        <!--ЗАДЕЙСТВОВАННЫЕ РЕСУРСЫ-->
                        <div class="field">
                            <label for="resources">Задействованные ресурсы</label>
                            <textarea
                                name="resources"
                                id="resources"
                                class="textarea"
                                cols="30"
                                rows="3"
                                v-model="model.resources"></textarea>
                        </div>
                        <div class="field is-grouped">
                            <!--ПОСТРАДАВШИХ ЛЮДЕЙ/ДЕТЕЙ-->
                            <div class="group_25">
                                <label for="injured">Пострадавших людей/детей</label>
                                <input
                                    type="number"
                                    v-model="model.injured"
                                    class="input"
                                    name="injured"
                                    id="injured">
                            </div>
                            <!--ПОГИБШИХ ЛЮДЕЙ/ДЕТЕЙ-->
                            <div class="group_25">
                                <label for="dead">Погибших людей/детей</label>
                                <input
                                    type="number"
                                    v-model="model.dead"
                                    class="input"
                                    name="dead"
                                    id="dead">
                            </div>
                            <!--ЭВАКУИРОВАННЫХ ЛЮДЕЙ/ДЕТЕЙ-->
                            <div class="group_25">
                                <label for="evacuated">Эвакуированных людей/детей</label>
                                <input
                                    type="number"
                                    v-model="model.evacuated"
                                    class="input"
                                    name="evacuated"
                                    id="evacuated">
                            </div>
                            <!--ГОСПИТАЛИЗИРОВАННЫХ ЛЮДЕЙ/ДЕТЕЙ-->
                            <div class="group_25">
                                <label for="hospitalized">Госпитализированных людей/детей</label>
                                <input
                                    type="number"
                                    v-model="model.hospitalized"
                                    class="input"
                                    name="hospitalized"
                                    id="hospitalized">
                            </div>
                        </div>
                        <div class="field is-grouped">
                            <!--ТРАВМИРОВАННЫХ ЛЮДЕЙ/ДЕТЕЙ-->
                            <div class="group_25">
                                <label for="injured_hard">Травмированных людей/детей</label>
                                <input
                                    type="number"
                                    v-model="model.injured_hard"
                                    class="input"
                                    name="injured_hard"
                                    id="injured_hard">
                            </div>
                            <!--ОТРАВЛЕННЫХ ЛЮДЕЙ/ДЕТЕЙ-->
                            <div class="group_25">
                                <label for="poisoned">Отравление людей/детей</label>
                                <input
                                    type="number"
                                    v-model="model.poisoned"
                                    class="input"
                                    name="poisoned"
                                    id="poisoned">
                            </div>
                            <!--СПАСЕНО ЛЮДЕЙ/ДЕТЕЙ-->
                            <div class="group_25">
                                <label for="saved">Спасено людей/детей</label>
                                <input
                                    type="number"
                                    v-model="model.saved"
                                    class="input"
                                    name="saved"
                                    id="saved">
                            </div>
                            <!--СПАСЕНО ЖИВОТНЫХ-->
                            <div class="group_25">
                                <label for="saved_animals">Спасено животных</label>
                                <input
                                    type="number"
                                    v-model="model.saved_animals"
                                    class="input"
                                    name="saved_animals"
                                    id="saved_animals">
                            </div>
                        </div>
                    </div>
                    <div :style="{'display': currentTabIndex === 3 ? 'block': 'none'}">
                        <h5 class="subtitle">Хронология событий:</h5>
                        <div class="add_button">
                            <button
                                class="button is-small is-outlined is-success"
                                type="button"
                                @click.prevent="addEmptyChronologyItem()">
                                <i class="fa fa-plus"></i>&nbsp;Добавить
                            </button>
                        </div>
                        <div
                            class="field is-grouped"
                            v-for="cItem in model.chronology"
                            :key="cItem.id">
                            <input
                                type="hidden"
                                v-model="cItem.id"
                                :name="'chronology['+cItem.id+'][id]'">
                            <div class="control is-narrow">
                                <label :for="'chronology['+cItem.id+'][time]'">Время</label>
                                <b-timepicker
                                    class="small-time-picker"
                                    icon="clock"
                                    icon-pack="far"
                                    :ref="'chronology_time_picker_' + cItem.id"
                                    type="text"
                                    :name="'chronology['+cItem.id+'][time]'"
                                    :value="cItem.time"
                                    v-model="cItem.time">
                                    <div
                                        class="field is-grouped"
                                        style="justify-content: space-between">
                                        <p class="control">
                                            <a
                                                class="button is-primary is-small"
                                                @click="setCurrentTimeForChronology(cItem.id)">
                                                <b-icon
                                                    pack="far"
                                                    icon="clock"/>
                                                <span>Сейчас</span>
                                            </a>
                                        </p>
                                        <p class="control">
                                            <a
                                                class="button is-outlined is-small"
                                                @click="closeTimePickerByRefName('chronology_time_picker_' + cItem.id)">
                                                <i class="fas fa-check"></i><span>Принять</span>
                                            </a>
                                        </p>
                                    </div>
                                </b-timepicker>
                            </div>
                            <div class="control is-expanded">
                                <label :for="'chronology['+cItem.id+'][comment]'">Ситуация</label>
                                <input
                                    placeholder="Комментарий"
                                    type="text"
                                    class="input input_95"
                                    v-model="cItem.comment"
                                    :name="'chronology['+cItem.id+'][comment]'">
                                <button
                                    class="button is-small is-outlined is-danger square-button-36"
                                    @click.prevent="removeChronology(cItem.id)"
                                    type="button"
                                    title="Удалить">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <!--ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ-->
                        <div class="field">
                            <label for="additional_comment">Дополнительная информация</label>
                            <textarea
                                name="additional_comment"
                                id="additional_comment"
                                class="textarea"
                                cols="30"
                                rows="3"
                                v-model="model.additional_comment"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="panel bottom_panel">
                <div class="level">
                    <p
                        class="level-left"
                        v-if="currentTabIndex !== lastTabIndex"
                        @click.prevent="currentTabIndex++">
                        <button
                            id="nexttab"
                            type="button"
                            class="button is-primary is-outlined"><i class="fas fa-arrow-right"></i>Следующий раздел
                        </button>
                    </p>
                    <p class="level-right">
                        <button
                            type="submit"
                            class="button is-success"><i class="fas fa-check"></i>Сохранить
                        </button>
                    </p>
                </div>
            </div>
        </form>
    </section>
</template>

<script>

import moment from 'moment';
import axios from 'axios';
import Buefy from 'buefy';
import {_} from 'vue-underscore';
import {Card112Utils} from './card112utils';
import {BuefyCommonSelect} from '../../components';
import {MAP_LOCATION_EXCHANGE_KEY, AREA_ID_FOUND} from '../../config/storage-keys';
import {globalBus} from '../../scripts/global-bus';
import YandexMapsBus from '../../scripts/yandex-maps-bus';

export default {
    name: 'Card112Form',
    data() {
        return {
            csrf: window.csrf_token,
            time: new Date(),
            streets: [],
            incidentTypes: [],
            serviceTypes: [],
            cityAreas: [],
            model: {
                location: '',
                city_area_id: ''
            },
            currentTabIndex: 0,
            lastTabIndex: 3,
            method: 'POST',
            formRoute: '',
            card112Utils: Card112Utils,
            yandexMapsBus: {},
            currentCity: 'Алматы',
            servicePlans: [],
            services: []
        };
    },
    components: {
        'b-icon': Buefy['Icon'],
        'b-timepicker': Buefy['Timepicker'],
        BuefyCommonSelect
    },
    computed: {
        formDataExists() {
            return !!window.card112FormData;
        },
        serviceTypeOptions() {
            return this.commonOptionsMapping(this.serviceTypes);
        },
        streetsOptions() {
            return this.commonOptionsMapping(this.streets);
        },
        cityAreasOptions() {
            return this.commonOptionsMapping(this.cityAreas);
        }
    },
    methods: {
        commonOptionsMapping(array) {
            return array.map((item) => {
                return {
                    'id': item.id,
                    'text': item.name
                };
            });
        },
        sendOneCheckService(event, cardId, service) {
            if (event.target.checked) {
                axios
                    .post('/service-plans/send', {
                        card_id: cardId,
                        service_id: service,
                        cardType: 112
                    })
                    .then((response) => {
                        // console.dir(response)
                        this.services[service].sent_at = response.data.created_at;
                        // console.dir(this.services[service].sent_at)
                    })
                    .catch(() => {
                    });

                axios
                    .post('/api/notification/ticket112send', {
                        notification_id: service,
                        cardType: 101,
                    })
                    .then((response) => {
                        let data = response.data;
                        if (data['success']) {
                            document.querySelector(`[id="${service + '_message_time'}"]`).value = data['time'];
                            document.querySelector(`[id="${service + '_name'}"]`).value = data['name'];
                        } else {
                            this.$snackbar.open({
                                message: data['message'],
                                type: 'is-danger',
                                duration: 3000
                            });
                        }
                    })
                    .catch(() => {
                        this.$snackbar.open({
                            message: 'Произошла ошибка во время отправки уведомления для "' + service + '"',
                            type: 'is-danger',
                            duration: 3000
                        });
                    });
            }
        },
        setTab(tabIndex) {
            this.currentTabIndex = tabIndex;
        },
        getServiceTypeNameById(id) {
            return _.where(this.serviceTypes, {id: id})[0].name;
        },
        setCurrentCallTime() {
            this.model.call_time = moment().toDate();
            this.$refs['call_time_picker'].close();
        },
        setCurrentTimeForServiceType(serviceTypeId, column) {
            _.where(this.model.service_reactions, {service_type_id: serviceTypeId})[0][column] = moment().toDate();
            this.closeTimePickerByRefName('service_reactions_' + column + '_picker_' + serviceTypeId);
        },
        setCurrentTimeForChronology(id) {
            _.where(this.model.chronology, {id: id})[0]['time'] = moment().toDate();
            this.closeTimePickerByRefName('chronology_time_picker_' + id);
        },
        closeTimePickerByRefName(refName) {
            if (this.$refs[refName].close) {
                this.$refs[refName].close();
            } else {
                this.$refs[refName][0].close();
            }
        },
        addEmptyChronologyItem() {
            this.model.chronology.push(this.card112Utils.getEmptyChronologyItem());
        },
        removeChronology(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                this.model.chronology = this.model.chronology.filter(function (item) {
                    return item.id !== id;
                });
            }
        },
        setCityAreaIdByStreetId(streetId) {
            const streetObject = _.where(this.streets, {id: streetId})[0];
            if (streetObject) {
                this.model.city_area_id = streetObject.city_area_id;
            }
        },
        notifyMap() {
            this.yandexMapsBus.debouncedFindHouse(this.currentCity + ' ' + this.model.location);
        }
    },
    watch: {
        // 'model.crossroad_1_id'(newValue) {
        //     this.setCityAreaIdByStreetId(newValue);
        // },
        'model.location'() {
            this.notifyMap();
        }
    },
    beforeMount() {
        if (window.card112FormData) {
            this.streets = window.card112FormData.streets;
            this.cityAreas = window.card112FormData.cityAreas;
            this.incidentTypes = window.card112FormData.incidentTypes;
            this.serviceTypes = window.card112FormData.serviceTypes;
            this.method = window.card112FormData.method;
            this.formRoute = window.card112FormData.formRoute;
            this.model = window.card112FormData.model;
            this.model = this.card112Utils.prepareModel(this.model, this.serviceTypes);
            this.servicePlans = window.card112FormData.servicePlans;
        }

        console.dir(this.servicePlans)

        this.servicePlans.forEach((plan) => {
            this.serviceTypes.forEach((item) => {
                if (plan.service_type_id === item.id) {
                    this.services[item.id] = {sent_at: plan.created_at};
                } else {
                    this.services[item.id] = {sent_at: ''};
                }
                // console.dir(this.services);
            });
        })

    },
    mounted() {
        this.yandexMapsBus = new YandexMapsBus();
        window.addEventListener('storage', (event) => {
            if (event.key === MAP_LOCATION_EXCHANGE_KEY) {
                this.model.location = event.newValue;
            }

            window.addEventListener('storage', (event) => {
                if (event.key === AREA_ID_FOUND) {
                    this.model.city_area_id = parseInt(event.newValue);
                }
            });
        });
        globalBus.$on(AREA_ID_FOUND, (value) => {
            this.model.city_area_id = value;
        });

    }
};
</script>

<style scoped>
    .small-time-picker {
        max-width: 6rem;
    }

    .select, .select > select {
        width: 100%
    }

    .bottom_panel {
        padding: 30px 10px 20px;
        margin-top: 20px;
        border-top: 1px solid #dbdbdb;
        background-color: #f7f7f7
    }

    .group_25 {
        width: 25%;
        padding: 0 0 0 20px;
    }
    .input.input_95{
        width: 95%;
        max-width: 95%;
    }
    .square-button-36{
        height: 36px;
        width: 36px;
    }
    .add_button {
        padding: 0 0 20px 0;
    }
</style>
