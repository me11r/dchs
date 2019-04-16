<template>
    <div class="field">
        <div class="add_button">
            <button
                class="button is-small is-basic"
                type="button"
                @click.prevent="addEmptyItem()">
                <i class="fa fa-plus"></i>&nbsp;Добавить
            </button>
        </div>

        <div
            class="panels"
            v-for="item, key in records_"
            :key="item.id">

            <div class="field is-grouped">

                <div
                     class="control column">
                    <label class="is-size-7" :for="getName('status', item.id)">Видеорегистратор {{ ++key }}</label><br>
                    <div class="select">
                        <select
                                :name="getName('status', item.id)"
                                :id="getName('status', item.id)"
                                v-model="item.status">
                            <option
                                    v-for="dvr, key in dvrStatuses"
                                    :key="'status_' + key"
                                    :value="key">{{ dvr }}
                            </option>
                        </select>
                    </div>

                </div>

                <div class="control column">
                    <label>Удалить</label>

                    <button
                        class="button is-small is-outlined is-danger square-button-36"
                        @click.prevent="removeItem(item.id)"
                        type="button"
                        title="Удалить">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>

            <div
                class="field is-grouped">
                <div class="control column">
                    <label :for="getName('note', item.id)">Комментарий</label>
                    <textarea
                        class="textarea"
                        v-model="item.note"
                        :name="getName('note', item.id)"
                        :id="getName('note', item.id)"
                        cols="10"
                        rows="1"></textarea>
                </div>

                <div class="control column">
                    <label :for="getName('date_from', item.id)">С</label><br>
                    <input
                        v-model="item.date_from"
                        :name="getName('date_from', item.id)"
                        :id="getName('date_from', item.id)"
                        class="control"
                        type="date">
                </div>

                <div class="control column">
                    <label :for="getName('date_to', item.id)">По</label><br>
                    <input
                        v-model="item.date_to"
                        :name="getName('date_to', item.id)"
                        :id="getName('date_to', item.id)"
                        class="control"
                        type="date">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import _ from 'lodash';

export default {
    name: 'OtherRecords',
    props: {
        report_id: {
            type: String,
            default: ''
        },
        fire_dep_id: {
            type: String,
            default: ''
        },
        records: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            records_: this.records,
            fire_dep_id_: this.fire_dep_id,
            report_id_: this.report_id,
            dvrStatuses: [
                'неисправен',
                'исправен',
            ],
            month_names: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            day_names: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']

        };
    },
    methods: {
        getName(control, id) {
            return `dvr[${control}][${id}]`;
        },
        defaultDateFormatter: (date) => { moment(date).format('DD/MM/YYYY'); },
        defaultDateFormatter2: (date) => { return date.toLocaleDateString('ru-RU'); },
        addEmptyItem() {
            this.addItem(this.getEmptyItem());
        },
        addItem(item) {
            this.records_.push(item);
        },
        getEmptyItem() {
            return {
                id: moment().valueOf(),
                date_from: null,
                date_to: null,
                status: 0,
                note: ''
            };
        },
        prepareRecords(records) {
            records.map((item) => {
                this.addItem({
                    date_begin: moment(item.date_from),
                    date_end: moment(item.date_to)
                });
            });
        },
        removeItem(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                this.records_ = this.records_.filter(function (item) {
                    return item.id !== id;
                });
            }
        },
    },
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
