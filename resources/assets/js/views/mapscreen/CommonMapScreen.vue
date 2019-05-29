<template>
    <div>
        <div
            class="panel"
            style="padding: 20px 0 20px 20px; background-color: #ffffff">
            <div class="field">
                <div class="control">
                    <div class="field">
                        <b-checkbox
                            v-model="showHydrants">Отображать гидранты
                        </b-checkbox>
                    </div>

                    <div class="field">
                        <b-checkbox
                            v-model="showDepartments">Отображать микроучастки
                        </b-checkbox>
                    </div>

                    <div class="field">
                        <b-checkbox
                            v-model="showDistricts">Отображать границы районов
                        </b-checkbox>
                    </div>

                    <div class="field">
                        <b-checkbox
                            v-model="showFds">Отображать ПЧ
                        </b-checkbox>
                    </div>
                </div>
            </div>
            <div
                class="field"
                id="hydrant-table"
                :class="isOpen ? 'hydrant-table-open' : 'hydrant-table-collapsed'"
                v-if="showHydrantTable && showHydrants"
            >
                <b-collapse
                    class="panel"
                    :open.sync="isOpen">
                    <div
                        slot="trigger"
                        class="panel-heading">
                        <strong>Гидранты</strong>
                    </div>
                    <table class="table is-bordered is-expanded">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    <b-autocomplete
                                        rounded
                                        v-model="fireDeptName"
                                        :data="filteredHydrants"
                                        placeholder="ПЧ"
                                        icon="magnify"
                                        @select="option => selectedHydrant = option">
                                        <template slot="empty">ПЧ не найдена</template>
                                        <template slot="header">
                                            <a @click="resetHydrantsFilter">
                                                <span>Сбросить фильтр</span>
                                            </a>
                                        </template>
                                    </b-autocomplete>
                                </th>
                                <th>Адрес</th>
                                <th>Спецификация</th>
                                <th>Широта</th>
                                <th>Долгота</th>
                                <th>Активен</th>
                                <th>Имя оператора</th>
                                <th>Дата корректировки</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(hydrant, key) in hydrantFireDepts"
                                :key="`tableItem_${key}`"
                                @click="zoomToObject(hydrant, hydrant.lat, hydrant.long)">
                                <td>{{ ++key }}</td>
                                <td>{{ hydrant.fire_department.title }}</td>
                                <td>{{ hydrant.address }}</td>
                                <td>{{ hydrant.specification }}</td>
                                <td>{{ hydrant.lat }}</td>
                                <td>{{ hydrant.long }}</td>
                                <td>{{ hydrant.active }}</td>
                                <td>{{ hydrant.operator_name }}</td>
                                <td>{{ hydrant.correction_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-collapse>
            </div>
        </div>

        <div class="map-block">
            <div
                id="common-map-screen-yandex-map"
                :ref="'common-map-screen-yandex-map'"></div>
        </div>

        <popup-form
            v-if="hydrantPopupShow"
            :item="hydrantModel"
            @onClose="closeHydrantPopup"
            @onSave="onSaveHydrant"/>
    </div>
</template>

<script>

import {
    MAP_LOCATION_EXCHANGE_KEY,
    YANDEX_FIRE_DEPT_FOUND,
    YANDEX_HOUSE_FOUND} from '../../config/storage-keys';
import YandexMapsBus from '../../scripts/yandex-maps-bus';
import {globalBus} from '../../scripts/global-bus';
import PopupForm from '../hydrant-map/PopupForm';
import axios from 'axios';
import _ from 'lodash';

export default {
    name: 'CommonMapScreen',
    components: {
        PopupForm
    },
    data: function () {
        return {
            location: '',
            isOpen: true,
            ymaps: null,
            map: {},
            currentCity: 'Алматы',
            yandexMapsBus: {},
            zoom: 16,
            fireDepartmentAreas: [],
            cityAreas: [],

            lastGeoObject: null,
            lastGeoData: null,

            hydrantModel: {},
            hydrantEmptyModel: {},
            hydrantList: [],
            hydrantPopupShow: false,
            hydrantsClusterer: null,
            currentHydrantMark: null,

            fireDeptName: '',
            selectedHydrant: null,
            fireDepartments: [],
            fireDepartmentNames: [],

            showHydrantTable: false,

            showHydrants: window.showHydrants,
            showDepartments: true,
            showDistricts: true,
            showFds: false,
            isAdmin: window.isAdmin,
            canEditOwnHydrants: window.canEditOwnHydrants,
            canEditAllHydrants: window.canEditAllHydrants,
            userDept: window.userDept
        };
    },
    methods: {
        initHydrantList() {
            axios.get('/api/hydrant')
                .then((response) => {
                    this.hydrantList = response['data']['hydrants'];
                    this.hydrantsClusterer = this.getHydrantsClusterer(this.hydrantList.map((item) => {
                        return this.getHydrantPlaceMarkFromItem(item, this.onMarkClick, this.onMarkDragEnd);
                    }));
                });
        },
        setHydrants() {
            // if (this.hydrantsClusterer) {
            //     this.map.geoObjects.add(this.hydrantsClusterer);
            // } else {
                axios.get('/api/hydrant')
                    .then((response) => {
                        this.hydrantList = response['data']['hydrants'];
                        let fireDeptsFiltered = response['data']['fireDepartments'];

                        if (window.userDeptRight !== 0 && window.userDeptRight !== null) {
                            fireDeptsFiltered = fireDeptsFiltered.filter((item) => {
                                return item.id === window.userDeptRight;
                            });
                        }

                        this.fireDepartments = fireDeptsFiltered;
                        this.mapFireDepts();
                        this.hydrantsClusterer = this.getHydrantsClusterer(this.hydrantList.map((item) => {
                            return this.getHydrantPlaceMarkFromItem(item, this.onMarkClick, this.onMarkDragEnd);
                        }));

                        this.map.geoObjects.add(this.hydrantsClusterer);
                        this.showHydrantTable = true;
                    });
            // }
        },
        mapFireDepts() {
            this.fireDepartmentNames = this.fireDepartments.map((item) => {
                return item.title;
            });
        },
        closeHydrantPopup() {
            this.hydrantPopupShow = false;
        },
        displayHydrantPopup(model) {
            this.hydrantModel = model;
            this.hydrantPopupShow = true;
        },
        onMarkClick(model) {
            this.displayHydrantPopup(model);
        },
        onMarkDragEnd(event, model) {
            if (this.canSaveOrUpdateHydrant(model)) {
                globalBus.$emit('api-map-request', {'request_count': 1, 'description': 'CommonMapScreen.onMarkDragEnd()'});

                const coords = event.originalEvent.target.geometry['getCoordinates']();
                model.lat = coords[0];
                model.long = coords[1];

                this.updateHydrant(model);
            }
        },
        onMapDoubleClick(event) {
            globalBus.$emit('api-map-request', {'request_count': 1, 'description': 'CommonMapScreen.onMapDoubleClick()'});

            const coords = event.get('coords');
            let model = {...this.emptyModel};
            model.lat = coords[0];
            model.long = coords[1];
            this.displayHydrantPopup(model);
        },
        canSaveOrUpdateHydrant(model) {
            if (this.canEditAllHydrants) {
                return true;
            }

            if ((this.canEditOwnHydrants === false && this.isAdmin === false) || (this.canEditOwnHydrants === true && this.userDept !== model.fire_department_id && this.isAdmin === false)) {
                this.$snackbar.open({
                    message: 'Недостаточно прав для редактирования',
                    position: 'is-top',
                    type: 'is-info'
                });
                return false;
            }

            return true;
        },
        onSaveHydrant(model) {
            if (this.canSaveOrUpdateHydrant(model)) {
                let isNewModel = +model.id === 0 || model.id === undefined;
                isNewModel ? this.createHydrant(model) : this.updateHydrant(model);
            }
        },
        createHydrant(model) {
            const self = this;
            axios.post('/api/hydrant', model)
                .then(() => {
                    self.closeHydrantPopup();
                    self.setMapData();
                });
        },
        updateHydrant(model) {
            const self = this;

            axios.post('/api/hydrant/' + model.id, {...model, '_method': 'PATCH'})
                .then(() => {
                    self.closeHydrantPopup();
                    self.setMapData();
                });
        },
        getHydrantsClusterer(geoObjects) {
            const clusterer = new this.ymaps.Clusterer({
                preset: 'islands#invertedBlueClusterIcons',
                clusterHideIconOnBalloonOpen: false,
                geoObjectHideIconOnBalloonOpen: false,
                gridSize: 100
            });
            clusterer.add(geoObjects);

            return clusterer;
        },
        getHydrantPlaceMarkFromItem(item, onClick, onDragEnd) {
            const id = item.id || Math.floor(Date.now() / 1000);
            const placemark = new this.ymaps.Placemark(
                [item.lat, item.long],
                {
                    id: id,
                    hintContent: item['address'],
                    clusterCaption: item['address'],
                    balloonContentBody: '<button class="button is-success edit-hydrant-button is-small" data-id="' + id + '">Редактировать</button>'
                },
                {
                    preset: parseInt(item['active']) === 1 ? 'islands#blueIcon' : 'islands#blackDotIcon',
                    draggable: true
                });
            placemark.events.add('click', (event) => {
                event.preventDefault();
                onClick(item);
            });
            placemark.events.add('dragend', (event) => {
                onDragEnd(event, item);
            });

            return placemark;
        },
        addHydrantClickListener() {
            document.addEventListener('click', (event) => {
                if (event.target.matches('.edit-hydrant-button')) {
                    this.onMarkClick(_.find(this.hydrantList, {id: parseInt(event.target.dataset.id)}));
                }
            });
        },
        /*initHydrants() {
            this.model = window.hydrantListData.model;
            this.emptyModel = {...window.hydrantListData.model};

            this.setHydrantsList();
        },*/
        resetHydrantsFilter() {
            this.selectedHydrant = null;
            this.hydrantsClusterer = null;
            this.setMapData();
        },

        setMapData() {
            // @TODO для оптимизации скорости работы - можно сделать независимое удаление и добавление геообъектов карты

            this.resetAllObjects();

            if (this.lastGeoObject) {
                this.setPointOnTheMap(
                    this.lastGeoObject.geometry.getBounds()[0][0],
                    this.lastGeoObject.geometry.getBounds()[0][1],
                    this.lastGeoObject.properties.get('name')
                );
            }

            if (this.lastGeoData) {
                this.setPointOnTheMap(
                    this.lastGeoData.lat,
                    this.lastGeoData.long,
                    this.lastGeoData.name
                );
            }

            if (this.showDepartments) {
                this.initFireDepartmentAreas();
            }

            if (this.showFds) {
                this.initFireDepartments();
            }

            if (this.showHydrants) {
                this.setHydrants();
            }

            if (this.showDistricts) {
                this.initCityAreas();
            }
        },

        initFireDepartments() {
            window.hydrantListData.fireDepartments.forEach((item) => {
                this.ymaps['geocode'](this.currentCity + ' ' + item.address, {results: 1})
                    .then((result) => {
                        let geoObject = result['geoObjects'].get(0);

                        if (geoObject) {

                            let coords = [
                                geoObject.geometry.getBounds()[0][0],
                                geoObject.geometry.getBounds()[0][1]
                            ];

                            let geoObject2 = new this.ymaps.GeoObject({
                                geometry: {
                                    type: 'Point',
                                    coordinates: coords
                                },
                                properties: {
                                    iconContent: item.title
                                }
                            }, {
                                // preset: 'islands#blueAutoIcon',
                                preset: 'islands#redStretchyIcon',
                                draggable: false
                            });

                            this.map.geoObjects.add(geoObject2);
                        }
                    });
            });

        },

        detectCityAreaOsm(lat, long) {
            let area = this.yandexMapsBus.detectCityAreaOsm(lat, long, this.cityAreas, this.map);

            if (area.title) {
                let districtModel = _.find(this.yandexMapsBus.areas, {'name': area.title.toLowerCase()});
                if (districtModel) {
                    window.localStorage.setItem('AREA_ID_FOUND', districtModel.id);
                    globalBus.$emit('AREA_ID_FOUND', districtModel.id);
                    globalBus.$emit('city_area_selected', districtModel);
                }
            }
        },

        doubleClickOnTheMap(event) {
            const coords = event.get('coords');
            this.lastGeoObject = null;

            this.ymaps
                .geocode(coords[0] + ',' + coords[1], {results: 1})
                .then((result) => {
                    const geoObject = result['geoObjects'].get(0);
                    if (geoObject) {
                        this.detectLocation(geoObject);
                        this.detectArea(geoObject);

                        this.lastGeoData = null;
                        this.lastGeoObject = geoObject;

                        this.setMapData();

                        let deptId = this.yandexMapsBus.fireDepartmentArea(coords[0], coords[1], this.fireDepartmentAreas, this.map);

                        let area = this.yandexMapsBus.detectCityAreaOsm(coords[0], coords[1], this.cityAreas, this.map);

                        if (area.title) {
                            let districtModel = _.find(this.yandexMapsBus.areas, {'name': area.title.toLowerCase()});
                            if (districtModel) {
                                window.localStorage.setItem('AREA_ID_FOUND', districtModel.id);
                                globalBus.$emit('AREA_ID_FOUND', districtModel.id);
                                globalBus.$emit('city_area_selected', districtModel);
                            }
                        }

                        window.localStorage.setItem(YANDEX_FIRE_DEPT_FOUND, deptId);
                        globalBus.$emit('is_common_house', deptId);
                    }
                });
        },
        houseFound(lat, long, name) {
            this.lastGeoObject = null;
            this.lastGeoData = {lat, long, name};
            this.setMapData();
            let deptId = this.yandexMapsBus.fireDepartmentArea(lat, long, this.fireDepartmentAreas, this.map);
            window.localStorage.setItem(YANDEX_FIRE_DEPT_FOUND, 0);
            window.localStorage.setItem(YANDEX_FIRE_DEPT_FOUND, deptId);
        },
        resetAllObjects() {
            this.map.geoObjects.removeAll();
        },
        setPointOnTheMap(lat, long, name) {
            const geoObject = new this.ymaps.GeoObject({
                geometry: {
                    type: 'Point',
                    coordinates: [lat, long]
                },
                properties: {
                    iconContent: name
                }
            }, {
                preset: 'islands#darkBlueStretchyIcon',
                draggable: false
            });
            this.map.geoObjects.add(geoObject);
            this.map.setZoom(this.zoom);
            this.map.panTo([lat, long]);
        },
        detectLocation(geoObject) {
            this.location = geoObject.properties
                .get('name')
                .replace(/(^|\s+)улица(\s+|$)/g, '')
                .replace(/(^|\s+)проспект(\s+|$)/g, '');
            window.localStorage.setItem(MAP_LOCATION_EXCHANGE_KEY, this.location);
        },
        detectArea(geoObject) {
            this.yandexMapsBus.detectArea(
                geoObject.geometry.getBounds()[0][0],
                geoObject.geometry.getBounds()[0][1]
            );
        },
        initMap() {
            const self = this;
            this.map = new this.ymaps.Map(this.$refs['common-map-screen-yandex-map'], {
                center: [43.259743, 76.926573],
                zoom: self.zoom,
                behaviors: ['drag', 'scrollZoom']
            });

            this.map.events.add('dblclick', (event) => {
                self.doubleClickOnTheMap(event);
            });

            this.map.events.add('contextmenu', (event) => {
                self.onMapDoubleClick(event);
            });

            globalBus.$emit('api-map-request', {'request_count': 1, 'description': 'CommonMapScreen.initMap()'});

            this.setMapData();
        },
        initCityAreas() {
            this.yandexMapsBus.cityAreas(this.map);
            this.cityAreas = this.yandexMapsBus.cityAreasPolygons;
        },
        initFireDepartmentAreas() {
            axios
                .get('/api/polygon')
                .then((response) => {
                    const polygons = response['data'].map((item) => {
                        return new this.ymaps.Polygon([
                            item.points
                        ], {
                            hintContent: item.title
                        }, {
                            strokeColor: item.line_color,
                            fillColor: item.fill_color,
                            strokeWidth: 1,
                            opacity: item.opacity
                        });
                    });

                    if (this.fireDepartmentAreas.length === 0) {
                        this.fireDepartmentAreas = polygons;
                    }

                    polygons.map((polygon) => {
                        this.map.geoObjects.add(polygon);
                    });
                });
        },
        zoomToObject(hydrant, lat, long) {
            window.scrollTo(0, document.body.scrollHeight);
            this.map.setZoom(18);
            lat = parseFloat(lat);
            long = parseFloat(long);
            this.map.panTo([lat, long]);

            globalBus.$emit('api-map-request', {'request_count': 1, 'description': 'CommonMapScreen.zoomToObject()'});

            if (this.currentHydrantMark) {
                this.map.geoObjects.remove(this.currentHydrantMark);
            }
            this.currentHydrantMark = new this.ymaps.Placemark([lat, long], {
                // iconCaption: [lat, long].join(', ')
                iconCaption: hydrant.address
            }, {
                preset: 'islands#violetDotIconWithCaption',
                draggable: true
            });

            this.currentHydrantMark.events.add('click', (event) => {
                event.preventDefault();
                this.onMarkClick(hydrant);
            });

            this.map.geoObjects.add(this.currentHydrantMark);
            // Слушаем событие окончания перетаскивания на метке.
            // this.currentHydrantMark.events.add('dragend', function () {
            //     getAddress(myPlacemark.geometry.getCoordinates());
            // });
        }
    },
    computed: {
        hydrantFireDepts() {
            return this.hydrantList.filter((option) => {
                return option.fire_department.title === this.selectedHydrant;
            });
        },
        filteredHydrants() {
            return this.fireDepartmentNames.filter((option) => {
                return option.toString()
                    .toLowerCase()
                    .indexOf(this.fireDeptName.toLowerCase()) >= 0;
            });
        }
    },
    watch: {
        'showHydrants'() {
            this.setMapData();
        },
        'showDepartments'() {
            this.setMapData();
        },
        'showDistricts'() {
            this.setMapData();
        },
        'showFds'() {
            this.setMapData();
        },
        'selectedHydrant'() {
            this.resetAllObjects();
            // this.map.geoObjects.remove(this.hydrantsClusterer);

            this.hydrantsClusterer = this.getHydrantsClusterer(this.hydrantFireDepts.map((item) => {
                return this.getHydrantPlaceMarkFromItem(item, this.onMarkClick, this.onMarkDragEnd);
            }));

            this.setMapData();

            // this.map.geoObjects.add(this.hydrantsClusterer);
        }
    },
    mounted() {
        (new YandexMapsBus())
            .getInstance()
            .then((yandexMapsBus) => {
                this.yandexMapsBus = yandexMapsBus;
                this.ymaps = this.yandexMapsBus.getYmaps();
                // this.initHydrantList();
                this.initMap();
                this.addHydrantClickListener();
                let initHouseData = window.localStorage.getItem(YANDEX_HOUSE_FOUND);
                if (initHouseData) {
                    initHouseData = JSON.parse(initHouseData);
                    this.houseFound(initHouseData['lat'], initHouseData['long'], initHouseData['name']);
                }

                window.addEventListener('storage', (event) => {
                    if (event.key === YANDEX_HOUSE_FOUND) {
                        let data = JSON.parse(event.newValue);
                        this.houseFound(data['lat'], data['long'], data['name']);
                    }

                    if (event.key === 'findAreaOsm') {
                        let coords = JSON.parse(event.newValue);
                        this.detectCityAreaOsm(coords[0], coords[1]);
                    }
                });
            });
    }
};
</script>

<style scoped>
    .map-block {
        width: 100%;
        height: 900px;
        resize: vertical;
        overflow: auto;
    }

    #common-map-screen-yandex-map {
        width: 100%;
        height: 100%;
    }

    #hydrant-table {
        overflow-y: scroll;
        background: ivory;
        max-height: 300px;
    }
    .hydrant-table-open {
        height: 300px;
    }
    .hydrant-table-collapsed {
        height: 50px;
    }
</style>
