<template>
    <div>
        <div
            v-if="loader"
            id="preload_pane"
            class="hero is-loading has-text-centered absolute-loader">
            <div
                class="hero-body has-text-info">
                <i class="fas fa-circle-notch fa-spin fa-6x"></i>
            </div>
        </div>

        <select
            class="select printing-invisible"
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
            :style="{'width':width + 'px', 'height': height + 'px'}"
            class="road-trip-view-yandex-map printing-full-width"
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
            map: null,
            route: null,
            firstPoint: null,
            secondPoint: null,
            distance: 1000,
            hydrants: [],
            emergencyCoordinates: [],
            width: 560,
            height: 300,
            angleFilter: 0.012,
            printed: false,
            loader: true
        };
    },
    computed: {
        staticMapPath() {
            if (this.map && this.route) {
                let routeCoordinates = [];
                let firstPoint = '';
                let lastPoint = '';
                let hydrants = [];
                let lastInserted = null;

                this.route
                    .getPaths()
                    .each((path) => {
                        path.geometry.getCoordinates().map((coordinatesPair, index) => {
                            const P1 = this.getCoordinatesFromPoint(coordinatesPair);
                            const P2 = this.getCoordinatesFromPoint(path.geometry.getCoordinates()[index + 1]);
                            const P3 = this.getCoordinatesFromPoint(path.geometry.getCoordinates()[index + 2]);
                            const toInsert = P1.join(',');
                            if (lastInserted && P2 && P3) {
                                const angle = this.getAngle(lastInserted, P2, P3);
                                if (angle > this.angleFilter) {
                                    lastInserted = P1;
                                    routeCoordinates.push(toInsert);
                                }
                            } else {
                                lastInserted = P1;
                                routeCoordinates.push(toInsert);
                            }

                            if (index === 0) {
                                firstPoint = P1.join(',');
                            }

                            if (index === path.geometry.getCoordinates().length - 1) {
                                lastPoint = P1.join(',');
                            }
                        });
                    });

                if (this.hydrants.length > 0) {
                    this.hydrants.map((item) => {
                        hydrants.push(item.long + ',' + item.lat + ',' + 'vkgrm');
                    });
                }

                const params = {
                    'l': 'map',
                    'll': this.map.getCenter()[1] + ',' + this.map.getCenter()[0],
                    'pl': routeCoordinates.join(','),
                    'pt': firstPoint + ',pm2ntm' + '~' + lastPoint + ',pm2wtm' + (hydrants.length > 0 ? '~' + hydrants.join('~') : ''),
                    'z': this.map.getZoom(),
                    'size': '650,450'
                };

                const queryString = Object.keys(params).map(key => key + '=' + params[key]).join('&');

                return 'https://static-maps.yandex.ru/1.x/?' + queryString;
            } else {
                return '';
            }
        }
    },
    methods: {
        getCoordinatesFromPoint(point) {
            return point ? [point[1], point[0]] : null;
        },
        getAngle(P1, P2, P3) {
            return Math.abs(Math.atan2(P3[0] - P1[0], P3[1] - P1[1]) - Math.atan2(P2[0] - P1[0], P2[1] - P1[1]));
        },
        getPlaceMarkForHydrantItem(item) {
            return new this.ymaps.Placemark(
                [item.lat, item.long],
                {
                    id: item.id,
                    hintContent: item['outputDescription'],
                    clusterCaption: item['outputDescription']
                },
                {
                    preset: parseInt(item['active']) === 1 ? 'islands#greenIcon' : 'islands#blackDotIcon',
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
                zoom: 12,
                behaviors: ['drag', 'scrollZoom']
            });
            self.setMapCenter();
        },
        drawMainRoute() {
            const self = this;
            self.ymaps
                .route([self.fromString, self.emergencyString], {avoidTrafficJams: true})
                .then((route) => {
                    self.route = route;
                    self.map.geoObjects.add(route);
                })
                .catch((error) => {
                    this.loader = false;
                    alert('При построении маршрута произошла ошибка');
                });
        },
        setMapCenter() {
            const self = this;
            self.ymaps
                .geocode(self.fromString, {results: 1})
                .then(function (firstGeoObjectResult) {
                    const firstGeoObject = firstGeoObjectResult.geoObjects.get(0);
                    self.firstPoint = firstGeoObject;
                    self.emergencyCoordinates = firstGeoObject.geometry.getCoordinates();
                    self.map.setCenter(self.emergencyCoordinates, 13);
                    self.setHydrants();
                })
                .catch(() => {
                    this.loader = false;
                    alert('При установке цента карты произошла ошибка');
                });
        },
        onChangeDistance() {
            this.$localStorage.set('road_trip_hydrants_distance', this.distance);
            if (this.distance && this.emergencyCoordinates) {
                this.setHydrants();
            }
        },
        printRoadTrip(newValue) {
            const url = window.location.pathname.split('/');
            const id = parseInt(url.pop());
            axios.get('/roadtrip/print/' + id,
                {
                    responseType: 'arraybuffer',
                    params: {
                        image_path: newValue
                    }
                })
                .then(response => {
                    this.send(response.data);
                })
                .catch(() => {
                    this.loader = false;
                    alert('При обработке документа для печати произошла ошибка');
                })
        },
        send: function (data) {
            this.printed = true;
            this.loader = false;
            axios.post('http://localhost:13800/print/', data).then(response => {
                console.log('Repsonse', response);
            }).catch(() => {
                alert('При отправке документа на печать произошла ошибка');
            });
        }
    },
    watch: {
        'staticMapPath'(newValue) {
            if (!this.printed) {
                this.printRoadTrip(newValue);
            }
        }
    },
    beforeMount() {
        this.distance = parseInt(this.$localStorage.get('road_trip_hydrants_distance', 1000));
        this.fromString = window.roadTripViewData.fromString;
        this.emergencyString = window.roadTripViewData.emergencyString;
    },
    mounted() {
        this.initMap();
    }
};
</script>

<style scoped>
    .hero.absolute-loader {
        height: 100%;
        align-items: center;
        width: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background: rgba(102, 102, 102, 0.5);
    }

    .hero.absolute-loader .hero-body {
        align-items: center;
        display: flex;
        justify-content: center;
    }
</style>
