
import payment from '../Mixins/Payment.js';

export default {
    mixins: [payment],
    props: ['stripeElements']
}
