/* eslint-disable no-new */
import '../sass/fonts.scss';
import '../sass/auth.scss';
import '../sass/app.scss';

import Vue from 'vue';
import Buefy from 'buefy';
import axios from 'axios';
import VueLocalStorage from 'vue-localstorage'

import App from './App.vue';
import Navbar from './ui/Navbar';
import Card from './Card101';
import { Card112Form } from './views/Card112';
import { MudflowProtectionForm } from './views/mudflowProtection';
import { HydrantMapList } from './views/hydrant-map';
import RoadtripNotifier from './ui/RoadtripNotifier';
import RoadTripViewMap from './views/roadtrip-map/RoadTripViewMap';

import Add101Functions from './scripts/add101/add101';
import Tabs from './scripts/add101/tabs';

import Add101Persons from './scripts/add101persons/add101persons';

const token = document.head.querySelector('meta[name="csrf-token"]');
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';

Vue.config.productionTip = false;

Vue.use(Buefy, {
    defaultIconPack: 'fas'
});

Vue.use(VueLocalStorage);

new Vue({
    render: h => h(Navbar)
}).$mount('#navbar');

// Карточка 112 (форма добавления/редактировани)
const card112FormBlockId = 'card112-form-block';
if (document.getElementById(card112FormBlockId)) {
    new Vue({el: '#' + card112FormBlockId, render: h => h(Card112Form)});
}

// Расположение гидрантов на карте (список)
const hydrantMapListBlock = 'hydrant-map-list-block';
if (document.getElementById(hydrantMapListBlock)) {
    window.initHydrantMapList = () => {
        new Vue({el: '#' + hydrantMapListBlock, render: h => h(HydrantMapList)});
    };
}

// Карта в просмотре путевого листа
const roadTripViewYandexMapBlockId = 'road-trip-view-yandex-map-block';
if (document.getElementById(roadTripViewYandexMapBlockId)) {
    window.initRoadTripViewMap = () => {
        new Vue({el: '#' + roadTripViewYandexMapBlockId, render: h => h(RoadTripViewMap)});
    };
}

// 101 карточка
if (document.getElementById('cardadd101')) {
    const tabs = new Tabs();
    window.add101tabs = tabs;

    window.addEventListener('load', () => {
        (new Add101Functions()).bindElements().bindPopupMessage();

        document.getElementById('preload_pane').style.display = 'none';
        window.add101tabs.setTab(0);
        document.getElementById('nexttab').addEventListener('click', (e) => {
            e.preventDefault();
            tabs.nextTab();
        });
    });
}

// Расположение гидрантов на карте (список)
const add101personsFormElement = document.getElementById('add-101-persons-form');
if (add101personsFormElement) {
    (new Add101Persons()).createApp(add101personsFormElement);
}

new Vue({
    el: '#mudflowProtection-form-block',
    render: h => h(MudflowProtectionForm)
});

new Vue({
    el: '#roadtrip-notifier',
    render: h => h(RoadtripNotifier)
});
