<template>
    <div>
        <div class="field">
            <label for="address">Адрес</label>
            <input required
                   type="text"
                   class="input"
                   name="address"
                   id="address"
                   @input="findFd"
                   v-model="model.address"/>
        </div>
        <div class="map-block">
            <div
                    id="departments-map-screen-yandex-map"
                    :ref="'departments-map-screen-yandex-map'"></div>
        </div>
    </div>
</template>

<script>
    import YandexMapsBus from '../../scripts/yandex-maps-bus';

    export default {
        name: "DepartmentsMap",
        props: {
            fireDepartment: {
                type: Object,
                default: () => {
                    return {
                        address: '',
                        fire_department_id: 0,
                        city_area_id: 0,
                    };
                }
            },
        },
        data() {
            return {
                ymaps: null,
                map: null,
                zoom: 16,
                currentCity: 'Алматы',
                yandexMapsBus: {},
                model: this.fireDepartment,
                coords: []
            };
        },
        methods: {
            findFd() {
                this.setPointOnTheMap(this.currentCity + ' ' + this.model.address)
                this.map.panTo(this.coords);
            },
            resetAllObjects() {
                this.map.geoObjects.removeAll();
            },
            setPointOnTheMap(address, needUpdate) {
                this.ymaps['geocode'](address, {results: 1})
                    .then((result) => {
                        let geoObject = result['geoObjects'].get(0);

                        if (geoObject) {

                            this.coords = [
                                geoObject.geometry.getBounds()[0][0],
                                geoObject.geometry.getBounds()[0][1]
                            ];

                            this.resetAllObjects();

                            let geoObject2 = new this.ymaps.GeoObject({
                                geometry: {
                                    type: 'Point',
                                    coordinates: this.coords
                                },
                                properties: {
                                    iconContent: this.model.title
                                }
                            }, {
                                preset: 'islands#darkBlueStretchyIcon',
                                draggable: false
                            });

                            if (needUpdate !== undefined && needUpdate === true) {
                                this.model.address = geoObject.properties
                                    .get('name')
                                    .replace(/(^|\s+)улица(\s+|$)/g, '')
                                    .replace(/(^|\s+)проспект(\s+|$)/g, '');

                            }

                            this.map.geoObjects.add(geoObject2);
                            this.map.setZoom(this.zoom);
                            this.map.panTo(this.coords);
                        }
                    });
            },
            initMap() {
                const self = this;
                this.map = new this.ymaps.Map(
                    this.$refs['departments-map-screen-yandex-map'],
                    {
                        center: [43.259743, 76.926573],
                        zoom: self.zoom,
                        behaviors: ['drag', 'scrollZoom']
                    }
                );

                this.map.events.add('dblclick', (event) => {
                    self.onMapDoubleClick(event);
                });

                this.setMapData();
            },
            onMapDoubleClick(event) {
                this.resetAllObjects();

                const coords = event.get('coords');
                let model = {};
                model.lat = coords[0];
                model.long = coords[1];

                this.setPointOnTheMap(`${model.lat}, ${model.long}`, true);
            },
            onMarkDragEnd(event, model) {

                const coords = event.originalEvent.target.geometry['getCoordinates']();
                model.lat = coords[0];
                model.long = coords[1];

                // this.updateModel(model);
            },
            setMapData() {
                this.setPointOnTheMap(this.model.address);
            },
            findHouse(location) {
                this.ymaps['geocode'](location, {results: 1})
                    .then((result) => {
                        const geoObject = result['geoObjects'].get(0);
                        if (geoObject) {
                            /*this.detectArea(
                                geoObject.geometry.getBounds()[0][0],
                                geoObject.geometry.getBounds()[0][1]
                            );*/
                            this.coords = [
                                geoObject.geometry.getBounds()[0][0],
                                geoObject.geometry.getBounds()[0][1]
                            ];
                        }
                    });
            }
        },
        mounted() {

            (new YandexMapsBus()).getInstance().then((yandexMapsBus) => {
                this.yandexMapsBus = yandexMapsBus;
                this.ymaps = this.yandexMapsBus.getYmaps();
                this.initMap();
            });
        }
    }
</script>

<style scoped>
    .map-block {
        width: 100%;
        height: 600px;
        margin-top: 50px;
    }

    #departments-map-screen-yandex-map {
        width: 100%;
        height: 100%;
    }
</style>