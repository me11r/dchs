<template>
    <div class="field">
        <div class="add_button">
            <button
                class="button is-small is-basic"
                type="button"
                @click.prevent="addEmptyItem()">
                <i class="fa fa-plus"></i>&nbsp;Добавить
            </button>
        </div>

        <div
            class="panels"
            v-for="item in records_"
            :key="item.id">

            <div class="field is-grouped">
                <div class="control column">
                    <label :for="getName('vehicle_id', item.id)">Тип основного пожарного а/М</label><br>
                    <div class="select">
                        <select
                            required
                            title=""
                            :name="getName('vehicle_id', item.id)"
                            :id="getName('vehicle_id', item.id)"
                            v-model="item.vehicle_id">
                            <option
                                v-for="vehicle in getTechFilter(item.vehicle_id)"
                                :key="'vehicle_' + vehicle.id"
                                :value="vehicle.id">{{ vehicle.name }} {{ vehicle.number }}
                            </option>
                        </select>
                    </div>

                </div>
                <div
                    v-if="block_type !== 'repair' && block_type !== 'reserve'"
                    class="control column">
                    <label :for="getName('department', item.id)">Отделение</label>
                    <input
                        required
                        placeholder="Отделение"
                        type="number"
                        class="input"
                        max="82"
                        min="1"
                        v-model="item.department"
                        @change="departmentCheck($event)"
                        :name="getName('department', item.id)">
                </div>



                <div
                    v-if="block_type === 'reserve'"
                    class="control column">
                    <label :for="getName('reserve', item.id)">Резерв</label><br>
                    <div class="select">
                        <select
                            required
                            title=""
                            :name="getName('reserve', item.id)"
                            :id="getName('reserve', item.id)"
                            v-model="item.reserve">
                            <option
                                v-for="x in 5"
                                :key="'vehicle_reserve' + x"
                                :value="x">Резерв{{ x }}
                            </option>
                        </select>
                    </div>
                </div>

                <div v-if="block_type === 'reserve' || block_type === 'action'"
                     class="control column">
                    <label :for="getName('vehicle_status_id', item.id)">Статус а/м</label><br>
                    <div class="select">
                        <select
                                title=""
                                :name="getName('vehicle_status_id', item.id)"
                                :id="getName('vehicle_status_id', item.id)"
                                v-model="item.vehicle_status_id">
                            <option value="">-</option>
                            <option
                                    v-for="vehicle_status in vehicle_statuses"
                                    :key="'vehicle_status_' + vehicle_status.id"
                                    :value="vehicle_status.id">{{ vehicle_status.name }}
                            </option>
                        </select>
                    </div>

                </div>

                <div class="control column">
                    <label>Удалить</label>

                    <button
                        class="button is-small is-outlined is-danger square-button-36"
                        @click.prevent="removeItem(item.id)"
                        type="button"
                        title="Удалить">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>

            <div
                v-if="block_type === 'repair'"
                class="field is-grouped">
                <div class="control column">
                    <label :for="getName('comment', item.id)">Комментарий</label>
                    <textarea
                        class="textarea"
                        v-model="item.comment"
                        :name="getName('comment', item.id)"
                        :id="getName('comment', item.id)"
                        cols="10"
                        rows="1"></textarea>
                </div>
                <div class="control column">
                    <label :for="getName('date_from', item.id)">С</label><br>
                    <input
                        v-model="item.date_from"
                        :name="getName('date_from', item.id)"
                        :id="getName('date_from', item.id)"
                        class="control"
                        type="date">
                </div>
                <div class="control column">
                    <label :for="getName('date_to', item.id)">По</label><br>
                    <input
                        v-model="item.date_to"
                        :name="getName('date_to', item.id)"
                        :id="getName('date_to', item.id)"
                        class="control"
                        type="date">
                        <!--todo: при использовании bulma datepicker - ошибка при отображении даты -->
                        <!--<b-datepicker-->
                        <!--:name="getName('date_to', item.id)"-->
                        <!--v-model="item.date_to"-->
                        <!--:first-day-of-week="1"-->
                        <!--:month-names="month_names"-->
                        <!--:day-names="day_names"-->
                        <!--placeholder=""-->
                        <!--icon="calendar-today"-->
                        <!--:readonly="false">-->
                        <!--</b-datepicker>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import {_} from 'vue-underscore';

export default {
    name: 'OtherRecords',
    props: {
        block_type: {
            type: String,
            default: ''
        },
        report_id: {
            type: String,
            default: ''
        },
        fire_dep_id: {
            type: String,
            default: ''
        },
        vehicles: {
            type: Array,
            default: () => []
        },
        vehicle_statuses: {
            type: Array,
            default: () => []
        },
        records: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            records_: this.records,
            trunks: [],
            block_type_: this.block_type,
            vehicles_: this.vehicles,
            fire_dep_id_: this.fire_dep_id,
            report_id_: this.report_id,
            month_names: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            day_names: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']

        };
    },
    methods: {
        getTechFilter(selectedId) {
            let scope = this;
            return this.vehicles.filter(function (item) {
                return scope.$parent.selectedTech.indexOf(item.id) === -1 || item.id === selectedId;
            });
        },
        getName(control, id) {
            return 'tech' + `[${this.block_type_}]` + `[${control}][${id}]`;
        },
        defaultDateFormatter: (date) => { moment(date).format('DD/MM/YYYY'); },
        defaultDateFormatter2: (date) => { return dt.toLocaleDateString('ru-RU'); },
        addEmptyItem() {
            this.addItem(this.getEmptyItem());
        },
        addItem(item) {
            this.records_.push(item);
        },
        departmentCheck(event) {
            let value = event.target.value;
            if (value <= 0 || value > 8) {
                return false;
            }
        },
        getEmptyItem() {
            return {
                id: moment().valueOf(),
                department: '',
                name: '',
                count: 0,
                square: 0
            };
        },
        prepareRecords(records) {
            records.map((item) => {
                this.addItem({
                    date_begin: moment(item.date_begin),
                    date_end: moment(item.date_end)
                });
            });
        },
        removeItem(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                this.records_ = this.records_.filter(function (item) {
                    return item.id !== id;
                });
            }
        },

        getStaff() {
            let self = this;
            axios.get('/api/101/get-tech', {
                params: {
                    status: self.block_type_,
                    id: self.report_id_,
                    fire_dep_id: self.fire_dep_id_
                }
            }).then((resp) => {

                self.records_ = resp.data;

                _.each(self.records_, (value) => {
                    self.$parent.$emit('addSelectedTech', value.vehicle_id);
                });
            });
        }
    },
    computed:{
        clonedItems(){
            return JSON.parse(JSON.stringify(this.records_));
        }
    },
    watch: {
        clonedItems(newValue, oldValue){
            _.each(newValue, (value, key) => {
                if(oldValue[key]) {
                    this.$parent.$emit('changeSelectedTech', {
                        oldValue: oldValue[key].vehicle_id,
                        newValue: value.vehicle_id
                    });
                }
            });
            _.each(oldValue, (value, key) => {
                if(!newValue[key]) {
                    this.$parent.$emit('removeSelectedTech', {
                        oldValue: value.vehicle_id
                    });
                }
            });
        }
    },
    beforeMount() {
        this.getStaff();
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
</style>
