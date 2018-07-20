<template>
    <section class="container">
        <h4
            class="title"
            style="padding: 3px 15px">Добавление: Карточка 112</h4>
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
                        <a @click="setTab(0)"><i class="fas fa-phone"/>Звонок</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 1}">
                        <a
                            @click="setTab(1)">
                        <i class="fas fa-envelope"/>Службы</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 2}">
                        <a
                            @click="setTab(2)">
                        <i class="fas fa-truck-moving"/>Информация с места проишествия</a>
                    </li>
                    <li :class="{'is-active': currentTabIndex === 3}">
                        <a
                            @click="setTab(3)">
                        <i class="fas fa-info-circle"/>Хронология событий</a>
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
                                <label for="street_id">Происшествие</label>
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
                                v-model="model.description"/>
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
                                            <i class="fas fa-check"/><span>Принять</span>
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
                                <!--@TODO doesn't work in the Edit page-->
                                <tr
                                    v-for="serviceType in serviceTypes"
                                    :key="serviceType.id">
                                    <td>
                                        {{ serviceType.name }}
                                        <input
                                            type="hidden"
                                            v-model="model.serviceReactions[serviceType.id].service_type_id"
                                            :name="'serviceReactions['+serviceType.id+'][service_type_id]'">
                                    </td>
                                    <td>
                                        <b-timepicker
                                            class="small-time-picker"
                                            icon="clock"
                                            icon-pack="far"
                                            :ref="'service_reactions_message_time_picker_' + serviceType.id"
                                            type="text"
                                            :name="'serviceReactions['+serviceType.id+'][message_time]'"
                                            v-model="model.serviceReactions[serviceType.id].message_time">
                                            <div
                                                class="field is-grouped"
                                                style="justify-content: space-between">
                                                <p class="control">
                                                    <a
                                                        class="button is-primary is-small"
                                                        @click="model.serviceReactions[serviceType.id].message_time = new Date();
                                                                closeTimePickerByRefName('service_reactions_message_time_picker_' + serviceType.id)">
                                                        <b-icon
                                                            pack="far"
                                                            icon="clock"/>
                                                        <span>Сейчас</span>
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <a
                                                        class="button is-outlined is-small"
                                                        @click="closeTimePickerByRefName('service_reactions_message_time_picker_' + serviceType.id)">
                                                        <i class="fas fa-check"/><span>Принять</span>
                                                    </a>
                                                </p>
                                            </div>
                                        </b-timepicker>
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            class="input"
                                            v-model="model.serviceReactions[serviceType.id].name"
                                            :name="'serviceReactions['+serviceType.id+'][name]'">
                                    </td>
                                    <td>
                                        <b-timepicker
                                            class="small-time-picker"
                                            icon="clock"
                                            icon-pack="far"
                                            :ref="'service_reactions_departure_time_picker_' + serviceType.id"
                                            type="text"
                                            :name="'serviceReactions['+serviceType.id+'][departure_time]'"
                                            v-model="model.serviceReactions[serviceType.id].departure_time">
                                            <div
                                                class="field is-grouped"
                                                style="justify-content: space-between">
                                                <p class="control">
                                                    <a
                                                        class="button is-primary is-small"
                                                        @click="model.serviceReactions[serviceType.id].departure_time = new Date();
                                                                closeTimePickerByRefName('service_reactions_departure_time_picker_' + serviceType.id)">
                                                        <b-icon
                                                            pack="far"
                                                            icon="clock"/>
                                                        <span>Сейчас</span>
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <a
                                                        class="button is-outlined is-small"
                                                        @click="closeTimePickerByRefName('service_reactions_departure_time_picker_' + serviceType.id)">
                                                        <i class="fas fa-check"/><span>Принять</span>
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
                                            :ref="'service_reactions_arrival_time_picker_' + serviceType.id"
                                            type="text"
                                            :name="'serviceReactions['+serviceType.id+'][arrival_time]'"
                                            v-model="model.serviceReactions[serviceType.id].arrival_time">
                                            <div
                                                class="field is-grouped"
                                                style="justify-content: space-between">
                                                <p class="control">
                                                    <a
                                                        class="button is-primary is-small"
                                                        @click="model.serviceReactions[serviceType.id].arrival_time = new Date();
                                                                closeTimePickerByRefName('service_reactions_arrival_time_picker_' + serviceType.id)">
                                                        <b-icon
                                                            pack="far"
                                                            icon="clock"/>
                                                        <span>Сейчас</span>
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <a
                                                        class="button is-outlined is-small"
                                                        @click="closeTimePickerByRefName('service_reactions_arrival_time_picker_' + serviceType.id)">
                                                        <i class="fas fa-check"/><span>Принять</span>
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
                                <label for="street_id">Уточненный адрес</label>
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
                                <label for="street_id">Происшествие</label>
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
                                v-model="model.measures"/>
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
                                v-model="model.resources"/>
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
                    <!--@TODO doesn't work in the Edit page-->
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
                                    v-if="model.chronology"
                                    v-for="i in [0,1,2,3,4]"
                                    :key="i">
                                    <td>
                                        <b-timepicker
                                            class="small-time-picker"
                                            icon="clock"
                                            icon-pack="far"
                                            :ref="'chronology_time_picker_' + i"
                                            type="text"
                                            :name="'chronology['+i+'][time]'"
                                            :value="model.chronology[i].time"
                                            v-model="model.chronology[i].time">
                                            <div
                                                class="field is-grouped"
                                                style="justify-content: space-between">
                                                <p class="control">
                                                    <a
                                                        class="button is-primary is-small"
                                                        @click="model.chronology[i].time = new Date();
                                                                closeTimePickerByRefName('chronology_time_picker_' + i)">
                                                        <b-icon
                                                            pack="far"
                                                            icon="clock"/>
                                                        <span>Сейчас</span>
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <a
                                                        class="button is-outlined is-small"
                                                        @click="closeTimePickerByRefName('chronology_time_picker_' + i)">
                                                        <i class="fas fa-check"/><span>Принять</span>
                                                    </a>
                                                </p>
                                            </div>
                                        </b-timepicker>
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            class="input"
                                            v-model="model.chronology[i].comment"
                                            :name="'chronology['+i+'][comment]'">
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            class="input"
                                            v-model="model.chronology[i].additional_comment"
                                            :name="'chronology['+i+'][additional_comment]'">
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
                            class="button is-primary is-outlined"><i class="fas fa-arrow-right"/>Следующий раздел
                        </button>
                    </p>
                    <p class="level-right">
                        <button
                            type="submit"
                            class="button is-success"><i class="fas fa-check"/>Сохранить
                        </button>
                    </p>
                </div>
            </div>
        </form>
    </section>
</template>

<script>
import moment from 'moment';

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
    methods: {
        setTab(tabIndex) {
            this.currentTabIndex = tabIndex;
        },
        prepareModel() {
            this.model.call_time = this.model.call_time ? moment(this.model.call_time).toDate() : moment().toDate();

            this.model.serviceReactions = this.model.serviceReactions || {};
            this.serviceTypes.map((serviceType) => {
                this.model.serviceReactions[serviceType.id] = {
                    service_type_id: serviceType.id,
                    message_time: moment().hour(0).minute(0).toDate(),
                    name: '',
                    departure_time: moment().hour(0).minute(0).toDate(),
                    arrival_time: moment().hour(0).minute(0).toDate()
                };
            });

            this.model.chronology = this.model.chronology || {};
            [0, 1, 2, 3, 4].map((i) => {
                this.model.chronology[i] = {
                    time: moment().hour(0).minute(0).toDate(),
                    comment: '',
                    additional_comment: ''
                };
            });
        },
        setCurrentCallTime() {
            this.model.call_time = moment().toDate();
            this.$refs['call_time_picker'].close();
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
