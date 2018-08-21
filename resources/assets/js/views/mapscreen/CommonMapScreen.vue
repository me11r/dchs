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

import {locationExchangeKey, mapLocationExchangeKey} from '../../config/storage-keys';
import * as lodash from 'lodash';

export default {
    name: 'CommonMapScreen',
    data: function () {
        return {
            location: '',
            ymaps: window.ymaps,
            map: {},
            currentCity: 'Алматы'
        };
    },
    methods: {
        onLocationInput(location) {
            if (this.location !== location) {
                this.location = location;
                this.findPointOnTheMap();
            }
        },
        findPointOnTheMap: lodash.debounce(function() {
            this.map.geoObjects.removeAll();
            this.ymaps
                .geocode(this.currentCity + ' ' + this.location, {results: 1})
                .then((result) => {
                    const firstGeoObject = result.geoObjects.get(0);
                    if (firstGeoObject) {
                        const bounds = firstGeoObject.properties.get('boundedBy');
                        firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
                        firstGeoObject.properties.set('iconCaption', firstGeoObject.properties.get('name'));
                        this.map.geoObjects.add(firstGeoObject);
                        this.map.setBounds(bounds, {checkZoomRange: true});
                    }
                });
        }, 500),
        doubleClickOnTheMap(event) {
            const coords = event.get('coords');
            this.map.geoObjects.removeAll();
            this.ymaps
                .geocode(coords[0] + ',' + coords[1], {results: 1})
                .then((result) => {
                    const firstGeoObject = result.geoObjects.get(0);
                    if (firstGeoObject) {
                        const bounds = firstGeoObject.properties.get('boundedBy');

                        firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
                        firstGeoObject.properties.set('iconCaption', firstGeoObject.properties.get('name'));

                        this.map.geoObjects.add(firstGeoObject);
                        this.map.setBounds(bounds, {checkZoomRange: true});

                        this.location = firstGeoObject.properties
                            .get('name')
                            .replace(/(^|\s+)улица(\s+|$)/g, '')
                            .replace(/(^|\s+)проспект(\s+|$)/g, '');
                        window.localStorage.setItem(mapLocationExchangeKey, this.location);
                    }
                });
        },
        initMap() {
            const self = this;
            this.map = new this.ymaps.Map(this.$refs['common-map-screen-yandex-map'], {
                center: [43.259743, 76.926573],
                zoom: 13,
                behaviors: ['drag', 'scrollZoom']
            });
            this.map.events.add('dblclick', (event) => {
                self.doubleClickOnTheMap(event);
            });
        }
    },
    mounted() {
        this.initMap();
        window.addEventListener('storage', (event) => {
            if (event.key === locationExchangeKey) {
                this.onLocationInput(event.newValue);
            }
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
