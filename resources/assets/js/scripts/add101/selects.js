import {AREA_ID_FOUND} from '../../config/storage-keys';
import {globalBus} from '../global-bus';
import Vue from '../../VueInstance';
import {_} from 'vue-underscore';

export default function bindSelects() {
    document.querySelectorAll('[data-component="simple-select"]')
        .forEach((element) => {
            return new Vue({
                el: element,
                data: {
                    selectedId: null,
                    name: '',
                    file: null
                },
                methods: {
                    onSpecialPlanFound(specialPlan) {
                        this.selectedId = parseInt(specialPlan[this.name]);
                    },
                    onChange(){
                        if (element.dataset.name === 'operational_cards') {
                            globalBus.$emit('operCardChanged', this.selectedId);
                        }
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
                            let cards = JSON.parse(element.dataset.cards) || [];
                            _.each(cards, (card) => {
                                if (parseInt(card.id) === parseInt(this.selectedId)) {
                                    this.file = card.file;
                                }
                            });
                        }
                    }
                }
            });
        });
}
