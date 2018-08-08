import {globalBus} from '../global-bus';
import Vue from 'vue';

export default function bindSelects() {
    document.querySelectorAll('[data-component="simple-select"]')
        .forEach((element) => {
            return new Vue({
                el: element,
                data: {
                    selectedId: null,
                    name: ''
                },
                methods: {
                    onSpecialPlanFound(specialPlan) {
                        this.selectedId = parseInt(specialPlan[this.name]);
                    }
                },
                mounted() {
                    this.selectedId = parseInt(element.dataset.value);
                    this.name = element.dataset.name;
                    if (element.dataset.special === 'specialPlan') {
                        globalBus.$on('specialPlanFound', this.onSpecialPlanFound);
                    }
                }
            });
        });
}
