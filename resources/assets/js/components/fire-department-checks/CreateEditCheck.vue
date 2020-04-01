<template>
    <div>
        <div class="add_button">
            <button
                class="button is-small is-basic"
                type="button"
                @click.prevent="addEmptyItem()">
                <i class="fa fa-plus"></i>&nbsp;Добавить
            </button>
        </div>
        <div
            v-for="(block, index) in firedeptBlocks"
            :key="'block_' + index">
            <create-edit-check-item
                :block="block"
                :fire-dept-id="fireDeptId"
                :fire-depts="fireDepts_"/>

            <div v-if="block.editable"
                class="field is-full"
                style="float: right;">
                <button
                    class="button is-small is-danger square-button-36"
                    type="button"
                    @click="dropById(block.id)"
                    title="Удалить">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

            <br>
            <hr>
        </div>

    </div>
</template>

<script>
import moment from 'moment';
import CreateEditCheckItem from './CreateEditCheckItem';
import _ from 'lodash';
export default {
    name: 'CreateEditCheck',
    components: {
        CreateEditCheckItem,
        'timepicker-input': require('../TimepickerInput')
    },
    props: {
        fireDepts: {
            type: Array,
            default: () => []
        },
        staff: {
            type: Array,
            default: () => []
        },
        records: {
            type: Object | Array,
            default: () => []
        },
        type: {
            type: String,
            required: true
        },
        fireDeptId: {
            type: Number,
            default: null
        }
    },
    data: function () {
        return {
            fireDepts_: this.fireDepts,
            firedeptBlocks: [],
            staff_: this.staff,
            record_: this.record ? this.record : {
                id: 1,
                fire_department_id: 1,
                note: '',
                editable: true,
                responsible_person: '',
                time_begin: '00:00:00',
                time_end: '00:00:00'
            }

        };
    },
    watch: {
    },
    methods: {
        dropById(id) {
            this.firedeptBlocks = this.firedeptBlocks.filter((item) => {
                return item.id !== id;
            });
        },
        addEmptyItem() {
            this.firedeptBlocks.push({
                id: moment().valueOf(),
                fire_department_id: this.fireDeptId,
                note: '',
                editable: true,
                responsible_person: '',
                time_begin: '00:00:00',
                time_end: '00:00:00',
                is_dspt: this.type === 'dspt'
            });
        }
    },
    created() {
        let records = !Array.isArray(this.records) ? _.toArray(this.records) : this.records;
        records.forEach(item => item.editable = false);

        this.firedeptBlocks = records.map((item) => {
            return item;
        });
    }
};
</script>

<style scoped>

</style>
