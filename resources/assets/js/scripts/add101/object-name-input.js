import Vue from '../../VueInstance';
import {globalBus} from '../global-bus';

export default function bindObjectNameInput() {
    const element = document.querySelector('[data-component="object-name-input"]');
    return new Vue({
        el: element,
        data: {
            object_name: ''
        },
        methods: {
            onSpecialPlanFound(specialPlan) {
                this.object_name = specialPlan['object_name'] + '';
            }
        },
        mounted() {
            this.object_name = element.dataset.value;
            globalBus.$on('specialPlanFound', this.onSpecialPlanFound);
        }
    });
}
