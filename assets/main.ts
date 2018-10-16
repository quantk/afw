import Vue from 'vue';
import VueRouter from 'vue-router';

import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/ru-RU';
import 'element-ui/lib/theme-chalk/index.css';
import './main.scss';

import router from './router';

Vue.use(ElementUI, {locale});
Vue.use(VueRouter);
import Root from './Root';

const vue = new Vue({
    template: `<Root/>`,
    router,
    components: {
        Root
    }
}).$mount('#app');