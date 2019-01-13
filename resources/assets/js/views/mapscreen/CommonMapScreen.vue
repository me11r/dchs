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
                </div>
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

            showHydrants: false,
            showDepartments: true,
            showDistricts: true,
            isAdmin: window.isAdmin,
            canEditOwnHydrants: window.canEditOwnHydrants,
            userDept: window.userDept
        };
    },
    methods: {
        initHydrantList() {
            axios.get('/api/hydrant')
                .then((response) => {
                    this.hydrantList = response['data']['data'];
                    this.hydrantsClusterer = this.getHydrantsClusterer(this.hydrantList.map((item) => {
                        return this.getHydrantPlaceMarkFromItem(item, this.onMarkClick, this.onMarkDragEnd);
                    }));
                });
        },
        setHydrants() {
            this.map.geoObjects.add(this.hydrantsClusterer);
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
            const coords = event.originalEvent.target.geometry['getCoordinates']();
            model.lat = coords[0];
            model.long = coords[1];
            this.updateHydrant(model);
        },
        onMapDoubleClick(event) {
            const coords = event.get('coords');
            let model = {...this.emptyModel};
            model.lat = coords[0];
            model.long = coords[1];
            this.displayHydrantPopup(model);
        },
        onSaveHydrant(model) {
            if (this.canEditOwnHydrants === false && this.userDept !== model.fire_department_id) {
                this.$snackbar.open({
                    message: 'Недостаточно прав для редактирования',
                    position: 'is-top',
                    type: 'is-info',
                });
                return null;
            }

            +model.id === 0 ? this.createHydrant(model) : this.updateHydrant(model);
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
                    hintContent: item['outputDescription'],
                    clusterCaption: item['outputDescription'],
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
                    this.onMarkClick(_.find(this.list, {id: parseInt(event.target.dataset.id)}));
                }
            });
        },
        initHydrants() {
            this.model = window.hydrantListData.model;
            this.emptyModel = {...window.hydrantListData.model};

            this.setHydrantsList();
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

            if (this.showHydrants) {
                this.setHydrants();
            }

            if (this.showDistricts) {
                this.initCityAreas();
            }
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

            this.setMapData();
        },
        initCityAreas() {
            this.yandexMapsBus.cityAreas(this.map);
            this.cityAreas = this.yandexMapsBus.cityAreasPolygons;
        },
        initFireDepartmentAreas() {
            var polygons = this.yandexMapsBus.polygons();
            if (this.fireDepartmentAreas.length === 0) {
                this.fireDepartmentAreas = polygons;
            }
            for (let polygon in polygons) {
                this.map.geoObjects.add(polygons[polygon]);
            }
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
    },
    mounted() {
        (new YandexMapsBus())
            .getInstance()
            .then((yandexMapsBus) => {
                this.yandexMapsBus = yandexMapsBus;
                this.ymaps = this.yandexMapsBus.getYmaps();
                this.initHydrantList();

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
</style>
