import axios from 'axios';
import Vue from '../../VueInstance';
import {globalBus} from '../global-bus';
import {MAP_LOCATION_EXCHANGE_KEY, YANDEX_FIRE_DEPT_FOUND} from '../../config/storage-keys';
import YandexMapsBus from '../../scripts/yandex-maps-bus';
import lodash from 'lodash';

export default function bindLocationInputApp() {
    const element = document.querySelector('[data-component="location-input"]');
    return new Vue({
        el: element,
        data: {
            location: '',
            fire_department_id: '',
            showList: false,
            keyUp: false,
            items: [],
            yandexMapsBus: {},
            currentCity: 'Алматы'
        },
        methods: {
            searchLocationPlans: lodash.debounce(function () {
                if (this.keyUp) {
                    axios.get('/ajax/find_special_plan', {
                        params: {
                            location: this.location
                        }
                    }).then((response) => {
                        if (response.data.special_plans[0].location === this.location) {
                            this.selectItem(response.data.special_plans[0]);
                            return;
                        }
                        this.setData(response.data);
                    }).catch(() => {
                        this.setData([]);
                    });
                }
            }, 300),
            setData(items) {
                if (items.special_plans) {
                    this.items = items.special_plans;

                    // определение ранга пожара только при создании карточки
                    // т.к. при редкатировании он может сбиться при вводе адреса
                    if (window.ticket101add.ticketId === '') {
                        // document.getElementById('fire_level_id1').value = this.items[0].fire_level_id; //без этого сбивается
                        document.getElementById('fire_level_id1').value = 1; // ARM-290
                    }
                } else {
                    if (window.ticket101add.ticketId === '') {
                        document.getElementById('fire_level_id1').value = 1; // ARM-290
                    }
                    this.items = items;
                }
                this.showList = this.items.length > 0;

                if (this.items.length === 1 && (this.items[0] && this.items[0].location === this.location)) {
                    this.showList = false;
                }

                if (items.building) {
                    document.getElementById('building_description').value = items.building.wall_material ? items.building.wall_material.name : '';
                    document.getElementById('square').value = items.building.square;
                    document.getElementById('year_of_development').value = items.building.year_of_development;
                    document.querySelector('[id="storey_count"]').value = items.building.number_of_storeys;
                } else {
                    // document.getElementById('fire_object_id').value = '';
                    document.getElementById('building_description').value = '';
                    document.getElementById('square').value = '';
                    document.getElementById('year_of_development').value = '';
                    document.querySelector('[id="storey_count"]').value = '';
                }
            },
            selectItem(item) {
                this.location = item.location;
                globalBus.$emit('specialPlanFound', item);

                document.getElementById('fire_level_id1').value = item.fire_level_id;
                document.getElementById('additional_description').value = item.additional_info;


                this.showList = false;
                this.keyUp = false;
                if (item.is_card === true) {
                    // document.getElementById('fire_level_id1').value = 2;
                    document.getElementById('operational_card_id').value = item.id;
                } else{
                    document.getElementById('operational_card_id').value = '';
                }
            },
            onBlur() {
                setTimeout(() => {
                    this.showList = false;
                }, 500);
            },
            onFocus() {
                // this.setData([]);
                this.searchLocationPlans();
            },
            notifyMap() {
                // window.localStorage.setItem(LOCATION_EXCHANGE_KEY, this.location);
                if (this.location.length > 0) {
                    //todo: перебивает поиск района города по OSM
                    this.yandexMapsBus.debouncedFindHouse(this.currentCity + ' ' + this.location);
                }
            }
        },
        watch: {
            location(newValue, oldValue) {
                if (newValue !== oldValue) {
                    this.searchLocationPlans();
                    this.notifyMap();
                }
            },
            'fire_department_id'(newValue, oldValue) {
                globalBus.$emit('is_common_house', newValue);
            }
        },
        computed: {
            fire_department() {
                document.getElementById('fire_department_id').value = this.fire_department_id;
            }
        },
        mounted() {
            window.addEventListener('storage', (event) => {

                if (event.key === MAP_LOCATION_EXCHANGE_KEY) {
                    this.location = event.newValue;
                } else if (event.key === YANDEX_FIRE_DEPT_FOUND) {
                    this.fire_department_id = event.newValue;

                    if(!document.getElementById('fire_level_id1').value){
                        document.getElementById('fire_level_id1').value = 1;
                    }
                    globalBus.$emit('is_common_house', event.newValue);
                }
            });

            globalBus.$on('operPlanChanged', (q) => {
                axios.get('/ajax/find_special_plan_by_id', {params: {
                    id: q
                }}).then((resp) => {
                    let data = resp.data.specialPlan;
                    if (data && data.special_plan) {
                        globalBus.$emit('specialPlanFound', data.special_plan);
                        this.location = data.special_plan.location;
                        document.getElementById('fire_level_id1').value = data.special_plan.fire_level_id;
                    }
                });
            });

            globalBus.$on('operCardChanged', (q) => {
                axios.get('/ajax/find_operational_card_by_id', {params: {
                    id: q
                }}).then((resp) => {
                    let data = resp.data.operCard;
                    if (data) {
                        this.location = data.location;
                        document.getElementById('fire_level_id1').value = data.fire_level_id;
                        this.fire_department_id = data.fire_department_id;
                        document.getElementById('object_name').value = data.object_name;
                    }
                });
            });

            (new YandexMapsBus())
                .getInstance()
                .then((yandexMapsBus) => {
                    this.yandexMapsBus = yandexMapsBus;
                    this.location = element.dataset.value;
                    var object = this.$refs.location;
                    Vue.nextTick(function() {
                        object.focus();
                    });
                });

            // this.checkRoadtrips();
        }
    });
}
