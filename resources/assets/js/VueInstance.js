import Vue from 'vue';
import Buefy from 'buefy';
import VueLocalStorage from 'vue-localstorage';
import Translate from './lang/translate';

Vue.config.productionTip = false;
Vue.use(Buefy, {defaultIconPack: 'fas'});
Vue.use(VueLocalStorage);
const translater = new Translate();
Vue.filter('trans', function (value) {
    return translater.get(value, window.language);
});

export default Vue;
