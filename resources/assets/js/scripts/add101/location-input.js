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
                        this.setData(response.data);
                    }).catch(() => {
                        this.setData([]);
                    });
                }
            }, 300),
            setData(items) {
                if (items.special_plans !== undefined) {
                    this.items = items.special_plans;

                    //определение ранга пожара только при создании карточки
                    //т.к. при редкатировании он может сбиться при вводе адреса
                    if (window.ticket101add.ticketId === '') {
                        document.getElementById('fire_level_id1').value = this.items[0].fire_level_id;
                    }
                    // console.dir("special plan found:")
                    // console.dir(this.items)

                } else {
                    if (window.ticket101add.ticketId === '') {
                        document.getElementById('fire_level_id1').value = 1;
                    }
                    this.items = items;
                }
                this.showList = this.items.length > 0;
                if (this.items.length === 1 && this.items[0].location === this.location) {
                    this.showList = false;
                }

                if (items.building) {
                    document.getElementById('building_description').value = items.building.wall_material.name;
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
                this.showList = false;
                if (item.is_card === true) {
                    // document.getElementById('fire_level_id1').value = 2;
                    document.getElementById('operational_card_id').value = item.id;
                }
            },
            onBlur() {
                setTimeout(() => {
                    this.showList = false;
                }, 500);
            },
            onFocus() {
                this.setData([]);
                this.searchLocationPlans();
            },
            notifyMap() {
                // window.localStorage.setItem(LOCATION_EXCHANGE_KEY, this.location);
                if (this.location.length > 0) {
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
                    // this.fire_department();
                    globalBus.$emit('is_common_house', event.newValue);
                }
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
