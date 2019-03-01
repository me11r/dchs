<template>
    <div class="section">
        <div class="field">
            <label for="">Дата</label>
            <input v-if="record.id" v-model="record.date" type="text" class="input" disabled>
            <input v-else type="datetime-local" class="input" value="" name="date">
        </div>

        <!--Всего-->
        <div class="field">
            <label for="">Общее количество принятых сообщений (звонков):</label>
            <input type="number" class="input" disabled v-model="total">
        </div>



        <div class="field">
            <label for="">По основной деятельности "112"</label>
            <input type="number" class="input" v-model="record.count_112" name="count_112">
        </div>

        <div class="field">
            <label for="">По линни "101"</label>
            <input type="number" class="input" v-model="record.count_101" name="count_101">
        </div>

        <div class="field">
            <label for="">По линни "102"</label>
            <input type="number" class="input" v-model="record.count_102" name="count_102">
        </div>

        <div class="field">
            <label for="">По линни "103"</label>
            <input type="number" class="input" v-model="record.count_103" name="count_103">
        </div>

        <div class="field">
            <label for="">По линни "109"</label>
            <input type="number" class="input" v-model="record.count_109" name="count_109">
        </div>

        <div class="field">
            <label for="">Информационно – справочного характера</label>
            <input type="number" class="input" v-model="record.count_info" name="count_info">
        </div>

        <div class="field">
            <label for="">Прочее (проверка сотовых телефонов, шалость детей и т.д.)</label>
            <input type="number" class="input" v-model="record.count_other" name="count_other">
        </div>

        <!--<div class="field">-->
            <!--<label for="">По вопросам реагирования на ЧС природного и техногенного характера</label>-->
            <!--<input type="number" class="input" v-model="record.count_emergency" name="count_emergency">-->
        <!--</div>-->

        <div class="field">
            <label for="">Примечание</label>
            <textarea class="textarea" name="note" v-model="record.note" id="note"></textarea>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        name: "CreateEdit",
        props: {
            inputRecord: {
                type: Object,
                default: () => { return null; }
            }
        },
        data() {
            return {
                record: this.inputRecord ? this.inputRecord : {
                    id: null,
                    count_101: 0,
                    count_102: 0,
                    count_103: 0,
                    count_109: 0,
                    count_112: 0,
                    count_info: 0,
                    count_other: 0,
                    note: '',
                    date: '',
                },
            }
        },
        computed: {
            total() {
                return +this.record.count_101
                    + +this.record.count_102
                    + +this.record.count_103
                    + +this.record.count_109
                    + +this.record.count_112
                    + +this.record.count_info
                    + +this.record.count_other;
            }
        },
        created() {
            if(this.record.id) {
                this.record.date = moment(this.record.date).format('DD/MM/YYYY');
            }
        }
    }
</script>

<style scoped>

</style>