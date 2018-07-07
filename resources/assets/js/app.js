import '../sass/fonts.scss';
import '../sass/auth.scss';
import '../sass/app.scss';

import Vue from 'vue';
import Buefy from 'buefy';

import App from './App.vue'
import Navbar from './ui/Navbar'

Vue.config.productionTip = false;

Vue.use(Buefy, {
    defaultIconPack: 'fas'
});

new Vue({
    render: h => h(Navbar)
}).$mount('#navbar');