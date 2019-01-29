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
                        <p v-if="fireDepartment_.id === 0" style="color: red">У пользователя не указан номер подразделения</p>
                        <input class="input" required readonly type="text" v-model="fireDepartment_.title">
                        <input class="input" name="fire_department_id" hidden type="hidden" v-model="fireDepartment_.id">
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <label for="">Отделение</label>
                        <input class="input" required name="department" type="text" v-model="record_.department">
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
            <div class="columns">
                <div class="column">
                </div>

                <div class="column">
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

                </div>
                <div class="column">
                    <div class="field">
                        <label for="">Ответственное лицо</label>
                        <input name="responsible_person" required class="input" type="text" v-model="record_.responsible_person">
                    </div>
                </div>
            </div>

        </div>
        <button v-if="fireDepartment_.id !== 0" type="submit" class="button is-success">Сохранить</button>

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
        },
        data: function () {
            return {
                record_: this.record,
                fireDepartment_: this.fireDepartment ? this.fireDepartment : {id: 0, title: ''},
                normNumbers_: this.normNumbers,
                normTypes_: this.normTypes,
            }
        },
        methods: {
            setCurrentTimeNow(ref) {
                this.record_[ref] = moment().toDate();
                this.closeTimePicker(ref);
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