<template>
    <div class="field">

        <div
            class="field is-grouped"
            v-for="item in records_"
            :key="item.id">
            <!--<input-->
            <!--type="hidden"-->
            <!--v-model="item.id"-->
            <!--:name="getName('vehicle_id', item.id)">-->
            <div class="control column is-two-thirds">
                <label :for="getName('fire_department_id', item.id)">ПЧ</label>
                <div class="select">
                    <select
                        required
                        title=""
                        :name="getName('fire_department_id', item.id)"
                        :id="getName('fire_department_id', item.id)"
                        v-model="item.fire_department_id">
                        <option
                            v-for="fire_department in fire_departments_"
                            :key="'fire_department_' + fire_department.id"
                            :value="fire_department.id">{{ fire_department.title }}
                        </option>
                    </select>
                </div>

            </div>
            <div class="control column">
                <label :for="getName('dict_fire_level_id', item.id)">Ранг пожара</label><br>
                <div class="select">
                    <select
                        required
                        title=""
                        :name="getName('dict_fire_level_id', item.id)"
                        :id="getName('dict_fire_level_id', item.id)"
                        v-model="item.dict_fire_level_id">
                        <option
                            v-for="fire_level in fire_levels_"
                            :key="'fire_level_' + fire_level.id"
                            :value="fire_level.id">{{ fire_level.name }}
                        </option>
                    </select>
                </div>

            </div>
            <div class="control column">
                <label :for="getName('department', item.id)">Отделение</label>
                <input
                    required
                    placeholder="Отделение"
                    type="text"
                    class="input"
                    v-model="item.department"
                    :name="getName('department', item.id)">
            </div>

            <div class="control column">
                <label for="">&nbsp;</label>
                <button
                    class="button is-small is-outlined is-danger"
                    @click.prevent="removeItem(item.id)"
                    type="button"
                    title="Удалить">
                    <i class="fa fa-trash"></i>
                    &nbsp;Удалить
                </button>
            </div>

        </div>
        <div class="add_button">
            <button
                class="button is-basic is-main"
                type="button"
                @click.prevent="addEmptyItem()">
                <i class="fa fa-plus"></i>&nbsp;Добавить
            </button>
        </div>
    </div>
</template>

<script>
import moment from 'moment';

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
        fire_departments: {
            type: Array,
            default: () => []
        },
        fire_levels: {
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
            fire_departments_: this.fire_departments,
            fire_levels_: this.fire_levels,
            report_id_: this.report_id
        };
    },
    methods: {
        getName(control, id) {
            return `${control}[${id}]`;
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
                fire_department_id: '',
                dict_fire_level_id: '',
                name: ''
            };
        },
        removeItem(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                this.records_ = this.records_.filter(function (item) {
                    return item.id !== id;
                });
            }
        }

        // getStaff() {
        //     let self = this;
        //     axios.get('/api/101/get-tech', {
        //         params: {
        //             status: self.block_type_,
        //             id: self.report_id_
        //         }
        //     }).then((resp) => {
        //         self.records_ = resp.data;
        //     });
        // }
    },
    beforeMount() {
        // this.getStaff();
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
