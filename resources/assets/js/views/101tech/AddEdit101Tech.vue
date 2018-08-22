<template>
    <div class="field">
        <div class="add_button">
            <button
                class="button is-small is-outlined is-success"
                type="button"
                @click.prevent="addEmptyItem()">
                <i class="fa fa-plus"></i>&nbsp;Добавить
            </button>
        </div>

        <div
            class="field is-grouped"
            v-for="item in records_"
            :key="item.id">
            <!--<input-->
                <!--type="hidden"-->
                <!--v-model="item.id"-->
                <!--:name="getName('vehicle_id', item.id)">-->
            <div class="control column is-four-fifths">
                <label :for="getName('vehicle_id', item.id)">Тип основного пожарного а/М</label><br>
                <div class="select">
                    <select
                            required
                            title=""
                            :name="getName('vehicle_id', item.id)"
                            :id="getName('vehicle_id', item.id)"
                            v-model="item.vehicle_id">
                        <option
                                v-for="vehicle in vehicles"
                                :key="'vehicle_' + vehicle.id"
                                :value="vehicle.id">{{ vehicle.name }}
                        </option>
                    </select>
                </div>

            </div>
            <div class="control column">
                <label :for="getName('department', item.id)">Отделение</label>
                <input
                    required
                    placeholder="Отделение"
                    type="number"
                    class="input"
                    max="8"
                    min="1"
                    v-model="item.department"
                    @change="departmentCheck($event)"
                    :name="getName('department', item.id)">
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
    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import Buefy from 'buefy';
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
        },
    },
    data() {
        return {
            records_: this.records,
            trunks: [],
            block_type_: this.block_type,
            vehicles_: this.vehicles,
            report_id_: this.report_id,
        };
    },
    components: {
        'b-icon': Buefy['Icon']
    },
    methods: {
        getName(control, id){
            return 'tech'+`[${this.block_type_}]`+`[${control}][${id}]`;
        },
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
                    id: parseInt(item.id),
                    comment: item.comment,
                    trunk_id: parseInt(item.trunk_id),
                    count: parseInt(item.count),
                    square: parseFloat(item.square)
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

        getTech() {
            let self = this;
            axios.get('/api/101/get-tech', {
                params: {
                    status: self.block_type_,
                    id: self.report_id_
                }
            }).then((resp) => {
                self.records_ = resp.data;
            });
        }
    },
    beforeMount() {
        this.getTech();
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
