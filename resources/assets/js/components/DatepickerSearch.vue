<template>
    <div>
        <b-field :label="label">
            <b-datepicker
                v-model="date_"
                placeholder="Нажмите для выбора"
                :date-formatter="(date) => formatDate(date)"
                :month-names="months"
                :first-day-of-week="1"
                :name="name_"
                :disabled="disabled"
                :day-names="days"
                @input="$emit('dateChanged',date_)"
                icon="calendar-today"/>
        </b-field>
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
        disabled: {
            type: Boolean,
            default: false,
        },
        position: {
            type: String,
            default: 'is-bottom-right'
        },
        name: {
            type: String,
            default: ''
        },
        label: {
            type: String,
            default: 'Выберите дату'
        },
        dateString: {
            type: String,
            default: ''
        },
        date: {
            type: Date,
            default: () => {return new Date}
        },
    },
    data: function () {
        return {
            formAction_: this.formAction,
            months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            days: ["Вс","Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
            date_: this.date,
            name_: this.name,
        };
    },
    methods: {
        changeDate() {
            alert();
        },
        formatDate(date) {
            return moment(date).format('DD-MM-YYYY');
        }

    },
    created() {
        if (this.dateString !== '') {
            this.date_ = moment(this.dateString).toDate();
        }
    }
};
</script>

<style scoped>

</style>
