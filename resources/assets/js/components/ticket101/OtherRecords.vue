<template>
    <div>
        <div class="add_button">
            <button
                class="button is-small is-outlined is-success"
                type="button"
                @click.prevent="createNewItem()">
                <i class="fa fa-plus"></i>&nbsp;Добавить
            </button>
        </div>

        <div
            class="field is-grouped"
            v-for="item in records"
            :key="item.id">
            <input
                type="hidden"
                v-model="item.id"
                :name="'other_records['+item.id+'][id]'">
            <div class="control">
                <label :for="'other_records['+item.id+'][time]'">Время</label>
                <b-timepicker
                    class="small-time-picker"
                    @change="postItems()"
                    icon="clock"
                    icon-pack="far"
                    :ref="'other_records_time_picker_' + item.id"
                    type="text"
                    :name="'other_records['+item.id+'][time]'"
                    :value="item.time"
                    v-model="item.time">
                    <div
                        class="field is-grouped"
                        style="justify-content: space-between">
                        <p class="control">
                            <a
                                class="button is-primary is-small"
                                @click="setCurrentTimeForItem(item.id)">
                                <b-icon
                                    pack="far"
                                    icon="clock"/>
                                <span>Сейчас</span>
                            </a>
                        </p>
                        <p class="control">
                            <a
                                class="button is-outlined is-small"
                                @click="closeTimePickerByRefName('other_records_time_picker_' + item.id)">
                                <i class="fas fa-check"></i><span>Принять</span>
                            </a>
                        </p>
                    </div>
                </b-timepicker>
            </div>
            <div class="control is-narrow">
                <label :for="'other_records['+item.id+'][comment]'">Ситуация</label>
                <input
                    required
                    placeholder="Комментарий"
                    @change="postItems()"
                    type="text"
                    class="input"
                    v-model="item.comment"
                    :name="'other_records['+item.id+'][comment]'">
            </div>

            <div class="control is-narrow">
                <label :for="'other_records['+item.id+'][trunk_id]'">Ствол</label>
                <div class="select">
                    <select
                        required
                        title="Ствол"
                        @change="postItems()"
                        :name="'other_records['+item.id+'][trunk_id]'"
                        :id="'other_records['+item.id+'][trunk_id]'"
                        v-model="item.trunk_id">
                        <option
                            v-for="trunk in trunks"
                            :key="'trunk_' + trunk.id"
                            :value="trunk.id">{{ trunk.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="control is-narrow">
                <label :for="'other_records['+item.id+'][count]'">Количество</label>
                <input
                    required
                    placeholder="Количество"
                    @change="postItems()"
                    type="number"
                    class="input"
                    v-model="item.count"
                    :name="'other_records['+item.id+'][count]'">
            </div>

            <div class="control is-narrow">
                <label :for="'other_records['+item.id+'][square]'">Площадь</label>
                <input
                    required
                    placeholder="Площадь"
                    @change="postItems()"
                    type="number"
                    class="input"
                    step="0.01"
                    v-model="item.square"
                    :name="'other_records['+item.id+'][square]'">
            </div>

            <div class="control is-narrow">
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
    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import {_} from 'vue-underscore';

export default {
    name: 'OtherRecords',
    data() {
        return {
            records: [],
            trunks: []
        };
    },
    methods: {
        createNewItem() {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
            let card_data = window.ticket101add;

            axios.post('/api/101card/save-other-records', {
                ticket_id: card_data.ticketId
            }).then((response) => {
                let data = response.data;
                console.dir(data);

                this.addItem({
                    id: data.id,
                    time: moment().hour(0).minute(0).toDate(),
                    comment: '',
                    trunk_id: 1,
                    count: 0,
                    square: 0
                });
            });
        },
        postItems() {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
            let card_data = window.ticket101add;

            axios.post('/api/101card/save-other-records', {
                ticket_id: card_data.ticketId,
                records: this.records
            });
        },
        addEmptyItem() {
            this.addItem(this.getEmptyItem());
        },
        addItem(item) {
            this.records.push(item);
        },
        getEmptyItem() {
            return {
                id: moment().valueOf(),
                time: moment().hour(0).minute(0).toDate(),
                comment: '',
                trunk_id: 1,
                count: 0,
                square: 0
            };
        },
        prepareRecords(records) {
            records.map((item) => {
                this.addItem({
                    id: parseInt(item.id),
                    time: moment(item.time, 'HH:mm:ss').toDate(),
                    comment: item.comment,
                    trunk_id: parseInt(item.trunk_id),
                    count: parseInt(item.count),
                    square: parseFloat(item.square)
                });
            });
        },
        closeTimePickerByRefName(refName) {
            if (this.$refs[refName].close) {
                this.$refs[refName].close();
            } else {
                this.$refs[refName][0].close();
            }
        },
        removeItem(id) {
            if (confirm('Вы действительно хотите удалить эту запись?')) {
                this.records = this.records.filter(function (item) {
                    return item.id !== id;
                });
            }
        },
        setCurrentTimeForItem(id) {
            _.where(this.records, {id: id})[0]['time'] = moment().toDate();
            this.closeTimePickerByRefName('other_records_time_picker_' + id);
        }
    },
    beforeMount() {
        this.prepareRecords(window.ticket101add.records);
        this.trunks = window.ticket101add.trunks;
    }
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
