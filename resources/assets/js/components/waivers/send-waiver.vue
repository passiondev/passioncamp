<template>
    <a :href="href" class="btn btn-sm" :class="`btn-${btnStyle}`" @click.prevent.once="submit">
        <slot></slot>
    </a>
</template>
<script>
    export default {
        props: ['href', 'btn-style'],

        methods: {
            submit(e) {
                const parent = e.target.parentNode;

                axios.post(e.target.href)
                    .then((response) => {
                        parent.removeChild(e.target);
                        parent.innerHTML = 'Sent';
                    })
                    .catch(error => console.log(error))
            }
        }
    }
</script>
