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
        <b-field v-if="includeTime">
            <b-timepicker
                class="small-time-picker"
                icon="clock"
                icon-pack="far"
                :ref="`${name_}_time`"
                type="text"
                @input="$emit('dateChanged',date_)"
                :disabled="disabled"
                :value="date_"
                v-model="date_">
                <div
                    class="field is-grouped"
                    style="justify-content: space-between">
                    <p class="control">
                        <a
                            class="button is-primary is-small"
                            @click="() => {date_ = new Date(); closeTimePickerByRefName(`${name_}_time`); }">
                            <b-icon
                                pack="far"
                                icon="clock"/>
                            <span>Сейчас</span>
                        </a>
                    </p>
                    <p class="control">
                        <a
                            class="button is-outlined is-small"
                            @click="closeTimePickerByRefName(`${name_}_time`)">
                            <i class="fas fa-check"></i><span>Принять</span>
                        </a>
                    </p>
                </div>
            </b-timepicker>
        </b-field>
        <input
            v-if="includeHidden"
            type="hidden"
            :name="name_"
            :value="dateYYYYMMDD">
    </div>

</template>

<script>
import moment from 'moment';
import {globalBus} from '../scripts/global-bus';
export default {
    name: 'DatepickerSearch',
    props: {
        formAction: {
            type: String,
            default: ''
        },
        disabled: {
            type: Boolean,
            default: false
        },
        includeTime: {
            type: Boolean,
            default: false
        },
        includeHidden: {
            type: Boolean,
            default: false
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
            type: String | Date,
            default: ''
        },
        date: {
            type: Date,
            default: () => new Date()
        }
    },
    data: function () {
        return {
            formAction_: this.formAction,
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            days: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            date_: this.date,
            name_: this.name
        };
    },
    methods: {
        formatDate(date) {
            let format = 'DD-MM-YYYY';
            if (this.includeTime) {
                format = 'DD-MM-YYYY HH:mm';
            }
            return moment(date).format(format);
        },
        closeTimePickerByRefName(refName) {
            if (this.$refs[refName].close) {
                this.$refs[refName].close();
            } else {
                this.$refs[refName][0].close();
            }
        }

    },
    computed: {
        dateYYYYMMDD() {
            return this.date_ ? moment(this.date_).format('YYYY-MM-DD') : null;
        }
    },
    created() {
        if (this.dateString !== '' && this.dateString !== null) {
            this.date_ = moment(this.dateString).toDate();
        }

        // костыль для карточки 112
        // дата в компонент передается после отрисовки карты
        globalBus.$on('dateIsReady', (date) => {
            this.date_ = date;
        });
    }
};
</script>

<style scoped>

</style>
