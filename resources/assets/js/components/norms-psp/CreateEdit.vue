<template>
    <div>
        <div class="field is-grouped">
            <v-datepicker-search
                    v-model="record_.date"
                    :date="record_.date"
                    class="control"
                    @dateChanged="record_.date = $event"
                    label="Дата">
            </v-datepicker-search>
            <input type="hidden" name="date" v-model="convertedDate">
        </div>
        <div class="field is-grouped">
            <b-field label="Дата начала">
                <b-timepicker
                    class="small-time-picker"
                    icon="clock"
                    icon-pack="far"
                    id="time_begin"
                    name="time_begin"
                    :ref="'time_begin'"
                    type="text"
                    v-model="record_.time_begin">
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
                                @click="closeTimePicker('time_begin')">
                            <i class="fas fa-check"></i><span>Принять</span>
                        </a>
                    </p>
                </div>
                </b-timepicker>
            </b-field>
                <b-field label="Дата окончания">
                    <b-timepicker
                        class="small-time-picker"
                        icon="clock"
                        icon-pack="far"
                        id="time_end"
                        name="time_end"
                        :ref="'time_end'"
                        type="text"
                        v-model="record_.time_end">
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
            </b-field>
        </div>
        <div class="field is-grouped">
            <div class="columns">
                <div class="column">
                    <div class="field">
                        <label for="">Подразделение</label>
                        <div class="box" v-if="canSelectFd === true">
                            <select name="fire_department_id"
                                    v-model="record_.fire_department_id"
                                    id="fire_department_id"
                                    class="control">
                                <option v-for="dept in fireDepartments" :value="dept.id">{{ dept.title }}</option>
                            </select>
                        </div>
                        <div class="box" v-else-if="fireDepartment_.id === 0 && canSelectFd === false">
                            <p style="color: red">У пользователя не указан номер подразделения</p>
                        </div>
                        <div v-else class="box">
                            <input class="input" required readonly type="text" v-model="fireDepartment_.title">
                            <input class="input" name="fire_department_id" hidden type="hidden" v-model="fireDepartment_.id">
                        </div>
                    </div>
                </div>

                <div class="column">
                    <div class="field">
                        <p class="control">
                            <label for="norm_number_id">№ норматива</label>
                        </p>
                        <div class="select">
                            <select name="norm_number_id" required id="norm_number_id" v-model="record_.norm_number_id">
                                <option v-for="number in normNumbers_" :value="number.id">{{ number.name }}</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="field is-grouped">
            <div class="field">
                <p class="control">
                    <label for="norm_number_id">Тип норматива</label>
                </p>
                <div class="select">
                    <select name="norm_type_id" required v-model="record_.norm_type_id" id="norm_type_id">
                        <option v-for="type in normTypes_" :value="type.id">{{ type.name }}</option>
                    </select>
                </div>
            </div>

            <div class="field">
                <label for="">Ответственное лицо</label>
                <input name="responsible_person" required class="input" type="text" v-model="record_.responsible_person">
            </div>

        </div>
        <div class="field is-grouped">
            <div class="columns">
                <div class="column">
                    <p>Отделение</p>&nbsp; <a @click.prevent="addDepartment" class="button is-info" href="">+</a>
                </div>
                <div class="column">
                    <div v-for="dept in departments_" :key="`department_${dept.id}`" class="columns">
                        <div class="column">
                            <input class="input" required name="departments[]" type="number" v-model="dept.name">
                        </div>
                        <div class="column">
                            <a class="button is-danger" @click.prevent="deleteDepartment(dept.id)" href="">-</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <button v-if="fireDepartment_.id !== 0 || canSelectFd" type="submit" class="button is-success">Сохранить</button>

    </div>
</template>

<script>
    import _ from 'lodash';
    import moment from 'moment';
    export default {
        name: "CreateEdit",
        props: {
            record: {
                type: Object,
                default: () => {
                    return {
                        date: null,
                        time_begin: null,
                        time_end: null,
                        department: '',
                        fire_department: {
                            id: 0,
                            title: '',
                        },
                        norm_type_id: 0,
                        norm_number_id: 0,
                        fire_department_id: 0,
                        responsible_person: '',
                    }
                }
            },
            fireDepartment: {
                type: Object,
                default: () => {
                    return {
                    }
                }
            },
            normNumbers: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            normTypes: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            fireDepartments: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            departments: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            canSelectFd: {
                type: Boolean,
                default: false
            },
        },
        data: function () {
            return {
                record_: this.record,
                fireDepartment_: this.fireDepartment ? this.fireDepartment : {id: 0, title: ''},
                normNumbers_: this.normNumbers,
                normTypes_: this.normTypes,
                departments_: this.departments
            }
        },
        methods: {
            setCurrentTimeNow(ref) {
                this.record_[ref] = moment().toDate();
                this.closeTimePicker(ref);
            },
            addDepartment() {
                this.departments_.push({
                    id: moment().valueOf(),
                    name: '',
                });
            },
            deleteDepartment(id) {
                this.departments_ = this.departments_.filter((item) => {
                    return item.id !== id;
                });
            },
            closeTimePicker(ref) {
                if (this.$refs[ref].close) {
                    this.$refs[ref].close();
                } else {
                    this.$refs[ref][0].close();
                }
            },
        },
        computed: {
            convertedDate() {
                return moment(this.record_.date).format('YYYY-MM-DD');
            }
        },
        created() {
            this.record_.date = this.record_.date !== null ? moment(this.record_.date).toDate() : null;
            this.record_.time_begin = this.record_.time_begin !== null ? moment(`01-01-1970 ${this.record_.time_begin}`).toDate() : null;
            this.record_.time_end = this.record_.time_end !== null ? moment(`01-01-1970 ${this.record_.time_end}`).toDate() : null;
        }
    }
</script>

<style scoped>

</style>