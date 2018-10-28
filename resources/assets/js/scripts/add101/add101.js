import bindTimepickers from './timepickers';
import bindAutocomplete from './autocomplete';
import bindBuefyCommonSelects from './buefy-common-selects';
import bindLocationInput from './location-input';
import bindObjectNameInput from './object-name-input';
import bindSelects from './selects';
import bindRoadTrip from './road-trip';
import bindServicePlan from './service-plan';
import {globalBus} from '../global-bus';
import Vue from 'vue';
import OtherRecords from '../../components/ticket101/OtherRecords';
import OtherRecordsReadOnly from '../../components/ticket101/OtherRecordsReadOnly.vue';
import axios from "axios/index";
import {HydrantMapList} from "../../views/hydrant-map";

export default class Add101Functions {
    bindElements() {
        bindTimepickers();
        bindAutocomplete();
        bindLocationInput();
        bindBuefyCommonSelects();
        bindObjectNameInput();
        bindSelects();
        bindRoadTrip();
        bindServicePlan();
        this.bindOtherRecordsBlock();
        this.bindOtherRecordsBlockResults();
        this.checkRoadtrips();
        return this;
    }

    bindPopupMessage() {
        globalBus.$on('specialPlanFound', (specialPlan) => {
            alert(specialPlan['object_name']);
        });
        return this;
    }

    buildingFound() {
        globalBus.$on('building_found', (building) => {
            alert(building['id']);
        });
        return this;
    }

    bindOtherRecordsBlock() {
        return new Vue({el: '#ticket101_other_records', render: h => h(OtherRecords)});
    }
    bindOtherRecordsBlockResults() {
        return new Vue({el: '#ticket101_other_records_results', render: h => h(OtherRecordsReadOnly)});
    }

    checkRoadtrips() {
        let ticket_id = window.ticket101add.ticketId;
        if (ticket_id !== 0) {
            var timerId = setInterval(function() {
                axios.post('/api/card101/check-roadtrip', {id: ticket_id}).then((response) => {
                    if (response.data.recommendations !== undefined) {
                        response.data.recommendations.forEach(function (item) {

                            let accepted_time = 'accepted_time_' + item.id;
                            let out_time = 'out_time_' + item.id;
                            let ret_time = 'ret_time_' + item.id;
                            let accepted_time_item = document.getElementById(
                                accepted_time);
                            let out_time_item = document.getElementById(
                                out_time);
                            let ret_time_item = document.getElementById(
                                ret_time);

                            if (accepted_time_item && out_time_item) {
                                // accepted_time_item.value = item.out_time;
                                // console.dir(item)
                                // if(item.accept_time !== null){
                                accepted_time_item.value = item.accept_time;
                                out_time_item.value = item.out_time;
                                // }

                            }

                            if (ret_time_item) {

                                ret_time_item.value = item.ret_time;

                            }

                        });
                    }
                });
            }, 10000);
        }
    }
}
