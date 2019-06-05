<template>
    <div class="container">
        <div class="panel">
            <div
                class="box"
                style="margin-top: 20px">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title">{{ '/reports/101/vehicles.title'|trans }}</h4><!--Отчет по строевой записке техники-->
                    </div>
                    <div class="level-right has-text-right">
                        <button
                            class="button is-primary "
                            @click.prevent="print()"><i class="fas fa-print"></i>&nbsp;{{ 'print'|trans }}</button><!--Печать-->
                    </div>
                </div>
                <br>
                <form>

                    <div class="field">
                        <label for="vehicles">{{ 'number'|trans }}, {{ 'type'|trans }}</label><!--Номер, тип-->
                        <select
                            @change="selectName"
                            class="select"
                            name=""
                            id="vehicles">
                            <option
                                v-model="selected_id"
                                v-for="vehicle in vehicles_"
                                :value="vehicle.id"
                                :key="vehicle.id">{{ vehicle.name }} - {{ vehicle.number }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="">{{ 'date_begin'|trans }}</label><!--Начало периода-->
                        <input
                            @blur="selectPeriod"
                            v-model="date_begin_"
                            type="date">
                    </div>
                    <div class="field">
                        <label for="">{{ 'date_end'|trans }}</label><!--Конец периода-->
                        <input
                            @blur="selectPeriod"
                            v-model="date_end_"
                            class="date"
                            type="date">
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>{{ '/reports/101/vehicles.action'|trans }}</td><!--В боевом расчете-->
                                <td>{{ '/reports/101/vehicles.repair'|trans }}</td><!--На ремонте-->
                                <td>{{ '/reports/101/vehicles.reserve'|trans }}</td><!--В резерве-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ report_summary.active_count }}</td>
                                <td>{{ report_summary.repair_count }}</td>
                                <td>{{ report_summary.reserve_count }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--<div class="buttons has-text-right is-grouped is-right" style="">-->
                    <!--<button type="submit" class="button is-success"><i class="fas fa-check"></i>&nbsp;Сохранить</button>-->
                    <!--</div>-->
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'Report101Vehicles',
    props: {
        vehicles: {
            type: Array,
            default: () => []
        },
        date_begin: {
            type: String,
            default: ''
        },
        date_end: {
            type: String,
            default: ''
        }
    },
    data: function () {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            vehicles_: this.vehicles,
            selected_id: '',
            date_begin_: this.date_begin,
            date_end_: this.date_end,
            report_summary: {},
            selected: null
        };
    },
    methods: {
        selectName(event) {
            this.selected_id = event.target.value;

            console.dir(this.selected_id);

            this.post_data();
        },
        print() {
            window.print();
        },
        post_data() {
            let self = this;
            axios.post('/reports/101/vehicles', {
                date_begin: self.date_begin_,
                date_end: self.date_end_,
                vehicle_id: self.selected_id
            }).then((resp) => {
                self.report_summary = resp.data;
                console.dir(self.report_summary);
            });
        },

        selectPeriod() {
            this.post_data();
        },

        onSelectVehicle: function (selected) {
            this.hid = selected.id;
            this.name = selected.name;
        }
    },
    created () {
        const token = document.head.querySelector('meta[name="csrf-token"]');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
    }
};
</script>

<style scoped>

</style>
