import {AREA_ID_FOUND} from '../../config/storage-keys';
import {globalBus} from '../global-bus';
import Vue from '../../VueInstance';

export default function bindSelects() {
    document.querySelectorAll('[data-component="simple-select"]')
        .forEach((element) => {
            return new Vue({
                el: element,
                data: {
                    selectedId: null,
                    name: '',
                    operationalCardPdf: false,
                    operationalPlanPdf: false,
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
                            if (event.key === AREA_ID_FOUND) {
                                this.selectedId = parseInt(event.newValue);
                            }
                        });
                        globalBus.$on(AREA_ID_FOUND, (value) => {
                            this.selectedId = parseInt(value);
                        });
                    }
                },
                watch: {
                    'selectedId'() {
                        if (element.dataset.name === 'fire_level_id') {
                            console.dir(this.selectedId);
                        }
                        if (element.dataset.name === 'operational_cards') {
                            this.operationalCardPdf = false;
                            console.dir(this.selectedId);
                        }
                        if (element.dataset.name === 'operational_plan_id') {
                            this.operationalCardPdf = false;
                            console.dir(this.selectedId);
                        }
                    }
                }
            });
        });
}
