


require('./bootstrap');

window.Vue = require('vue');
import Buefy from 'buefy';
import router from './routes.js';
import Vuetify from 'vuetify';
window.VueRouter = require('vue-router').default;
import AppLayout from './components/appLayout.vue';
import VueCroppie from 'vue-croppie'
import 'croppie/croppie.css'

Vue.use(Buefy);
Vue.use(Vuetify);
Vue.use(VueCroppie);

Vue.config.productionTip = false;

new Vue(
    Vue.util.extend(
        {router},
        AppLayout,
    )
).$mount('#mainLayout');

