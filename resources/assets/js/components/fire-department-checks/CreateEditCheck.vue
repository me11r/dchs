<template>
    <div>
        <div class="columns">
            <div class="column">
                <div class="field">
                    <label for="fire_department_id">Подразделение</label><br>
                    <select class="select" v-model="record_.fire_department_id" name="fire_department_id" id="fire_department_id" required>
                        <option :value="item.id" v-for="item in fireDepts_">{{ item.title }}</option>
                    </select>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label for="time_begin">Время начала</label>
                    <timepicker-input :value="record_.time_begin" v-model="record_.time_begin" id="time_begin" name="time_begin"></timepicker-input>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label for="time_end">Время окончания</label>
                    <timepicker-input :value="record_.time_end" v-model="record_.time_end" id="time_end" name="time_end"></timepicker-input>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label for="responsible_person">Ответственное лицо</label><br>
                    <input type="text"
                           v-model="record_.responsible_person"
                           id="responsible_person"
                           name="responsible_person"
                           class="input">
                </div>
            </div>

        </div>

        <div class="field">
            <label for="note">Примечание</label>
            <textarea v-model="record_.note" class="textarea" name="note" id="note"></textarea>
        </div>

    </div>
</template>

<script>
    export default {
        name: "CreateEditCheck",
        components: {
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
            record: {
                type: Object,
                default: () => {}
            },
        },
        data: function () {
            return {
                fireDepts_: this.fireDepts,
                staff_: this.staff,
                record_: this.record ? this.record : {
                    id: 1,
                    fire_department_id: 1,
                    note: '',
                    responsible_person: '',
                    time_begin: '00:00:00',
                    time_end: '00:00:00'
                },

            }
        },
        watch: {
        },
        methods: {

        },
        created(){
            console.dir(this.record_)
        }
    }
</script>

<style scoped>

</style>