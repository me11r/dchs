<template>
    <div>
        <div class="map-block">
            <div
                id="polygon-map-screen-yandex-map"
                :ref="'polygon-map-screen-yandex-map'"></div>
        </div>
    </div>
</template>

<script>

// import {
//     MAP_LOCATION_EXCHANGE_KEY,
//     YANDEX_FIRE_DEPT_FOUND,
//     YANDEX_HOUSE_FOUND} from '../../config/storage-keys';
import YandexMapsBus from '../../scripts/yandex-maps-bus';
// import {globalBus} from '../../scripts/global-bus';
import PopupForm from '../hydrant-map/PopupForm';
// import axios from 'axios';
// import _ from 'lodash';

export default {
    name: 'EditPolygonMapScreen',
    components: {
        PopupForm
    },
    data: function () {
        return {
            ymaps: null,
            map: {},
            currentCity: 'Алматы',
            yandexMapsBus: {},
            zoom: 16
        };
    },
    methods: {
        initMap() {
            const self = this;
            this.map = new this.ymaps.Map(this.$refs['polygon-map-screen-yandex-map'], {
                center: [43.259743, 76.926573],
                zoom: self.zoom,
                behaviors: ['drag', 'scrollZoom']
            });

            // this.map.events.add('dblclick', (event) => {
            //     self.doubleClickOnTheMap(event);
            // });
            //
            // this.map.events.add('contextmenu', (event) => {
            //     self.onMapDoubleClick(event);
            // });

            // this.setMapData();
        }
    },
    mounted() {
        (new YandexMapsBus())
            .getInstance()
            .then((yandexMapsBus) => {
                this.yandexMapsBus = yandexMapsBus;
                this.ymaps = this.yandexMapsBus.getYmaps();
                console.log('test');
                this.initMap();
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

    #polygon-map-screen-yandex-map {
        width: 100%;
        height: 100%;
    }
</style>
