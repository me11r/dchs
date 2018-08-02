import '../sass/fonts.scss';
import '../sass/auth.scss';
import '../sass/app.scss';

import Vue from 'vue';
import Buefy from 'buefy';

import App from './App.vue';
import Navbar from './ui/Navbar';
import Card from './Card101';
import {Card112Form} from './views/Card112';
import {MudflowProtectionForm} from './views/mudflowProtection';

Vue.config.productionTip = false;

Vue.use(Buefy, {
    defaultIconPack: 'fas'
});

new Vue({
    render: h => h(Navbar)
}).$mount('#navbar');

new Vue({
    el: '#card112-form-block',
    render: h => h(Card112Form)
});

new Vue({
    el: '#mudflowProtection-form-block',
    render: h => h(MudflowProtectionForm)
});

