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
            :key="`records_${item.id}`">

            <div class="field is-grouped">
                <div class="control column is-four-fifths">
                    <label :for="getName('staff_id', item.id)">Ф.И.О.</label><br>
                    <div class="select">
                        <select
                            @change="selectStaff"
                            required
                            :name="inactiveType === 'active' ? getName('staff_id', item.id) : getNameInactive('staff_id', item.id)"
                            :id="inactiveType === 'active' ? getName('staff_id', item.id) : getNameInactive('staff_id', item.id)"
                            v-model="item.staff_id">
                            <option
                                v-for="s in getStaffFilter(item.staff_id)"
                                :key="'staff_' + s.id"
                                :value="s.id">{{ s.name }}
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
            <div v-if="inactiveType !== 'active'" class="field is-grouped">
                <div class="field is-grouped">
                    <div class="control column">
                        <label :for="getNameInactive('inactive_type', item.id)">Тип</label><br>

                        <div class="select">
                            <select
                                    required
                                    title=""
                                    :name="getNameInactive('inactive_type',item.id)"
                                    :id="getNameInactive('inactive_type',item.id)"
                                    v-model="item.inactive_type">
                                <option
                                        v-for="s in inactiveTypes"
                                        :key="'inactive_type_' + s.id"
                                        :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>

                    </div>
                    <div class="control column">
                        <v-datepicker-search
                                label="С"
                                :name="getNameInactive('date_from',item.id)"
                                :date-string="item.date_from"
                                @dateChanged="item.date_from = $event"
                                v-model="item.date_from"
                        ></v-datepicker-search>
                    </div>
                    <div class="control column">
                        <v-datepicker-search
                                label="По"
                                :name="getNameInactive('date_to',item.id)"
                                :date-string="item.date_to"
                                @dateChanged="item.date_to = $event"
                                v-model="item.date_to"
                        ></v-datepicker-search>
                    </div>
                </div>
                <div class="field is-grouped">
                    <div class="control column">
                        <label>Комментарий</label>
                        <textarea v-model="item.comment"
                                  class="textarea"
                                  :name="getNameInactive('comment',item.id)"
                                  :id="getNameInactive('comment',item.id)"
                        ></textarea>
                    </div>
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
    name: 'CreateEditStaff',
    props: {
        block_type: {
            type: String,
            default: ''
        },
        inactiveType: {
            type: String,
            default: 'active'
        },
        active: {
            type: Boolean,
            default: true
        },
        modelId: {
            type: Number,
            default: 0
        },
        dateInput: {
            type: String,
            default: ''
        },
        staff_type: {
            type: String,
            default: 'od'
        },
        shiftId: {
            type: Number,
            default: 0
        },
        staff: {
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
            shiftId_: this.shiftId,
            dateInput_: this.dateInput,
            total: 0,
            isActive_: this.active,
            inactiveTypes: [
                {id: 'vacation', name: 'Отпуск'},
                {id: 'business_trip', name: 'Командировка'},
                {id: 'maternity', name: 'Декрет'},
                {id: 'sick_leave', name: 'Больничный'},
                {id: 'other', name: 'Другие причины'},
            ],
        };
    },
    methods: {
        getStaffFilter(selectedId) {
            let scope = this;

            return this.staff_.filter(function (item) {
                return scope.$parent.selectedPersons.indexOf(item.id) === -1 || item.id === selectedId;
            });
        },
        getName(control, id) {
            return 'staff' + `[${this.block_type_}][${control}][${id}]`;
        },
        getNameInactive(control, userId) {
            return 'staff_inactive' + `[${this.block_type_}][${control}][${userId}]`;
        },
        selectStaff($event) {
            console.dir($event.target.value);
        },
        addEmptyItem() {
            this.addItem(this.getEmptyItem());
            this.$parent.$emit('totalChange', 1);
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
            axios.get('/api/112-formation/get-staff', {
                params: {
                    rank: self.block_type_,
                    shift_id: self.shiftId,
                    inactive: self.inactiveType === 'active' ? null : 'inactive',
                    date: self.dateInput_,
                    staffType: self.staff_type,
                }
            }).then((resp) => {
                self.records_ = resp.data;
                self.$parent.$emit('totalSet', resp.data.length);

                if (self.isActive_ === true) {
                    self.$parent.$emit('totalActiveSet', resp.data.length);
                }

                _.each(self.records_, (value) => {
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
