import bindTimepickers from './timepickers';
import bindAutocomplete from './autocomplete';
import bindBuefyCommonSelects from './buefy-common-selects';
import bindLocationInput from './location-input';
import bindObjectNameInput from './object-name-input';
import bindSelects from './selects';
import bindRoadTrip from './road-trip';
import bindServicePlan from './service-plan';
import {globalBus} from '../global-bus';
import OtherRecords from '../../components/ticket101/OtherRecords';
import OtherRecordsReadOnly from '../../components/ticket101/OtherRecordsReadOnly.vue';
import PopupNotifications from '../../components/ticket101/PopupNotifications.vue';
import axios from 'axios';
import Vue from '../../VueInstance';

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
        this.bindPopupNotifications();
        return this;
    }

    bindPopupMessage() {
        globalBus.$on('specialPlanFound', (specialPlan) => {
            // alert(specialPlan['object_name']);
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

    bindPopupNotifications() {
        return new Vue({el: '#ticket101add_popup_notifications', render: h => h(PopupNotifications)});
    }
}
