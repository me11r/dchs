<template>
    <div>
        <div class="field">
            <label for="energy_class">Энергетический класс землетрясения</label>
            <input type="number" step="0.01" class="input" name="energy_class" id="energy_class" v-model="energyClass" required/>
        </div>
        <div class="field">
            <label for="coordinates">Координаты эпицентра</label>
            <input type="text" class="input" name="coordinates" id="coordinates" v-model="coordinates" required/>
        </div>
        <div style="height: 300px" id="map"></div>
    </div>
</template>

<script>
    // import YandexMapsBus from '../../scripts/yandex-maps-bus';
    export default {
        name: "CreateEditForm",
        data: function () {
            return {
                lat: 0,
                long: 0,
                map: {},
                // coordinates: '42.30° с.ш. 76.33° в.д.',
                energyClass: this.inputEnergyClass,
                coordinates: this.inputCoordinates,
                ymaps: {},
                placemark: {}
            };
        },
        props: {
            inputCoordinates: {
                type: String,
                default: '43.259743, 76.926573'
            },
            inputEnergyClass: {
                type: Number,
                default: 0.0
            },
        },
        methods: {
            initMap() {
                let coords = this.coordinates.split(', ');
                this.map = new this.ymaps.Map('map', {
                    center: this.parsedCoordinates,
                    zoom: 8
                });
                /*var str = '42.30° с.ш. 76.33° в.д.';
                var regexp = /\d+\.\d*|\.?\d+/g;
                var matches_array = str.match(regexp);
                console.dir(matches_array);*/
            },
            addMark(){
                //[42.30, 76.33]
                this.map.geoObjects.remove(this.placemark);
                this.placemark = new this.ymaps.Placemark(this.parsedCoordinates, {
                    hintContent: 'Собственный значок метки',
                    balloonContent: 'Это красивая метка'
                }, {
                    // Опции.
                    // Необходимо указать данный тип макета.
                    iconLayout: 'default#image',
                    // Своё изображение иконки метки.
                    // iconImageHref: '/quake_icons/9-10.png',
                    iconImageHref: this.iconImageHref,
                    // Размеры метки.
                    iconImageSize: [30, 30],
                    // Смещение левого верхнего угла иконки относительно
                    // её "ножки" (точки привязки).
                    // iconImageOffset: [-5, -38]
                });
                this.map.geoObjects.add(this.placemark);
            },
        },
        computed: {
            parsedCoordinates() {
                let regexp = /\d+\.\d*|\.?\d+/g;
                return this.coordinates.match(regexp);
            },
            iconImageHref(){
                if(this.energyClass < 10) {
                    return '/quake_icons/9-10.png';
                }

                if(this.energyClass >= 10 && this.energyClass < 11) {
                    return '/quake_icons/10-11.png';
                }

                if(this.energyClass >= 11 && this.energyClass < 12) {
                    return '/quake_icons/11-12.png';
                }

                if(this.energyClass >= 12 && this.energyClass < 13) {
                    return '/quake_icons/12-13.png';
                }

                if(this.energyClass >= 13 && this.energyClass < 14) {
                    return '/quake_icons/13-14.png';
                }

                if(this.energyClass >= 14) {
                    return '/quake_icons/14-more.png';
                }
            }
        },
        watch: {
            'parsedCoordinates'() {
                this.addMark();
            },
            'iconImageHref'() {
                this.addMark();
            }
        },
        mounted() {
            ymaps.ready(() => {
                this.ymaps = ymaps;

                this.initMap();
                this.addMark();
            });
        }
    }
</script>

<style scoped>

</style>