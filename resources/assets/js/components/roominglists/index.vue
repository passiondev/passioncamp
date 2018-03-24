<script>
import room from './room'
import unassigned from './unassigned'

export default {
    props: ['initialTickets'],
    components: {
        room,
        unassigned
    },

    data() {
        return {
            tickets: _.values(this.$props.initialTickets)
        }
    },

    computed: {
        unassigned() {
            return this.tickets.filter(ticket => ticket.room_id == null).sort((a,b) => a.unassigned_sort > b.unassigned_sort)
        }
    },

    methods: {
        getTicketsForRoom(room_id) {
            return this.tickets.filter(ticket => ticket.room_id == room_id).sort((a,b) => a.assigned_sort < b.assigned_sort)
        }
    }
}
</script>
