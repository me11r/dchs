<template>
    <div>
        <select
            id="distance"
            name="distance"
            v-model="distance"
            @change="onChangeDistance()"
            required>
            <option
                v-for="item in [150, 200, 250, 300, 400, 500, 1000, 1500, 2000]"
                :key="item"
                :value="item">{{ item }} метров
            </option>
        </select>
        <div
            class="road-trip-view-yandex-map"
            :ref="'road-trip-view-yandex-map'"
            id="road-trip-view-yandex-map"></div>
    </div>
</template>

<script>

import axios from 'axios';

export default {
    name: 'RoadTripViewMap',
    data() {
        return {
            fromString: '',
            emergencyString: '',
            ymaps: window.ymaps,
            map: {},
            distance: 1000,
            hydrants: [],
            emergencyCoordinates: []
        };
    },
    methods: {
        getPlaceMarkForHydrantItem(item) {
            return new this.ymaps.Placemark(
                [item.lat, item.long],
                {
                    id: item.id,
                    hintContent: item['outputDescription'],
                    clusterCaption: item['outputDescription']
                },
                {
                    preset: parseInt(item['active']) === 1 ? 'islands#greenIcon' : 'islands#redIcon',
                    draggable: false
                });
        },
        resetHydrants() {
            const self = this;
            this.map.geoObjects.removeAll();
            if (this.hydrants) {
                this.hydrants.map((item) => {
                    return this.map.geoObjects.add(self.getPlaceMarkForHydrantItem(item));
                });
            }
            this.drawMainRoute();
            this.map.setBounds(this.map.getBounds(), {checkZoomRange: true});
        },
        setHydrants() {
            const self = this;
            axios.get('/api/hydrant/hydrants_for_point_by_radius',
                {
                    params: {
                        latitude: self.emergencyCoordinates[0],
                        longitude: self.emergencyCoordinates[1],
                        distance: self.distance
                    }
                })
                .then((response) => {
                    self.hydrants = response['data']['data'];
                    self.resetHydrants();
                });
        },
        initMap() {
            const self = this;
            self.map = new self.ymaps.Map(self.$refs['road-trip-view-yandex-map'], {
                center: [43.259743, 76.926573],
                zoom: 13,
                behaviors: ['drag', 'scrollZoom']
            });
            self.setMapCenter();
        },
        drawMainRoute() {
            const self = this;
            self.ymaps
                .route([self.fromString, self.emergencyString], {avoidTrafficJams: true})
                .then((route) => {
                    self.map.geoObjects.add(route);
                })
                .catch((error) => {
                    alert('При построении маршрута произошла ошибка: ' + error.message);
                });
        },
        setMapCenter() {
            const self = this;
            self.ymaps
                .geocode(self.fromString, {results: 1})
                .then(function (firstGeoObjectResult) {
                    const firstGeoObject = firstGeoObjectResult.geoObjects.get(0);
                    self.emergencyCoordinates = firstGeoObject.geometry.getCoordinates();
                    self.map.setCenter(self.emergencyCoordinates, 13);
                    self.setHydrants();
                })
                .catch(() => {
                    alert('При установке цента карты произошла ошибка');
                });
        },
        onChangeDistance() {
            this.$localStorage.set('road_trip_hydrants_distance', this.distance);
            if (this.distance && this.emergencyCoordinates) {
                this.setHydrants();
            }
        }
    },
    beforeMount() {
        this.distance = parseInt(this.$localStorage.get('road_trip_hydrants_distance', 150));
        this.fromString = window.roadTripViewData.fromString;
        this.emergencyString = window.roadTripViewData.emergencyString;
    },
    mounted() {
        this.initMap();
    }
};
</script>

<style scoped>
    .road-trip-view-yandex-map {
        width: 560px;
        height: 300px;
    }
</style>
