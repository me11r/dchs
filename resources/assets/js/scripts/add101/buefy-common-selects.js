import {BuefyCommonSelect} from '../../components';
import {globalBus} from '../global-bus';
import Vue from '../../VueInstance';
import {_} from "vue-underscore";

export default function bindBuefyCommonSelects() {
    document.querySelectorAll('[data-component="buefy-common-select"]')
        .forEach((element) => {
            return new Vue({
                el: element,
                data: {
                    selectedId: null,
                    name: '',
                    file: null
                },
                components: {
                    BuefyCommonSelect
                },
                methods: {
                    onSpecialPlanFound(specialPlan) {
                        this.selectedId = parseInt(specialPlan[this.name]);
                    },
                    onInput(){
                        if (element.dataset.name === 'operational_plan_id') {
                            globalBus.$emit('operPlanChanged', this.selectedId);
                        }
                    }
                },
                mounted() {
                    this.selectedId = parseInt(element.dataset.value);
                    this.name = element.dataset.name;
                    globalBus.$on('specialPlanFound', this.onSpecialPlanFound);

                    if (element.dataset.name === 'fire_department_id') {
                        // globalBus.$on('city_area_selected', (value) => {
                        //     let resp = value.fire_departments[0];
                        //     this.selectedId = resp.id;
                        //     this.name = resp.title;
                        // });

                        globalBus.$on('is_common_house', (value) => {
                            this.selectedId = parseInt(value);
                        });
                    }
                },
                watch: {
                    'selectedId'() {
                        if (element.dataset.name === 'operational_plan_id') {
                            let plans = JSON.parse(element.dataset.plans) || [];
                            _.each(plans, (plan) => {
                                if (parseInt(plan.operational_plan_id) === parseInt(this.selectedId) &&  plan.file !== '') {
                                    this.file = plan.file;
                                }
                            });
                        }
                    }
                }
            });
        });
}
