<template>
    <div>
        <div v-if="otherRide_.id === 0">
            <div class="section">
                <div class="field is-grouped">
                    <div class="field">
                        <label for="ride_type_id">Наименование</label>
                        <div class="select"
                             style="display: block">
                            <select required v-model="otherRide_.ride_type_id"
                                    style="width: 100%;">
                                <option value=""></option>
                                <option v-for="rideType in rideTypes_"
                                        :value="rideType.id">{{ rideType.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label for="">Ответственное лицо</label>
                        <input required v-model="otherRide_.responsible_person" required class="input" type="text">
                    </div>
                </div>
                <div class="field is-grouped">
                    <div class="field is-full">
                        <label for="">Адрес</label>
                        <input required v-model="otherRide_.direction" name="direction" required class="input" type="text">
                    </div>
                    <div class="field">
                        <label for="">Наименование объекта</label>
                        <input v-model="otherRide_.object_name" name="object_name" required class="input" type="text">
                    </div>
                </div>
                <p style="color:red;">Для продолжения необходимо сохранить карточку</p>
            </div>
        </div>
        <b-tabs v-else>
            <b-tab-item label="Высылка" icon="fa fa-truck-moving">
                <b-tabs>
                    <b-tab-item label="Боевой расчет" icon="fa fa-truck-moving">
                        <div class="levels">
                            <div class="level-left">
                                <div class="level-item">
                                    <div class="section">
                                        <div class="field is-grouped">
                                            <div class="field">
                                                <label for="ride_type_id">Наименование</label>
                                                <div class="select"
                                                     style="display: block">
                                                    <select v-model="otherRide_.ride_type_id"
                                                            style="width: 100%;">
                                                        <option value=""></option>
                                                        <option v-for="rideType in rideTypes_"
                                                                :value="rideType.id">{{ rideType.name }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label for="">Ответственное лицо</label>
                                                <input v-model="otherRide_.responsible_person" required class="input" type="text">
                                            </div>
                                        </div>
                                        <div class="field is-grouped">
                                            <div class="field is-full">
                                                <label for="">Адрес</label>
                                                <input v-model="otherRide_.direction" required class="input" type="text">
                                            </div>
                                            <div class="field">
                                                <label for="">Наименование объекта</label>
                                                <input v-model="otherRide_.object_name" required class="input" type="text">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="level-right">
                                <div class="level-item">
                                    <div class="field is-grouped">
                                        <div class="field">
                                            <div class="control">
                                                <label for="">Время регистрации</label>
                                                <input type="text"
                                                       :value="otherRide_.created_at|dateFilter('DD.MM.YYYY H:m')"
                                                       readonly="readonly"
                                                       class="input"
                                                >
                                            </div>
                                        </div>
                                    </div>

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
                                <th>Статус</th>
                                <th>Время оповещения</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="department in fireDepartments_">

                                <!--Подразделение-->
                                <td class=""
                                    :id="`ph_${department.id}_text`"
                                    :class="[isRecommended(department), needToGetBack(department)]">
                                    {{ department.title }}
                                </td>

                                <!--Отделения-->
                                <td>
                                    <p v-for="i in formActive[department.id]">
                                        <label :class="[i.recommended === 1 ? 'color-green' : '', needToGetBack(department)]">
                                            <input @change="selectToSend($event, i.id)"
                                                   :name="`departments_to_ride[${department.id }][${i.id}]`"
                                                   :id="`dept_${i.id}`"
                                                   value="1"
                                                   v-model="i.recommended"
                                                   type="checkbox"> {{ i.tech.department ? i.tech.department : i.promoted_department }}
                                        </label>
                                    </p>

                                </td>

                                <!--{#Принято в работу#}-->
                                <td>
                                    <p v-for="i in formActive[department.id]">
                                        <input :id="`accept_time_${i.id }`"
                                               type="text"
                                               :value="formatDate(i.accept_time, 'HH:mm:SS')"
                                               readonly
                                               class="input small-imput">
                                    </p>

                                </td>

                                <!--{#Время выезда#}-->
                                <td>
                                    <p v-for="i in formActive[department.id]">
                                        <input :id="`out_time_${i.id }`"
                                               type="text"
                                               readonly
                                               :value="i.out_time"
                                               class="input small-imput">
                                    </p>

                                </td>

                                <!--{#Время прибытия#}-->
                                <td>
                                    <p v-for="i in formActive[department.id]">
                                        <timepicker-input :name="`time_arrive[${i.id}]`"
                                                          class="small-imput"
                                                          :value="i.arrive_time">
                                        </timepicker-input>
                                    </p>
                                </td>

                                <!--{#Время возвращения#}-->
                                <td>
                                    <p v-for="i in formActive[department.id]">
                                        <input :id="`ret_time_${i.id }`"
                                               type="text"
                                               readonly
                                               :value="i.ret_time"
                                               class="input small-imput">
                                    </p>
                                </td>

                                <!--{#Отправка#}-->
                                <td>
                                    <p v-for="i in formActive[department.id]">
                                        <a :id="`ret_time_${i.id }`"
                                           v-if="(i.dispatched)"
                                           type="text"
                                           :value="i.ret_time"
                                           class="button is-primary small is-outlined small-a">
                                            <i class="fas fa-bus"></i>&nbsp;Подразделение отправлено
                                        </a>

                                        <a :id="`ret_time_${i.id }`"
                                           v-else-if="(!i.dispatched)"
                                           @click="sendOneCheck($event, department.id, i.tech.id, i.id)"
                                           type="text"
                                           :value="i.ret_time"
                                           class="button is-primary small is-outlined small-a">
                                            <i class="fas fa-bus"></i>&nbsp;Выслать
                                        </a>
                                    </p>
                                </td>

                                <td class="is-expanded">
                                    <p v-for="i in formActive[department.id]">{{ i.status }}</p>
                                </td>

                                <!--{#Время оповещения#}-->
                                <td class="is-expanded">
                                    <p v-for="i in formActive[department.id]">
                                        <input :id="`send_time_${i.id }`"
                                               type="text"
                                               readonly
                                               :value="i.dispatch_time"
                                               class="input small-imput">
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </b-tab-item>
                    <b-tab-item label="Резерв" icon="fa fa-truck">
                        <table class="table is-hoverable is-fullwidth">
                            <thead>
                            <tr>
                                <th>Подразделение</th>
                                <th>Отделения</th>
                                <th>Время ввода в боевой расчет</th>
                                <th>Номер отделения</th>
                                <th>Ввести в боевой расчет</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="department in fireDepartments_">

                                <!--Подразделение-->
                                <td class=""
                                    :id="`ph_${department.id}_text`"
                                    :class="isRecommended(department)">
                                    {{ department.title }}
                                </td>

                                <!--Отделения-->
                                <td>
                                    <p v-for="i in formReserve[department.id]">
                                        <!--<label v-if="(i.tech.formation_tech_report.dept_id == department.id && i.tech.status === 'reserve')">-->
                                        {{ i.tech.reserve }} <span class="small">Р</span>

                                        <!--<label>
                                            <input @change="selectToSend($event, i.id)"
                                                   :name="`departments_to_ride[${department.id }][${i.id}]`"
                                                   :id="`dept_${i.id}`" value="1"
                                                   type="checkbox"> {{ i.tech.reserve }}
                                        </label>
                                        &lt;!&ndash;<br>&ndash;&gt;-->
                                    </p>
                                </td>

                                <!--Время ввода в боевой расчет-->
                                <td>
                                    <p v-for="i in formReserve[department.id]">
                                        <!--<label v-if="(i.tech.formation_tech_report.dept_id == department.id && i.tech.status == 'reserve')">-->
                                        <label for="">
                                            <input v-model="i.promoted_at" type="text" class="input small-imput">
                                        </label>
                                    </p>

                                </td>

                                <!--Номер отделения-->
                                <td>
                                    <p v-for="i in formReserve[department.id]">
                                        <label v-if="i.tech.formation_tech_report.dept_id == department.id">
                                            <input v-model="i.promoted_department" type="text" class="input small-imput">
                                        </label>
                                        <!--<br>-->
                                    </p>
                                </td>

                                <!--Ввести в боевой расчет-->
                                <td>
                                    <p v-for="i in formReserve[department.id]">
                                        <!--<a v-if="(i.tech.formation_tech_report.dept_id == department.id && i.tech.status == 'reserve')"-->
                                        <a
                                                v-if="i.promoted_at === null"
                                                @click="addToActive(i)"
                                                class="small-a button is-primary is-outlined"><i class="fas fa-bus"></i>&nbsp;Ввести
                                        </a>
                                        <a
                                                v-else
                                                class="small-a button is-primary is-outlined"><i class="fas fa-bus"></i>&nbsp;В боевом расчете
                                        </a>
                                    </p>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </b-tab-item>

                </b-tabs>
            </b-tab-item>
            <b-tab-item label="Итоги выезда" icon="fa fa-check-double">
                <div class="field is-grouped">
                    <div class="field">
                        <div class="bd-notification is-primary">
                            <label for="other_rides[time_begin]">Время начала</label>
                            <b-timepicker
                                    class="small-time-picker"
                                    icon="clock"
                                    icon-pack="far"
                                    id="arrive_time"
                                    :ref="'time_begin'"
                                    type="text"
                                    v-model="otherRide_.time_begin">
                                <div
                                        class="field is-grouped"
                                        style="justify-content: space-between">
                                    <p class="control">
                                        <a
                                                class="button is-primary is-small"
                                                @click="setCurrentTimeNow('time_begin')">
                                            <b-icon
                                                    pack="far"
                                                    icon="clock"/>
                                            <span>Сейчас</span>
                                        </a>
                                    </p>
                                    <p class="control">
                                        <a
                                                class="button is-basic is-small"
                                                @click="closeTimePicker('arrive_time')">
                                            <i class="fas fa-check"></i><span>Принять</span>
                                        </a>
                                    </p>
                                </div>
                            </b-timepicker>
                        </div>
                    </div>
                    <div class="field is-grouped">
                        <div class="bd-notification is-primary">
                            <label for="other_rides[time_end]">Время окончания</label>
                            <b-timepicker
                                    class="small-time-picker"
                                    icon="clock"
                                    icon-pack="far"
                                    :ref="'time_end'"
                                    type="text"
                                    v-model="otherRide_.time_end">
                                <div
                                        class="field is-grouped"
                                        style="justify-content: space-between">
                                    <p class="control">
                                        <a
                                                class="button is-primary is-small"
                                                @click="setCurrentTimeNow('time_end')">
                                            <b-icon
                                                    pack="far"
                                                    icon="clock"/>
                                            <span>Сейчас</span>
                                        </a>
                                    </p>
                                    <p class="control">
                                        <a
                                                class="button is-basic is-small"
                                                @click="closeTimePicker('time_end')">
                                            <i class="fas fa-check"></i><span>Принять</span>
                                        </a>
                                    </p>
                                </div>
                            </b-timepicker>
                        </div>
                    </div>

                </div>
                <div class="field is-grouped">
                    <div class="bd-notification is-primary">
                        <div class="field">
                            <div class="bd-notification is-primary">
                                <label for="ride_type_id">Наименование</label>
                                <div class="select"
                                     style="display: block">
                                    <select v-model="otherRide_.final_ride_type_id"
                                            style="width: 100%;">
                                        <option value=""></option>
                                        <option v-for="rideType in rideTypes_"
                                                :value="rideType.id">{{ rideType.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="bd-notification is-primary">
                                <label for="">Ответственное лицо</label>
                                <input v-model="otherRide_.final_responsible_person" required class="input" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field is-grouped">
                    <div class="field">
                        <label for="">Адрес</label>
                        <input v-model="otherRide_.final_direction" class="input" type="text">
                    </div>
                    <div class="field">
                        <label for="">Наименование объекта</label>
                        <input v-model="otherRide_.final_object_name" class="input" type="text">
                    </div>
                </div>
                <div class="field">
                    <div class="field is-fullwidth">
                        <label for="">Примечание</label>
                        <textarea name=""
                                  v-model="otherRide_.note"
                                 class="textarea"></textarea>
                    </div>
                </div>

            </b-tab-item>
        </b-tabs>
        <button type="submit" @click.prevent="saveCard" class="button is-success">Сохранить</button>

    </div>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import _ from 'lodash';
    import {globalBus} from '../../scripts/global-bus';
    export default {
        name: "OtherRides",
        props: {
            rideTypes: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            otherRide: {
                type: Object,
                default: () => {
                    return {
                        ride_type_id: null,
                        time_begin: '00:00',
                        time_end: '00:00',
                        object_name: '',
                        note: '',
                        responsible_person: '',
                        direction: '',
                        final_ride_type_id: null,
                        final_responsible_person: '',
                        final_direction: '',
                        final_object_name: '',
                        created_at: ''
                    };
                }
            },
            ticket: {
                type: Object,
                default: () => {
                    return {};
                }
            },
            fireDepartments: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            staff: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            techItems: {
                type: Array,
                default: () => {
                    return [];
                }
            },
        },
        data: function () {
            return {
                rideTypes_: this.rideTypes,
                fireDepartments_: this.fireDepartments,
                staff_: this.staff,
                ticket_: this.ticket,
                techItems_: this.techItems,
                active: [],
                hq: [],
                reserve: [],
                time: 1000 * 10,
                otherRide_: {
                    id: 0,
                    ride_type_id: null,
                    time_begin: '00:00',
                    time_end: '00:00',
                    object_name: '',
                    note: '',
                    responsible_person: '',
                    direction: '',
                    final_ride_type_id: null,
                    final_responsible_person: '',
                    final_direction: '',
                    final_object_name: '',
                    created_at: ''
                }
            }
        },
        methods: {
            saveCard() {
                let form = document.getElementById('other-rides-form');
                let loadingComponent = this.$loading.open({
                    container: form
                })
                let urlToSave = this.urlToSave;
                axios.post(urlToSave, this.otherRide_).then((r) => {
                    this.otherRide_ = this.prepareRecord(r.data.record);
                    this.techItems_ = r.data.techItems;
                    window.history.pushState('page2', 'Title', `/card101-other-rides/${this.otherRide_.id}/edit`);
                    loadingComponent.close();
                });
            },
            addToActive(result){
                if(result.promoted_at === null && result.promoted_department !== null){
                    result.promoted_at = moment().format();
                    let newResult = JSON.parse(JSON.stringify(result));

                    newResult.tech.status = 'action';

                    axios.post('/api/101card/promote-to-action', {
                        id: newResult.id,
                        promoted_department: newResult.promoted_department,
                    });

                    this.active[newResult.fire_department_id].push(newResult);
                }

            },
            sendAllTripPlans() {
                axios.post('/roadtrip/other/send-all/' + this.otherRide_.id).then((response) => {
                    alert('Силы отправлены');
                }).catch(() => {
                });
            },
            needToGetBack(department) {
                if(department.get_back === 1){
                    return 'blink5';
                }

                return '';
            },
            selectToSend(event, id) {
                let recommended = event.target.checked;

                axios.post('/roadtrip/recommend', {
                    id: id, recommended: recommended
                }).then((response) => {
                });
            },
            isRecommended(department) {
                if (department.res !== null
                    && department.res !== undefined
                    && department.res.fire_department_id !== undefined
                    && department.res.fire_department_id === department.id
                    && department.res.recommended
                    && !department.res.get_back) {
                    return 'color-green';
                }
                else if(department.res !== null
                    && department.res !== undefined
                    && department.res.fire_department_id !== undefined
                    && department.res.fire_department_id === department.id
                    && department.res.recommended
                    && department.res.get_back){
                    return 'color-red';
                }

                return '';
            },
            formatDate(date, format){
                if(date){
                    return moment(date).format(format);
                }

                return '';
            },
            sendOneCheck(event, dept_id, dept_number, res_id) {

                /* проставляем галочки в чекбосах */
                let object = document.getElementById(`dept_${res_id}`);
                let is_checked = object.checked;
                object.checked = !is_checked;

                axios.post('/roadtrip/other/send/' + dept_id + '/' + this.otherRide_.id + '/' + dept_number).then((response) => {
                    alert(`Подразделение отправлено`);
                    event.target.disabled = true;
                    event.target.classList.add('is-danger');
                }).catch((e) => {
                    console.dir(e);
                });
            },
            setCurrentTimeNow(ref) {
                this.otherRide_[ref] = moment().toDate();
                this.closeTimePicker(ref);
            },
            closeTimePicker(ref) {
                if (this.$refs[ref].close) {
                    this.$refs[ref].close();
                } else {
                    this.$refs[ref][0].close();
                }
            },
            dateFormat(date) {
                return moment(date).format('DD.MM.YYYY HH:mm');
            },
            prepareRecord(record) {
                record.time_begin = record.time_begin !== null ? moment("1970-01-01 "+record.time_begin).toDate() : null;
                record.time_end = record.time_end !== null ? moment("1970-01-01 "+record.time_end).toDate() : null;

                return record;
            },
            checkRoadtrips() {
                let ticket_id = this.otherRide_.id;
                let self = this;
                if (ticket_id !== 0) {
                    axios.post('/api/card101/check-roadtrip', {ticket_other_id:ticket_id}).then((response) => {
                        if (response.data.recommendations !== undefined) {

                            this.techItems_ = response.data.recommendations;

                        }

                        setTimeout(this.checkRoadtrips, this.time);
                    });
                }
            },
        },
        computed: {
            urlToSave() {
                return `/card101-other-rides/` + (this.otherRide_.id !== 0 ? `${this.otherRide_.id}/edit` : 'create');
            },
            formActive() {
                this.fireDepartments_.forEach((dept) => {
                    this.active[dept.id] = _.filter(this.techItems_, function (result) {
                        return (result.tech.department && result.tech.formation_tech_report.dept_id === dept.id && result.tech.status === 'action') ||
                            (result.promoted_at !== null && result.tech.formation_tech_report.dept_id === dept.id);
                    });
                });

                return this.active;
            },
            formReserve() {
                this.fireDepartments_.forEach((dept) => {
                    this.reserve[dept.id] = _.filter(this.techItems_, function (result) {
                        return result.tech.formation_tech_report.dept_id === dept.id && result.tech.status === 'reserve';
                    });
                });

                return this.reserve;
            },
        },
        mounted(){
            if(this.otherRide !== null) {
                this.otherRide_ = this.otherRide;
            }
            this.checkRoadtrips();

            // setTimeout(this.checkRoadtrips, this.time);

            this.prepareRecord(this.otherRide_);
        }
    }
</script>

<style scoped>
    .small-time-picker {
        max-width: 6rem;
    }

    .small-imput {
        height: 25px;
        font-size: 12px;
    }

    .small-a {
        height: 25px;
    }

    .select, .select > select {
        width: 100%
    }

    .is-narrow {
        width: 20%;
    }

    .square-button-36 {
        display: block;
        height: 36px;
        width: 36px;
    }

    .add_button {
        padding: 0 0 20px 0;
    }

    .color-green{
        color: green;
    }

    .color-red{
        color: #f9221e;
    }

    .blink5 {
        -webkit-animation: blink5 1s linear infinite;
        animation: blink5 1s linear infinite;
    }
    @-webkit-keyframes blink5 {
        0% { color: rgb(226, 228, 0); }
        100% { color: rgb(246, 0, 0); }
    }
    @keyframes blink5 {
        0% { color: rgb(226, 228, 0); }
        100% { color: rgb(246, 0, 0); }
    }

</style>