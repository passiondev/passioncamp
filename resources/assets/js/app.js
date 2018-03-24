
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import { currency } from './currency';
import TransactionComponent from './components/Transaction'
import AccountPaymentForm from './components/AccountPaymentForm'
import TransactionForm from './components/TransactionForm'
import TicketConsiderations from './components/TicketConsiderations'
import ChurchSearch from './components/ChurchSearch'
import sendWaiver from './components/waivers/send-waiver'
import Ajax from './components/Ajax.vue'
import TicketWaiver from './components/TicketWaiver'
import Flash from './components/Flash'
import roominglists from './components/roominglists'

Vue.filter('currency', currency);
Vue.component('register-form', require('./components/RegisterForm.vue'));
Vue.component('Transaction', TransactionComponent);

const app = new Vue({
    el: '#app',
    components: {
        AccountPaymentForm,
        TransactionForm,
        TicketConsiderations,
        ChurchSearch,
        sendWaiver,
        Ajax,
        TicketWaiver,
        Flash,
        roominglists
    },
    data: store,
    mounted() {
        window.addEventListener('keydown', e => {
            if (e.target.localName != 'body') {
                return;
            }

            if (e.keyCode == 191) {
                e.preventDefault();
                document.getElementById('church-search').getElementsByTagName('input')[0].focus()
            }
        });
    }
});
