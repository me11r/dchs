<template>
    <div>
        <!--<form-->
            <!--id="date-form"-->
            <!--action="formAction_">-->
            <b-field :label="label">
                <b-datepicker
                    v-model="date_"
                    placeholder="Click to select..."
                    :date-formatter="(date) => formatDate(date)"
                    :month-names="months"
                    :first-day-of-week="1"
                    :day-names="days"
                    @input="$emit('dateChanged',date_)"
                    icon="calendar-today"/>
            </b-field>
        <!--</form>-->
    </div>

</template>

<script>
import moment from 'moment';
export default {
    name: 'DatepickerSearch',
    props: {
        formAction: {
            type: String,
            default: ''
        },
        label: {
            type: String,
            default: 'Выберите дату'
        },
        date: {
            type: Date,
            default: () => {return new Date}
        },
    },
    data: function () {
        return {
            formAction_: this.formAction,
            months: ["Январь", "Февраль", "Март", "Апреть", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            days: ["Вс","Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
            date_: this.date,
        };
    },
    methods: {
        changeDate() {
            alert();
        },
        formatDate(date) {
            return moment(date).format('DD-MM-YYYY');
        }

    }
};
</script>

<style scoped>

</style>
