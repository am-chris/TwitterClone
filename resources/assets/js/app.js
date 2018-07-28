
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
import VueAutosize from 'vue-autosize';
import { EventBus } from './event-bus.js';
import BootstrapVue from 'bootstrap-vue';
import Moment from 'moment';
import VueMoment from 'vue-moment';

Vue.use(BootstrapVue);
Vue.use(VueAutosize);
Vue.use(EventBus);
Vue.use(Moment);
Vue.use(VueMoment);

import 'bootstrap-vue/dist/bootstrap-vue.css';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('new-post', require('./components/posts/New.vue'));
Vue.component('posts', require('./components/Posts.vue'));
Vue.component('post-like', require('./components/posts/Like.vue'));
Vue.component('post-share', require('./components/posts/Share.vue'));

Vue.component('user-about', require('./components/users/About.vue'));
Vue.component('user-follow', require('./components/users/Follow.vue'));
Vue.component('user-posts', require('./components/users/Posts.vue'));
Vue.component('user-edit', require('./components/users/Edit.vue'));
Vue.component('user-cover-photo', require('./components/users/CoverPhoto.vue'));
Vue.component('user-photo', require('./components/users/Photo.vue'));

Vue.component('follow-request-actions', require('./components/users/FollowRequestActions.vue'));

// Utilities
Vue.component('image-upload', require('./components/utilities/ImageUpload.vue'));

Vue.mixin({
    methods: {
        route: route
    }
});

const app = new Vue({
    el: '#app'
});
