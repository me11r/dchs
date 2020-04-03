/* eslint-disable no-new */
import '../sass/app.scss';

import axios from 'axios';
import moment from 'moment';

import Navbar from './ui/Navbar';
import {Card112Form} from './views/card112';
import {MudflowProtectionForm} from './views/mudflowProtection';
// import {HydrantMapList} from './views/hydrant-map';
import {CommonMapScreen} from './views/mapscreen';
import RoadtripNotifier from './ui/RoadtripNotifier';
import ServicePlanNotifier from './ui/ServicePlanNotifier';
import AddEdit101Tech from './views/101tech/AddEdit101Tech.vue';
import Schedule from './views/schedule/Schedule.vue';
import RoadTripViewMap from './views/roadtrip-map/RoadTripViewMap';
import RoadtripDeptBtn from './views/roadtrip-map/RoadtripDeptBtn';
import ReportForces from './views/reports/emergency/ReportForces';

import AdditionalData from './views/fire-departments/AdditionalData';
import Add101Functions from './scripts/add101/add101';
import Tabs from './scripts/add101/tabs';
import TPicker from './components/Timepicker';
import FireObject from './views/FireObject';

import Add101Persons from './scripts/add101persons/add101persons';
import Add101Tech from './scripts/add101tech/add101tech';
import Add101Staff from './views/101staff/AddEdit101Staff';
import Report101Staff from './views/101staff/Report101Staff';
import Report101Vehicles from './views/101tech/Report101Vehicles';
import Report101Emergency from './views/reports/emergency/ReportPeriod101';
import Report112Emergency from './views/reports/emergency/ReportPeriod112';
import ReportPeriod112Branches from './views/reports/emergency/ReportPeriod112Branches';
import MainPageReport from './views/MainPageReport';
import AirRescueStaff from './views/AirRescueStaff/AddEditStaff';
import AirRescueTech from './views/AirRescueTech/AddEditTech';
import PhoneItem from './views/dictionary/Phone';
import DistrictManagers from './components/DistrictManagers';
import DatepickerSearch from './components/DatepickerSearch';
import View101App from './scripts/formation/view-101-app';
import NotificationGroupsUsersMultiselect from './components/notification-groups/NotificationGroupsUsersMultiselect';

import FormationRecord112Staff from './views/formation-record/CreateEditStaff';
import FormationRecord112StaffPageSelector from './views/formation-record/PageSelector';
import VueMessenger from './ui/messenger/Messenger';
import PopupNotifier from './ui/PopupNotifier';
import Vue from './VueInstance';
import VueDateFilter from './scripts/DateFilter';
import {globalBus} from './scripts/global-bus';
import EditPolygonMapScreen from './views/polygons/EditPolygonMapScreen';
import QueudReports from './views/queued-reports/QueuedReports';
import QueudReportView from './views/queued-reports/QueuedReportView';
import Translate from './lang/translate';

import SocketListener from './scripts/socket-listener';
SocketListener.defineDefaultListeners();

window.globalBus = new Vue({ });
const translator = new Translate();
window.trans = translator;

const token = document.head.querySelector('meta[name="csrf-token"]');
window.token = token;
window.axios = axios;
window.moment = moment;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';

Object.defineProperty(Array.prototype, 'chunk', {
    value: function(chunkSize) {
        var R = [];
        for (var i = 0; i < this.length; i += chunkSize) { R.push(this.slice(i, i + chunkSize)); }
        return R;
    },
    configurable: true
});

Object.defineProperty(Array.prototype, 'inArray', {
    value: function(key) {
        return this.indexOf(key) !== -1;
    },
    configurable: true
});

Object.defineProperty(Array.prototype, 'clone', {
    value: function() {
        return JSON.parse(JSON.stringify(this));
    },
    configurable: true
});

translator.getLocaleFromUrl();

Vue.filter('dateFilter', VueDateFilter);
Vue.filter('trans', function (key, replace) {
    return translator.get(key, replace);
});
Vue.component('add-edit-tech', AddEdit101Tech);
Vue.component('add-edit-dvr', require('./views/101tech/AddEdit101Dvr'));
Vue.component('schedule', Schedule);
Vue.component('v-navbar', Navbar);
Vue.component('delete-button', require('./components/DeleteButton'));
Vue.component('card112', Card112Form);
Vue.component('mudflow-protection-form', MudflowProtectionForm);
// Vue.component('hydrants-map', HydrantMapList);
Vue.component('common-map', CommonMapScreen);
// Vue.component('roadtrip-notifier', RoadtripNotifier);
Vue.component('roadtrip-map', RoadTripViewMap);
Vue.component('tabs', Tabs);
Vue.component('persons-101', Add101Persons);
Vue.component('fire-object', FireObject);
Vue.component('report101-staff', Report101Staff);
Vue.component('report101-vehicles', Report101Vehicles);
Vue.component('roadtrip-dept-btn', RoadtripDeptBtn);
Vue.component('report101-emergency', Report101Emergency);
Vue.component('report-forces', ReportForces);
Vue.component('report112-emergency', Report112Emergency);
Vue.component('report112-branches', ReportPeriod112Branches);
Vue.component('main-report', MainPageReport);

