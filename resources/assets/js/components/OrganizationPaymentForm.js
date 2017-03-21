
import payment from '../Mixins/Payment.js';

export default {
    mixins: [payment],
    props: ['stripeElements'],
    data() {
        return store.OrganizationPaymentForm;
    },
    methods: {
        submitHandler(e) {
            if (this.type == 'credit') {
                return this.elementsSubmitHandler(e);
            }

            return e.target.submit();
        }
    }
}
