<template>
    <div>
        <b-tabs>
            <b-tab-item label="Боевой расчет" icon="fa fa-truck-moving">
                <div class="levels">
                    <div class="level-left">
                        <div class="control">
                            <label for="">Время регистрации</label>
                            <input type="text"
                                   :value="formatDate(ticket_.created_at, 'HH:mm:SS DD-MM-YYYY')"
                                   readonly="readonly"
                                   class="input"
                            >
                        </div>
                    </div>
                    <div class="level-right">
                        <a @click="sendAllTripPlans()"
                           class="button is-primary is-outlined"><i class="fas fa-bus"></i>&nbsp;Отправка
                        </a>
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
                                <!--<br>-->
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
                        <tr v-for="department in departments_">

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
                                    <label v-if="(i.tech.formation_tech_report.dept_id == department.id && i.tech.status == 'reserve')">
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

    </div>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import _ from 'lodash';

    export default {
        name: "Card101Truck",
        props: {
            departments: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            results: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            ticket: {
                type: Object,
                default: () => {
                    return {};
                }
            },

        },
        data() {
            return {
                departments_: this.departments,
                results_: this.results,
                ticket_: this.ticket,
                active: [],
                reserve: [],
            };
        },
        methods: {
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
            sendAllTripPlans() {
                axios.get('/roadtrip/send-all/' + window.ticket101add.ticketId).then((response) => {
                    alert('Силы отправлены');
                }).catch(() => {
                });
            },
            needToGetBack(department){
                if(department.get_back === 1){
                    return 'blink5';
                }

                return '';
            },
            formatDate(date, format){
                if(date){
                    return moment(date).format(format);
                }

                return '';
            },
            addToActive(result){
                if(result.promoted_at === null && result.promoted_department !== null){
                    result.promoted_at = moment().format();
                    let newResult = JSON.parse(JSON.stringify(result));

                    newResult.tech.status = 'action';
                    // newResult.promoted_department = this.reserve[newResult.fire_department_id].promoted_department;

                    axios.post('/api/101card/promote-to-action', {
                        id: newResult.id,
                        promoted_department: newResult.promoted_department,
                    });

                    this.active[newResult.fire_department_id].push(newResult);
                }

            },

            sendOneCheck(event, dept_id, dept_number, res_id) {
                /* проставляем галочки в чекбосах */
                let object = document.getElementById(`dept_${res_id}`);
                let is_checked = object.checked;
                object.checked = !is_checked;

                axios.get('/roadtrip/send/' + dept_id + '/' + window.ticket101add.ticketId + '/' + dept_number).then((response) => {
                    alert(`Подразделение отправлено`);
                    event.target.disabled = true;
                    event.target.classList.add('is-danger');
                }).catch((e) => {
                    console.dir(e);
                });
            },

            selectToSend(event, id) {
                let recommended = event.target.checked;

                axios.post('/roadtrip/recommend', {
                    id: id, recommended: recommended
                }).then((response) => {

                });
            },

            isSelected(item){
                if(item.recommended === 1 || item.dispatched === 1){
                    return true;
                }

                return false;
            },
            findActive(id){
                return _.findIndex(this.results_, {id:id});
            },
            checkRoadtrips() {
                let ticket_id = window.ticket101add.ticketId;
                let self = this;
                if (ticket_id !== 0) {
                    var timerId = setInterval(() => {
                        axios.post('/api/card101/check-roadtrip', {id: ticket_id}).then((response) => {
                            if (response.data.recommendations !== undefined) {
                                // self.results_ = response.data.recommendations;
                                response.data.recommendations.forEach((item) => {
                                    let accepted_time = 'accept_time_' + item.id;
                                    let out_time = 'out_time_' + item.id;
                                    let ret_time = 'ret_time_' + item.id;
                                    let send_time = 'send_time_' + item.id;

                                    let accepted_time_item = document.getElementById(accepted_time);

                                    let out_time_item = document.getElementById(out_time);

                                    let ret_time_item = document.getElementById(ret_time);

                                    let send_time_item = document.getElementById(send_time);

                                    if (item.dispatched === 1) {
                                        send_time_item.value = item.dispatch_time;
                                    }

                                    if (accepted_time_item && out_time_item) {
                                        accepted_time_item.value = item.accept_time;
                                        out_time_item.value = item.out_time;
                                    }

                                    if (ret_time_item) {
                                        ret_time_item.value = item.ret_time;
                                    }

                                    let index = _.findIndex(self.results_, {id: item.id});

                                    // Replace item at index using native splice
                                    self.results_.splice(index, 1, item);
                                });

                                if (response.data.service_plans !== undefined) {
                                    response.data.service_plans.forEach((item) => {
                                        let accepted_name = item.id + '_name';
                                        let message_time = item.id + '_message_time';
                                        let arrive_time = item.id + '_arrive_time';

                                        let accepted_name_item = document.getElementById(accepted_name);
                                        let message_time_item = document.getElementById(message_time);
                                        let arrive_time_item = document.getElementById(arrive_time);

                                        accepted_name_item.value = item.name_accepted;

                                        if(message_time_item){
                                            message_time_item.value = item.dispatch_time;
                                        }
                                        arrive_time_item.value = item.arrive_time;

                                    });
                                }

                            }
                        });
                    // }, 10000);
                    }, 3000);
                }
            }


        },
        computed: {
            formActive() {
                this.departments_.forEach((dept) => {
                    this.active[dept.id] = _.filter(this.results_, function (result) {
                        return (result.tech.department && result.tech.formation_tech_report.dept_id === dept.id && result.tech.status === 'action') ||
                            (result.promoted_at !== null && result.tech.formation_tech_report.dept_id === dept.id);
                    });
                });

                return this.active;
            },
            formReserve(){
                this.departments_.forEach((dept) => {
                    this.reserve[dept.id] = _.filter(this.results_, function (result) {
                        return result.tech.formation_tech_report.dept_id === dept.id && result.tech.status === 'reserve';
                    });
                });

                return this.reserve;
            }
        },
        mounted(){
            this.checkRoadtrips();
            // console.dir(this.results_);
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