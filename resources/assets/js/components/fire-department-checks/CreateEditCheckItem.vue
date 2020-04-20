<template>
    <div>
        <div class="columns">
            <div class="column">
                <input
                    type="hidden"
                    :name="setName('id', block)"
                    :value="block.id">
                <div class="field">
                    <label>Подразделение</label><br>
                    <select
                        :disabled="!block.editable"
                        class="select"
                        v-model="block.fire_department_id"
                        title="Подразделение"
                        :name="setName('fire_department_id', block)"
                        :id="setName('fire_department_id', block)"
                        required>
                        <option
                            :value="item.id"
                            :key="'fire_dept_' + item.id + '_' + subIndex"
                            v-for="(item, subIndex) in fireDeptsByDivision">{{ item.title }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label>Время начала</label>
                    <timepicker-input
                        :value="block.time_begin"
                        :disabled="!block.editable"
                        v-model="block.time_begin"
                        :id="setName('time_begin', block)"
                        :name="setName('time_begin', block)"/>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label>Время окончания</label>
                    <timepicker-input
                        :value="block.time_end"
                        :disabled="!block.editable"
                        v-model="block.time_end"
                        :id="setName('time_end', block)"
                        :name="setName('time_end', block)"/>
                </div>
            </div>

            <div class="column">
                <div class="field">
                    <label>Ответственное лицо</label><br>
                    <input
                        :disabled="!block.editable"
                        type="text"
                        v-model="block.responsible_person"
                        title="Ответственное лицо"
                        :id="setName('responsible_person', block)"
                        :name="setName('responsible_person', block)"
                        class="input">
                </div>
            </div>
        </div>

        <div class="field">
            <label>Примечание</label>
            <input
                :disabled="!block.editable"
                type="text"
                v-model="block.note"
                class="input"
                title="Примечание"
                :name="setName('note', block)"
                :id="setName('note', block)">
        </div>

        <input
            type="hidden"
            :name="setName('is_dspt', block)"
            :value="block.is_dspt ? 1 : 0">
    </div>
</template>

<script>
export default {
    props: {
        block: {
            type: Object,
            required: true
        },
        hasEditRight: {
            type: Boolean,
            default: false
        },
        fireDepts: {
            type: Array,
            required: true
        },
        fireDeptId: {
            type: Number,
            default: null
        }
    },
    name: 'CreateEditCheckItem',
    methods: {
        setName(item, block) {
            return `items[${block.id}][${item}]`;
        }
    },
    computed: {
        fireDeptsByDivision() {
            if(this.fireDeptId) {
                if (this.block.created_at) {
                    return window._.filter(this.fireDepts, (item) => {
                        return item.id === this.block.fire_department_id;
                    });
                } else {
                    return window._.filter(this.fireDepts, (item) => {
                        return item.id === this.fireDeptId;
                    });
                }
            }
            else {
                return this.fireDepts;
            }


        }
    },
    created() {
    }
};
</script>

<style scoped>

</style>
