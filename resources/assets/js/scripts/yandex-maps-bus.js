import {AREA_ID_FOUND, YANDEX_HOUSE_FOUND} from '../config/storage-keys';
import {globalBus} from '../scripts/global-bus';
import * as _ from 'lodash';
import axios from 'axios';
import YMapsService from '../services/yandex-maps-service';

let instance = null;

export default class YandexMapsBus {
    constructor() {
        const self = this;
        this.debouncedFindHouse = _.debounce(function (location) {
            self.findHouse(location);
        }, 500);
        this.cityAreasPolygons = [];
    }

    getYmaps() {
        return this.ymaps;
    }

    getInstance() {
        return new Promise((resolve) => {
            if (!instance) {
                (new YMapsService()).getYmaps()
                    .then((ymaps) => {
                        this.ymaps = ymaps;
                        this.areas = window.areas.map((item) => {
                            item.name = item.name.toLowerCase();
                            return item;
                        });

                        instance = this;
                        resolve(instance);
                    });
            } else {
                resolve(instance);
            }
        });
    }

    findHouse(location) {
        globalBus.$emit('api-map-request', {'request_count': 1, 'description': 'yandex-maps-bus.findHouse()'});

        this.ymaps['geocode'](location, {results: 1})
            .then((result) => {
                const geoObject = result['geoObjects'].get(0);
                if (geoObject) {
                    this.detectArea(
                        geoObject.geometry.getBounds()[0][0],
                        geoObject.geometry.getBounds()[0][1]
                    );
                    window.localStorage.setItem(
                        YANDEX_HOUSE_FOUND,
                        JSON.stringify({
                            lat: geoObject.geometry.getBounds()[0][0],
                            long: geoObject.geometry.getBounds()[0][1],
                            name: geoObject.properties.get('name')
                        })
                    );
                }
            });
    }

    detectArea(lat, long) {
        globalBus.$emit('api-map-request', {'request_count': 1, 'description': 'yandex-maps-bus.detectArea()'});

        const self = this;
        this.ymaps['geocode'](lat + ',' + long, {results: 10, kind: 'district'})
            .then((result) => {
                let firstGeoObject = null;

                if (result.geoObjects.get(1) !== undefined) {
                    firstGeoObject = result['geoObjects'].get(1);
                } else {
                    firstGeoObject = result['geoObjects'].get(0);
                }

                if (firstGeoObject !== undefined) {
                    const metaData = firstGeoObject.properties.get('metaDataProperty')['GeocoderMetaData'];
                    if (metaData['Address'] && metaData['Address']['Components']) {
                        metaData['Address']['Components'].map((item) => {
                            if (item.kind === 'district') {
                                let districtName = item.name
                                    .replace(/(^|\s+)район(\s+|$)/g, '')
                                    .replace(/(^|\s+)Район(\s+|$)/g, '')
                                    .toLowerCase();
                                let districtModel = _.find(self.areas, {'name': districtName});
                                if (districtModel) {
                                    window.localStorage.setItem(AREA_ID_FOUND, districtModel.id);
                                    globalBus.$emit(AREA_ID_FOUND, districtModel.id);
                                    globalBus.$emit('city_area_selected', districtModel);

                                    //уточняем район по OpenStreetMap (работает только со включенной картой)
                                    window.localStorage.setItem('findAreaOsm', JSON.stringify([lat, long]));
                                }
                            }
                        });
                    }
                }
            });
    }

    fireDepartmentArea(lat, long, polygons, map) {
        // Добавляем многоугольник на карту.
        for (let polygon in polygons) {
            let area = polygons[polygon];

            map.geoObjects.add(area);

            if (area.geometry.contains([lat, long])) {
                map.geoObjects.remove(area);
                return polygon;
            }
            map.geoObjects.remove(area);
        }
        return 'no fd dound';
    }

    detectCityAreaOsm(lat, long, polygons, map) {

        for (let polygon in polygons) {
            let area = polygons[polygon];

            map.geoObjects.add(area.polygon);

            if (area.polygon.geometry.contains([lat, long])) {
                map.geoObjects.remove(area.polygon);
                return area;
            }
            map.geoObjects.remove(area.polygon);
        }
        return 'no area dound';
    }

    cityAreas(map) {
        // 1. Запрашиваем через геокодер район (у Яндекса этой возможности пока нет, придется пользоваться OSM)
        let url = "https://nominatim.openstreetmap.org/search";
        let regions = [
            'Алатауский',
            'Алмалинский',
            'Ауэзовский',
            'Бостандыкский',
            'Жетысуский',
            'Медеуский', //два полигона
            'Турксибский',
            'Наурызбайский'
        ];

        for (let region in regions) {
            let regionName = `Казахстан, Алматы, ${regions[region]} район`;
            let self = this;

            axios.get(url, { params: {q: regionName, format: "json", polygon_geojson: 1}})
                .then(function (data) {
                    _.each(data.data, function(place) {

                        if (place.osm_type === "relation") {
                            // 2. Создаем полигон с нужными координатами

                            let rightCoordinates = [];
                            if (regions[region] !== 'Медеуский') {
                                let coordinates = place.geojson.coordinates[0];
                                for (let i in coordinates) {
                                    rightCoordinates[i] = [coordinates[i][1], coordinates[i][0]];
                                }

                                let p = new self.ymaps.Polygon([rightCoordinates], {
                                    hintContent: `${regions[region]} район`
                                }, {
                                    strokeColor: 'rgba(10,34,120,1)',
                                    fillColor: 'rgba(255,255,255,0)',
                                    strokeWidth: 2,
                                    opacity: 0.5
                                });
                                map.geoObjects.add(p);
                                let element = {
                                    title: regions[region],
                                    polygon: p,
                                };
                                self.cityAreasPolygons.push(element);

                            } else {

                                for (let index in place.geojson.coordinates) {
                                    let rightCoordinates1 = [];

                                    let coordinates = place.geojson.coordinates[index][0];

                                    for (let i in coordinates) {
                                        rightCoordinates1[i] = [coordinates[i][1], coordinates[i][0]];
                                    }
                                    let p = new self.ymaps.Polygon([rightCoordinates1], {
                                        hintContent: `${regions[region]} район`
                                    }, {
                                        strokeColor: 'rgba(10,34,120,1)',
                                        fillColor: 'rgba(255,255,255,0)',
                                        strokeWidth: 2,
                                        opacity: 0.5
                                    });
                                    map.geoObjects.add(p);
                                    let element = {
                                        title: regions[region],
                                        polygon: p,
                                    };
                                    self.cityAreasPolygons.push(element);
                                }
                            }
                            // localStorage.setItem('test_area', JSON.stringify(rightCoordinates));
                            // 3. Добавляем полигон на карту
                            // self.getInstance().geoObjects.add(p);
                        }
                    });
                }, function (err) {
                    console.log(err);
                });
        }
    }
}
