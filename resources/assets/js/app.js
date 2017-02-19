
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import { currency } from './currency';

$(function() {
    $('.js-form-input-card-number').payment('formatCardNumber');
    $('.js-form-input-card-cvc').payment('formatCardCVC');
    $('.js-form-input-card-expiry').payment('formatCardExpiry');
});

Vue.filter('currency', currency);
Vue.component('register-form', require('./components/RegisterForm.vue'));

const app = new Vue({
    el: '#app'
});