Vue.component('staff-101', Add101Staff);

Vue.component('staff-formation-record112', FormationRecord112Staff);
Vue.component('staff-formation-page-selector', FormationRecord112StaffPageSelector);

Vue.component('staff-air-rescue', AirRescueStaff);
Vue.component('tech-air-rescue', AirRescueTech);
Vue.component('t-picker', TPicker);
Vue.component('v-phone', PhoneItem);
Vue.component('district-managers', DistrictManagers);

Vue.component('v-datepicker-search', DatepickerSearch);
Vue.component('timepicker-input', require('./components/TimepickerInput.vue'));
Vue.component('ticket101-onway', require('./components/ticket101/OnWayInfo'));
Vue.component('ticket101-arrived', require('./components/ticket101/ArrivedInfo'));
Vue.component('notifications-groups-users-multiselect', NotificationGroupsUsersMultiselect);
Vue.component('ticket101-chronology', require('./components/ticket101/Card101Chronology'));
Vue.component('ticket101-chronology-from-fd', require('./components/ticket101/Card101ChronologyFromFd'));
Vue.component('ticket101-summary-from-fd', require('./components/ticket101/Card101SummaryFromFd.vue'));

Vue.component('notification', require('./components/Notification'));
Vue.component('notifications-groups-users-multiselect', NotificationGroupsUsersMultiselect);
Vue.component('btn-close-card', require('./components/ticket101/CloseTicket'));
Vue.component('other-rides', require('./components/ticket101/OtherRides'));
Vue.component('drill-rides', require('./components/ticket101/DrillRides'));
Vue.component('ticket101-truck', require('./components/ticket101/Card101Truck'));
Vue.component('delete-card-btn', require('./components/ticket101/DeleteCardButton'));
Vue.component('siren-speech-tech', require('./views/reports/SirenSpeechTech/SirenSpeechTechCreate'));
Vue.component('analytics-edit', require('./views/analytics/EditAnalytics'));
Vue.component('fire-dept-check-form', require('./components/fire-department-checks/CreateEditCheck'));
Vue.component('fire-dept-check-item', require('./components/fire-department-checks/CreateEditCheckItem'));
Vue.component('ticket101-save-btn', require('./components/ticket101/SaveBtn'));
Vue.component('quakes-form', require('./views/quakes/CreateEditForm'));
Vue.component('messenger-permissions', require('./views/messenger-permissions/MessengerPermissions'));
Vue.component('report-emergency-type-period', require('./views/reports/emergency/ReportEmergencyTypePeriod'));
Vue.component('report-other-rides-period', require('./views/reports/emergency/ReportTicket101OtherRidesPeriod'));
Vue.component('report-drill-rides-period', require('./views/reports/emergency/ReportTicket101DrillRidesPeriod'));
Vue.component('report-consolidated-result-period', require('./views/reports/emergency/ReportTicket101ResultPeriod'));
Vue.component('card-notification-services', require('./components/ticket101/NotificationServices'));
Vue.component('norm-psp-form', require('./components/norms-psp/CreateEdit'));
Vue.component('report-forces-resources', require('./views/reports/emergency/ReportForcesResources'));
Vue.component('report-emergency-rescue-gu', require('./views/reports/emergency/ReportTicket101EmergencyRescueGu'));
Vue.component('report-object-classification', require('./views/reports/emergency/ReportTicket101ObjectClassification'));
Vue.component('report-call-infos', require('./views/reports/emergency/ReportCallInfos'));
Vue.component('report-water-consumption', require('./views/reports/emergency/ReportTicket101WaterConsumption'));
Vue.component('report-quakes', require('./views/reports/emergency/ReportQuakes'));
Vue.component('report-avalanches', require('./views/reports/emergency/ReportAvalanches'));
Vue.component('report-elevators', require('./views/reports/emergency/ReportElevators'));
Vue.component('report-disease', require('./views/reports/emergency/ReportDisease'));
Vue.component('mudflow-date-selector', require('./views/mudflowProtection/SelectDate'));
Vue.component('call-info-create-edit', require('./views/call-infos/CreateEdit'));
Vue.component('report-staff-managers-ods', require('./views/reports/emergency/ReportStaffManagersODS'));
Vue.component('fire-departments-map', require('./views/fire-departments/DepartmentsMap'));
Vue.component('service-plans-additional', require('./views/service-plans/ServicePlanAdditional'));
Vue.component('card101-additional-oc', require('./components/ticket101/AdditionalOC'));
Vue.component('civil-protection-service-form', require('./views/civil-protection-services/CivilProtectionServiceForm'));
Vue.component('report-services', require('./views/reports/services/ReportServices'));

