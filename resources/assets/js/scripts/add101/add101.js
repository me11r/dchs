import bindTimepickers from './timepickers';
import bindAutocomplete from './autocomplete';
import bindBuefyCommonSelects from './buefy-common-selects';
import bindLocationInput from './location-input';
import bindObjectNameInput from './object-name-input';
import bindSelects from './selects';
import {globalBus} from '../global-bus';

export default class Add101Functions {
    bindElements() {
        bindTimepickers();
        bindAutocomplete();
        bindLocationInput();
        bindBuefyCommonSelects();
        bindObjectNameInput();
        bindSelects();
        return this;
    }

    bindPopupMessage() {
        globalBus.$on('specialPlanFound', (specialPlan) => {
            alert(specialPlan['object_name']);
        });
        return this;
    }
}
