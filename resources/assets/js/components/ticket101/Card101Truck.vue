<template>
    <div>
        <b-tabs>
            <b-tab-item label="Боевой расчет" icon="fa fa-truck-moving">
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
                                <label :class="[isRecommended(department), needToGetBack(department)]">
                                    <input @change="selectToSend($event, i.id)"
                                           :name="`departments_to_ride[${department.id }][${i.id}]`"
                                           :id="`dept_${i.id}`" value="1"

                                           type="checkbox"> {{ i.tech.department ? i.tech.department : i.promoted_department }}
                                </label>
                                <!--<br>-->
                            </p>

                        </td>

                        <!--{#Принято в работу#}-->
                        <td>
                            <p v-for="i in formActive[department.id]">
                                <input :id="`accepted_time_${i.id }`"
                                       type="text"
                                       :value="(i.dispatched && i.out_time) ? i.updated_at : ''"
                                       readonly
                                       class="input small-imput">
                            </p>

                        </td>

                        <!--{#Время выезда#}-->
                        <td>
                            <p v-for="i in formActive[department.id]">
                                <input :id="`accepted_time_${i.id }`"
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
                                       :value="i.ret_time"
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

        },
        data() {
            return {
                departments_: this.departments,
                results_: this.results,
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
            needToGetBack(department){
                if(department.get_back === 1){
                    return 'blink5';
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