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
        props: [
            'href',
            'method',
            'is-success',
            'confirm',
        ],

        data() {
            return {
                success: this.isSuccess
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
                axios[this.method.toLowerCase()](this.href)
                    .then(response => {
                        this.success = true;
                        this.$emit('success', response.data);
                    })
                    .catch(error => {})
            }
        }
    }
</script>
