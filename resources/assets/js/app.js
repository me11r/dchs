/* eslint-disable no-new */
import '../sass/fonts.scss';
import '../sass/auth.scss';
import '../sass/app.scss';

import Vue from 'vue';
import Buefy from 'buefy';
import axios from 'axios';
import VueLocalStorage from 'vue-localstorage';

import Navbar from './ui/Navbar';
import {Card112Form} from './views/card112';
import {MudflowProtectionForm} from './views/mudflowProtection';
import {HydrantMapList} from './views/hydrant-map';
import {CommonMapScreen} from './views/mapscreen';
import RoadtripNotifier from './ui/RoadtripNotifier';
import AddEdit101Tech from './views/101tech/AddEdit101Tech.vue';
import Schedule from './views/schedule/Schedule.vue';
import RoadTripViewMap from './views/roadtrip-map/RoadTripViewMap';
// import YandexMapsBus from './scripts/yandex-maps-bus';

import Add101Functions from './scripts/add101/add101';
import Tabs from './scripts/add101/tabs';
import FireObject from './views/FireObject';

import Add101Persons from './scripts/add101persons/add101persons';
import Add101Staff from './views/101staff/AddEdit101Staff';
import Report101 from './views/101staff/Report101';

import AutoPrint from './components/Autoprint';

const token = document.head.querySelector('meta[name="csrf-token"]');
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';

Vue.component('add-edit-tech', AddEdit101Tech);
Vue.component('schedule', Schedule);
Vue.component('v-navbar', Navbar);
Vue.component('card112', Card112Form);
Vue.component('mudflow-protection-form', MudflowProtectionForm);
Vue.component('hydrants-map', HydrantMapList);
Vue.component('common-map', CommonMapScreen);
// Vue.component('roadtrip-notifier', RoadtripNotifier);
Vue.component('roadtrip-map', RoadTripViewMap);
Vue.component('tabs', Tabs);
Vue.component('persons-101', Add101Persons);
Vue.component('fire-object', FireObject);
Vue.component('report101', Report101);

Vue.component('staff-101', Add101Staff);

Vue.config.productionTip = false;

Vue.use(Buefy, {
    defaultIconPack: 'fas'
});

Vue.use(VueLocalStorage);

// верхнее меню
if (document.getElementById('navbar')) {
    new Vue({
        render: h => h(Navbar)
    }).$mount('#navbar');
}

// Карточка 112 (форма добавления/редактировани)
const card112FormBlockId = 'card112-form-block';
if (document.getElementById(card112FormBlockId)) {
    window.addEventListener('load', () => {
        new Vue({el: '#' + card112FormBlockId, render: h => h(Card112Form)});
    });
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
        new Vue({
            el: '#' + roadTripViewYandexMapBlockId,
            render: h => h(RoadTripViewMap)
        });
    };
}

// Общая карта для работы с карточками (101 и 112)
const commonMapScreenBlockId = 'common-map-screen-block';
window.initCommonMapScreen = () => {
    new Vue({el: '#' + commonMapScreenBlockId, render: h => h(CommonMapScreen)});
};

// 101 карточка
if (document.getElementById('cardadd101')) {
    const tabs = new Tabs();
    window.add101tabs = tabs;

    window.addEventListener('load', () => {
        (new Add101Functions()).bindElements().bindPopupMessage();

        document.getElementById('preload_pane').style.display = 'none';
        const ret = window.location.hash.match(/#return=(\d+)/);
        if (ret !== null) {
            window.add101tabs.setTab(parseInt(ret[1]));
        } else {
            window.add101tabs.setTab(0);
        }
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

if (document.getElementById('mudflowProtection-form-block')) {
    new Vue({
        el: '#mudflowProtection-form-block',
        render: h => h(MudflowProtectionForm)
    });
}

if (document.getElementById('roadtrip_notifier')) {
    new Vue({
        el: '#roadtrip_notifier',
        render: h => h(RoadtripNotifier)
    });
}

if (document.getElementById('fire-object-div')) {
    new Vue({
        el: '#fire-object-div'
    });
}

if (document.getElementById('vue')) {
    new Vue({
        el: '#vue'
    });
}

if (document.getElementById('autoprint')) {
    new Vue({
        el: '#autoprint',
        render: h => h(AutoPrint)
    });
}

require('./scripts/emergency-situation/edit-form');
