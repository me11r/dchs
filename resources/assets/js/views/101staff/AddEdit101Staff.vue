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
                <div class="control column " :class="block_type_ !== 'sick_leave' ? 'is-four-fifths' : ''">
                    <label :for="getName('staff_id', item.id)">Ф.И.О.</label><br>
                    <div class="select">
                        <select
                            @change="selectStaff"
                            required
                            title=""
                            :name="getName('staff_id', item.id)"
                            :id="getName('staff_id', item.id)"
                            v-model="item.staff_id">
                            <option
                                v-for="s in getStaffFilter(item.staff_id)"
                                :key="'staff_' + s.id"
                                :value="s.id">{{ s.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="control column " v-if="block_type_ === 'trainee'">
                    <label :for="getName('trainee_type', item.id)">Должность</label><br>
                    <div class="select">
                        <select
                            required
                            title=""
                            :name="getName('trainee_type', item.id)"
                            :id="getName('trainee_type', item.id)"
                            v-model="item.trainee_type">
                            <option
                                v-for="type in traineeTypes()"
                                :key="'type_' + type.type"
                                :value="type.type">{{ type.title }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="control column" v-if="block_type_ === 'sick_leave'">
                    <label :for="getName('guard_number_id', item.id)">Номер караула</label>
                    <div class="select">
                        <select
                            required
                            title=""
                            :name="getName('guard_number_id', item.id)"
                            :id="getName('guard_number_id', item.id)"
                            v-model="item.guard_number_id">
                            <option
                                v-for="s in guardNumbers_"
                                :key="'guard_number_id_' + s.id"
                                :value="s.id">{{ s.name }}
                            </option>
                        </select>
                    </div>

                </div>

                <div class="control column">
                    <label>Удалить</label>

                    <button
                        class="button is-small is-danger square-button-36"
                        @click.prevent="removeItem(item.id)"
                        type="button"
                        title="Удалить">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            <div
                v-if="block_type_ === 'business_trip' || block_type_ === 'sick' || block_type_ === 'dispatchers' || block_type_ === 'sick_leave'"
                class="field is-grouped">
                <div class="control column is-half">
                    <label :for="getName('comment', item.id)">Комментарий</label>
                    <textarea
                        class="textarea"
                        v-model="item.comment"
                        :name="getName('comment', item.id)"
                        :id="getName('comment', item.id)"
                        cols="10"
                        rows="1"></textarea>
                </div>
                <div class="control column" v-if="block_type_ === 'business_trip' || block_type_ === 'sick' || block_type_ === 'sick_leave'">
                    <label :for="getName('date_from', item.id)">С</label><br>
                    <input
                        v-model="item.date_from"
                        :name="getName('date_from', item.id)"
                        :id="getName('date_from', item.id)"
                        class="control"
                        type="date">
                </div>
                <div class="control column" v-if="block_type_ === 'business_trip' || block_type_ === 'sick' || block_type_ === 'sick_leave'">
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
import _ from 'lodash';

export default {
    name: 'Add101Staff',
    props: {
        block_type: {
            type: String,
            default: ''
        },
        active: {
            type: Boolean,
            default: true
        },
        modelId: {
            type: Number,
            default: 0
        },
        staff: {
            type: Array,
            default: () => []
        },
        guardNumbers: {
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
            staff_: this.staff,
            guardNumbers_: this.guardNumbers,
            model_id_: this.modelId,
            total: 0,
            isActive_: this.active
        };
    },
    methods: {
        getName(control, id) {
            return 'staff' + `[${this.block_type_}]` + `[${control}][${id}]`;
        },
        getStaffFilter(selectedId) {
            let scope = this;
            return this.staff_.filter(function (item) {
                return scope.$parent.selectedPersons.indexOf(item.id) === -1 || item.id === selectedId;
            });
        },
        selectStaff($event) {
            console.dir($event.target.value);
        },
        addEmptyItem() {
            this.addItem(this.getEmptyItem());

            if (this.block_type_ !== 'sick_leave') {
                this.$parent.$emit('totalChange', 1);
            }

            if (this.isActive_ === true) {
                this.$parent.$emit('activeChange', 1);
            }
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
        traineeTypes(){
            return [
                {type:'head_guards', title:'Нач. караулов'},
                {type:'commander_squads', title:'Ком. отделений'},
                {type:'drivers', title:'Водители'},
                {type:'dispatchers', title:'Диспетчера'},
                {type:'privates', title:'Ряд. состав'},
            ];
        },
        getEmptyItem() {
            return {
                id: moment().valueOf(),
                department: '',
                name: '',
                count: 0,
                square: 0,
                staff_id: 0,
                trainee_type: '',
            };
        },
        prepareRecords(records) {
            records.map((item) => {
                this.addItem({
                    id: parseInt(item.id),
                    comment: item.comment,
                    trunk_id: parseInt(item.trunk_id),
                    count: parseInt(item.count),
                    square: parseFloat(item.square),
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
                this.$parent.$emit('totalChange', -1);

                if (this.isActive_ === true) {
                    this.$parent.$emit('activeChange', -1);
                }
            }
        },

        getStaff() {
            let self = this;
            axios.get('/api/101/get-staff', {
                params: {
                    rank: self.block_type_,
                    id: self.model_id_
                }
            }).then((resp) => {
                self.records_ = resp.data;
                if (this.block_type_ !== 'sick_leave') {
                    self.$parent.$emit('totalSet', resp.data.length);
                }

                if (self.isActive_ === true) {
                    self.$parent.$emit('totalActiveSet', resp.data.length);
                }

                _.each(this.records_, (value) => {
                    self.$parent.$emit('addSelectedPersons', value.staff_id);
                });
            });
        }
    },
    computed: {
        clonedItems() {
            return JSON.parse(JSON.stringify(this.records_));
        }
    },
    watch: {
        clonedItems(newValue, oldValue) {
            _.each(newValue, (value, key) => {
                if (oldValue[key]) {
                    this.$parent.$emit('changeSelectedPersons', {
                        oldValue: oldValue[key].staff_id,
                        newValue: value.staff_id
                    });
                }
            });
            _.each(oldValue, (value, key) => {
                if (!newValue[key]) {
                    this.$parent.$emit('removeSelectedPersons', {
                        oldValue: value.staff_id
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
