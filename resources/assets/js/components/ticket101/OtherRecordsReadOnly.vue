<template>
    <div>
        <div
            class="field is-grouped"
            v-for="item in records"
            :key="item.id">

            <div class="control">
                <label :for="'other_records['+item.id+'][trunk_id]'">Наименование ствола</label>
                <div class="select">
                    <select
                        title="Ствол"
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

            <div class="control">
                <label :for="'other_records['+item.id+'][count]'">Количество</label>
                <input
                    placeholder="Количество"
                    type="number"
                    class="input"
                    v-model="item.count">
            </div>

            <!--<div class="control is-narrow">-->
                <!--<label :for="'other_records['+item.id+'][square]'">Площадь</label>-->
                <!--<input-->
                    <!--placeholder="Площадь"-->
                    <!--type="number"-->
                    <!--class="input"-->
                    <!--step="0.01"-->
                    <!--v-model="item.square">-->
            <!--</div>-->
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import Buefy from 'buefy';
import {_} from 'vue-underscore';

export default {
    name: 'OtherRecords',
    data() {
        return {
            records: [],
            trunks: []
        };
    },
    components: {
        'b-icon': Buefy['Icon'],
        'b-timepicker': Buefy['Timepicker']
    },
    methods: {
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
        this.prepareRecords(window.ticket101add.other_records_unique);
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
