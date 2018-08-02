<template>
    <div>
        <yandex-map
            class="hydrant-yandex-map"
            map-type="hybrid"
            :zoom="14"
            :coords="[43.259743, 76.926573]"
            :behaviors="['drag', 'scrollZoom']"
            :cluster-options="{
                1: {
                    clusterDisableClickZoom: true
                }
            }"
            @map-was-initialized="initHandler">

            <!--@TODO remake callbacks-->
            <ymap-marker
                marker-type="Placemark"
                v-for="item in list"
                :key="item.id"
                :marker-id="'marker_' + item.id"
                :cluster-name="'marker_' + item.id"
                :coords="[item.lat, item.long]"
                :properties="{
                    iconContent: '',
                    hintContent: item['outputDescription']
                }"
                :options="{
                    draggable: true
                }"
                :callbacks="{
                    click: (e) => onMarkClick(e, item),
                    dragend: (e) => onMarkDragEnd(e, item)
                }"
            />
        </yandex-map>

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
import {yandexMap, ymapMarker} from 'vue-yandex-maps';
import PopupForm from './PopupForm';

export default {
    name: 'HydrantMapList',
    data() {
        return {
            list: [],
            model: {},
            emptyModel: {},
            popupShow: false
        };
    },
    components: {
        PopupForm,
        yandexMap,
        ymapMarker
    },
    methods: {
        setList() {
            axios.get('/api/hydrant')
                .then((response) => {
                    this.list = response['data']['data'];
                });
        },
        initHandler(map) {
            const self = this;
            // @TODO remake callback
            map.events.add('dblclick', (event) => {
                self.onMapDoubleClick(event);
            });
        },
        onMarkClick(event, model) {
            const coords = event.get('coords');
            model.lat = coords[0];
            model.long = coords[1];
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
        }
    },
    beforeMount() {
        this.setList();
        if (window.hydrantListData) {
            this.model = window.hydrantListData.model;
            this.emptyModel = {...window.hydrantListData.model};
        }
    }
};
</script>

<style scoped>
    .hydrant-yandex-map {
        width: 100%;
        height: 600px;
    }
</style>
