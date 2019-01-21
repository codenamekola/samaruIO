
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
import VueRouter from 'vue-router';
import VueProgressBar from 'vue-progressbar';
import swal from 'sweetalert2';
import {Form, HasError, AlertError} from 'vform';//npm install axios vform
//register the vform components globally
window.Form = Form;
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

Vue.use(VueProgressBar,{
    color: 'green',
    failedColor: 'red',
    height: '2px'
});

Vue.use(VueRouter);

const routes = [
    { path: '/dashboard', component: Vue.component('dashboard', require('./components/Dashboard.vue').default)},
    { path: '/profile', component: Vue.component('profile', require('./components/Profile.vue').default)},
    { path: '/clients', component: Vue.component('clients', require('./components/Clients.vue').default) },
    { path: '/products', component: Vue.component('products', require('./components/Products.vue').default) },
    { path: '/developers', component: Vue.component('developers', require('./components/Developers.vue').default) },
    { path: '/users', component: Vue.component('users', require('./components/Users.vue').default) },
];

const router = new VueRouter({
    mode: 'history',
    routes
});

//create global filter for names to always start with upper case
Vue.filter('capitalName',(name) => {
    return name.charAt(0).toUpperCase() + name.slice(1);
});
//create global filter for formatting data using moment.js
Vue.filter('customDate',(myDate) => {
    return moment(myDate).format('MMMM Do YYYY, h:mm:ss a');
});

let Fire = new Vue();
window.Fire = Fire;

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

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
