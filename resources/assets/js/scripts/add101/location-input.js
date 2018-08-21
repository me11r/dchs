import axios from 'axios';
import Vue from 'vue';
import {globalBus} from '../global-bus';

const lodash = require('lodash');

export default function bindLocationInputApp() {
    const element = document.querySelector('[data-component="location-input"]');
    return new Vue({
        el: element,
        data: {
            location: '',
            showList: false,
            items: []
        },
        methods: {
            searchLocationPlans: lodash.debounce(function () {
                axios.get('/ajax/find_special_plan', {
                    params: {
                        location: this.location
                    }
                }).then((response) => {
                    this.setData(response.data);
                }).catch(() => {
                    this.setData([]);
                });
            }, 300),
            setData(items) {
                this.items = items;
                this.showList = this.items.length > 0;
                if (this.items.length === 1 && this.items[0].location === this.location) {
                    this.showList = false;
                }
                // @todo: for demo ================================
                if(items.length > 0){
                    if(items[0].object_name === 'Абылай хана, 62')
                    {
                        document.querySelector('[id="ph_1_ot"]').value = '2,3 отд';
                        document.querySelector('[id="ph_2_ot"]').value = '1,2,3,4 отд';
                        document.querySelector('[id="ph_3_ot"]').value = '1,2 отд';
                        document.querySelector('[id="ph_5_ot"]').value = '1,2,5,6 отд';
                        document.querySelector('[id="ph_6_ot"]').value = '1,2 отд';
                        document.querySelector('[id="ph_7_ot"]').value = '2,5 отд';
                        document.querySelector('[id="ph_8_ot"]').value = '1,2 отд';
                        document.querySelector('[id="ph_11_ot"]').value = '1,2 отд';
                        document.querySelector('[id="ph_12_ot"]').value = '2 отд';
                        document.querySelector('[id="ph_13_ot"]').value = '1,2,4,5,7,8 отд';
                        document.querySelector('[id="ph_15_ot"]').value = '4 отд';
                        document.querySelector('[id="ph_16_ot"]').value = '1 отд';
                    }
                    if(items[0].object_name === 'пр.Достык,56')
                    {
                        document.querySelector('[id="ph_8_ot"]').value = '1,2,3,5 отд';
                        document.querySelector('[id="ph_1_ot"]').value = '1,2,3 отд';
                        document.querySelector('[id="ph_2_ot"]').value = '2,3,4 отд';
                        document.querySelector('[id="ph_3_ot"]').value = '1,2 отд';
                        document.querySelector('[id="ph_6_ot"]').value = '1,2,3 отд';
                        document.querySelector('[id="ph_9_ot"]').value = '2 отд';
                        document.querySelector('[id="ph_13_ot"]').value = '1,2,4,7 отд';
                    }
                }
                // =================================================
            },
            selectItem(item) {
                this.location = item.location;
                globalBus.$emit('specialPlanFound', item);
                this.showList = false;
            },
            onBlur() {
                setTimeout(() => {
                    this.showList = false;
                }, 500);
            },
            onFocus() {
                this.setData([]);
                this.searchLocationPlans();
            }
        },
        watch: {
            location(newValue, oldValue) {
                if (newValue !== oldValue) {
                    this.searchLocationPlans();
                }
            }
        },
        mounted() {
            this.location = element.dataset.value;
        }
    });
}
