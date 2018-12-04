<template>
    <div>
        <!--<div class="column is-2">
            <div class="bd-notification is-primary">
                <label for="drill_type">Тип карточки</label>
                <div class="select"
                     style="display: block">
                    <select v-model="ticket_.drill_type"
                            name="drill_type"
                            id="drill_type"
                            style="width: 100%;">
                        <option value=""></option>
                        <option v-for="item in ['ПТЗ', 'ПТУ']" :value="item">{{ item }}</option>
                        &lt;!&ndash;<option value="ПТЗ">ПТЗ</option>&ndash;&gt;
                        &lt;!&ndash;<option value="ПТУ">ПТУ</option>&ndash;&gt;
                    </select>
                </div>
            </div>
        </div>-->
        <div class="column is-2">
            <div class="bd-notification is-primary">
                <label for="ride_type_id">Наименование</label>
                <div class="select"
                     style="display: block">
                    <select v-model="drill_.ride_type_id"
                            name="drill[ride_type_id]"
                            id="ride_type_id"
                            style="width: 100%;">
                        <option value=""></option>
                        <option v-for="rideType in rideTypes_"
                                :value="rideType.id">{{ rideType.name }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <label for="drill[fire_department_id]">Подразделение</label>
                    <div class="select"
                         style="display: block">
                        <select v-model="drill_.fire_department_id"
                                name="drill[fire_department_id]"
                                id="drill[fire_department_id]"
                                style="width: 100%;">
                            <option value="">-</option>
                            <option v-for="dept in fireDepartments_"
                                    :value="dept.id">{{ dept.title }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <div class="field">
                        <label for="drill[department]">Отделение</label>
                        <input type="text"
                               class="input"
                               name="drill[department]"
                               v-model="drill_.department"
                               id="drill[department]">
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <div class="field">
                        <label for="drill[object_name]">Наименование объекта</label>
                        <input type="text"
                               class="input"
                               name="drill[object_name]"
                               v-model="drill_.object_name"
                               id="drill[object_name]">
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <label for="drill[time_begin]">Время начала</label>
                    <!--<t-picker v-model="drill_.time_begin"-->
                              <!--name="drill[time_begin]"-->
                              <!--id="drill[time_begin]"></t-picker>-->
                    <timepicker-input
                            v-model="drill_.time_begin"
                            :value="drill_.time_begin"
                            name="drill[time_begin]"
                            id="drill[time_begin]"
                    ></timepicker-input>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <label for="drill[time_end]">Время окончания</label>
                    <timepicker-input
                            v-model="drill_.time_end"
                            :value="drill_.time_end"
                            name="drill[time_end]"
                            id="drill[time_end]"
                    ></timepicker-input>
                    <!--<t-picker v-model="drill_.time_end"
                              name="drill[time_end]"
                              id="drill[time_end]"></t-picker>-->
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <label for="drill[staff_id]">Ответственное лицо</label>
                    <div class="select"
                         style="display: block">
                        <select v-model="drill_.staff_id"
                                name="drill[staff_id]"
                                id="drill[staff_id]"
                                style="width: 100%;">
                            <option value="">-</option>
                            <option v-for="item in staff_"
                                    :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <div class="field">
                        <label for="drill[direction]">Куда</label>
                        <textarea class="textarea"
                                  v-model="drill_.direction"
                                  name="drill[direction]"
                                  id="drill[direction]"></textarea>
                    </div>
                </div>
            </div>
        </div>


        <div class="columns">
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <div class="field">
                        <label for="drill[checked_pg]">Проверено ПГ</label>
                        <input type="text"
                               class="input"
                               name="drill[checked_pg]"
                               v-model="drill_.checked_pg"
                               id="drill[checked_pg]">
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <div class="field">
                        <label for="drill[checked_pv]">Проверено ПВ</label>
                        <input type="text"
                               class="input"
                               name="drill[checked_pv]"
                               v-model="drill_.checked_pv"
                               id="drill[checked_pv]">
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <div class="field">
                        <label for="drill[broken_pg]">Неисправно ПГ</label>
                        <input type="text"
                               class="input"
                               name="drill[broken_pg]"
                               v-model="drill_.broken_pg"
                               id="drill[broken_pg]">
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <div class="field">
                        <label for="drill[broken_pv]">Неисправно ПВ</label>
                        <input type="text"
                               class="input"
                               name="drill[broken_pv]"
                               v-model="drill_.broken_pv"
                               id="drill[broken_pv]">
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <div class="field">
                        <label for="drill[corrected_op]">Откорректированно ОП</label>
                        <input type="text"
                               class="input"
                               name="drill[corrected_op]"
                               v-model="drill_.corrected_op"
                               id="drill[corrected_op]">
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <div class="field">
                        <label for="drill[corrected_ok]">Откорректированно ОК</label>
                        <input type="text"
                               class="input"
                               name="drill[corrected_ok]"
                               v-model="drill_.corrected_ok"
                               id="drill[corrected_ok]">
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-full">
            <div class="bd-notification is-primary">
                <div class="field">
                    <label for="drill[note]">Примечание</label>
                    <textarea class="textarea"
                              v-model="drill_.note"
                              name="drill[note]"
                              id="drill[note]"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DrillRides",
        props: {
            rideTypes: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            drill: {
                type: Object,
                default: () => {
                    return {
                        ride_type_id: 0,
                        ticket_101_id: 0,
                        fire_department_id: 0,
                        department: 0,
                        time_begin: '00:00',
                        time_end: '00:00',
                        object_name: '',
                        staff_id: 0,
                        note: '',
                        direction: ''
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
        },
        data: function () {
            return {
                rideTypes_: this.rideTypes,
                fireDepartments_: this.fireDepartments,
                staff_: this.staff,
                ticket_: this.ticket,
                drill_: {
                    ride_type_id: 0,
                    ticket_101_id: 0,
                    fire_department_id: 0,
                    department: 0,
                    time_begin: '00:00',
                    time_end: '00:00',
                    object_name: '',
                    staff_id: 0,
                    note: '',
                    checked_pg: '',
                    checked_pv: '',
                    broken_pg: '',
                    broken_pv: '',
                    corrected_op: '',
                    corrected_ok: '',
                    direction: ''
                }
            }
        },
        methods: {

        },
        computed: {

        },
        mounted(){
            if(this.drill !== null){
                this.drill_ = this.drill;
            }
        }
    }
</script>

<style scoped>

</style>