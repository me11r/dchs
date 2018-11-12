let ymaps = null;

export default class YMapsService {
    constructor() {
        if (!ymaps) {
            this.listenToReady();
        }
    }

    listenToReady() {
        window.addEventListener('load', () => {
            window.ymaps.ready(function () {
                ymaps = window.ymaps;
            });
        });
    }

    getYmaps() {
        return new Promise((resolve) => {
            const resolveMap = (resolve) => {
                if (ymaps) {
                    resolve(ymaps);
                } else {
                    setTimeout(() => {
                        resolveMap(resolve);
                    }, 100);
                }
            };
            resolveMap(resolve);
        });
    }
}
