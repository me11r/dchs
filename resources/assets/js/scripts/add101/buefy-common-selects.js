import {BuefyCommonSelect} from '../../components';
import Vue from 'vue';
import {globalBus} from '../global-bus';

export default function bindBuefyCommonSelects() {
    document.querySelectorAll('[data-component="buefy-common-select"]')
        .forEach((element) => {
            return new Vue({
                el: element,
                data: {
                    selectedId: null,
                    name: ''
                },
                components: {
                    BuefyCommonSelect
                },
                methods: {
                    onSpecialPlanFound(specialPlan) {
                        this.selectedId = parseInt(specialPlan[this.name]);
                    }
                },
                mounted() {
                    this.selectedId = parseInt(element.dataset.value);
                    this.name = element.dataset.name;
                    globalBus.$on('specialPlanFound', this.onSpecialPlanFound);
                }
            });
        });
}
