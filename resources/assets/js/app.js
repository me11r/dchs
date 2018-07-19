import '../sass/fonts.scss';
import '../sass/auth.scss';
import '../sass/app.scss';

import Vue from 'vue';
import Buefy from 'buefy';

import App from './App.vue'
import Navbar from './ui/Navbar'
import Card from './Card101'
import {Card112Form} from './views/Card112';

Vue.config.productionTip = false;

Vue.use(Buefy, {
    defaultIconPack: 'fas'
});

// Vue.component('card112-form', Card112Form);

new Vue({
    render: h => h(Navbar),
}).$mount('#navbar');


new Vue({
    components: {
        Card112Form
    }
}).$mount('#card112-form');