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
                <div class="control column is-four-fifths">
                    <label :for="getName('aircraft_id', item.id)">Тип воздушного судна</label><br>
                    <div class="select">
                        <select
                            required
                            title=""
                            :name="getName('aircraft_id', item.id)"
                            :id="getName('aircraft_id', item.id)"
                            v-model="item.aircraft_id">
                            <option
                                v-for="vehicle in getTechFilter(item.aircraft_id)"
                                :key="'vehicle_' + vehicle.id"
                                :value="vehicle.id">{{ vehicle.name }} {{ vehicle.number }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <div class="control">
                            <div class="field">
                                <input type="hidden" value="0" :name="getName('simplex', item.id)">
                                <b-checkbox v-model="item.simplex" :native-value="1" :true-value="1" :false-value="0" :name="getName('simplex', item.id)">Simplex</b-checkbox>

                                <input type="hidden" value="0" :name="getName('vsu3', item.id)">
                                <b-checkbox v-model="item.vsu3" :native-value="1" :true-value="1" :false-value="0" :name="getName('vsu3', item.id)">ВСУ 3</b-checkbox>

                                <input type="hidden" value="0" :name="getName('vsu5', item.id)">
                                <b-checkbox v-model="item.vsu5" :native-value="1" :true-value="1" :false-value="0" :name="getName('vsu5', item.id)">ВСУ 5</b-checkbox>

                                <input type="hidden" value="0" :name="getName('vsu10', item.id)">
                                <b-checkbox v-model="item.vsu10" :native-value="1" :true-value="1" :false-value="0" :name="getName('vsu10', item.id)">ВСУ 10</b-checkbox>
                            </div>
                        </div>
                        <div class="control">
                            <div class="field">
                                <input type="hidden" value="0" :name="getName('winch', item.id)">
                                <b-checkbox v-model="item.winch" :native-value="1" :true-value="1" :false-value="0" :name="getName('winch', item.id)">Лебедка</b-checkbox>

                                <input type="hidden" value="0" :name="getName('sur', item.id)">
                                <b-checkbox v-model="item.sur" :native-value="1" :true-value="1" :false-value="0" :name="getName('sur', item.id)">СУР</b-checkbox>

                                <input type="hidden" value="0" :name="getName('external_suspension', item.id)">
                                <b-checkbox v-model="item.external_suspension" :native-value="1" :true-value="1" :false-value="0" :name="getName('external_suspension', item.id)">Внешняя подвеска (до 5т)</b-checkbox>
                            </div>
                        </div>
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
                    <label :for="getName('comment', item.id)">Причина</label>
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
        vehicles: {
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
            axios.get('/api/air-rescue/get-tech', {
                params: {
                    status: self.block_type_,
                    id: self.report_id_
                }
            }).then((resp) => {
                // for (let item in resp.data) {
                //     resp.data[item].date_begin = resp.data[item].date_begin ? new Date(resp.data[item].date_begin) : null;
                //     resp.data[item].date_end = resp.data[item].date_end ? new Date(resp.data[item].date_end) : null;
                // }

                self.records_ = resp.data;

                _.each(self.records_, (value) => {
                    self.$parent.$emit('addSelectedTech', value.aircraft_id);
                });

                console.dir(self.records_);
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
                        oldValue: oldValue[key].aircraft_id,
                        newValue: value.aircraft_id
                    });
                }
            });
            _.each(oldValue, (value, key) => {
                if(!newValue[key]) {
                    this.$parent.$emit('removeSelectedTech', {
                        oldValue: value.aircraft_id
                    });
                }
            });
        }
    },
    beforeMount() {
        this.getStaff();
        // this.prepareRecords(window.ticket101add.records);
        // this.vehicles = window.vehicles.records;
        // this.vehicles = this.test;
        // console.dir(this.vehicles);
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
