<template>
    <div>
        <div
            class="road-trip-view-yandex-map"
            :ref="'road-trip-view-yandex-map'"
            id="road-trip-view-yandex-map"></div>
    </div>
</template>

<script>
export default {
    name: 'RoadTripViewMap',
    data() {
        return {
            fromString: '',
            emergencyString: '',
            ymaps: window.ymaps,
            map: {}
        };
    },
    methods: {
        initMap() {
            const self = this;
            self.map = new self.ymaps.Map(self.$refs['road-trip-view-yandex-map'], {
                center: [43.259743, 76.926573],
                zoom: 13,
                behaviors: ['drag', 'scrollZoom']
            });
            self.drawMainRoute();
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
                    const fromCoords = firstGeoObject.geometry.getCoordinates();
                    self.map.setCenter(fromCoords, 13);
                })
                .catch(() => {
                    alert('При установке цента карты произошла ошибка');
                });
        }
    },
    mounted() {
        if (window.roadTripViewData) {
            this.fromString = window.roadTripViewData.fromString;
            this.emergencyString = window.roadTripViewData.emergencyString;
            this.initMap();
        }
    }
};
</script>

<style scoped>
    .road-trip-view-yandex-map {
        width: 560px;
        height: 300px;
    }
</style>
