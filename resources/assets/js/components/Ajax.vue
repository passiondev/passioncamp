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
            'is-success'
        ],

        data() {
            return {
                success: this.isSuccess
            };
        },

        methods: {
            click(e) {
                const method = this.method.toLowerCase();

                axios[this.method.toLowerCase()](this.href)
                    .then(response => {
                        this.success = true;
                    })
                    .catch(error => {})
            }
        }
    }
</script>
