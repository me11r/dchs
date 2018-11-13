<template>
    <div>
        <div class="column is-2">
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
                        <!--<option value="ПТЗ">ПТЗ</option>-->
                        <!--<option value="ПТУ">ПТУ</option>-->
                    </select>
                </div>
            </div>
        </div>
        <div class="column is-2">
            <div class="bd-notification is-primary">
                <label for="ride_type_id">Наименование</label>
                <div class="select"
                     style="display: block">
                    <select v-model="otherRide_.ride_type_id"
                            name="other_rides[ride_type_id]"
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
                    <label for="other_rides[fire_department_id]">Подразделение</label>
                    <div class="select"
                         style="display: block">
                        <select v-model="otherRide_.fire_department_id"
                                name="other_rides[fire_department_id]"
                                id="other_rides[fire_department_id]"
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
                        <label for="other_rides[department]">Отделение</label>
                        <input type="text"
                               class="input"
                               name="other_rides[department]"
                               v-model="otherRide_.department"
                               id="other_rides[department]">
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <label for="other_rides[time_begin]">Время начала</label>
                    <!--<t-picker v-model="otherRide_.time_begin"-->
                              <!--name="other_rides[time_begin]"-->
                              <!--id="other_rides[time_begin]"></t-picker>-->
                    <timepicker-input
                            v-model="otherRide_.time_begin"
                            :value="otherRide_.time_begin"
                            name="other_rides[time_begin]"
                            id="other_rides[time_begin]"
                    ></timepicker-input>
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <label for="other_rides[time_end]">Время окончания</label>
                    <timepicker-input
                            v-model="otherRide_.time_end"
                            :value="otherRide_.time_end"
                            name="other_rides[time_end]"
                            id="other_rides[time_end]"
                    ></timepicker-input>
                    <!--<t-picker v-model="otherRide_.time_end"
                              name="other_rides[time_end]"
                              id="other_rides[time_end]"></t-picker>-->
                </div>
            </div>
            <div class="column is-2">
                <div class="bd-notification is-primary">
                    <label for="other_rides[staff_id]">Ответственное лицо</label>
                    <div class="select"
                         style="display: block">
                        <select v-model="otherRide_.staff_id"
                                name="other_rides[staff_id]"
                                id="other_rides[staff_id]"
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
                        <label for="other_rides[direction]">Куда</label>
                        <textarea class="textarea"
                                  v-model="otherRide_.direction"
                                  name="other_rides[direction]"
                                  id="other_rides[direction]"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-full">
            <div class="bd-notification is-primary">
                <div class="field">
                    <label for="other_rides[note]">Примечание</label>
                    <textarea class="textarea"
                              v-model="otherRide_.note"
                              name="other_rides[note]"
                              id="other_rides[note]"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
                otherRide_: {
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
                }
            }
        },
        methods: {

        },
        computed: {

        },
        mounted(){
            // console.dir(this.otherRide);
            if(this.otherRide !== null){
                this.otherRide_ = this.otherRide;
            }
        }
    }
</script>

<style scoped>

</style>