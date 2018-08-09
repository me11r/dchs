import bindTimepickers from './timepickers';
import bindAutocomplete from './autocomplete';
import bindBuefyCommonSelects from './buefy-common-selects';
import bindLocationInput from './location-input';
import bindObjectNameInput from './object-name-input';
import bindSelects from './selects';
import {globalBus} from '../global-bus';
import Vue from 'vue';
import OtherRecords from '../../components/ticket101/OtherRecords';

export default class Add101Functions {
    bindElements() {
        bindTimepickers();
        bindAutocomplete();
        bindLocationInput();
        bindBuefyCommonSelects();
        bindObjectNameInput();
        bindSelects();
        this.bindOtherRecordsBlock();
        return this;
    }

    bindPopupMessage() {
        globalBus.$on('specialPlanFound', (specialPlan) => {
            alert(specialPlan['object_name']);
        });
        return this;
    }

    bindOtherRecordsBlock() {
        return new Vue({el: '#ticket101_other_records', render: h => h(OtherRecords)});
    }
}