// трекер яндекс-запросов
globalBus.$on('api-map-request', (r) => {
    if (r.request_count !== null && r.request_count !== undefined) {
        axios.post('/increment-map-request', {description: r.description, count: r.request_count}).then((rr) => {
        });
    }
});

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

// отложенные отчеты
const queuedReportsBlock = 'queued-reports-block';
if (document.getElementById(queuedReportsBlock)) {
    window.addEventListener('load', () => {
        new Vue({el: '#' + queuedReportsBlock, render: h => h(QueudReports)});
    });
}
const queuedReportViewBlock = 'queued-report-view-block';
if (document.getElementById(queuedReportViewBlock)) {
    window.addEventListener('load', () => {
        new Vue({el: '#' + queuedReportViewBlock, render: h => h(QueudReportView)});
    });
}

// Расположение гидрантов на карте (список)
// const hydrantMapListBlock = 'hydrant-map-list-block';
// if (document.getElementById(hydrantMapListBlock)) {
//     window.initHydrantMapList = () => {
//         new Vue({el: '#' + hydrantMapListBlock, render: h => h(HydrantMapList)});
//     };
// }

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

// Карта для редактирвоания границ микроучастков
const editPolygonsMapScreenBlockId = 'edit-polygons-map-screen-block';
window.initPolygonsMapScreenBlock = () => {
    new Vue({el: '#' + editPolygonsMapScreenBlockId, render: h => h(EditPolygonMapScreen)});
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
        /* document.getElementById('nexttab').addEventListener('click', (e) => {
            e.preventDefault();
            tabs.nextTab();
        }); */
    });
}

// Расположение гидрантов на карте (список)
const add101personsFormElement = document.getElementById('add-101-persons-form');
if (add101personsFormElement) {
    (new Add101Persons()).createApp(add101personsFormElement);
}

const add101techFormElement = document.getElementById('add-101-tech-form');
if (add101techFormElement) {
    (new Add101Tech()).createApp(add101techFormElement);
}

if (document.getElementById('roadtrip_notifier')) {
    new Vue({
        el: '#roadtrip_notifier',
        render: h => h(RoadtripNotifier)
    });
}

if (document.getElementById('service_plans_notifier')) {
    new Vue({
        el: '#service_plans_notifier',
        render: h => h(ServicePlanNotifier)
    });
}

if (document.getElementById('fire-object-div')) {
    new Vue({
        el: '#fire-object-div'
    });
}

if (document.getElementById('ticket101-onway')) {
    new Vue({
        el: '#ticket101-onway'
    });
}

if (document.getElementById('ticket101-arrived')) {
    new Vue({
        el: '#ticket101-arrived'
    });
}

if (document.getElementById('vue')) {
    new Vue({
        el: '#vue'
    });
}

if (document.getElementById('btn-close-card')) {
    new Vue({
        el: '#btn-close-card'
    });
}

if (document.getElementById('other-rides')) {
    new Vue({
        el: '#other-rides'
    });
}

if (document.getElementById('ticket101-chronology')) {
    new Vue({
        el: '#ticket101-chronology'
    });
}

if (document.getElementById('emergency_messenger')) {
    new Vue({
        el: '#emergency_messenger',
        render: h => h(VueMessenger)
    });
}

if (document.getElementById('popup_notification')) {
    new Vue({
        el: '#popup_notification',
        render: h => h(PopupNotifier)
    });
}

if (document.getElementById('tab-truck-vue')) {
    new Vue({
        el: '#tab-truck-vue'
    });
}

if (document.getElementById('card101_index_table')) {
    new Vue({
        el: '#card101_index_table'
    });
}

if (document.getElementById('card101_save_btn')) {
    new Vue({
        el: '#card101_save_btn'
    });
}

if (document.getElementById('card-notification-services')) {
    new Vue({
        el: '#card-notification-services'
    });
}

// дополнительные ОП/ОК в учебных карточках 101
if (document.getElementById('additional-okop')) {
    new Vue({
        el: '#additional-okop'
    });
}

const View101AppElement = document.getElementById('view-101-app');
if (View101AppElement) {
    window.View101App = (new View101App()).createApp(View101AppElement, window.people, window.odStaff, window.formId);
}

if (document.getElementById('fire-department-data')) {
    new Vue({
        el: '#fire-department-data',
        render: h => h(AdditionalData)
    });
}

require('./scripts/emergency-situation/edit-form');
require('./scripts/Notifications');