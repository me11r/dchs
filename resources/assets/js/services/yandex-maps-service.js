let localYmaps = null;

export default class YMapsService {
    constructor() {
        if (!localYmaps) {
            this.listenToReady();
        }
    }

    listenToReady() {
        const setYmaps = () => {
            if (window.ymaps) {
                window.ymaps.ready(function () {
                    localYmaps = window.ymaps;
                });
            } else {
                setTimeout(() => {
                    setYmaps();
                }, 50);
            }
        };
        setYmaps();
    }

    getYmaps() {
        return new Promise((resolve) => {
            const resolveMap = (resolve) => {
                if (localYmaps) {
                    resolve(localYmaps);
                } else {
                    setTimeout(() => {
                        resolveMap(resolve);
                    }, 50);
                }
            };
            resolveMap(resolve);
        });
    }
}
