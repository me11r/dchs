import Vue from 'vue';
import Buefy from 'buefy';
import VueLocalStorage from 'vue-localstorage';

Vue.config.productionTip = false;
Vue.use(Buefy, {defaultIconPack: 'fas'});
Vue.use(VueLocalStorage);

export default Vue;
