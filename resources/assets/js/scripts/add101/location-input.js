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
