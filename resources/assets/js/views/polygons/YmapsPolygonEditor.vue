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

import YandexMapsBus from '../../scripts/yandex-maps-bus';

export default {
    props: {
        polygonModel: {
            type: Object,
            required: true
        }
    },
    name: 'YmapsPolygonEditor',
    data() {
        return {
            ymaps: null,
            map: null,
            zoom: 16,
            currentCity: 'Алматы',
            yandexMapsBus: {},
            localModel: {},
            localPoints: []
        };
    },
    methods: {
        initMap() {
            const self = this;
            this.map = new this.ymaps.Map(
                this.$refs['polygon-map-screen-yandex-map'],
                {
                    center: [43.259743, 76.926573],
                    zoom: self.zoom,
                    behaviors: ['drag', 'scrollZoom']
                }
            );
        },
        resetAllObjects() {
            this.map.geoObjects.removeAll();
        },
        drawCurrentPolygon() {
            if (this.localPoints && this.ymaps) {
                const item = this.localModel;
                const editablePolygon = new this.ymaps.Polygon(
                    [
                        item.points
                    ],
                    {
                        hintContent: item.title
                    },
                    {
                        editorDrawingCursor: 'crosshair',

                        fillColor: item.fill_color,
                        strokeColor: item.line_color,
                        strokeWidth: 1,
                        opacity: item.opacity
                    });

                this.map.geoObjects.add(editablePolygon);

                editablePolygon.events.add('geometrychange', (IEvent) => {
                    const changedPoints = IEvent.originalEvent.originalEvent.originalEvent.newCoordinates[0];
                    this.$emit('pointsChanged', changedPoints);
                });

                editablePolygon.editor.startDrawing();
            }
        }
    },
    watch: {
        polygonModel() {
            this.localModel = this.polygonModel;
            this.resetAllObjects();
            this.drawCurrentPolygon();
        }
    },
    mounted() {
        (new YandexMapsBus()).getInstance().then((yandexMapsBus) => {
            this.yandexMapsBus = yandexMapsBus;
            this.ymaps = this.yandexMapsBus.getYmaps();
            this.initMap();
        });
    }
};
</script>

<style scoped>
.map-block {
width: 100%;
height: 600px;
    margin-top: 50px;
}

#polygon-map-screen-yandex-map {
width: 100%;
height: 100%;
}
</style>
