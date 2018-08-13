import Vue from 'vue';
import {_} from 'vue-underscore';

export default class Add101Persons {
    createApp(element) {
        this.app = new Vue({
            'el': element,
            data: {
                departmentId: '',
                inputs: {
                    'field_0': '',
                    'field_2_0': '',
                    'field_2_1': '',
                    'field_2_2': '',
                    'field_2_3': '',
                    'field_2_4': '',
                    'field_2_5': '',
                    'field_3_0': '',
                    'field_3_1': '',
                    'field_3_2': '',
                    'field_3_3': '',
                    'field_3_4': '',
                    'field_3_5': ''
                }
            },
            watch: {
                'departmentId'(newValue, oldValue) {
                    if (oldValue !== '' && oldValue !== newValue) {
                        let location = window.location.pathname.split('/');
                        location.pop();
                        location.push(newValue);
                        window.location = location.join('/');
                    }
                },
                'inputs.field_2_1'() {
                    this.calculateTotalSum();
                },
                'inputs.field_2_2'() {
                    this.calculateTotalSum();
                },
                'inputs.field_2_3'() {
                    this.calculateTotalSum();
                },
                'inputs.field_2_4'() {
                    this.calculateTotalSum();
                },
                'inputs.field_2_5'() {
                    this.calculateTotalSum();
                },
                'inputs.field_3_0'() {
                    this.calculateTotalSum();
                },
                'inputs.field_3_1'() {
                    this.calculateTotalSum();
                },
                'inputs.field_3_2'() {
                    this.calculateTotalSum();
                },
                'inputs.field_3_3'() {
                    this.calculateTotalSum();
                },
                'inputs.field_3_4'() {
                    this.calculateTotalSum();
                },
                'inputs.field_3_5'() {
                    this.calculateTotalSum();
                }
            },
            methods: {
                calculateTotalSum() {
                    let total = 0;
                    _.each(this.inputs, (value, key) => {
                        if (key !== 'field_2_0' && key.indexOf('field_2') !== -1 && !isNaN(parseInt(value))) {
                            total += parseInt(value);
                        }
                    });
                    this.inputs['field_2_0'] = total;
                    this.calculateMainSum();
                },
                calculateMainSum() {
                    let total = 0;
                    _.each(this.inputs, (value, key) => {
                        if (!isNaN(parseInt(value)) && key !== 'field_2_0' && key !== 'field_0') {
                            total += parseInt(value);
                        }
                    });
                    this.inputs['field_0'] = total;
                },
                isReadonly(prefix, index) {
                    const fieldName = 'field' + prefix + '_' + index;
                    return fieldName === 'field_0' || fieldName === 'field_2_0';
                }
            },
            beforeMount() {
                this.departmentId = element.dataset.department_value;
                _.each(document.getElementsByClassName('add101persons-input'), (inputElement) => {
                    if (inputElement.dataset.value) {
                        this.inputs[inputElement.id] = parseInt(inputElement.dataset.value);
                    }
                });
                this.calculateTotalSum();
            }
        });
    }
}
