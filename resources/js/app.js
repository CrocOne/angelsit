import Vue from 'vue'
import VueRouter from 'vue-router'
import pagination from 'laravel-vue-pagination';
Vue.use(VueRouter) 
Vue.component('pagination', require('laravel-vue-pagination'));

import App from './views/App'
import Vkapp from './views/VkApp'

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'vkapp',
      component: Vkapp
    },  
  ],
});

const app = new Vue({
  el: '#app',
  components: { App },
  router,
});