<template>
    <div>
        <div class="map-block">
            <div
                id="common-map-screen-yandex-map"
                :ref="'common-map-screen-yandex-map'"></div>
        </div>
    </div>
</template>

<script>

import {
    LOCATION_EXCHANGE_KEY,
    MAP_LOCATION_EXCHANGE_KEY,
    AREA_ID_FOUND,
    LOCATION_COORDINATES_FOUND,
    YANDEX_FIRE_DEPT_FOUND,
    YANDEX_HOUSE_FOUND} from '../../config/storage-keys';
import * as _ from 'lodash';
import YandexMapsBus from '../../scripts/yandex-maps-bus';
import {globalBus} from '../../scripts/global-bus';

export default {
    name: 'CommonMapScreen',
    data: function () {
        return {
            location: '',
            ymaps: window.ymaps,
            map: {},
            currentCity: 'Алматы',
            yandexMapsBus: {},
            zoom: 16,
            fireDepartmentAreas: []
        };
    },
    methods: {
        doubleClickOnTheMap(event) {
            const coords = event.get('coords');
            this.resetAllObjects();
            this.ymaps
                .geocode(coords[0] + ',' + coords[1], {results: 1})
                .then((result) => {
                    const geoObject = result['geoObjects'].get(0);
                    if (geoObject) {
                        this.setPointOnTheMap(
                            geoObject.geometry.getBounds()[0][0],
                            geoObject.geometry.getBounds()[0][1],
                            geoObject.properties.get('name')
                        );
                        this.detectLocation(geoObject);
                        this.detectArea(geoObject);
                    }
                });

            let dept_id = this.yandexMapsBus.fireDepartmentArea(coords[0], coords[1], this.fireDepartmentAreas, this.map);
            window.localStorage.setItem('fire_department_id_found', dept_id);
            window.localStorage.setItem(YANDEX_FIRE_DEPT_FOUND, dept_id);
            globalBus.$emit('is_common_house', dept_id);

            console.dir(dept_id);

        },
        houseFound(lat, long, name) {
            this.resetAllObjects();
            this.setPointOnTheMap(lat, long, name);
            this.initFireDepartmentAreas();
            console.dir(this.fireDepartmentAreas)
            let dept_id = this.yandexMapsBus.fireDepartmentArea(lat, long, this.fireDepartmentAreas, this.map);
            window.localStorage.setItem('fire_department_id_found', dept_id);
            window.localStorage.setItem('YANDEX_FIRE_DEPT_FOUND', dept_id);

        },
        resetAllObjects() {
            this.map.geoObjects.removeAll();
        },
        setPointOnTheMap(lat, long, name) {
            const geoObject = new ymaps.GeoObject({
                    geometry: {
                        type: "Point",
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
            this.initFireDepartmentAreas();
        },
        initFireDepartmentAreas() {

            // var polygons = this.fireDepartmentAreas.length > 0 ? this.fireDepartmentAreas : this.yandexMapsBus.polygons();
            var polygons = this.yandexMapsBus.polygons();
            if(this.fireDepartmentAreas.length === 0){
                this.fireDepartmentAreas = polygons;
            }
            // Добавляем многоугольник на карту.
            for (let polygon in polygons) {
                this.map.geoObjects.add(polygons[polygon]);
            }
        }
    },
    mounted() {
        this.yandexMapsBus = new YandexMapsBus();
        this.initMap();
        let initHouseData = window.localStorage.getItem(YANDEX_HOUSE_FOUND);
        if (initHouseData){
            initHouseData = JSON.parse(initHouseData);
            this.houseFound(initHouseData['lat'], initHouseData['long'], initHouseData['name']);
        }

        let cityAreas = this.yandexMapsBus.polygons();

        for (let polygon in cityAreas) {
            this.map.geoObjects.add(cityAreas[polygon]);
        }

        window.addEventListener('storage', (event) => {
            if (event.key === YANDEX_HOUSE_FOUND) {
                let data = JSON.parse(event.newValue);
                this.houseFound(data['lat'], data['long'], data['name']);
            }
            // else if(event.key === YANDEX_FIRE_DEPT_FOUND) {
            //
            // }
        });
    }
};
</script>

<style scoped>
    .map-block {
        width: 100%;
        height: 600px;
        resize: vertical;
        overflow: auto;
    }

    #common-map-screen-yandex-map {
        width: 100%;
        height: 100%;
    }
</style>
