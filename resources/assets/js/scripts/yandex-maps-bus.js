import {AREA_ID_FOUND, YANDEX_HOUSE_FOUND} from '../config/storage-keys';
import {globalBus} from '../scripts/global-bus';
import * as _ from 'lodash';

let instance = null;

export default class YandexMapsBus {
    constructor() {
        if (!instance) {
            instance = this;
        }

        this.ymaps = window.ymaps;
        this.areas = window.areas.map((item) => {
            item.name = item.name.toLowerCase();
            return item;
        });
        this.debouncedFindHouse = _.debounce(function (location) {
            this.findHouse(location);
        }, 500);

        return instance;
    }

    findHouse(location) {
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
        const self = this;
        this.ymaps['geocode'](lat + ',' + long, {results: 1, kind: 'district'})
            .then((result) => {
                const firstGeoObject = result['geoObjects'].get(0);
                if (firstGeoObject) {
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
                                }
                            }
                        });
                    }
                }
            });
    }
}
