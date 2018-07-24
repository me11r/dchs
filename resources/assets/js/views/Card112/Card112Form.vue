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
                        <a @click="setTab(0)"><i class="fas fa-phone"></i>Звонок</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 1}">
                        <a
                            @click="setTab(1)">
                        <i class="fas fa-envelope"></i>Службы</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 2}">
                        <a
                            @click="setTab(2)">
                        <i class="fas fa-truck-moving"></i>Информация с места проишествия</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 3}">
                        <a
                            @click="setTab(3)">
                        <i class="fas fa-info-circle"></i>Хронология событий</a>
                    </li>
                </ul>
            </div>
            <div class="panels">
                <div class="panels">
                    <div :style="{'display': currentTabIndex === 0? 'block': 'none'}">
                        <h5 class="subtitle">Первоначальная информация заявителя:</h5>
                        <!--АДРЕС-->
                        <div class="field">
                            <p class="control">
                                <label for="street_id">Адрес</label>
                            </p>
                            <div class="select">
                                <select
                                    id="street_id"
                                    name="street_id"
                                    v-model="model.street_id"
                                    required>
                                    <option
                                        v-for="street in streets"
                                        :key="street.id"
                                        :value="street.id">{{ street.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="field is-grouped">
                            <!--ПЕРЕСЕЧЕНИЕ УЛИЦЫ 1-->
                            <div>
                                <p class="control">
                                    <label for="crossroad_1_id">Пересечение улицы</label>
                                </p>
                                <div class="select">
                                    <select
                                        id="crossroad_1_id"
                                        name="crossroad_1_id"
                                        v-model="model.crossroad_1_id"
                                        required>
                                        <option
                                            v-for="street in streets"
                                            :key="street.id"
                                            :value="street.id">{{ street.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!--ПЕРЕСЕЧЕНИЕ УЛИЦЫ 2-->
                            <div
                                class="control is-expanded right-select">
                                <p class="control">
                                    <label for="crossroad_2_id">и улицы</label>
                                </p>
                                <div class="select">
                                    <select
                                        id="crossroad_2_id"
                                        name="crossroad_2_id"
                                        v-model="model.crossroad_2_id"
                                        required>
                                        <option
                                            v-for="street in streets"
                                            :key="street.id"
                                            :value="street.id">{{ street.name }}
                                        </option>
                                    </select>
                                </div>
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
                        <table class="table is-expanded is-striped is-fullwidth">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Время сообщения</th>
                                    <th>Фамилия<br>принявшего сообщение</th>
                                    <th>Время выезда</th>
                                    <th>Время прибытия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="serviceReaction in model.service_reactions"
                                    :key="serviceReaction.service_type_id">
                                    <td>
                                        {{ getServiceTypeNameById(serviceReaction.service_type_id) }}
                                        <input
                                            type="hidden"
                                            v-model="serviceReaction.service_type_id"
                                            :name="'service_reactions['+serviceReaction.service_type_id+'][service_type_id]'">
                                        <input
                                            type="hidden"
                                            v-model="serviceReaction.id"
                                            :name="'service_reactions['+serviceReaction.service_type_id+'][id]'">
                                    </td>
                                    <td>
                                        <b-timepicker
                                            class="small-time-picker"
                                            icon="clock"
                                            icon-pack="far"
                                            :ref="'service_reactions_message_time_picker_' + serviceReaction.service_type_id"
                                            type="text"
                                            v-model="serviceReaction.message_time"
                                            :name="'service_reactions['+serviceReaction.service_type_id+'][message_time]'">
                                            <div
                                                class="field is-grouped"
                                                style="justify-content: space-between">
                                                <p class="control">
                                                    <a
                                                        class="button is-primary is-small"
                                                        @click="setCurrentTimeForServiceType(serviceReaction.service_type_id, 'message_time')">
                                                        <b-icon
                                                            pack="far"
                                                            icon="clock"/>
                                                        <span>Сейчас</span>
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <a
                                                        class="button is-outlined is-small"
                                                        @click="closeTimePickerByRefName('service_reactions_message_time_picker_' + serviceReaction.service_type_id)">
                                                        <i class="fas fa-check"></i><span>Принять</span>
                                                    </a>
                                                </p>
                                            </div>
                                        </b-timepicker>
                                    </td>
                                    <td>
                                        <input
                                            title="Фамилия принявшего сообщение"
                                            type="text"
                                            class="input"
                                            v-model="serviceReaction.name"
                                            :name="'service_reactions['+serviceReaction.service_type_id+'][name]'">
                                    </td>
                                    <td>
                                        <b-timepicker
                                            class="small-time-picker"
                                            icon="clock"
                                            icon-pack="far"
                                            :ref="'service_reactions_departure_time_picker_' + serviceReaction.service_type_id"
                                            type="text"
                                            v-model="serviceReaction.departure_time"
                                            :name="'service_reactions['+serviceReaction.service_type_id+'][departure_time]'">
                                            <div
                                                class="field is-grouped"
                                                style="justify-content: space-between">
                                                <p class="control">
                                                    <a
                                                        class="button is-primary is-small"
                                                        @click="setCurrentTimeForServiceType(serviceReaction.service_type_id, 'departure_time')">
                                                        <b-icon
                                                            pack="far"
                                                            icon="clock"/>
                                                        <span>Сейчас</span>
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <a
                                                        class="button is-outlined is-small"
                                                        @click="closeTimePickerByRefName('service_reactions_departure_time_picker_' + serviceReaction.service_type_id)">
                                                        <i class="fas fa-check"></i><span>Принять</span>
                                                    </a>
                                                </p>
                                            </div>
                                        </b-timepicker>
                                    </td>
                                    <td>
                                        <b-timepicker
                                            class="small-time-picker"
                                            icon="clock"
                                            icon-pack="far"
                                            :ref="'service_reactions_arrival_time_picker_' + serviceReaction.service_type_id"
                                            type="text"
                                            v-model="serviceReaction.arrival_time"
                                            :name="'service_reactions['+serviceReaction.service_type_id+'][arrival_time]'">
                                            <div
                                                class="field is-grouped"
                                                style="justify-content: space-between">
                                                <p class="control">
                                                    <a
                                                        class="button is-primary is-small"
                                                        @click="setCurrentTimeForServiceType(serviceReaction.service_type_id, 'arrival_time')">
                                                        <b-icon
                                                            pack="far"
                                                            icon="clock"/>
                                                        <span>Сейчас</span>
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <a
                                                        class="button is-outlined is-small"
                                                        @click="closeTimePickerByRefName('service_reactions_arrival_time_picker_' + serviceReaction.service_type_id)">
                                                        <i class="fas fa-check"></i><span>Принять</span>
                                                    </a>
                                                </p>
                                            </div>
                                        </b-timepicker>
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
                                <select
                                    id="additional_street_id"
                                    name="additional_street_id"
                                    v-model="model.additional_street_id"
                                    required>
                                    <option
                                        v-for="street in streets"
                                        :key="street.id"
                                        :value="street.id">{{ street.name }}
                                    </option>
                                </select>
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
                                    required>
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
                    </div>
                    <div :style="{'display': currentTabIndex === 3 ? 'block': 'none'}">
                        <h5 class="subtitle">Хронология событий:</h5>
                        <table class="table is-expanded is-striped is-fullwidth">
                            <thead>
                                <tr>
                                    <th>Время</th>
                                    <th>Комментарий</th>
                                    <th>Дополнительная информация</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="cItem in model.chronology"
                                    :key="cItem.id">
                                    <td>
                                        <input
                                            type="hidden"
                                            v-model="cItem.id"
                                            :name="'chronology['+cItem.id+'][id]'">
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
                                    </td>
                                    <td>
                                        <input
                                            placeholder="Комментарий"
                                            type="text"
                                            class="input"
                                            v-model="cItem.comment"
                                            :name="'chronology['+cItem.id+'][comment]'">
                                    </td>
                                    <td>
                                        <input
                                            placeholder="Дополнительный комментарий"
                                            type="text"
                                            class="input"
                                            v-model="cItem.additional_comment"
                                            :name="'chronology['+cItem.id+'][additional_comment]'">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
import Buefy from 'buefy';
import {_} from 'vue-underscore';

export default {
    name: 'Card112Form',
    data() {
        return {
            csrf: window.csrf_token,
            time: new Date(),
            streets: [],
            incidentTypes: [],
            serviceTypes: [],
            model: {},
            currentTabIndex: 0,
            lastTabIndex: 3,
            method: 'POST',
            formRoute: ''
        };
    },
    components: {
        'b-icon': Buefy['Icon'],
        'b-timepicker': Buefy['Timepicker']
    },
    computed: {
        formDataExists() {
            return !!window.card112FormData;
        }
    },
    methods: {
        setTab(tabIndex) {
            this.currentTabIndex = tabIndex;
        },
        prepareModel() {
            this.model.call_time = this.model.call_time ? moment(this.model.call_time).toDate() : moment().toDate();

            this.model.service_reactions = this.model.service_reactions
                ? this.prepareServiceReactions(this.model.service_reactions)
                : this.getEmptyServiceReactions(this.serviceTypes);

            this.model.chronology = this.model.chronology
                ? this.prepareChronology(this.model.chronology)
                : this.getEmptyChronology();
        },
        getEmptyChronology() {
            let result = [];
            [0, 1, 2, 3, 4].map((i) => {
                result.push({
                    id: i,
                    time: moment().hour(0).minute(0).toDate(),
                    comment: '',
                    additional_comment: ''
                });
            });
            return result;
        },
        getEmptyServiceReactions(serviceTypes) {
            let result = [];
            serviceTypes.map((serviceType) => {
                result.push({
                    id: null,
                    service_type_id: serviceType.id,
                    message_time: moment().hour(0).minute(0).toDate(),
                    name: '',
                    departure_time: moment().hour(0).minute(0).toDate(),
                    arrival_time: moment().hour(0).minute(0).toDate()
                });
            });
            return result;
        },
        prepareServiceReactions(serviceReactions) {
            return serviceReactions.map(function (item) {
                item.message_time = moment(item.message_time, 'YYYY-MM-DD HH:mm:ss').toDate();
                item.departure_time = moment(item.departure_time, 'YYYY-MM-DD HH:mm:ss').toDate();
                item.arrival_time = moment(item.arrival_time, 'YYYY-MM-DD HH:mm:ss').toDate();
                return item;
            });
        },
        prepareChronology(chronology) {
            return chronology.map(function (item) {
                item.time = moment(item.time, 'YYYY-MM-DD HH:mm:ss').toDate();
                return item;
            });
        },
        getServiceTypeNameById(id) {
            return _.where(this.serviceTypes, {id: id})[0].name;
        },
        setCurrentCallTime() {
            this.model.call_time = moment().toDate();
            this.$refs['call_time_picker'].close();
        },
        setCurrentTimeForServiceType(serviceTypeId, column) {
            _.where(this.model.service_reactions, {service_type_id: serviceTypeId})[0][column] = new Date();
            this.closeTimePickerByRefName('service_reactions_' + column + '_picker_' + serviceTypeId);
        },
        setCurrentTimeForChronology(id) {
            _.where(this.model.chronology, {id: id})[0]['time'] = new Date();
            this.closeTimePickerByRefName('chronology_time_picker_' + id);
        },
        closeTimePickerByRefName(refName) {
            if (this.$refs[refName].close) {
                this.$refs[refName].close();
            } else {
                this.$refs[refName][0].close();
            }
        }
    },
    beforeMount() {
        if (window.card112FormData) {
            this.streets = window.card112FormData.streets;
            this.incidentTypes = window.card112FormData.incidentTypes;
            this.serviceTypes = window.card112FormData.serviceTypes;
            this.model = window.card112FormData.model;
            this.method = window.card112FormData.method;
            this.formRoute = window.card112FormData.formRoute;
            this.prepareModel();
        }
    }
};
</script>

<style scoped>
    .right-select {
        padding: 0 0 0 20px;
    }

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
</style>
