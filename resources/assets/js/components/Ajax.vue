<template>
    <span v-if="success">
        <slot name="success"></slot>
    </span>
    <a :href="href" @click.prevent="click" v-else>
        <slot></slot>
    </a>
</template>
<script>
    export default {
        props: {
            'href': String,
            'action': {
                default: null
            },
            'method': String,
            'is-success': Boolean,
            'confirm': Boolean,
        },

        data() {
            return {
                success: this.isSuccess,
                actionHref: this.action || this.href,
            };
        },

        methods: {
            click(e) {
                const method = this.method.toLowerCase();

                if (this.confirm) {
                    return confirm(this.confirm) ? this._submit(e) : null;
                }

                return this._submit(e);
            },

            _submit(e) {
                axios[this.method.toLowerCase()](this.actionHref)
                    .then(response => {
                        this.success = true;
                        this.$emit('success', response.data);
                    })
                    .catch(error => {})
            }
        }
    }
</script>
