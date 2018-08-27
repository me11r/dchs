import {areaIdFound} from '../../config/storage-keys';
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
                    if (element.dataset.name === 'city_area_id') {
                        window.addEventListener('storage', (event) => {
                            if (event.key === areaIdFound) {
                                this.selectedId = parseInt(event.newValue);
                            }
                        });
                    }
                }
            });
        });
}
