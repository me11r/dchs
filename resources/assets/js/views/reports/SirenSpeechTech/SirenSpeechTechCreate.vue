<template>
    <div class="columns">
        <table class="table table-mobile-sort">
            <tr>
                <td>Дата</td>
                  <td>
                    <input v-if="record_.id" v-model="record_.created_at" type="text" class="input"  disabled>
                    <input v-else type="date" class="input" value="" name="date">
                 </td>
            </tr>
            <tr>
                <td>{{ '/reports/siren-speeches.total'|trans }}</td><!--Всего-->
                <td><input readonly type="text" class="input" v-model="total"></td>
            </tr>
            <tr>
                <td>{{ '/reports/siren-speeches.motor'|trans }}</td><!--Моторные-->
                <td><input type="number" class="input" name="motor" v-model="record_.motor"></td>
            </tr>
            <tr>
                <td>{{ '/reports/siren-speeches.sst'|trans }}</td><!--СРУ-->
                <td><input type="number" class="input" name="sst" v-model="record_.sst"></td>
            </tr>
            <tr>
                <td>{{ '/reports/siren-speeches.demounted'|trans }}</td><!--Демонтированные-->
                <td>
                    <div class="field">
                        <input type="number" class="input" name="demounted" v-model="record_.demounted">
                    </div>
                    <div class="field" v-for="item in demounted">
                        <div class="columns" :key="item.id">
                            <div class="column">
                                <input type="text" v-model="item.text" class="input" name="demounted_text[]">
                            </div>
                            <div class="column">
                                <button @click.prevent="deleteDemounted($event, item.id)" class="button is-danger"><span class="fa fa-times"></span></button>
                            </div>
                        </div>
                    </div>
                </td>
                <td><button @click.prevent="addDemounted()" class="button is-info">{{ '/reports/siren-speeches.add'|trans }}</button></td><!--Добавить-->
            </tr>
            <tr>
                <td>{{ '/reports/siren-speeches.broken'|trans }}</td><!--В не рабочем состоянии-->
                <td>
                    <div class="field">
                        <input type="number" name="broken" class="input" v-model="record_.broken">
                    </div>
                    <div class="field" v-for="item in broken">
                        <div class="columns" :key="item.id">
                            <div class="column">
                                <input type="text" v-model="item.text" class="input" name="broken_text[]">
                            </div>
                            <div class="column">
                                <button @click.prevent="deleteBroken($event, item.id)" class="button is-danger"><span class="fa fa-times"></span></button>
                            </div>
                        </div>
                    </div>
                </td>
                <td><button @click.prevent="addBroken()" class="button is-info">{{ '/reports/siren-speeches.add'|trans }}</button></td><!--Добавить-->
            </tr>
            <tr>
                <td>{{ '/reports/siren-speeches.inactive'|trans }}</td><!--Не активные-->
                <td>
                    <div class="field">
                        <input type="number" name="inactive" class="input" v-model="record_.inactive">
                    </div>
                    <div class="field" v-for="item in inactive">
                        <div class="columns" :key="item.id">
                            <div class="column">
                                <input type="text" v-model="item.text" class="input" name="inactive_text[]">
                            </div>
                            <div class="column">
                                <button @click.prevent="deleteInactive($event, item.id)" class="button is-danger"><span class="fa fa-times"></span></button>
                            </div>
                        </div>
                    </div>
                </td>
                <td><button @click.prevent="addInactive()" class="button is-info">{{ '/reports/siren-speeches.add'|trans }}</button></td><!--Добавить-->
            </tr>
        </table>
    </div>
</template>

<script>
    import moment from 'moment';
    import axios from 'axios';
    import _ from 'lodash';
    export default {
        name: "SirenSpeechTechCreate",
        props: {
            record: {
                type: Object,
                default: function () {
                    return {};
                }
            },
        },
        data: function () {
            return {
                demounted: [],
                record_: this.record,
                broken: [],
                inactive: [],
                randomNum: moment().valueOf(),
            }
        },
        methods: {
            addDemounted(){
                if(this.record_.demounted !== undefined && this.record_.demounted !== null && this.record_.demounted !== 0){
                    for(let i = 0; i < this.record_.demounted; i++){

                        this.demounted.push({
                            id: this.random(),
                            text: '',
                        });
                    }
                }
                else{
                    this.demounted.push({
                        id: moment().valueOf(),
                        text: '',
                    });
                }
            },
            addBroken(){
                if(this.record_.broken !== undefined && this.record_.broken !== null && this.record_.broken !== 0){
                    for(let i = 0; i < this.record_.broken; i++){

                        this.broken.push({
                            id: this.random(),
                            text: '',
                        });
                    }
                }
                else{
                    this.broken.push({
                        id: moment().valueOf(),
                        text: '',
                    });
                }
            },
            addInactive(){
                if(this.record_.inactive !== undefined && this.record_.inactive !== null && this.record_.inactive !== 0){
                    for(let i = 0; i < this.record_.inactive; i++){

                        this.inactive.push({
                            id: this.random(),
                            text: '',
                        });
                    }
                }
                else{
                    this.inactive.push({
                        id: moment().valueOf(),
                        text: '',
                    });
                }
            },
            deleteBroken(event, id){
                this.broken = this.broken.filter((item) => {
                    return item.id !== id;
                })
            },
            deleteDemounted(event, id){
                this.demounted = this.demounted.filter((item) => {
                    return item.id !== id;
                })
            },
            deleteInactive(event, id){
                this.inactive = this.inactive.filter((item) => {
                    return item.id !== id;
                })
            },
            random(){
                return ++this.randomNum;
            }
        },
        computed: {
            total(){
                let total = (!isNaN(this.record_.motor) && this.record_.motor !== null && this.record_.motor !== '') ? parseInt(this.record_.motor) : 0;
                total += (!isNaN(this.record_.sst) && this.record_.sst !== null && this.record_.sst !== '') ? parseInt(this.record_.sst) : 0;

                //todo: неактивные не учитываем АРМ-336
                // total -= (!isNaN(this.record_.demounted) && this.record_.demounted !== null && this.record_.demounted !== '') ? parseInt(this.record_.demounted) : 0;
                // total -= (!isNaN(this.record_.broken) && this.record_.broken !== null && this.record_.broken !== '') ? parseInt(this.record_.broken) : 0;
                // total -= (!isNaN(this.record_.inactive) && this.record_.inactive !== null && this.record_.inactive !== '') ? parseInt(this.record_.inactive) : 0;
                return total;
            },

        },
        created() {
            this.demounted = _.filter(this.record_.items, {type: 'demounted'});
            this.broken = _.filter(this.record_.items, {type: 'broken'});
            this.inactive = _.filter(this.record_.items, {type: 'inactive'});
        }

    }
</script>

<style scoped>

</style>