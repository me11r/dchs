import '../sass/fonts.scss';
import '../sass/auth.scss';
import '../sass/app.scss';

import Vue from 'vue';
import Buefy from 'buefy';
import axios from 'axios';

import App from './App.vue';
import Navbar from './ui/Navbar';
import Card from './Card101';
import {Card112Form} from './views/card112';
import {HydrantMapList} from './views/hydrant-map';

const token = document.head.querySelector('meta[name="csrf-token"]');
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';

Vue.config.productionTip = false;

Vue.use(Buefy, {
    defaultIconPack: 'fas'
});

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
    new Vue({el: '#' + hydrantMapListBlock, render: h => h(HydrantMapList)});
}
