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

            <div class="control column is-four-fifths">
                <label :for="getName('staff_id', item.id)">Ф.И.О.</label><br>
                <div class="select">
                    <select
                            required
                            title=""
                            :name="getName('staff_id', item.id)"
                            :id="getName('staff_id', item.id)"
                            v-model="item.staff_id">
                        <option
                                v-for="s in staff"
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
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import Buefy from 'buefy';

export default {
    name: 'OtherRecords',
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
            staff_: this.staff,
            model_id_: this.modelId,
            total: 0,
            isActive_: this.active,
        };
    },
    components: {
        'b-icon': Buefy['Icon']
    },
    methods: {
        getName(control, id){
            return 'staff' + `[${this.block_type_}]` + `[${id}]`;
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
                    square: parseFloat(item.square)
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
                self.$parent.$emit('totalSet', resp.data.length);

                if (self.isActive_ === true) {
                    self.$parent.$emit('totalActiveSet', resp.data.length);
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
