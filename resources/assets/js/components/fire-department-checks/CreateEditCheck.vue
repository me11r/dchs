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
        <div v-for="block in firedeptBlocks">
            <div class="columns" :key="block.id">
                <div class="column">
                    <div class="field">
                        <label for="fire_department_id">Подразделение</label><br>
                        <select class="select" v-model="block.fire_department_id" :name="setName('fire_department_id', block)" :id="setName('fire_department_id', block)" required>
                            <option :value="item.id" v-for="item in fireDepts_">{{ item.title }}</option>
                        </select>
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <label for="time_begin">Время начала</label>
                        <timepicker-input :value="block.time_begin" v-model="block.time_begin" :id="setName('time_begin', block)" :name="setName('time_begin', block)"></timepicker-input>
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <label for="time_end">Время окончания</label>
                        <timepicker-input :value="block.time_end" v-model="block.time_end" :id="setName('time_end', block)" :name="setName('time_end', block)"></timepicker-input>
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <label for="responsible_person">Ответственное лицо</label><br>
                        <input type="text"
                               v-model="block.responsible_person"
                               :id="setName('responsible_person', block)"
                               :name="setName('responsible_person', block)"
                               class="input">
                    </div>
                </div>

            </div>

            <div class="field">
                <label for="note">Примечание</label>
                <textarea v-model="block.note" class="textarea" :name="setName('note', block)" :id="setName('note', block)"></textarea>
            </div>
            <div class="field is-full" style="float: right;">
                <button
                        class="button is-small is-danger square-button-36"

                        type="button"
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
                firedeptBlocks: [],
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
            addEmptyItem(){
                this.firedeptBlocks.push({
                    id: moment().valueOf(),
                    fire_department_id: 1,
                    note: '',
                    responsible_person: '',
                    time_begin: '00:00:00',
                    time_end: '00:00:00'
                });
            },
            setName(item, block){
                return `item[${block}]`;
            }
        },
        created(){
            console.dir(this.record_)
            //todo: продолжить эпопею (деление на ДСПТ и ПЧ (нужны миграции)
            // this.firedeptBlocks = this.records_.filter((item) => {
            //     return item.type === 'fireDept';
            // });

        }
    }
</script>

<style scoped>

</style>