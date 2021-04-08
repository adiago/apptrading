/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import $ from 'jquery';
window.$ = window.jQuery = $;

import 'jquery-ui/ui/widgets/datepicker.js';

//add as many widget as you may need
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('base-home', require('./components/BaseHome.vue').default);
Vue.component('widget', require('./components/Widget.vue').default);
Vue.component('trade-form', require('./components/TradeForm.vue').default);
Vue.component('trade-list', require('./components/TradeList.vue').default);
Vue.component('toast-message', require('./components/ToastMessage.vue').default);
Vue.component('account', require('./components/Account.vue').default);
Vue.component('linechart', require('./components/LineChart.vue').default);
Vue.component('piechart', require('./components/PieChart.vue').default);
Vue.component('chartjs', require('./components/ChartJS.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    count: 'heheh',
    transaction: {}
  },
  mutations: {
    updateTransaction (state,newTransaction) {
        state.transaction = newTransaction;
      }
  }
})

const app = new Vue({
    el: '#app',
    store: store,
});
