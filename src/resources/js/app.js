/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('./ace-editor');

import $ from 'jquery';
window.$ = window.jQuery = $;


// import 'bootstrap';
// import 'bootstrap/js/dist/dropdown';

// import cronstrue from 'cronstrue'
import cronstrue  from 'cronstrue/i18n'
window.cronstrue = cronstrue;
//
window.Vue = require('vue').default;

import VueShortKey from 'vue-shortkey';
Vue.use(VueShortKey);

// import ElementUI from 'element-ui';
// import 'element-ui/lib/theme-chalk/index.css';
// Vue.component('element-component', require('./components/ElementComponent.vue'));
//
// Vue.use(ElementUI);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });
