<template>
    <div class="ticket list-group-item w-100 d-flex justify-content-between">
        {{ ticket.name }}
        <div class="meta">
            <span :class="`color-${ ticket.type }`" style="white-space: nowrap">
                <span v-if="ticket.grade" :class="`badge badge-${ ticket.type }`">
                    {{ ticket.grade }}
                </span>
                <template v-else>
                    {{ ticket.type[0].toUpperCase() + ticket.type.slice(1) }}
                </template>
            </span>
            <span v-if="ticket.gender == 'M'" class="badge badge-pill badge-success">&nbsp;</span>
            <span v-if="ticket.gender == 'F'" class="badge badge-pill badge-danger">&nbsp;</span>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['ticket']
    }
</script>
