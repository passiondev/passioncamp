
import Ajax from './Ajax.vue'

export default {
    components: {
        Ajax
    },

    props: [
        'data'
    ],

    data() {
        return {
            waiver: this.data,
            updated: false,
        };
    },

    computed: {
        status() {
            return this.waiver ? this.waiver.status : false
        }
    },

    methods: {
        success(waiver) {
            this.updated = true;
            this.waiver = waiver;
        }
    },
}
