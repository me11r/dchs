import '../sass/fonts.scss';
import '../sass/auth.scss';
import '../sass/app.scss';

import Vue from 'vue';
import Buefy from 'buefy';
import axios from 'axios';

import App from './App.vue';
import Navbar from './ui/Navbar';
import Card from './Card101';
import {Card112Form} from './views/Card112';
import {MudflowProtectionForm} from './views/mudflowProtection';
import {HydrantMapList} from './views/hydrant-map';

import Add101Functions from './scripts/add101';

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

new Vue({
    el: '#mudflowProtection-form-block',
    render: h => h(MudflowProtectionForm)
});


// 101 карточка
if (document.getElementById('cardadd101')) {
    window.add101 = new Add101Functions();

    // @TODO удалить, когда функционал будет реализован
    document.getElementById('location').addEventListener('keyup', function () {
        const value = document.getElementById('location').value;
        if (value.toLowerCase().indexOf('гоголя 133') >= 0) {
            alert('Школа: Гимназия №15');
            document.getElementById('storey_count').value = 4;
            document.getElementById('fire_level_id').value = 3;
        }
    });

    window.addEventListener('load', () => {
        window.add101.bindTimepickers();
        window.add101.bindAutocomplete();
        document.getElementById('preload_pane').style.display = 'none';
        window.add101.setTab(0);
        document.getElementById('nexttab').addEventListener('click', window.add101.nextTab);
    });
}
