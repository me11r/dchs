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
            class="panels"
            v-for="item in records_"
            :key="item.id">

            <div class="field is-grouped">
                <div class="control column is-four-fifths">
                    <label :for="getName('manager_id')">Ф.И.О.</label><br>

                    <div class="select">
                        <select
                            required
                            title=""
                            :name="getName('manager_id')"
                            :id="getName('manager_id')"
                            v-model="item.manager_id">
                            <option
                                v-for="s in getStaffFilter(item.manager_id)"
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
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import Buefy from 'buefy';
import _ from 'lodash';
export default {
    name: 'DistrictManagers',
    props: {
        block_type: {
            type: Number,
            default: 0
        },
        model_id: {
            type: Number,
            default: 0
        },
        records: {
            type: Array,
            default: () => []
        },
        staff: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            records_: this.records,
            staff_: this.staff,
            block_type_: this.block_type,
            model_id_: this.model_id
        };
    },
    components: {
        'b-icon': Buefy['Icon']
    },
    methods: {
        getStaffFilter(selectedId) {
            let scope = this;
            return this.staff_.filter(function (item) {
                return scope.$parent.selectedPersons.indexOf(item.id) === -1 || item.id === selectedId;
            });
        },
        getName(control) {
            return control + `[${this.block_type_}][]`;
        },
        addEmptyItem() {
            this.addItem({
                id: moment().valueOf()
            });
        },
        addItem(item) {
            this.records_.push(item);
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
            axios.get('/api/district-managers/get-staff', {
                params: {
                    city_area_id: self.block_type_,
                    report_id: self.model_id_
                }
            }).then((resp) => {
                self.records_ = resp.data;
                // console.dir(resp.data)
                // console.dir(this.staff_)
                self.$parent.$emit('totalSet', resp.data.length);

                if (self.isActive_ === true) {
                    self.$parent.$emit('totalActiveSet', resp.data.length);
                }

                _.each(self.records_, (value) => {
                    self.$parent.$emit('addSelectedPersons', value.manager_id);
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
                        oldValue: oldValue[key].manager_id,
                        newValue: value.manager_id
                    });
                }
            });
            _.each(oldValue, (value, key) => {
                if (!newValue[key]) {
                    this.$parent.$emit('removeSelectedPersons', {
                        oldValue: value.manager_id
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
