<template>
    <div>
        <div
            class="hydrant-yandex-map"
            :ref="'hydrant-list-yandex-map'"
            id="hydrant-list-yandex-map"></div>

        <div style="margin: 20px 0 0 20px">
            <b>* Двойной клик на пустом месте - создание</b>
            <br>
            <b>* Клик на существующем - редактирование</b>
            <br>
            <b>* Перемещение сохраняет новую позицию автоматически</b>
        </div>

        <popup-form
            v-if="popupShow"
            :item="model"
            @onClose="closePopup"
            @onSave="onSave"/>
    </div>
</template>

<script>
import axios from 'axios';
import PopupForm from './PopupForm';
import moment from 'moment';
import {_} from 'vue-underscore';

export default {
    name: 'HydrantMapList',
    data() {
        return {
            list: [],
            model: {},
            emptyModel: {},
            popupShow: false,
            ymaps: window.ymaps,
            map: {}
        };
    },
    components: {
        PopupForm
    },
    methods: {
        setList() {
            axios.get('/api/hydrant')
                .then((response) => {
                    this.list = response['data']['data'];
                    this.resetPlaceMarks();
                    this.addPlaceMarks();
                });
        },
        resetPlaceMarks() {
            this.map.geoObjects.removeAll();
        },
        addPlaceMarks() {
            const clusterer = new this.ymaps.Clusterer({
                preset: 'islands#invertedBlueClusterIcons',
                clusterHideIconOnBalloonOpen: false,
                geoObjectHideIconOnBalloonOpen: false,
                gridSize: 100
            });
            let geoObjects = [];

            this.list.map((item) => {
                geoObjects.push(this.getPlaceMarkFromItem(item));
            });
            clusterer.add(geoObjects);
            this.map.geoObjects.add(clusterer);
        },
        getPlaceMarkFromItem(item) {
            const self = this;
            const id = item.id || moment().valueOf();
            const placemark = new this.ymaps.Placemark(
                [item.lat, item.long],
                {
                    id: id,
                    hintContent: item['outputDescription'],
                    clusterCaption: item['outputDescription'],
                    balloonContentBody: '<button class="button is-success edit-hydrant-button is-small" data-id="' + id + '">Редактировать</button>'
                },
                {
                    preset: parseInt(item['active']) === 1 ? 'islands#blueIcon' : 'islands#redIcon',
                    draggable: true
                });
            placemark.events.add('click', (event) => {
                event.preventDefault();
                self.onMarkClick(item);
            });
            placemark.events.add('dragend', (event) => {
                self.onMarkDragEnd(event, item);
            });
            return placemark;
        },
        onMarkClick(model) {
            this.displayPopup(model);
        },
        onMarkDragEnd(event, model) {
            const coords = event.originalEvent.target.geometry['getCoordinates']();
            model.lat = coords[0];
            model.long = coords[1];
            this.update(model);
        },
        onMapDoubleClick(event) {
            const coords = event.get('coords');
            let model = {...this.emptyModel};
            model.lat = coords[0];
            model.long = coords[1];
            this.displayPopup(model);
        },
        onSave(model) {
            +model.id === 0 ? this.create(model) : this.update(model);
        },
        create(model) {
            const self = this;
            axios.post('/api/hydrant', model)
                .then(() => {
                    self.closePopup();
                    self.setList();
                });
        },
        update(model) {
            const self = this;
            axios.post('/api/hydrant/' + model.id, {...model, '_method': 'PATCH'})
                .then(() => {
                    self.closePopup();
                    self.setList();
                });
        },
        closePopup() {
            this.popupShow = false;
        },
        displayPopup(model) {
            this.model = model;
            this.popupShow = true;
        },
        initMap() {
            const self = this;
            this.map = new this.ymaps.Map(this.$refs['hydrant-list-yandex-map'], {
                center: [43.259743, 76.926573],
                zoom: 14,
                behaviors: ['drag', 'scrollZoom']
            });
            this.map.events.add('dblclick', (event) => {
                self.onMapDoubleClick(event);
            });
        },
        addClickListener() {
            document.addEventListener('click', (event) => {
                if (event.target.matches('.edit-hydrant-button')) {
                    this.onMarkClick(_.find(this.list, {id: parseInt(event.target.dataset.id)}));
                }
            });
        }
    },
    mounted() {
        if (window.hydrantListData) {
            this.setList();
            this.model = window.hydrantListData.model;
            this.emptyModel = {...window.hydrantListData.model};
            this.initMap();
            this.addClickListener();
        }
    }
}
;
</script>

<style>
    .hydrant-yandex-map {
        width: 100%;
        height: 700px;
    }
</style>
