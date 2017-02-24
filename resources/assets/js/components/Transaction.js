
import payment from '../Mixins/Payment.js';

export default {
    mixins: [payment],
    props: ['canMakeStripePayments'],
    data() {
        return store.Transaction;
    },
    methods: {
        submitHandler(e) {
            if (this.payment_method !== 'credit') {
                return e.target.submit();
            }

            return this.stripeSubmitHandler(e);
        }
    }
}
