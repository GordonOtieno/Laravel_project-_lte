
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import moment from 'moment';
window.Vue = require('vue');
//registering global routes. the can now be accessed anywhare in the application.
import { Form, HasError, AlertError } from 'vform';
window.Form=Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import VueRouter from 'vue-router'
//progressbar
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '5px'
})

//sweet alert
import Swal from 'sweetalert2'; 
window.Swal=Swal;
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast=Toast;
//end sweet alert
Vue.use(VueRouter)

let routes = [
    { path: '/dashboard', component:require('./components/Dashboard.vue') },
    { path: '/profile', component: require('./components/Profile.vue') },
    { path: '/users', component: require('./components/Users.vue') },
    { path: '/developer', component: require('./components/Developer.vue') }
  ]
  
  const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`

  })
//fiter global
Vue.filter('upText',function(text){
  return text.charAt(0).toUpperCase()+text.slice(1)
});

Vue.filter('mydate', function(created){
  return moment(created).format("MMM Do YY");  
});

let Fire=new Vue();
window.Fire= Fire;

//window.Fire=new Vue();

Vue.component(
  'passport-clients',
  require('./components/passport/Clients.vue')
);

Vue.component(
  'passport-authorized-clients',
  require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
  'passport-personal-access-tokens',
  require('./components/passport/PersonalAccessTokens.vue')
);

/**s
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    
    router
}); 
