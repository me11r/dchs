<template>
    <div class="section">
        <div class="field">
            <label for="">{{'date'|trans}}</label><!--Дата-->
            <input v-if="record.id" v-model="record.date" type="text" class="input" disabled>
            <input v-else type="datetime-local" class="input" value="" name="date">
        </div>

        <!--Всего-->
        <div class="field">
            <label for="">{{ '/reports/call-infos.total'|trans }}:</label><!--Общее количество принятых сообщений (звонков)-->
            <input type="number" class="input" disabled v-model="total">
        </div>



        <div class="field">
            <label for="">{{ '/reports/call-infos.112'|trans }}</label><!--По основной деятельности "112"-->
            <input type="number" class="input" v-model="record.count_112" name="count_112">
        </div>

        <div class="field">
            <label for="">{{ '/reports/call-infos.line'|trans({line: 101}) }}</label><!--По линии "101"-->
            <input type="number" class="input" v-model="record.count_101" name="count_101">
        </div>

        <div class="field">
            <label for="">{{ '/reports/call-infos.line'|trans({line: 102}) }}</label><!--По линии "102"-->
            <input type="number" class="input" v-model="record.count_102" name="count_102">
        </div>

        <div class="field">
            <label for="">{{ '/reports/call-infos.line'|trans({line: 103}) }}</label><!--По линии "103"-->
            <input type="number" class="input" v-model="record.count_103" name="count_103">
        </div>

        <div class="field">
            <label for="">{{ '/reports/call-infos.line'|trans({line: 109}) }}</label><!--По линии "109"-->
            <input type="number" class="input" v-model="record.count_109" name="count_109">
        </div>

        <div class="field">
            <label for="">{{ '/reports/call-infos.info'|trans }}</label><!--Информационно – справочного характера-->
            <input type="number" class="input" v-model="record.count_info" name="count_info">
        </div>

        <div class="field">
            <label for="">{{ '/reports/call-infos.other'|trans }}</label><!--Прочее (проверка сотовых телефонов, шалость детей и т.д.)-->
            <input type="number" class="input" v-model="record.count_other" name="count_other">
        </div>

        <!--<div class="field">-->
            <!--<label for="">По вопросам реагирования на ЧС природного и техногенного характера</label>-->
            <!--<input type="number" class="input" v-model="record.count_emergency" name="count_emergency">-->
        <!--</div>-->

        <div class="field">
            <label for="">{{ 'note'|trans }}</label><!--Примечание-->
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