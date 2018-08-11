import axios from 'axios';
import Vue from 'vue';
import {_} from 'lodash';
import {globalBus} from '../global-bus';

export default function bindLocationInputApp() {
    const element = document.querySelector('[data-component="location-input"]');
    return new Vue({
        el: element,
        data: {
            location: ''
        },
        methods: {
            searchLocationPlans() {
                axios.get('/ajax/find_special_plan', {
                    params: {
                        location: this.location
                    }
                }).then((response) => {
                    globalBus.$emit('specialPlanFound', response.data);
                }).catch(() => {
                    // not found
                });
            }
        },
        watch: {
            location(newValue) {
                _.debounce(this.searchLocationPlans(newValue), 300);
            }
        },
        mounted() {
            this.location = element.dataset.value;
        }
    });
}
