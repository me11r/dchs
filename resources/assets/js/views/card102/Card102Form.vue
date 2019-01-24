<template>
    <section
        class="container section"
        v-if="formDataExists">
        <h4
            class="title"
            style="padding: 3px 15px">{{ model.id ? 'Редактирование' : 'Добавление' }}: Карточка 103</h4>
        <form
            :action="this.formRoute"
            id="card102_form"
            method="POST">
            <input
                type="hidden"
                name="_method"
                :value="method">
            <input
                type="hidden"
                name="_token"
                :value="csrf">
            <input
                type="hidden"
                name="currentTabIndex"
                id="currentTabIndex"
                v-model="currentTabIndex"
            >

            <div class="tabs buttab is-boxed">
                <ul>
                    <li :class="{'is-active': currentTabIndex === 0}">
                        <a @click="setTab(0)"><i class="fas fa-phone"></i>&nbsp;Звонок</a>
                    </li>

                    <li :class="{'is-active': currentTabIndex === 1}">
                        <a
                            @click="setTab(1)">
                        <i class="fas fa-truck"></i>&nbsp;Высылка</a>
                    </li>

                    <li :class="{'is-active': currentTabIndex === 2}">
                        <a
                                @click="setTab(2)">
                            <i class="fas fa-envelope"></i>&nbsp;Уведомления</a>
                    </li>

                    <li :class="{'is-active': currentTabIndex === 3}">
                        <a
                            @click="setTab(3)">
                        <i class="fas fa-truck-moving"></i>&nbsp;Итоги выезда</a>
                    </li>
                </ul>
            </div>
            <div class="panels">
                <div class="panels">
                    <div :style="{'display': currentTabIndex === 0 ? 'block': 'none'}">
                        <h5 class="subtitle">Первоначальная информация заявителя:</h5>
                        <!--АДРЕС-->
                        <div class="field">
                            <label for="location">Адрес
                                <a
                                    :href="'/card/mapscreen'"
                                    target="_blank"
                                    class="button is-small is-basic">
                                    <i class="far fa-map"></i>&nbsp;Открыть карту
                                </a>
                            </label>
                            <input
                                name="location"
                                required
                                id="location"
                                class="input"
                                v-model="model.location">
                        </div>

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
                            <!--ТИП ПРОИСШЕСТВИЯ-->
                            <div
                                class="control"
                                style="width: 50%; padding: 0 6px 0 0; margin-right: 5px;">
                                <p class="control">
                                    <label for="incident_type_id">Происшествие</label>
                                </p>
                                <input type="text" class="input" id="incident_type_id" name="incident_type_id" v-model="model.incident_type_id">
                            </div>
                            <!--ПРЕДВАРИТЕЛЬНАЯ ИНФОРМАЦИЯ-->
                            <div
                                    class="control"
                                    style="width: 50%; padding: 0 6px 0 0; margin-right: 5px;">
                                <p class="control">
                                    <label for="pre_information">Предварительная информация</label>
                                </p>
                                <input type="text" class="input" id="pre_information" name="pre_information" v-model="model.pre_information">
                            </div>
                        </div>

                        <!--ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ-->
                        <div class="field">
                            <label for="add_info">Дополнительная информация</label>
                            <textarea
                                name="add_info"
                                id="add_info"
                                class="textarea"
                                cols="30"
                                rows="3"
                                v-model="model.add_info"></textarea>
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
                                            class="button is-basic is-small"
                                            @click="$refs['call_time_picker'].close()">
                                            <i class="fas fa-check"></i><span>Принять</span>
                                        </a>
                                    </p>
                                </div>
                            </b-timepicker>
                        </div>
                    </div>
                    <div :style="{'display': currentTabIndex === 1 ? 'block': 'none'}">
                        <div class="levels">
                            <div class="level-left">
                                <div class="control">
                                    <label for="">Время регистрации</label>
                                    <input type="text"
                                           :value="model.created_at"
                                           readonly="readonly"
                                           class="input"
                                    >
                                </div>
                            </div>
                        </div>

                        <table class="table is-hoverable is-fullwidth">
                            <thead>
                            <tr>
                                <th>Подразделение</th>
                                <th>Отделения</th>
                                <th>Принято в работу</th>
                                <th>Время выезда</th>
                                <th>Время прибытия</th>
                                <th>Время возвращения</th>
                                <th>Отправка</th>
                                <th>Время оповещения</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="department in departments_">

                                <!--Подразделение-->
                                <td class=""
                                    :id="`ph_${department.name}_text`"
                                    :class="">
                                    {{ department.title }}
                                </td>

                                <!--Отделения-->
                                <td>
                                    <p v-for="i in departmentsToSend">
                                        {{ i.title }}
                                        <!--<label class="">
                                            <input @change="selectToSend($event, i.name)"
                                                   :name="`departments_to_ride[${department.name }][${i.name}]`"
                                                   :id="`dept_${i.name}`"
                                                   value="1"
                                                   class=""
                                                   v-model="i.name"
                                                   type="checkbox"> {{ i.title }}
                                        </label>-->
                                    </p>

                                </td>

                                <!--{#Принято в работу#}-->
                                <td>
                                    <p v-for="i in departmentsToSend">
                                        <input :id="`accept_time_${i.name }`"
                                               type="text"
                                               readonly
                                               class="input small-imput">
                                    </p>

                                </td>

                                <!--{#Время выезда#}-->
                                <td>
                                    <p v-for="i in departmentsToSend">
                                        <input :id="`out_time_${i.name }`"
                                               type="text"
                                               readonly
                                               class="input small-imput">
                                    </p>

                                </td>

                                <!--{#Время прибытия#}-->
                                <td>
                                    <p v-for="i in departmentsToSend">
                                        <timepicker-input
                                                :id="`time_arrive_${i.name }`"
                                                :name="`time_arrive[${i.name}]`"
                                                class="small-imput">
                                        </timepicker-input>
                                    </p>
                                </td>

                                <!--{#Время возвращения#}-->
                                <td>
                                    <p v-for="i in departmentsToSend">
                                        <input :id="`ret_time_${i.name }`"
                                               type="text"
                                               readonly
                                               class="input small-imput">
                                    </p>
                                </td>

                                <!--{#Отправка#}-->
                                <td>
                                    <p v-for="i in departmentsToSend">

                                        <a :id="`ret_time_${i.name }`"
                                           @click="sendOneDepartment($event, i.name)"
                                           type="text"
                                           :value="i.ret_time"
                                           class="button is-primary small is-outlined small-a small-imput">
                                            <i class="fas fa-bus"></i>&nbsp;Выслать
                                        </a>
                                    </p>
                                </td>

                                <!--{#Время оповещения#}-->
                                <td class="is-expanded">
                                    <p v-for="i in departmentsToSend">
                                        <input :id="`send_time_${i.name }`"
                                               type="text"
                                               readonly
                                               class="input small-imput">
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div :style="{'display': currentTabIndex === 2 ? 'block': 'none'}">
                        <div class="tabs buttab is-boxed">
                            <ul>
                                <li :class="{'is-active': servicesTabIndex === 0}">
                                    <a @click="servicesTabIndex = 0">
                                        <i class="fas fa-envelope"></i>&nbsp;Руководство
                                    </a>
                                </li>
                                <li :class="{'is-active': servicesTabIndex === 1}">
                                    <a @click="servicesTabIndex = 1">
                                        <i class="fas fa-envelope"></i>&nbsp;Службы взаимодействия
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!--<div
                            class="panels index-1"
                            :style="{'display': servicesTabIndex === 0? 'block': 'none'}">
                            <card112-popup-notifications/>
                        </div>-->

                        <div
                            class="panels index-1"
                            :style="{'display': servicesTabIndex === 1? 'block': 'none'}">
                            <table class="table is-expanded is-striped is-narrow is-fullwidth">
                                <thead>
                                    <tr>
                                        <th>Службы</th>
                                        <th>Время сообщения</th>
                                        <th>Фамилия<br>принявшего сообщение</th>
                                        <th>Время прибытия</th>
                                        <th>Путевой лист отправлен</th>
                                        <th>Уведомление отправлено</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="service in serviceTypes">
                                        <td>{{ service.name }}</td>
                                        <td>
                                            <input
                                                type="text"
                                                :name="`notification_services[${service.id}][message_time]`"
                                                v-model="services[service.id].created_at"
                                                :id="service.id + '_created_at'"
                                                class="input">
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                :name="`notification_services[${service.id}][name]`"
                                                class="input"
                                                :id="service.id + '_name'"
                                                v-model="services[service.id].name_accepted">
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                :name="`notification_services[${service.id}][arrive_time]`"
                                                v-model="services[service.id].arrive_time"
                                                :id="service.id + '_arrived_at'"
                                                class="input">
                                        </td>
                                        <td>

                                            <input
                                                type="text"
                                                :name="`notification_services[${service.id}][dispatched_time]`"
                                                class="input"
                                                v-model="services[service.id].dispatched_time"
                                                :id="service.id + '_dispatched_time'"
                                            >

                                        </td>
                                        <td>
                                            <label for="">
                                                <input
                                                    type="checkbox"
                                                    @change="sendOneCheckService($event, model.id, service.id)"
                                                    value="1"

                                                    class="checkbox">
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div :style="{'display': currentTabIndex === 3 ? 'block': 'none'}">

                        <h5 class="subtitle">Основная информация</h5>

                        <div class="field">
                            <p class="control">
                                <label for="additional_street_id">Исходящий номер</label>
                            </p>
                            <input
                                    class="input"
                                    v-model="model.caller_phone">
                        </div>

                        <div class="field"
                             v-if="model.city_area">
                            <p class="control">
                                <label for="additional_street_id">Район города</label>
                            </p>
                            <input
                                    class="input"
                                    readonly
                                    v-model="model.city_area.name">
                        </div>

                        <div class="field">
                            <p class="control">
                                <label for="additional_street_id">Предварительная информация</label>
                            </p>
                            <textarea
                                    class="textarea"
                                    v-model="model.pre_information"></textarea>
                        </div>
                        <div class="field">
                            <p class="control">
                                <label for="location">Предварительный адрес</label>
                            </p>
                            <input
                                    class="input"
                                    v-model="model.location">
                        </div>
                        <div class="field">
                            <p class="control">
                                <label for="detailed_address">Уточненный адрес</label>
                            </p>
                            <textarea
                                    class="textarea"
                                    id="detailed_address"
                                    name="detailed_address"
                                    v-model="model.detailed_address"></textarea>
                        </div>

                        <br>

                            <div
                                    class="field"
                                    >
                                <p class="control">
                                    <label for="trip_result">Результат выезда</label>
                                </p>
                                <div class="select">
                                    <input
                                            class="input"
                                            v-model="model.trip_result">
                                    <!--<select-->
                                            <!--id="trip_result"-->
                                            <!--name="trip_result"-->
                                            <!--v-model="model.trip_result">-->
                                        <!--<option-->
                                                <!--v-for="result in tripResultOptions"-->
                                                <!--:key="result.name"-->
                                                <!--:value="result.name">{{ result.title }}-->
                                        <!--</option>-->
                                    <!--</select>-->
                                </div>
                            </div>
                            <div class="field">
                                <p class="control">
                                    <label for="trip_result_add">Итог выезда</label>
                                </p>
                                <textarea
                                        class="textarea"
                                        id="trip_result_add"
                                        name="trip_result_add"
                                        v-model="model.trip_result_add"></textarea>
                            </div>
                            <div class="field">
                                <p class="control">
                                    <label for="add_info2">Дополнительная информация</label>
                                </p>
                                <textarea
                                        class="textarea"
                                        id="add_info2"
                                        name="add_info2"
                                        v-model="model.add_info2"></textarea>
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
                        @click.prevent="nextTab">
                        <button
                            id="nexttab"
                            type="button"
                            class="button is-info is-main"><i class="fas fa-arrow-right"></i>&nbsp;Следующий раздел
                        </button>
                    </p>
                    <p class="level-right">
                        <button
                            type="submit"
                            class="button is-basic is-main"><i class="fas fa-check"></i>&nbsp;Сохранить
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
import {_} from 'vue-underscore';
import {Card102utils} from './card102utils.js';
import {BuefyCommonSelect} from '../../components';
import {MAP_LOCATION_EXCHANGE_KEY, AREA_ID_FOUND} from '../../config/storage-keys';
import {globalBus} from '../../scripts/global-bus';
import YandexMapsBus from '../../scripts/yandex-maps-bus';
// import Card112PopupNotifications from "../../components/card112/Card112PopupNotifications";

export default {
    name: 'Card102Form',
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
            gendersOptions: [
                {name: 'male', title: 'мужской'},
                {name: 'female', title: 'женский'},
            ],
            tripResultOptions: [
                {name: 'hospitalization', title: 'Госпитализация'},
                {name: 'denial', title: 'Отказ'}
            ],
            currentTabIndex: 0,
            lastTabIndex: 3,
            method: 'POST',
            formRoute: '',
            card112Utils: Card102utils,
            yandexMapsBus: {},
            currentCity: 'Алматы',
            servicePlans: [],
            services: [],
            departments_: [
                {name: 'ruvd', title: 'РУВД Алмалинского района'}
            ],
            departmentsToSend: [
                {name: 'buran1', title: 'Буран 1'},
                {name: 'buran2', title: 'Буран 2'},
                {name: 'buran3', title: 'Буран 3'},
                {name: 'buran4', title: 'Буран 4'},
            ],
            servicesTabIndex: 0
        };
    },
    components: {
        // Card112PopupNotifications,
        BuefyCommonSelect
    },
    computed: {
        formDataExists() {
            return !!window.cardFormData;
        },
        serviceTypeOptions() {
            return this.commonOptionsMapping(this.serviceTypes);
        },
        streetsOptions() {
            return this.commonOptionsMapping(this.streets);
        },
        cityAreasOptions() {
            return this.commonOptionsMapping(this.cityAreas);
        },

    },
    methods: {
        parsedTime() {
            if (!this.model.call_time) {
                this.model.call_time = new Date('01-01-1970 00:00');
                return this.model.call_time;
            }
            let dt = new Date('01-01-1970 00:00');
            const tm = this.model.call_time.split(':');
            if (tm.length > 1) {
                dt.setHours(tm[0]);
                dt.setMinutes(tm[1]);
            }
            return dt;
        },
        commonOptionsMapping(array) {
            return array.map((item) => {
                return {
                    'id': item.id,
                    'text': item.name
                };
            });
        },
        selectToSend(event, id) {
            let recommended = event.target.checked;

            axios.post('/roadtrip/recommend', {
                id: id, recommended: recommended
            }).then((response) => {

            });
        },
        checkServices() {
            let self = this;
            if (window.cardFormData) {
                setInterval(function() {
                    axios
                        .post('/card102/service-plans/check', {
                            card_id: window.cardFormData.model.id,
                            cardType: 102
                        })
                        .then((response) => {
                            let servicePlans = response.data.servicePlans;
                            let roadtrips = response.data.roadtrips;

                            if (servicePlans !== undefined) {
                                servicePlans.forEach((plan) => {
                                    self.serviceTypes.forEach((serviceType) => {
                                        if (plan.service_type_id === serviceType.id) {
                                            self.services[serviceType.id] = {
                                                dispatched_time: plan.dispatched_time, //|| moment().format('d-m-Y'),
                                                created_at: plan.dispatched_time,// || moment().format('d-m-Y'),
                                                name_accepted: plan.name_accepted || '',
                                                arrive_time: plan.arrive_time || ''
                                            };
                                            let name = document.getElementById(serviceType.id + '_name');
                                            let created_at = document.getElementById(serviceType.id + '_created_at');
                                            let arrived_at = document.getElementById(serviceType.id + '_arrived_at');
                                            let dispatched_time = document.getElementById(serviceType.id + '_dispatched_time');

                                            if (name.value === '') {
                                                name.value = plan.name_accepted || '';
                                            }

                                            if (created_at.value === '') {
                                                created_at.value = plan.dispatched_time || '';
                                            }

                                            if (arrived_at.value === '') {
                                                arrived_at.value = plan.arrive_time || '';
                                            }

                                            if (dispatched_time.value === '') {
                                                dispatched_time.value = plan.dispatched_time || '';
                                            }
                                        }
                                    });
                                });
                            }

                            if (roadtrips !== undefined) {
                                roadtrips.forEach((trip) => {
                                    let dept = _.find(self.departmentsToSend, {name: trip.department_id});

                                    if (dept !== null) {
                                        let dept_accepted = document.getElementById(`accept_time_${dept.name}`);
                                        let dept_out = document.getElementById(`out_time_${dept.name}`);
                                        let dept_arrived = document.getElementById(`time_arrive_${dept.name}`);
                                        let dept_return = document.getElementById(`ret_time_${dept.name}`);
                                        let dept_created = document.getElementById(`arrived_at_${dept.name}`);
                                        let dept_dispatched = document.getElementById(`send_time_${dept.name}`);

                                        if (dept_accepted) {
                                            dept_accepted.value = trip.accept_time;
                                        }

                                        if (dept_out) {
                                            dept_out.value = trip.out_time;
                                        }

                                        if (dept_arrived) {
                                            dept_arrived.value = trip.arrive_time;
                                        }

                                        if (dept_return) {
                                            dept_return.value = trip.ret_time;
                                        }

                                        if (dept_dispatched) {
                                            dept_dispatched.value = trip.dispatch_time;
                                        }
                                    }
                                });
                            }
                        })
                        .catch(() => {
                        });
                }, 10000);
            }
        },
        sendOneCheckService(event, cardId, service) {
            if (event.target.checked) {
                axios
                    .post('/service-plans/send', {
                        card_id: cardId,
                        service_id: service,
                        cardType: 102
                    })
                    .then((response) => {
                        this.services[service].dispatched_time = response.data.dispatched_time;
                    })
                    .catch(() => {
                    });

                /*axios
                    .post('/api/notification/ticket112send', {
                        notification_id: service,
                        cardType: 101
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
                    });*/
            }
        },
        setTab(tabIndex) {
            if (window.cardFormData.model.id === 0 || window.cardFormData.model.id === undefined) {
                let form = document.getElementById('card102_form');
                let valid = form.checkValidity();

                if (valid) {
                    document.getElementById('currentTabIndex').value = tabIndex;
                    form.submit();
                } else {
                    return false;
                }
            }

            this.currentTabIndex = tabIndex;
            window.location.hash = '#return=' + tabIndex;
        },
        sendOneDepartment(event, deptName) {

            axios.post('/card102/send-department', {
                cardId: window.cardFormData.model.id,
                department: deptName,
            }).then((response) => {
                alert(`Подразделение отправлено`);
                event.target.disabled = true;
                event.target.classList.add('is-danger');
            }).catch((e) => {
                console.dir(e);
            });
        },
        nextTab() {
            let inx = this.currentTabIndex;
            this.setTab(++inx);
        },
        getServiceTypeNameById(id) {
            return _.where(this.serviceTypes, {id: id})[0].name;
        },
        setCurrentCallTime() {
            this.model.call_time = new Date();
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
        },
        getTabIndex() {
            const ret = window.location.hash.match(/#return=(\d+)/);
            if (ret !== null) {
                this.setTab(parseInt(ret[1]));
            } else {
                this.setTab(0);
            }
        }
    },
    watch: {
        'model.location'() {
            this.notifyMap();
        }
    },
    beforeMount() {
    },
    mounted() {
        this.getTabIndex();
        (new YandexMapsBus())
            .getInstance()
            .then((yandexMapsBus) => {
                this.yandexMapsBus = yandexMapsBus;
                this.cityAreas = window.cardFormData.cityAreas;
                this.incidentTypes = window.cardFormData.incidentTypes;
                this.serviceTypes = window.cardFormData.serviceTypes;
                this.method = window.cardFormData.method;
                this.formRoute = window.cardFormData.formRoute;
                this.model = window.cardFormData.model;
                this.model = this.card112Utils.prepareModel(this.model, this.serviceTypes);
                this.servicePlans = window.cardFormData.servicePlans;
                this.model.call_time = this.parsedTime();
                // this.model.created_at = this.parsedTime();

                this.serviceTypes.forEach((item) => {
                    this.services[item.id] = {
                        dispatched_time: '',
                        created_at: '',
                        name_accepted: '',
                        arrive_time: ''
                    };
                });

                if (this.servicePlans !== undefined) {
                    this.servicePlans.forEach((plan) => {
                        this.serviceTypes.forEach((item) => {
                            if (plan.service_type_id === item.id) {
                                this.services[item.id] = {
                                    dispatched_time: plan.dispatched_time, //|| moment().format('d-m-Y'),
                                    created_at: plan.dispatched_time,// || moment().format('d-m-Y'),
                                    name_accepted: plan.name_accepted || '',
                                    arrive_time: plan.arrive_time || ''
                                };
                            }
                        });
                    });
                }

                this.checkServices();
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

    .small-imput {
        height: 25px;
        font-size: 12px;
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
